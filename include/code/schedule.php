<?php
class Schedule
{
	function Schedule(){}
	
	function run()
	{
		/*
		number ID
		inscripcion 1,sorteo 2,activar Batalla 3, conteo votos 4,cambiar estado torneo 5,crear Batallas 6
		*/
		$this->BG = new DataBase();
		$this->BG->connect();
		$process = new calendario($this->BG->con);
		$process ->sethecho(-1);
		$process=$process->read(true,1,array("hecho"),1,array("fecha","ASC")); 
		$fechaActual = fechaHoraActual();
		$sigue=true;
		for($i=0;$i<count($process)&&$sigue;$i++)
		{
			if(FechaMayor($fechaActual,$process[$i]->getfecha())!=-1)
			{
				if($process[$i]->getaccion()=="SORTE")
				{
					$target = explode(",",$process[$i]->gettargetstring());
					$this->sorteo($target[0],$target[1]);
				}
				if($process[$i]->getaccion()=="ACTBA")
				{
					$this->activarBatalla($process[$i]->gettargetdate());
				}
				if($process[$i]->getaccion()=="CONVO")
				{
					$this->ConteoVotos();
				}
				if($process[$i]->getaccion()=="CHTOR")
				{
					$this->changeChampionship($process[$i]->gettargetint());
				}
				if($process[$i]->getaccion()=="CHEVE")
				{
					$this->changeEvento($process[$i]->gettargetstring());
				}
				if($process[$i]->getaccion()=="CALPO")
				{
					$target = explode(",",$process[$i]->gettargetstring());
					$this->calcularPonderacion($target[0],$target[1]);
				}
				if($process[$i]->getaccion()=="INMAT")
				{
					$this->ingresarMatch();
				}		
				
				if($process[$i]->getaccion()=="PAREP")
				{
					$target = explode(",",$process[$i]->gettargetstring());
					$this->pasarrepechaje($target[0],$target[1],$target[2],$target[3]);
				}		
				$process[$i]->setHecho(1);
				$process[$i]->update(1,array("hecho"),1,array("id"));
				$lognew = new reg($this->BG->con,-10,$process[$i]->getaccion(),$fechaActual,1,"system",$process[$i]->gettargetstring()." ".$process[$i]->gettargetdate()." ".$process[$i]->gettargetint());
				$lognew->save(); 
			}
			else
				$sigue=false;
		}
		$this->grafoenvivo();
		$this->BG->close();
	}
	function sorteo($instancia="",$numeroGrupo="")
	{
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
			
		$newConf = new configuracion($this->BG->con);
		$newConf->setid($instancia);
		$newConf->setidtorneo($torneoActual->getid());
		$newConf = $newConf->read(false,2,array("id","AND","idtorneo"));
		$seed=$newConf->getextra();
		
		if($newConf->gettipo() == "ELIMI")
		{		
			
			$personajeTotal = new personajepar($this->BG->con);
			$personajeTotal->setronda($instancia);
			$personajeTotal = $personajeTotal->read(true,1,array("ronda"));
			$numeroTotal = count($personajeTotal);
			$cantidadGrupo = floor($numeroTotal/$newConf->getnumerogrupos());
			if(($numeroTotal-($cantidadGrupo*$newConf->getnumerogrupos()))>=$numeroGrupo)
				$cantidadGrupo++;
	
			$perListos = new personajepar($this->BG->con);
			$perListos->setronda($instancia);
			$perListos->setgrupo($numeroGrupo);
			$perListos->setidtorneo($torneoActual->getid());
			$perListos = $perListos->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
			$cantidadListos = count($perListos);
			
			$personajesSortear = new personajepar($this->BG->con);
			$personajesSortear->setronda($instancia);
			$personajesSortear->setgrupo("N");
			$personajesSortear->setidtorneo($torneoActual->getid());
			$personajesSortear = $personajesSortear->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
			
			$batallasele = new batalla($this->BG->con);
			$batallasele->setronda($instancia);
			$batallasele->setidtorneo($torneoActual->getid());
			$batallasele = $batallasele->read(true,2,array("ronda","AND","idtorneo"));
	
			$coladepersonajes = new colaclass();
			
			for($i=0;$i<count($personajesSortear);$i++)
				$coladepersonajes->insert($i);
				
			if($seed==1)
			{
				$totalPonderacion = 0;
				for($i=0;$i<$cantidadListos;$i++)
					$totalPonderacion += $perListos[$i]->getponderacion();
				
				for($i=$cantidadListos;$i<$cantidadGrupo;$i++)
				{
					$escoger=rand(0,count($personajesSortear)-1);
					$sigue=true;
					while($sigue)
					{
						if($perSort[$escoger]["act"]==1)
							$escoger=$perSort[$escoger]["prox"];
						else
						{
							$porcentaje = ($totalPonderacion+$personajesSortear[$escoger]->getponderacion())/($cantidadListos+$i+1);
							$porcentaje = abs(($porcentaje-$torneoActual->getponderacionprom())/$torneoActual->getponderacionprom());
							$porcentaje = 1000 - $porcentaje*1000;
							if($porcentaje<0)
								$escoger=$perSort[$escoger]["prox"];
							else
							{
								$res = rand(0,1000);
								if($res<$porcentaje)
								{
									$personajesSortear[$escoger]->setgrupo($numeroGrupo);
									$personajesSortear[$escoger]->update(1,array("grupo"),1,array("id"));
									$perSort[$escoger]["act"]=1;
									$temSort = $perSort[$escoger];
									
									$tempProx=$perSort[$escoger]["prox"];
									while($perSort[$tempProx]["act"]==1&&$perSort[$tempMov]["prox"]!=$escoger)
										$tempProx=$perSort[$tempMov]["prox"];
									$tempAnt=$perSort[$escoger]["ant"];
									while($perSort[$tempAnt]["act"]==1&&$perSort[$tempMov]["ant"]!=$escoger)
										$tempAnt=$perSort[$tempAnt]["ant"];
										
									$perSort[$escoger]["prox"]=$tempProx;
									$perSort[$escoger]["ant"]=$tempAnt;
									$perSort[$tempAnt]["prox"]=$tempProx;
									$perSort[$tempProx]["ant"]=$tempAnt;
									$sigue=false;
									
									$batalla = arrayobjeto($batallasele,"grupo",$numeroGrupo);
									$nuevaparticiapcion = new participacion($this->BG->con,$personajesSortear[$escoger]->getid(),$batalla->getid());
									$nuevaparticiapcion->save();
									
									$totalPonderacion += $personajesSortear[$escoger]->getponderacion();							
								}
								else
									$escoger=$perSort[$escoger]["prox"];
							}
						}
					}
				}
			}
			else
			{
				for($i=$cantidadListos;$i<$cantidadGrupo;$i++)
				{
					if($coladepersonajes->cantidad()>0)
					{
						$escoger=$coladepersonajes->randominit();
						$coladepersonajes->randomquit();
						
						$personajesSortear[$escoger]->setgrupo($numeroGrupo);
						$personajesSortear[$escoger]->update(1,array("grupo"),1,array("id"));
						
						$batalla = arrayobjeto($batallasele,"grupo",$numeroGrupo);
						$nuevaparticiapcion = new participacion($this->BG->con,$personajesSortear[$escoger]->getid(),$batalla->getid());
						$nuevaparticiapcion->save();
					}
				}
			}
		}
		elseif($newConf->gettipo() == "ELGRU")
		{
			$personajeTotal = new personajepar($this->BG->con);
			$personajeTotal->setronda($instancia);
			$personajeTotal = $personajeTotal->read(true,1,array("ronda"));
			$numeroTotal = count($personajeTotal);
			
			$totalDelGrupo1 = 0;
			$totalDelGrupo2 = 0;
			//muestra la lista del grupo
			$cantidadListos1 = 0;
			$cantidadListos2 = 0;
			//ponderacion del grupo
			$totalPonderacion1 = 0;
			$totalPonderacion2 = 0;
			$batallasele = new batalla($this->BG->con);
			$batallasele->setronda($instancia);
			$batallasele->setidtorneo($torneoActual->getid());
			$batallasele = $batallasele->read(true,2,array("ronda","AND","idtorneo"));
			
			for($i=0;$i<$newConf->getnumerobatallas();$i++)
			{
				$datosGrupo[$i]["cantidad"] = floor($numeroTotal/($newConf->getnumerogrupos()*$newConf->getnumerobatallas()));
				$datosGrupo[$i]["nombre"] = $numeroGrupo."-".($i+1);
				if(($numeroTotal-($newConf->getnumerogrupos()*$newConf->getnumerobatallas()*$datosGrupo[$i]["cantidad"]))>(cambiarletra($numeroGrupo,false)-1)+$i*$newConf->getnumerogrupos())
					$datosGrupo[$i]["cantidad"]++;
				
				$perListos = new personajepar($this->BG->con);
				$perListos->setronda($instancia);
				$perListos->setgrupo($datosGrupo[$i]["nombre"]);
				$perListos->setidtorneo($torneoActual->getid());
				$perListos = $perListos->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
				if(($newConf->getnumerobatallas()/2)>$i)
				{
					$cantidadListos1 += count($perListos);
					if(count($perListos)>0&&$seed==1)
						for($j=0;$j<count($perListos);$j++)
							$totalPonderacion1 += $perListos[$j]->getponderacion();
					$totalDelGrupo1 += $datosGrupo[$i]["cantidad"];
				}
				else
				{
					$cantidadListos2 += count($perListos);
					if(count($perListos)>0&&$seed==1)
						for($j=0;$j<count($perListos);$j++)
							$totalPonderacion2 += $perListos[$j]->getponderacion();
					$totalDelGrupo2 += $datosGrupo[$i]["cantidad"];
				}
			}
			$personajesSortear = new personajepar($this->BG->con);
			$personajesSortear->setronda($instancia);
			$personajesSortear->setgrupo("N");
			$personajesSortear->setidtorneo($torneoActual->getid());
			$personajesSortear = $personajesSortear->read(true,3,array("ronda","AND","grupo","AND","idtorneo"),1,array("ponderacion","DESC"));
			
			$coladepersonajes = new colaclass();
			
			for($i=0;$i<count($personajesSortear);$i++)
				$coladepersonajes->insert($i);
			
			if($seed==1)
			{
				$cola1personaje = new colaclass();
				$cola2personaje = new colaclass();
				for($i=$cantidadListos1;$i<$totalDelGrupo1;$i++)
				{	
					if($i==0)
						$escoger=$coladepersonajes->firstinit();
					else		
						$escoger=$coladepersonajes->randominit();
					$sigue=true;
				
					while($sigue)
					{
						$porcentaje = ($totalPonderacion1+$personajesSortear[$escoger]->getponderacion())/($cantidadListos1+$i+1);
						$porcentaje = abs(($porcentaje-$torneoActual->getponderacionprom())/$torneoActual->getponderacionprom());
						$porcentaje = 1000 - $porcentaje*1000;
						if($porcentaje<15)
							$porcentaje=15;
						$res = rand(0,1000);
						if($res<$porcentaje||$i==0)
						{
							$totalPonderacion1 += $personajesSortear[$escoger]->getponderacion();
							$cola1personaje->insert($escoger);
							$sigue = false;
							$coladepersonajes->randomquit();
						}
						else
							$escoger=$coladepersonajes->randominit();
					}
				}
				
				for($i=$cantidadListos2;$i<$totalDelGrupo2;$i++)
				{		
					if($i==0)
						$escoger=$coladepersonajes->firstinit();
					else		
						$escoger=$coladepersonajes->randominit();
					$sigue=true;
					while($sigue)
					{
						$porcentaje = ($totalPonderacion2+$personajesSortear[$escoger]->getponderacion())/($cantidadListos2+$i+1);
						$porcentaje = abs(($porcentaje-$torneoActual->getponderacionprom())/$torneoActual->getponderacionprom());
						$porcentaje = 1000 - $porcentaje*1000;
						if($porcentaje<15)
							$porcentaje=15;
						$res = rand(0,1000);
						if($res<$porcentaje||$i==0)
						{
							$totalPonderacion2 += $personajesSortear[$escoger]->getponderacion();
							$cola2personaje->insert($escoger);
							$sigue = false;
							$coladepersonajes->randomquit();
						}
						else
							$escoger=$coladepersonajes->randominit();
					}
				}	
	
				$cuantosVan=0;
				
				$cantidadtotallisto=$cantidadListos1+$cantidadListos2;
				for($i=0;$i<count($datosGrupo);$i++)
				{
					for($j=$cantidadtotallisto;$j<$datosGrupo[$i]["cantidad"];$j++)
					{
						if(count($datosGrupo)/2>$i)
						{
							$escoger=$cola1personaje->randominit();
							$batalla = arrayobjeto($batallasele,"grupo",$datosGrupo[$i]["nombre"]);
							$personajesSortear[$escoger]->setgrupo($datosGrupo[$i]["nombre"]);
							$personajesSortear[$escoger]->update(1,array("grupo"),1,array("id"));
							$nuevaparticiapcion = new participacion($this->BG->con,$personajesSortear[$escoger]->getid(),$batalla->getid());
							$nuevaparticiapcion->save();
							$cola1personaje->randomquit();
						}
						else
						{
							$escoger=$cola2personaje->randominit();
							$batalla = arrayobjeto($batallasele,"grupo",$datosGrupo[$i]["nombre"]);
							$personajesSortear[$escoger]->setgrupo($datosGrupo[$i]["nombre"]);
							$personajesSortear[$escoger]->update(1,array("grupo"),1,array("id"));
							$nuevaparticiapcion = new participacion($this->BG->con,$personajesSortear[$escoger]->getid(),$batalla->getid());
							$nuevaparticiapcion->save();
							$cola2personaje->randomquit();
						}
					}
					$cantidadtotallisto -= $datosGrupo[$i]["cantidad"];
					if($cantidadtotallisto<0)
						$cantidadtotallisto=0;
				}
			}
			else
			{
				$cuantosVan=0;
				for($i=0;$i<count($datosGrupo);$i++)
				{
					if($cuantosVan+$datosGrupo[$i]["cantidad"]>$cantidadListos)
					{
						for($j=0;$j<$datosGrupo[$i]["cantidad"];$j++)
						{
							if($cuantosVan+$j+1>$cantidadListos)
							{
								$escoger=$coladepersonajes->randominit();
								$coladepersonajes->randomquit();
								$personajesSortear[$escoger]->setgrupo($datosGrupo[$i]["nombre"]);
								$personajesSortear[$escoger]->update(1,array("grupo"),1,array("id"));
								$batalla = arrayobjeto($batallasele,"grupo",$datosGrupo[$i]["nombre"]);
								$nuevaparticiapcion = new participacion($this->BG->con,$personajesSortear[$escoger]->getid(),$batalla->getid());
								$nuevaparticiapcion->save();
							}
						}
					}
					$cuantosVan+=$datosGrupo[$i]["cantidad"];
				}				
			}
		}
	}
	function activarBatalla($fecha="")
	{
		$vamosBatallas = new batalla($this->BG->con);
		$vamosBatallas->setestado(-1);
		$vamosBatallas->setfecha($fecha);
		$vamosBatallas = $vamosBatallas->read(true,2,array("estado","AND","fecha"));
		for($i=0;$i<count($vamosBatallas);$i++)
		{
			$vamosBatallas[$i]->setestado(0);
			$vamosBatallas[$i]->update(1,array("estado"),1,array("id"));
		}	
	}
	function ConteoVotos()
	{
		$BatallasActivas=new batalla($this->BG->con);
		$BatallasActivas->setestado(0);
		$BatallasActivas = $BatallasActivas->read(true,1,array("estado"));
		
		$evetoActual = new evento($this->BG->con);
		$evetoActual->setestado(1);
		$evetoActual = $evetoActual->read(false,1,array("estado"));
		
		$ipVotantes = new ip($this->BG->con);
		$ipVotantes->setidevento($evetoActual->getid());
		$ipVotantes->setusada(1);
		$ipVotantes = $ipVotantes->read(true,2,array("idevento","AND","usada"));
		$votosTotales = count($ipVotantes);
			
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		
		$multiplepersonaje = new personajepar($this->BG->con);
		$multiplepersonaje = $multiplepersonaje->read();
					
		for($i=0;$i<count($BatallasActivas);$i++)
		{
			echo $BatallasActivas[$i]->getgrupo()." esta batalla<br>";
			$partBat = new participacion($this->BG->con);
			$partBat->setidbatalla($BatallasActivas[$i]->getid());
			$partBat = $partBat->read(true,1,array("idbatalla"));
			
			$peleaLista = new pelea($this->BG->con);
			$peleaLista->setidbatalla($BatallasActivas[$i]->getid());
			$peleaLista = $peleaLista->read(true,1,array("idbatalla"));
			
			echo " participaciones ".count($partBat)." estan listos ".count($peleaLista)."<br>";
			
			for($j=0;$j<count($partBat);$j++)
			{
				$peleas[$j]["votos"]=0;
				$peleas[$j]["id"]=$partBat[$j]->getidpersonaje();
				$peleas[$j]["listo"]=0;
				
				if(comprobararray($peleaLista,"idpersonaje",$peleas[$j]["id"]))
				{
					$mostrarpelealista = arrayobjeto($peleaLista,"idpersonaje",$peleas[$j]["id"]);
					$peleas[$j]["votos"]=$mostrarpelealista->getvotos();
					$peleas[$j]["listo"]=1;
				}
				
				//echo " id del personaje: ".$peleas[$j]["id"]." estan listos ".$peleas[$j]["listo"]." votos ".$peleas[$j]["votos"]."<br>";
				
				if($peleas[$j]["listo"]==0)
				{
					$votosDelPersonaje = new voto($this->BG->con);
					$votosDelPersonaje->setidbatalla($BatallasActivas[$i]->getid());
					$votosDelPersonaje->setidpersonaje($partBat[$j]->getidpersonaje());
					$votosDelPersonaje = $votosDelPersonaje->read(true,2,array("idbatalla","AND","idpersonaje"),1,array("fecha","ASC"));
					
					//echo "votos totales del personaje ".count($votosDelPersonaje)."<br>";
					
					//$fechaInicio = $BatallasActivas[$i]->getfecha()." ".$torneoActual->gethorainicio();
					//$fechaFinal = cambioFecha($fechaInicio,$torneoActual->getduracionbatalla());
					
					//$estadisticasListas = new estadistica($this->BG->con);
					//$estadisticasListas->setidpersonaje($partBat[$j]->getidpersonaje());
					//$estadisticasListas->setidbatalla($BatallasActivas[$i]->getid());
					//$estadisticasListas = $estadisticasListas->read(true,2,array("idpersonaje","AND","idbatalla"),1,array("fecha","ASC"));
					//$sigueLoop = true;
					//if(count($estadisticasListas)>0)
					//	$k=$estadisticasListas[count($estadisticasListas)-1]->getvotos();
					//else
					//	$k=0;
					//for($l=0;$sigueLoop;$l++)
					//{
					//	if(count($estadisticasListas)<=$l)
					//	{
					//		while($k<count($votosDelPersonaje)&&FechaMayor($fechaInicio,$votosDelPersonaje[$k]->getfecha())==1)
					//			$k++;
					//		$estadisticaNueva = new estadistica($this->BG->con,$partBat[$j]->getidpersonaje(),$BatallasActivas[$i]->getid(),$fechaInicio,$k);
					//		$estadisticaNueva->save();
					//	}
					//	$fechaInicio=cambioFecha($fechaInicio,$torneoActual->getintervalo());
					//	
					//	if(FechaMayor($fechaInicio,$fechaFinal)==1)
					//		$sigueLoop=false;
					//}
					$guardarPelea = new pelea($this->BG->con,$partBat[$j]->getidpersonaje(),$BatallasActivas[$i]->getid(),count($votosDelPersonaje));
					$guardarPelea->save();
				}
			}
			
			$peleaBatalla = new pelea($this->BG->con);
			$peleaBatalla->setidbatalla($BatallasActivas[$i]->getid());
			$peleaBatalla = $peleaBatalla->read(true,1,array("idbatalla"),1,array("votos","DESC"));
			
			$configuracionUsar = new configuracion($this->BG->con);
			$configuracionUsar->setid($BatallasActivas[$i]->getronda());
			$configuracionUsar->setidtorneo($torneoActual->getid());
			$configuracionUsar = $configuracionUsar->read(false,2,array("id","AND","idtorneo"));
			
			$configuracionSig = new configuracion($this->BG->con);
			$configuracionSig->setid($configuracionUsar->getprimproxronda());
			$configuracionSig->setidtorneo($torneoActual->getid());
			$configuracionSig = $configuracionSig->read(false,2,array("id","AND","idtorneo"));
			
			$primeraPos = true;
			$primPos = $configuracionUsar->getprimclas();
			
			if($configuracionSig->getsorteo()==0&&$configuracionUsar->gettipo()!="EXHIB")
			{
				if($configuracionSig->gettipo()=="ELIMI")
					$siggrupo = cambioGrupo($BatallasActivas[$i]->getgrupo(),$configuracionUsar->getnumerogrupos(),$configuracionSig->getnumerogrupos(),"ELIMI");
				elseif($configuracionSig->gettipo()=="ELGRU")
					$siggrupo = cambioGrupo($BatallasActivas[$i]->getgrupo(),$configuracionUsar->getnumerobatallas(),$configuracionSig->getnumerobatallas(),"ELGRU");
			
				$sigbatalla = new batalla($this->BG->con);
				$sigbatalla->setronda($configuracionSig->getid());
				$sigbatalla->setgrupo($siggrupo);
				$sigbatalla->setidtorneo($torneoActual->getid());
				$sigbatalla = $sigbatalla->read(false,3,array("ronda","AND","grupo","AND","idtorneo"));
			}
			
			
			if($configuracionUsar->getsegundo()==1)
			{
				$segPos = $configuracionUsar->getsegclas();
				$configuracionSigSeg = new configuracion($this->BG->con);
				$configuracionSigSeg->setid($configuracionUsar->getsegproxronda());
				$configuracionSigSeg->setidtorneo($torneoActual->getid());
				$configuracionSigSeg = $configuracionSigSeg->read(false,2,array("nombre","AND","idtorneo"));	
				
				$sigseggrupo="N";
				if($configuracionSig->getsorteo()==0&&$configuracionSig->gettipo()=="ELIMI")
					$sigseggrupo = cambioGrupo($BatallasActivas[$i]->getgrupo(),$configuracionUsar->getnumerogrupos(),$configuracionSig->getnumerogrupos(),"ELIMI");
				elseif($configuracionSig->getsorteo()==0&&$configuracionSig->gettipo()=="ELGRU")
					$sigseggrupo = cambioGrupo($BatallasActivas[$i]->getgrupo(),$configuracionUsar->getnumerobatallas(),$configuracionSig->getnumerobatallas(),"ELGRU");		
					
				
				$sigsegbatalla = new batalla($this->BG->con);
				$sigsegbatalla->setronda($configuracionSigSeg->getid());
				$sigsegbatalla->setgrupo($sigseggrupo);
				$sigsegbatalla->setidtorneo($torneoActual->getid());
				if($sigseggrupo!="N")
					$sigsegbatalla = $sigsegbatalla->read(false,3,array("ronda","AND","grupo","AND","idtorneo"));	
			}
			$idGanador="";
			$r=0;
			for($j=0;$j<count($peleaBatalla);$j++)
			{
				if($primeraPos)
				{
					$idGanador.=$peleaBatalla[$j]->getidpersonaje()."-";
					if($j+1<count($peleaBatalla)&&($peleaBatalla[$j]->getvotos()!=$peleaBatalla[$j+1]->getvotos()))
					{
						$primeraPos=false;
						$idGanador.="END";
					}
				}
				if($configuracionUsar->gettipo()!="EXHIB")
					if($j<$primPos)
					{
						$personajeCambiar = arrayobjeto($multiplepersonaje,"id",$peleaBatalla[$j]->getidpersonaje());
						$personajeCambiar->setronda($configuracionUsar->getprimproxronda());
						if($configuracionSig->getsorteo()==1)
							$personajeCambiar->setgrupo("N");
						else
						{
							if($configuracionSig->gettipo()=="ELIMI")
							{
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerogrupos(),$configuracionSig->getnumerogrupos(),"ELIMI"));
								$participacionnueva = new participacion($this->BG->con,$personajeCambiar->getid(),$sigbatalla->getid());
								$participacionnueva->save();
							}
							elseif($configuracionSig->gettipo()=="ELGRU")
							{
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerobatallas(),$configuracionSig->getnumerobatallas(),"ELGRU"));
								$participacionnueva = new participacion($this->BG->con,$personajeCambiar->getid(),$sigbatalla->getid());
								$participacionnueva->save();							
							}
						}
						$personajeCambiar->update(2,array("grupo","ronda"),1,array("id"));
						if($j==$primPos-1&&$j<count($peleaBatalla)-1&&$peleaBatalla[$j]->getvotos()==$peleaBatalla[$j+1]->getvotos())
						{
							$primPos++;
							if($configuracionUsar->getsegundo()==1)
								$segPos++;
						}
						if($j==0)
							$r=1;
						elseif($peleaBatalla[$j]->getvotos()!=$peleaBatalla[$j-1]->getvotos())
							$r=$j+1;
						
						$peleaBatalla[$j]->setposicion($r);
						$peleaBatalla[$j]->setclasifico(1);
						$peleaBatalla[$j]->update(2,array("posicion","clasifico"),2,array("idpersonaje","AND","idbatalla"));
					}
					elseif($configuracionUsar->getsegundo()==1&&$j<$segPos)
					{
						$personajeCambiar = arrayobjeto($multiplepersonaje,"id",$peleaBatalla[$j]->getidpersonaje());
						
						$personajeCambiar->setronda($configuracionUsar->getsegproxronda());
						if($configuracionSigSeg->getsorteo()==1)
							$personajeCambiar->setronda("N");
						else
							if($configuracionSigSeg->gettipo()=="ELIMI")
							{
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerogrupos(),$configuracionSigSeg->getnumerogrupos(),"ELIMI"));
								$participacionnueva = new participacion($this->BG->con,$personajeCambiar->getid(),$sigsegbatalla->getid());
								$participacionnueva->save();
							}
							elseif($configuracionSigSeg->gettipo()=="ELGRU")
							{
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerobatallas(),$configuracionSigSeg->getnumerobatallas(),"ELGRU"));
								$participacionnueva = new participacion($this->BG->con,$personajeCambiar->getid(),$sigsegbatalla->getid());
								$participacionnueva->save();		
							}
						$personajeCambiar->update(2,array("grupo","ronda"),1,array("id"));
						if($j==$segPos-1&&$j<count($peleaBatalla)-1&&$peleaBatalla[$j]->getvotos()==$peleaBatalla[$j+1]->getvotos())
						{
							$segPos++;
						}	
						
						if($j==0)
							$r=1;
						elseif($peleaBatalla[$j]->getvotos()!=$peleaBatalla[$j-1]->getvotos())
							$r=$j+1;
		
						$peleaBatalla[$j]->setposicion($r);
						$peleaBatalla[$j]->setclasifico(1);
						$peleaBatalla[$j]->update(2,array("posicion","clasifico"),2,array("idpersonaje","AND","idbatalla"));					
					}
					else
					{
						$personajeCambiar = arrayobjeto($multiplepersonaje,"id",$peleaBatalla[$j]->getidpersonaje());
						$personajeCambiar->setestado(3);
						$personajeCambiar->update(1,array("estado"),1,array("id"));
						
						if($j==0)
							$r=1;
						elseif($peleaBatalla[$j]->getvotos()!=$peleaBatalla[$j-1]->getvotos())
							$r=$j+1;
						
						$peleaBatalla[$j]->setposicion($r);
						$peleaBatalla[$j]->setclasifico(0);
						$peleaBatalla[$j]->update(2,array("posicion","clasifico"),2,array("idpersonaje","AND","idbatalla"));
					}
				
			}
						
			$BatallasActivas[$i]->setestado(1);
			$BatallasActivas[$i]->setnumerovotos($votosTotales);
			$BatallasActivas[$i]->setganador($idGanador);
			$BatallasActivas[$i]->update(3,array("estado","numerovotos","ganador"),1,array("id"));
			
			$fechalimite = $BatallasActivas[$i]->getfecha()." ".$torneoActual->gethorainicio();
			$fechalimite = cambioFecha($fechalimite,$torneoActual->getduracionbatalla());
			
			$horaLimite = sacarhora($fechalimite).":00";
			$this->creargrafo($BatallasActivas[$i]->getid(),$torneoActual->getintervalo(),$torneoActual->gethorainicio(),$horaLimite,$torneoActual->getmaxmiembrosgraf());
		}
		$this->changeEvento("KILL");
	}//fin funcion conteo votos
	function changeChampionship($nuevoEstado="")
	{
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		$torneoActual->setestado($nuevoEstado);
		$torneoActual->update(1,array("estado"),1,array("id"));
	}
	function changeEvento($nuevoEstado="",$tipo="")
	{
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));

		if($nuevoEstado=="CREAR")
		{
			$nuevoEvento = new evento($this->BG->con,"",1,$torneoActual->getid(),fechaHoraActual(),$tipo);
			$nuevoEvento->save();
		}
		elseif($nuevoEstado=="KILL")
		{
			$nuevoEvento = new evento($this->BG->con);
			$nuevoEvento->setestado(1);
			$nuevoEvento = $nuevoEvento->read(false,1,array("estado"));
			$nuevoEvento->setfechatermino(fechaHoraActual());
			$nuevoEvento->setestado(-1);
			$nuevoEvento->update(2,array("estado","fechatermino"),1,array("id"));
		}
	}
	//instanciaActual-la ronda donde estan los personajes,instanciaPonderar-ronda que se analizara las ponderaciones 
	function calcularPonderacion($instanciaActual="",$instanciaPonderar)
	{
		$personajesutil = new personajepar($this->BG->con);
		$personajesutil->setronda($instanciaActual);
		$personajesutil = $personajesutil->read(true,1,array("ronda"));
		
		$batallutil = new batalla($this->BG->con);
		$batallutil->setronda($instanciaPonderar);
		$batallutil = $batallutil->read(true,1,array("ronda"));
		
		$totalponderacion=0;
		for($i=0;$i<count($batallutil);$i++)
		{
			$pos[0] = 1500;
			$pos[1] = 600;
			$pos[2] = 400;
			$pos[3] = 300;
			$pos[4] = 200;
			$pos[5] = 100;
			$pos[6] = 100;
			$pos[7] = 20;
			$peleapersonaje = new pelea($this->BG->con);
			$peleapersonaje->setidbatalla($batallutil[$i]->getid());
			$peleapersonaje = $peleapersonaje->read(true,1,array("idbatalla"),1,array("votos","DESC"));
			$k=0;
			$calponderacion = 0;
			for($j=0;$j<count($peleapersonaje);$j++)
			{
				if(comprobararray($personajesutil,"id",$peleapersonaje[$j]->getidpersonaje()))
				{
					if($j>0 && $peleapersonaje[$j]->getvotos() == $peleapersonaje[$j-1]->getvotos())
					{}
					else
					{
						if(count($pos)<=$j)
							$calponderacion = $pos[count($pos)-1];
						else
							$calponderacion = $pos[$j];
							
						$calponderacion += $peleapersonaje[$j]->getvotos();
					}		
					$totalponderacion+=$calponderacion;
					$personajemod = arrayobjeto($personajesutil,"id",$peleapersonaje[$j]->getidpersonaje());
					$personajemod->setponderacion($calponderacion);
					$personajemod->update(1,array("ponderacion"),1,array("id"));
				}	
			}
		}
		
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		$torneoActual->setponderacionprom(round($totalponderacion/count($personajesutil)));
		$torneoActual->update(1,array("ponderacionprom"),1,array("id"));
	}
	
	function creargrafo($idbatalla,$intervalo,$horaInicio,$horaLimite,$limitePersonaje)
	{
		$text = "var lineChartData".$idbatalla." = {
			labels :[";
		$sigue = true;
		$batallaactual = new batalla($this->BG->con);
		$batallaactual->setid($idbatalla);
		$batallaactual = $batallaactual->read(false,1,array("id"));
		
		$arreglofecha = array();
		$fecha = $batallaactual->getfecha()." ".$horaInicio;
		$fechafinal = $batallaactual->getfecha()." ".$horaLimite;
		while(FechaMayor($fecha,$fechafinal)!=1)
		{
			$arreglofecha[] = $fecha;
			$fecha = cambioFecha($fecha,$intervalo);
		}
		for($i=0;$i<count($arreglofecha);$i++)
		{
			if($i!=0)
				$text .= ",";
			$text .= "\"".sacarhora($arreglofecha[$i])."\"";
		}
		$text .= "],";
		
		$verparticipacion = new participacion($this->BG->con);
		$verparticipacion->setidbatalla($idbatalla);
		$verparticipacion = $verparticipacion->read(true,1,array("idbatalla"));
		
		$revisarpersonaje = new personajepar($this->BG->con);
		$revisarpersonaje = $revisarpersonaje->read();
		$todospersonajes=array();
		foreach($verparticipacion as $votoparticipante)
		{
			$datospersonaje = array();
			$personaje = arrayobjeto($revisarpersonaje,"id",$votoparticipante->getidpersonaje());

			$datospersonaje["personaje"]=$personaje;
						
			$votocontar = new voto($this->BG->con);
			$votocontar->setidbatalla($batallaactual->getid());
			$votocontar->setidpersonaje($personaje->getid());
			$votocontar = $votocontar->read(true,0,"",0,""," idpersonaje=".$personaje->getid()." AND fecha <= \"".$arreglofecha[count($arreglofecha)-1]."\" AND idbatalla=".$batallaactual->getid()." ");
						
			$datospersonaje["voto"]=count($votocontar);
			$todospersonajes[] = $datospersonaje;
		}
					
		for($i=0;$i<count($todospersonajes);$i++)
			for($j=0;$j<count($todospersonajes)-1;$j++)
			{
				if($todospersonajes[$j]["voto"]<$todospersonajes[$j+1]["voto"])
				{
					$temp = $todospersonajes[$j];
					$todospersonajes[$j] = $todospersonajes[$j+1];
					$todospersonajes[$j+1] = $temp;
				}
			}
		$text .= "			datasets : [";
		
		$borracolores = new colores($this->BG->con);
		$borracolores->setidbatalla($idbatalla);
		$borracolores->delete(1,array("idbatalla"));
		
		for($i=0;$i<$limitePersonaje && $i<count($todospersonajes);$i++)
		{
			if($i!=0)
				$text .=",";
			$color = coloresgraf($i);
			$text .= "				{
					fillColor : \"rgba(".coloresgraf($color,false).",0.1)\",
					strokeColor : \"rgba(".coloresgraf($color,false).",1)\",
					pointColor : \"rgba(".coloresgraf($color,false).",1)\",
					pointStrokeColor : \"#fff\",
					data : [";		
			$conteovotos = 0;
			
			$agregarcolor = new colores($this->BG->con,$todospersonajes[$i]["personaje"]->getid(),$idbatalla,$color);
			$agregarcolor->save();
			$votocontar = new voto($this->BG->con);
			$votocontar->setidbatalla($idbatalla);
			$votocontar->setidpersonaje($todospersonajes[$i]["personaje"]->getid());
			$votocontar = $votocontar->read(true,2,array("idbatalla","AND","idpersonaje"),1,array("fecha","ASC"));
			$text .=$conteovotos;
			for($j=1;$j<count($arreglofecha);$j++)
			{
				$text .=",";
				while($conteovotos<count($votocontar)&&FechaMayor($arreglofecha[$j],$votocontar[$conteovotos]->getfecha())!=-1)
				{
					$conteovotos++;
				}
				$text .=$conteovotos;
			}
			$text .= "]
				}";
		}
		$text .= "				]
		}";
		//$text .= "var myLine = new Chart(document.getElementById(\"graphbatalla".$idbatalla."\").getContext(\"2d\")).Line(lineChartData".$idbatalla.");";

		$fp = fopen("../charts/graph-batalla".$idbatalla.".js", 'w');
		fwrite($fp, $text);
		fclose($fp);
	}
	
	
	
	function grafoenvivo()
	{
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		
		$evetoActual = new evento($this->BG->con);
		$evetoActual->setestado(1);
		$evetoActual = $evetoActual->read(true,1,array("estado"));
		
		if(count($evetoActual)>0)
		{
			$batallaactiva = new batalla($this->BG->con);	
			$batallaactiva->setestado(0);
			$batallaactiva = $batallaactiva->read(true,1,array("estado"));
			for($i=0;$i<count($batallaactiva);$i++)
			{
				$fechalimite = $batallaactiva[$i]->getfecha()." ".$torneoActual->gethorainicio();
				$fechalimite = cambioFecha($fechalimite,$torneoActual->getduracionlive());
				$fechaactual = fechaHoraActual();
				if(FechaMayor($fechaactual,$fechalimite)==1)
					$horaLimite = $fechalimite;
				else
					$horaLimite = $fechaactual;
				$horaLimite = sacarhora($horaLimite).":00";
				$this->creargrafo($batallaactiva[$i]->getid(),$torneoActual->getintervalo(),$torneoActual->gethorainicio(),$horaLimite,$torneoActual->getmaxmiembrosgraf());
			}
		}
	}
	
	//instactual-la ronda donde estan los personajes,insanalizar-la ronda que se analizaran los personajes
	//insproxima-la instancia donde iran los personajes,limite-los personajes total de primera ronda
	function pasarrepechaje($instactual,$insanalizar,$insproxima,$limite)
	{
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		
		$selepersonaje = new personajepar($this->BG->con);
		$selepersonaje->setronda($instactual);
		$selepersonaje->setidtorneo($torneoActual->getid());
		$selepersonaje = $selepersonaje->read(true,2,array("ronda","AND","idtorneo"));
		
		$batallascon = new batalla($this->BG->con);
		$batallascon->setidtorneo($torneoActual->getid());
		$batallascon->setronda($insanalizar);
		$batallascon = $batallascon->read(true,2,array("ronda","AND","idtorneo"));
		
		$todasparticipacion = array();
		$colaar = 0;
		foreach($selepersonaje as $estepersonaje)
		{
			$buspelea = new pelea($this->BG->con);
			$buspelea->setidpersonaje($estepersonaje->getid());
			$buspelea = $buspelea->read(true,1,array("idpersonaje"));
			if(count($buspelea)>0)
			{
				$existe=0;
				$cual=0;
				for($i=0;$i<count($buspelea);$i++)
				{
					for($j=0;$j<count($batallascon);$j++)
					{
						if($buspelea[$i]->getidbatalla() == $batallascon[$j]->getid())
						{
							$existe = 1;	
							$cual = $i;
						}
					}	
				}
				if($existe == 1)
				{
					$sigue=true;
					$fin = $colaar;
					while($sigue)
					{
						if($fin==0)
						{
							$todasparticipacion[0]=$buspelea[$cual];
							$colaar++;
							$sigue=false;
						}
						elseif($todasparticipacion[$fin-1]->getvotos()<$buspelea[$cual]->getvotos())
						{
							$todasparticipacion[$fin] = $todasparticipacion[$fin-1];
							$fin--;
						}
						else
						{
							$todasparticipacion[$fin]=$buspelea[$cual];
							$colaar++;
							$sigue=false;
						}
					}
				}
			}
		}
		
		$cuantolisto = new personajepar($this->BG->con);
		$cuantolisto->setronda($insproxima);
		$cuantolisto->setidtorneo($torneoActual->getid());
		$cuantolisto = $cuantolisto->read(true,2,array("ronda","AND","idtorneo"));
		$estosson = count($cuantolisto);
		$agregar = $limite-$estosson;
		
		for($i=0;$i<$colaar;$i++)
		{
			if($agregar>0)
			{
				$cambiar = arrayobjeto($selepersonaje,"id",$todasparticipacion[$i]->getidpersonaje());
				$cambiar->setronda($insproxima);
				$cambiar->setgrupo("N");
				$cambiar->update(2,array("ronda","grupo"),1,array("id"));
				if($agregar>1)
					$agregar--;
				elseif($i<$colaar-1&&$todasparticipacion[$i]->getvotos()==$todasparticipacion[$i+1]->getvotos())
					$fer=0;
				else
					$agregar--;
			}
			else
			{
				$todasparticipacion[$i]->setclasifico(0);
				$todasparticipacion[$i]->update(1,array("clasifico"),2,array("idpersonaje","AND","idbatalla"));
				
				$cambiar = arrayobjeto($selepersonaje,"id",$todasparticipacion[$i]->getidpersonaje());
				$cambiar->setestado(3);
				$cambiar->update(1,array("estado"),1,array("id"));
				$agregar--;
			}
		}
	}
}
?>