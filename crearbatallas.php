<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("crearbatallas",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("crearbatallas.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$configuraciones = new configuracion($BG->con);
	$configuraciones->setidtorneo($torneoActual[0]->getid());
	$configuraciones = $configuraciones->read(true,1,array("idtorneo"));	
	
	$BG->close();
	
	for($i=0;$i<count($configuraciones);$i++)
	{
		$valoresR[] = $configuraciones[$i]->getid();
		$opcionesR[] = $configuraciones[$i]->getnombre();
	}
	
	$pagina = ingcualpag($pagina,"input_1",inputselected("tipobatalla","Tipo de batalla",$valoresR,$opcionesR));
	$pagina = ingcualpag($pagina,"input_2",inputcalendar("fechabatalla","Fecha de la batalla","2015-01-01"));
	$pagina = ingcualpag($pagina,"input_3",inputform("grupo","Grupo de la batalla"));
	
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$nuevabatalla = new batalla($BG->con);
	$nuevabatalla->setfecha($_POST["fechabatalla"]);
	$nuevabatalla->setronda($_POST["tipobatalla"]);
	$nuevabatalla->setgrupo($_POST["grupo"]);
	$nuevabatalla->setidtorneo($torneoActual[0]->getid());
	$nuevabatalla->setestado(-1);
	$nuevabatalla->save();
	
	$otroconteo = $nuevabatalla->read(true,2,array("fecha","AND","idtorneo"));
	if(count($otroconteo)>1)
	{
	$fecha = $_POST["fechabatalla"]." ".$torneoActual[0]->gethorainicio();
	
		$nuevoschedule = new calendario($BG->con);
		$nuevoschedule->setaccion("CHTOR");
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->sethecho(-1);
		$nuevoschedule->settargetint(2);
		$nuevoschedule->save();
		
		$nuevoschedule = new calendario($BG->con);
		$nuevoschedule->setaccion("ACTBA");
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->sethecho(-1);
		$nuevoschedule->settargetdate($_POST["fechabatalla"]);
		$nuevoschedule->save();
		
		$nuevoschedule = new calendario($BG->con);
		$nuevoschedule->setaccion("CHEVE");
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->sethecho(-1);
		$nuevoschedule->settargetstring("CREAR");
		$nuevoschedule->save();
		
		$fecha = cambioFecha($fecha,$torneoActual[0]->getduracionbatalla());
		
		$nuevoschedule = new calendario($BG->con);
		$nuevoschedule->setaccion("CHTOR");
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->sethecho(-1);
		$nuevoschedule->settargetint(1);
		$nuevoschedule->save();
		
		$fecha = cambioFecha($fecha,$torneoActual[0]->getextraconteo());
			
		$nuevoschedule = new calendario($BG->con);
		$nuevoschedule->setaccion("CONVO");
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->sethecho(-1);
		$nuevoschedule->save();	
	}
	$BG->close();
	redireccionar("batalla.php");
}
?>