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
		$process = new calendario();
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
					$this->sorteo($target[0],$target[1],$target[2],$target[3]);
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
					$this->calcularPonderacion();
				}
				
				if($process[$i]->getaccion()=="INMAT")
				{
					$this->ingresarMatch();
				}			
				$process[$i]->setHecho(1);
				$process[$i]->update(1,array("hecho"),1,array("id"));
				$lognew = new log(-10,$process[$i]->getaccion(),$fechaActual,1,"system",$process[$i]->gettargetstring()." ".$process[$i]->gettargetdate()." ".$process[$i]->gettargetint());
				$lognew->save(); 
			}
			else
				$sigue=false;
		}
	}
	function sorteo($instancia="",$numeroGrupo="",$tipo=0,$seed=0)
	{
		if($tipo == "ELIMI")
		{		
			$torneoActual = new torneo();
			$torneoActual->setactivo(1);
			$torneoActual = $torneoActual->read(false,1,array("activo"));
			
			$newConf = new configuracion();
			$newConf->setnombre($instancia);
			$newConf->setidtorneo($torneoActual->getid());
			$newConf = $newConf->read(false,2,array("nombre","idtorneo"));
			
			$personajeTotal = new personajepar();
			$personajeTotal->setronda($instancia);
			$personajeTotal = $personajeTotal->read(true,1,array("ronda"));
			$numeroTotal = count($personajeTotal);
			$cantidadGrupo = floor($numeroTotal/$newConf->getnumerogrupos());
			if(($personajeTotal-($cantidadGrupo*$newConf->getnumerogrupos()))>=$numeroGrupo)
				$cantidadGrupo++;
				
			$perListos = new personajepar();
			$perListos->setronda($instancia);
			$perListos->setgrupo($numeroGrupo);
			$perListos->setidtorneo($torneoActual->getid());
			$perListos = $perListos->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
			$cantidadListos = count($perListos);
			
			$personajesSortear = new personajepar();
			$personajesSortear->setronda($instancia);
			$personajesSortear->setgrupo("N");
			$personajesSortear->setidtorneo($torneoActual->getid());
			$personajesSortear = $personajesSortear->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
			
			for($i=0;$i<count($personajesSortear);$i++)
			{
				$perSort[$i]["prox"]=($i+1)%count($personajesSortear);
				$perSort[$i]["ant"]=($i-1)%count($personajesSortear);
				$perSort[$i]["act"]=0;
			}
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
					$escoger=rand(0,count($personajesSortear)-1);
					$sigue=true;
					while($sigue)
					{
						if($perSort[$escoger]["act"]==1)
							$escoger=$perSort[$escoger]["prox"];
						else
						{
							$personajesSortear[$escoger]->setgrupo($numeroGrupo);
							$personajesSortear[$escoger]->update(1,array("grupo"),1,array("id"));
							$perSort[$escoger]["act"]=1;
							$temSort = $perSort[$escoger];
									
							$tempProx=$perSort[$escoger]["prox"];
							while($perSort[$tempProx]["act"]==1)
								$tempProx=$perSort[$tempMov]["prox"];
							$tempAnt=$perSort[$escoger]["ant"];
							while($perSort[$tempAnt]["act"]==1)
								$tempAnt=$perSort[$tempAnt]["ant"];
										
							$perSort[$escoger]["prox"]=$tempProx;
							$perSort[$escoger]["ant"]=$tempAnt;
							$perSort[$tempAnt]["prox"]=$tempProx;
							$perSort[$tempProx]["ant"]=$tempAnt;
							$sigue=false;
						}
					}
				}
			}
		}
		elseif($tipo == "ELGRU")
		{
			$torneoActual = new torneo();
			$torneoActual->setactivo(1);
			$torneoActual = $torneoActual->read(false,1,array("activo"));
			
			$newConf = new configuracion();
			$newConf->setnombre($instancia);
			$newConf->setidtorneo($torneoActual->getid());
			$newConf = $newConf->read(false,2,array("nombre","idtorneo"));
			
			$personajeTotal = new personajepar();
			$personajeTotal->setronda($instancia);
			$personajeTotal = $personajeTotal->read(true,1,array("ronda"));
			$numeroTotal = count($personajeTotal);
			
			$totalDelGrupo = 0;
			$cantidadListos = 0;
			$totalPonderacion= 0;
			for($i=0;$i<$newConf->getnumerobatallas();$i++)
			{
				$datosGrupo[$i]["cantidad"] = floor($numeroTotal/($newConf->getnumerogrupos()*$newConf->getnumerobatallas()));
				$datosGrupo[$i]["nombre"] = $numeroGrupo."-".($i+1);
				if(($personajeTotal-($cantidadGrupo*$newConf->getnumerogrupos()))>=convertNumber($numeroGrupo)+$i*$newConf->getnumerogrupos())
					$datosGrupo[$i]["cantidad"]++;
				$totalDelGrupo += $datosGrupo[$i]["cantidad"];
				
				$perListos = new personajepar();
				$perListos->setronda($instancia);
				$perListos->setgrupo($datosGrupo[$i]["nombre"]);
				$perListos->setidtorneo($torneoActual->getid());
				$perListos = $perListos->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
				$cantidadListos += count($perListos);
				if(count($perListos)>0&&$seed==1)
					for($j=0;$j<count($perListos);$j++)
						$totalPonderacion+=$perListos[$j]->getponderacion();
			}
			$personajesSortear = new personajepar();
			$personajesSortear->setronda($instancia);
			$personajesSortear->setgrupo("N");
			$personajesSortear->setidtorneo($torneoActual->getid());
			$personajesSortear = $personajesSortear->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
			for($i=0;$i<count($personajesSortear);$i++)
			{
				$perSort[$i]["prox"]=($i+1)%count($personajesSortear);
				$perSort[$i]["ant"]=($i-1)%count($personajesSortear);
				$perSort[$i]["act"]=0;
			}
			if($seed==1)
			{
				for($i=$cantidadListos;$i<$totalDelGrupo;$i++)
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
							if($porcentaje<15)
								$porcentaje=15;
							$res = rand(0,1000);
							if($res<$porcentaje)
							{
								$sorGrupo[$i-$cantidadListos]["prox"]=($i-$cantidadListos+1)%(count($totalDelGrupo)-$cantidadListos);
								$sorGrupo[$i-$cantidadListos]["ant"]=($i-$cantidadListos-1)%(count($totalDelGrupo)-$cantidadListos);
								$sorGrupo[$i-$cantidadListos]["act"]=0;
								$sorGrupo[$i-$cantidadListos]["id"]=$escoger;

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
											
								$totalPonderacion += $personajesSortear[$escoger]->getponderacion();							
							}
							else
								$escoger=$perSort[$escoger]["prox"];
						}
					}
				}

				$cuantosVan=0;
				for($i=0;$i<count($datosGrupo);$i++)
				{
					$ponderacionGrupo=0;
					if($cuantosVan+$datosGrupo[$i]["cantidad"]>$cantidadListos)
					{
						if($cuantosVan < $cantidadListos)
						{
							$personajesUsados = new personajepar();
							$personajesUsados ->setronda($instancia);
							$personajesUsados ->setgrupo($datosGrupo[$i]["nombre"]);
							$personajesUsados ->setidtorneo($torneoActual->getid());
							$personajesUsados = $personajesUsados->read(true,3,array("ronda","AND","grupo","AND","idtorneo"));
						}
						for($j=0;$j<$datosGrupo[$i]["cantidad"];$j++)
						{
							if($cuantosVan+$j+1>$cantidadListos)
							{
								$escoger=rand(0,count($sorGrupo)-1);
								$sigue=true;
								while($sigue)
								{
									if($sorGrupo[$escoger]["act"]==1)
										$escoger=$sorGrupo[$escoger]["prox"];
									else
									{
										$porcentaje = ($ponderacionGrupo+$personajesSortear[$sorGrupo[$escoger]["id"]]->getponderacion())/($j+1);
										$porcentaje = abs(($porcentaje-$torneoActual->getponderacionprom())/$torneoActual->getponderacionprom());
										$porcentaje = 1000 - $porcentaje*1000;
										if($porcentaje<50)
											$porcentaje=50;
										$res = rand(0,1000);
										if($res<$porcentaje)
										{			
											$personajesSortear[$sorGrupo[$escoger]["id"]]->setgrupo($datosGrupo[$i]["nombre"]);
											$personajesSortear[$sorGrupo[$escoger]["id"]]->update(1,array("grupo"),1,array("id"));
											
											$sorGrupo[$escoger]["act"]=1;
											$temSort = $sorGrupo[$escoger];
														
											$tempProx=$sorGrupo[$escoger]["prox"];
											while($sorGrupo[$tempProx]["act"]==1&&$sorGrupo[$tempMov]["prox"]!=$escoger)
												$tempProx=$sorGrupo[$tempMov]["prox"];
											$tempAnt=$sorGrupo[$escoger]["ant"];
											while($sorGrupo[$tempAnt]["act"]==1&&$sorGrupo[$tempMov]["ant"]!=$escoger)
												$tempAnt=$sorGrupo[$tempAnt]["ant"];
															
											$sorGrupo[$escoger]["prox"]=$tempProx;
											$sorGrupo[$escoger]["ant"]=$tempAnt;
											$sorGrupo[$tempAnt]["prox"]=$tempProx;
											$sorGrupo[$tempProx]["ant"]=$tempAnt;
											$sigue=false;
														
											$ponderacionGrupo += $personajesSortear[$sorGrupo[$escoger]["id"]]->getponderacion();							
										}
										else
											$escoger=$perSort[$escoger]["prox"];
									}
								}
							}
							else
							{
								$ponderacionGrupo += $personajesUsados[$j]->getponderacion();
							}
						}
					}
					$cuantosVan+=$datosGrupo[$i]["cantidad"];
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
								$escoger=rand(0,count($perSort)-1);
								$sigue=true;
								while($sigue)
								{
									if($sorGrupo[$escoger]["act"]==1)
										$escoger=$sorGrupo[$escoger]["prox"];
									else
									{
										$personajesSortear[$escoger]->setgrupo($datosGrupo[$i]["nombre"]);
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
									}
								}
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
		$vamosBatallas = new batalla();
		$vamosBatallas->setactiva(-1);
		$vamosBatallas->setfecha($fecha);
		$vamosBatallas = $vamosBatallas->read(true,2,array("activa","AND","fecha"));
		for($i=0;$i<count($vamosBatallas);$i++)
		{
			$vamosBatallas[$i]->setActiva(0);
			$vamosBatallas[$i]->update(1,array("activa"),1,array("id"));
		}	
	}
	function ConteoVotos()
	{
		$BatallasActivas=new batalla();
		$BatallasActivas->setestado(0);
		$BatallasActivas = $BatallasActivas->read(true,1,array(estado));
		
		$evetoActual = new evento();
		$evetoActual->setestado(1);
		$evetoActual = $evetoActual->read(false,1,array("estado"));
		
		$ipVotantes = new ip();
		$ipVotantes->setidevento($evetoActual->getid());
		$ipVotantes->setusada(1);
		$ipVotantes = $ipVotantes->read(true,2,array("evento","AND","usada"));
		$votosTotales = count($ipVotantes);
		
		for($i=0;$i<count($BatallasActivas);$i++)
		{
			$torneoActual = new torneo();
			$torneoActual->setactivo(1);
			$torneoActual = $torneoActual->read(false,1,array("activo"));
		
			$partBat = new participacion();
			$partBat->setidbatalla($BatallasActivas[$i]->getid());
			$partBat = $partBat->read(true,1,array("idbatalla"));
			
			$peleaLista = new pelea();
			$peleaLista->setidbatalla($BatallasActivas[$i]->getid());
			$peleaLista = $peleaLista->read(true,1,array("idbatalla"));
			
			for($i=0;$i<count($partBat);$i++)
			{
				$peleas[$i]["votos"]=0;
				$peleas[$i]["id"]=$partBat[$i]->getidpersonaje();
				$peleas[$i]["listo"]=0;
				for($j=0;$j<count($peleaLista);$j++)
					if($peleaLista[$j]->getidpersonaje()==$peleas[$i]["id"])
					{
						$peleas[$i]["votos"]=$peleaLista[$j]->getvotos();
						$peleas[$i]["listo"]=1;
					}
				if($peleas[$i]["listo"]==0)
				{
					$votosDelPersonaje = new voto();
					$votosDelPersonaje->setidbatalla($partBat[$i]->getidpersonaje());
					$votosDelPersonaje->setidpersonaje($BatallasActivas[$i]->getid());
					$votosDelPersonaje = $votosDelPersonaje->read(true,2,array("idbatalla","AND","idpersonaje"),1,array("fecha","ASC"));
					
					$fechaInicio = $BatallasActivas[$i]->getfecha()." ".$torneoActual->gethorainicio();
					
					$estadisticasListas = new estadistica();
					$estadisticasListas->setidpersonaje($partBat[$i]->getidpersonaje());
					$estadisticasListas->setidbatalla($BatallasActivas[$i]->getid());
					$estadisticasListas = $estadisticasListas->read(true,2,array("idpersonaje","AND","idbatalla"),1,array("fecha","ASC"));
					$sigueLoop = true;
					if(count($estadisticasListas)>0)
						$k=$estadisticasListas[count($estadisticasListas)-1]->getvotos();
					else
						$k=0;
					for($j=0;$sigueLoop;$j++)
					{
						if(count($estadisticasListas)<=$j)
						{
							while(FechaMayor($fechaInicio,$votosDelPersonaje[$k]->getfecha()&&$k<count($votosDelPersonaje))==1)
								$k++;
							$estadisticaNueva = new estadistica($partBat[$i]->getidpersonaje(),$BatallasActivas[$i]->getid(),$fechaInicio,$k);
							$estadisticaNueva->save();
						}
						cambioFecha($fechaInicio,$torneoActual->getintervalo());
						if($k>=count($votosDelPersonaje))
							$sigueLoop=false;
					}
					$guardarPelea = new pelea($partBat[$i]->getidpersonaje(),$BatallasActivas[$i]->getid(),count($votosDelPersonaje));
					$guardarPelea->save();
				}
			}
			
			$peleaBatalla = new pelea();
			$peleaBatalla->setidbatalla($BatallasActivas[$i]->getid());
			$peleaBatalla = $peleaBatalla->read(true,1,array("idbatalla"),1,array("votos","DESC"));
			
			$configuracionUsar = new configuracion();
			$configuracionUsar->setnombre($BatallasActivas[$i]->getronda());
			$configuracionUsar->setidtorneo($torneoActual->getid());
			$configuracionUsar = $configuracionUsar->read(false,2,array("nombre","AND","idtorneo"));
			
			$configuracionSig = new configuracion();
			$configuracionSig->setnombre($configuracionUsar->getprimproxronda());
			$configuracionSig->setidtorneo($torneoActual->getid());
			$configuracionSig = $configuracionSig->read(false,2,array("nombre","AND","idtorneo"));
			
			$primeraPos = true;
			$primPos = $configuracionUsar->getprimclas();
			if($configuracionUsar->getsegundo()==1)
			{
				$segPos = $configuracionUsar->getsegclas();
				$configuracionSigSeg = new configuracion();
				$configuracionSigSeg->setnombre($configuracionUsar->getsegproxronda());
				$configuracionSigSeg->setidtorneo($torneoActual->getid());
				$configuracionSigSeg = $configuracionSigSeg->read(false,2,array("nombre","AND","idtorneo"));				
			}
			$idGanador="";
				
			for($i=0;$i<count($peleaBatalla);$i++)
			{
				if($primeraPos)
				{
					$idGanador.=$peleaBatalla[$i]->getidpersonaje()."-";
					if($i+1<count($peleaBatalla)&&($peleaBatalla[$i]->getvotos()!=$peleaBatalla[$i+1]->getvotos()))
					{
						$primeraPos=false;
						$idGanador.="END";
					}
				}
				if($configuracionUsar->gettipo()!="EXHIB")
					if($i<$primPos)
					{
						$personajeCambiar = new personajepar();
						$personajeCambiar->setid($peleaBatalla[$i]->getidpersonaje());
						$personajeCambiar = $personajeCambiar->read(false,1,array("id"));
						
						$personajeCambiar->setronda($configuracionUsar->getprimproxronda());
						if($configuracionSig->getsorteo()==1)
							$personajeCambiar->setgrupo("N");
						else
						{
							if($configuracionSig->gettipo()=="ELIMI")
							{
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerogrupos(),$configuracionSig->getnumerogrupos(),"ELIMI"));
							}
							elseif($configuracionSig->gettipo()=="ELGRU")
							{
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerobatallas(),$configuracionSig->getnumerobatallas(),"ELGRU"));
							}
						}
						$personajeCambiar->update(2,array("grupo","ronda"),1,array("id"));
						if($i==$primPos-1&&$i<count($peleaBatalla)-1&&$peleaBatalla[$i]->getvotos()==$peleaBatalla[$i+1]->getvotos())
						{
							$primeraPos++;
							if($configuracionUsar->getsegundo()==1)
								$segPos++;
						}
					}
					elseif($configuracionUsar->getsegundo()==1&&$i<$segPos)
					{
						$personajeCambiar = new personajepar();
						$personajeCambiar->setid($peleaBatalla[$i]->getidpersonaje());
						$personajeCambiar = $personajeCambiar->read(false,1,array("id"));
						
						$personajeCambiar->setronda($configuracionUsar->getsegproxronda());
						if($configuracionSigSeg->getsorteo()==1)
							$personajeCambiar->setronda("N");
						else
							if($configuracionSigSeg->gettipo()=="ELIMI")
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerogrupos(),$configuracionSigSeg->getnumerogrupos(),"ELIMI"));
							elseif($configuracionSigSeg->gettipo()=="ELGRU")
								$personajeCambiar->setgrupo(cambioGrupo($personajeCambiar->getgrupo(),$configuracionUsar->getnumerobatallas(),$configuracionSigSeg->getnumerobatallas(),"ELGRU"));
						$personajeCambiar->update(2,array("grupo","ronda"),1,array("id"));
						if($i==$segPos-1&&$i<count($peleaBatalla)-1&&$peleaBatalla[$i]->getvotos()==$peleaBatalla[$i+1]->getvotos())
						{
							$segPos++;
						}						
					}
					else
					{
						$personajeCambiar = new personajepar();
						$personajeCambiar->setid($peleaBatalla[$i]->getidpersonaje());
						$personajeCambiar = $personajeCambiar->read(false,1,array("id"));
						$personajeCambiar->setestado(3);
						$personajeCambiar->update(1,array("estado"),1,array("id"));
					}
			}
						
			$BatallasActivas[$i]->setestado(1);
			$BatallasActivas[$i]->setnumerovotos($votosTotales);
			$BatallasActivas[$i]->setganador($idGanador);
			$BatallasActivas[$i]->update(3,array("estado","numerovotos","ganador"),1,array("id"));
		}
		changeEvento("KILL");
	}//fin funcion conteo votos
	function changeChampionship($nuevoEstado="")
	{
		$torneoActual = new torneo();
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		$torneoActual->setestado($nuevoEstado);
		$torneoActual->update(1,array("estado"),1,array("id"));
	}
	function changeEvento($nuevoEstado="",$tipo="")
	{
		$torneoActual = new torneo();
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));

		if($nuevoEstado=="CREAR")
		{
			$nuevoEvento = new evento("",1,$torneoActual->getid(),fechaHoraActual(),$tipo);
			$nuevoEvento->save();
		}
		elseif($nuevoEstado=="KILL")
		{
			$nuevoEvento = new evento();
			$nuevoEvento->setestado(1);
			$nuevoEvento = $nuevoEvento->read(false,1,array("estado"));
			$nuevoEvento->setfechatermino(fechaHoraActual());
			$nuevoEvento->setestado(-1);
			$nuevoEvento->update(2,array("estado","fechatermino"),1,array("id"));
		}
	}
	function calcularPonderacion($instanciaActual="",$instanciaPonderar)
	{
		$personajesutil = new personajepar();
		$personajesutil->setronda($instanciaActual);
		$personajesutil = $personajesutil->read(true,1,array("ronda"));
		
		$batallutil = new batalla();
		$batallutil->setronda($instanciaPonderar);
		$batallutil = $batallutil->read(true,1,array("ronda"));
		
		for($i=0;$i<count($batallutil);$i++)
		{
			$pos[0] = 1000;
			$pos[1] = 500;
			$pos[2] = 300;
			$pos[3] = 200;
			$pos[4] = 150;
			$pos[5] = 150;
			$pos[6] = 150;
			$pos[7] = 150;
			$pos[8] = 100;
			$pos[9] = 100;
			$pos[10] = 100;
			$pos[11] = 100;
			$pos[12] = 100;
			$pos[13] = 100;
			$pos[14] = 60;
			$pos[15] = 60;
			$pos[16] = 60;
			$pos[17] = 60;
			$pos[18] = 60;
			$pos[19] = 40;
			$participaciones = new participacion();
			$participaciones->setidbatalla($batallutil[$i]->getid());
			$participaciones = $participaciones->read(true,1,array("idbatalla"));
			$pelearealizada = new pelea();
			$pelearealizada->setidbatalla($batallutil[$i]->getid());
			$pelearealizada = $pelearealizada->read(true,1,array("idbatalla"));
			$k=0;
			$totalponderacion=0;
			for($j=0;$j<count($personajesutil);$j++)
			{
				if(comprobararray($participaciones,"idpersonaje",$personajesutil[$j]->getid()))
				{
					$grupoponderar[$i][$k][0]=$personajesutil[$i];
					$grupoponderar[$i][$k][1]=arrayobjeto($pelearealizada,"idpersonaje",$personajesutil[$j]->getid())->getvotos();
					$grupoponderar[$i][$k][2]=0;
					$k++;	
				}
			}
			for($j=0;$j<count($grupoponderar[$i])-1;$j++)
			{
				for($l=0;$l<count($grupoponderar[$i])-1;$l++)
				{
					if($grupoponderar[$i][$l][1] < $grupoponderar[$i][$l+1][1])
					{
						$temp = $grupoponderar[$i][$l+1];
						$grupoponderar[$i][$l+1] = $grupoponderar[$i][$l];
						$grupoponderar[$i][$l] = $temp;
					}
				}	
			}
			$totalvotos=0;
			for($j=0;$j<count($grupoponderar[$i])-1;$j++)
			{
				$totalvotos += $grupoponderar[$i][$j][1];
				if($j!=0 && $grupoponderar[$i][$j-1][1] == $grupoponderar[$i][$j][1])
				{
					$grupoponderar[$i][$j][2] = $grupoponderar[$i][$j-1][2];
				}
				else
				{
					$posicion = $j;
					if(count($pos)<=$j)
						$posicion = count($pos)-1;
					$grupoponderar[$i][$j][2] = $pos[$posicion];
				}
			}	
			for($j=0;$j<count($grupoponderar[$i])-1;$j++)
			{
				$grupoponderar[$i][$j][2] += round(1000*($grupoponderar[$i][$j][1]/$totalvotos));
				$grupoponderar[$i][$j][0]->setponderacion($grupoponderar[$i][$j][2]);
				$grupoponderar[$i][$j][0]->update(1,array("ponderacion"),1,array("id"));
				$totalponderacion += $grupoponderar[$i][$j][2];
			}
		}
		
		$torneoActual = new torneo();
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		$torneoActual->setponderacionprom(round($totalponderacion/count($personajesutil)));
		$torneoActual->update(1,array("ponderacionprom"),1,array("id"));
	}
}
?>