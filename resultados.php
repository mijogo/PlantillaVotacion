<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	if(isset($_GET['tipo']))
	{
		$BG = new DataBase();
		$BG->connect();
		
		$torneoActual = new torneo($BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));
		
		if($_GET['tipo']=="ronda")
		{
			if($_GET['idronda']==10)
				$ClaseMaestra = new MasterClass("preliminar");
			if($_GET['idronda']==8)
				$ClaseMaestra = new MasterClass("primeraronda");
			if($_GET['idronda']==7)
				$ClaseMaestra = new MasterClass("segundaronda");
			if($_GET['idronda']==6)
				$ClaseMaestra = new MasterClass("terceraronda");
			if($_GET['idronda']==5)
				$ClaseMaestra = new MasterClass("finalgrupo");
			if($_GET['idronda']==4)
				$ClaseMaestra = new MasterClass("cuartofinal");
			if($_GET['idronda']==1)
				$ClaseMaestra = new MasterClass("exhibicion");
			if(!$ClaseMaestra->VerificacionIdentidad(1))
				Redireccionar("home.php");
			
			$batallasusar = new batalla($BG->con);
			$batallasusar->setronda($_GET['idronda']);
			$batallasusar->setidtorneo($torneoActual->getid());
			$batallasusar = $batallasusar->read(true,2,array("ronda","AND","idtorneo"));
		}
		else
		{
			$ClaseMaestra = new MasterClass("resultados",false,-1);
			if(!$ClaseMaestra->VerificacionIdentidad(1))
				Redireccionar("home.php");
			
			$batallasusar = new batalla($BG->con);
			$batallasusar->setfecha($_GET['fecha']);
			$batallasusar->setidtorneo($torneoActual->getid());
			$batallasusar = $batallasusar->read(true,2,array("fecha","AND","idtorneo"));
		}
		$file = fopen("resultados.html", "r") or exit("Unable to open file!");
		$pagina="";
		while(!feof($file))
		{
			$pagina .= fgets($file);
		}
		$text = "";
		$script = "";
		$datos = "";
		
		$todopersonaje = new personajepar($BG->con);
		$todopersonaje = $todopersonaje->read();
		if(count($batallasusar)>0)
		{
			$rondarev = new configuracion($BG->con);
			$rondarev->setid($batallasusar[0]->getronda());
			$rondarev = $rondarev->read(false,1,array("id"));
			
			if($rondarev->gettipo()=="ELGRU")
				$estado = substr($batallasusar[0]->getgrupo(),0,1);
			
			foreach($batallasusar as $estabatalla)
			{
				$coloresbatalla = new colores($BG->con);
				$coloresbatalla->setidbatalla($estabatalla->getid());
				$coloresbatalla = $coloresbatalla->read(true,1,array("idbatalla"));
				if($rondarev->gettipo()=="ELGRU" && $estado != substr($estabatalla->getgrupo(),0,1)&&$_GET['tipo']=="ronda")
				{
					$text .=panelcollapse("grupo".$estado,"Grupo ".$estado,$datos);
					$estado = substr($estabatalla->getgrupo(),0,1);
					$datos="";
				}
				$todospersonajes = array();
				if($estabatalla->getestado() == 1)
				{
					$cuentavotos = new pelea($BG->con);
					$cuentavotos->setidbatalla($estabatalla->getid());
					$cuentavotos = $cuentavotos->read(true,1,array("idbatalla"),1,array("votos","DESC"));
					$i=1;
					foreach($cuentavotos as $votoparticipante)
					{
						$datospersonaje = array();
						$personaje = arrayobjeto($todopersonaje,"id",$votoparticipante->getidpersonaje());
						$datospersonaje["pos"]=$votoparticipante->getposicion();
						$i++;
						$datospersonaje["img"]=$personaje->getimagenpeq();
						$datospersonaje["nombre"]=$personaje->getnombre();
						$datospersonaje["serie"]=$personaje->getserie();
						if(comprobararray($coloresbatalla,"idpersonaje",$votoparticipante->getidpersonaje()))
							$datospersonaje["color"]="<div style=\"border-radius: 4px; background-color:#".arrayobjeto($coloresbatalla,"idpersonaje",$votoparticipante->getidpersonaje())->getcolor().";\">&nbsp;</div>";
						else
							$datospersonaje["color"]="";
						$datospersonaje["voto"]=$votoparticipante->getvotos();
						$todospersonajes[] = $datospersonaje;
					}
					
				}
				else if($estabatalla->getestado() == 0)
				{
					$participantes = new participacion($BG->con);
					$participantes->setidbatalla($estabatalla->getid());
					$participantes = $participantes->read(true,1,array("idbatalla"));
					
					$posvar=1;
					$pos = $posvar;
					$votoanterior=0;
					foreach($participantes as $votoparticipante)
					{
						$datospersonaje = array();
						$personaje = arrayobjeto($todopersonaje,"id",$votoparticipante->getidpersonaje());
						$datospersonaje["img"]=$personaje->getimagenpeq();
						$datospersonaje["nombre"]=$personaje->getnombre();
						$datospersonaje["serie"]=$personaje->getserie();
						if(comprobararray($coloresbatalla,"idpersonaje",$votoparticipante->getidpersonaje()))
							$datospersonaje["color"]="<div style=\"border-radius: 4px; background-color:#".arrayobjeto($coloresbatalla,"idpersonaje",$votoparticipante->getidpersonaje())->getcolor().";\">&nbsp;</div>";
						else
							$datospersonaje["color"]="";
						
						$votocontar = new voto($BG->con);
						$votocontar->setidbatalla($estabatalla->getid());
						$votocontar->setidpersonaje($personaje->getid());
						//$votocontar = $votocontar->read(true,2,array("idbatalla","AND","idpersonaje"));
						$fechahoraactual = fechaHoraActual();
						$fechalimite = $estabatalla->getfecha()." 00:00:00";
						$fechalimite = cambioFecha($fechalimite,$torneoActual->getduracionlive());
						if(FechaMayor($fechahoraactual,$fechalimite)==1)
							$fechausar = $fechalimite;
						else
							$fechausar = $fechahoraactual;
						
						$votocontar = $votocontar->read(true,0,"",0,""," idpersonaje=".$personaje->getid()." AND fecha <= \"".$fechausar."\" AND idbatalla=".$estabatalla->getid()." ");
						
						
						$datospersonaje["voto"]=count($votocontar);


						$todospersonajes[] = $datospersonaje;
						$votoanterior=$datospersonaje["voto"];

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
					for($i=0;$i<count($todospersonajes);$i++)
					{
						if($i!=0&&$todospersonajes[$i-1]["voto"]!=$todospersonajes[$i]["voto"])
							$pos = $posvar;
					
						$todospersonajes[$i]["pos"]=$pos;
						$posvar++;
					}
					
				}
				else
				{
					$participantes = new participacion($BG->con);
					$participantes->setidbatalla($estabatalla->getid());
					$participantes = $participantes->read(true,1,array("idbatalla"));
					
					$i=1;
					foreach($participantes as $seleccpersonaje)
					{
						$datospersonaje = array();
						$personaje = arrayobjeto($todopersonaje,"id",$seleccpersonaje->getidpersonaje());
						$datospersonaje["pos"]=$i;
						$i++;
						$datospersonaje["img"]=$personaje->getimagenpeq();
						$datospersonaje["nombre"]=$personaje->getnombre();
						$datospersonaje["serie"]=$personaje->getserie();
						
						if(comprobararray($coloresbatalla,"idpersonaje",$seleccpersonaje->getidpersonaje()))
							$datospersonaje["color"]="<div style=\"border-radius: 4px; background-color:#".arrayobjeto($coloresbatalla,"idpersonaje",$seleccpersonaje->getidpersonaje())->getcolor().";\">&nbsp;</div>";
						else
							$datospersonaje["color"]="";
						$datospersonaje["voto"]="";
						$todospersonajes[] = $datospersonaje;
					}	
				}
				$datos.=panelvotos($rondarev->getnombre()." ".$estabatalla->getgrupo(),$todospersonajes);
				
				if($estabatalla->getestado()!=-1)
				{
					$script.="<script src=\"charts/graph-batalla".$estabatalla->getid().".js\"></script><script>
					var myLine = new Chart(document.getElementById(\"graphbatalla".$estabatalla->getid()."\").getContext(\"2d\")).Line(lineChartData".$estabatalla->getid().");</script>";
					$datos.= "<canvas id=\"graphbatalla".$estabatalla->getid()."\" height=\"500\" width=\"1100\"></canvas>";
				}
				if($_GET['tipo']=="ronda" && $rondarev->gettipo()=="ELIMI")
				{
					$text.=panelcollapse("grupo".$estabatalla->getgrupo(),"Grupo ".$estabatalla->getgrupo(),$datos);
					$datos = "";
				}
			}
			if($_GET['tipo']=="fecha" || ($_GET['tipo']=="ronda" && $rondarev->gettipo()=="EXHIB"))
				$text = $datos;
		}
		
		if($_GET['tipo']=="ronda" && $rondarev->gettipo()=="ELGRU")
		{
			$estado = substr($batallasusar[count($batallasusar)-1]->getgrupo(),0,1);
			$text .=panelcollapse("grupo".$estado,"Grupo ".$estado,$datos);
		}
		
		
		
		$pagina = ingcualpag($pagina,"enfrentamientos",$text);
		$ClaseMaestra->Pagina($script,$pagina);
		
	}
	else
	{
		Redireccionar("home.php");
	}
}