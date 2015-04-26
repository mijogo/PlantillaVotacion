<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("planificacion");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("planificacion.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Planificacion",arreglocalendario()));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$calendarioelmi = new calendario($BG->con);
	$calendarioelmi->setid($_GET["idcalendario"]);
	$calendarioelmi->delete(1,array("id"));
	$BG->close();
	Redireccionar("planificacion.php");
	
}

function arreglocalendario()
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));	
	
	$mcalendario = new calendario($BG->con);
	$mcalendario->setidtorneo($torneoActual->getid());
	$mcalendario = $mcalendario->read(true,1,array("idtorneo"),1,array("fecha","ASC"));
	

	$objetos[] = array("Tipo","Fecha","Estado","","");
		
	for($i=0;$i<count($mcalendario);$i++)
	{
		switch($mcalendario[$i]->getaccion())
		{
			case "SORTE":	$accion = "Sorteo";break;
			case "ACTVA": 	$accion = "Activacion de la batalla";break;
			case "CONVO": 	$accion = "Conteo de Votos";break;
			case "CHTOR": 	$accion = "Cambio de estado del Torneo";break;
			case "CHEVE": 	$accion = "Cambio de evento";break;
			case "CALPO": 	$accion = "Calculo de la ponderacion";break;
			case "INMAT": 	$accion = "Exhibicion";break;
			default : $accion="Exhibicion";break;
		}

		switch($mcalendario[$i]->gethecho())
		{
			case -1:	$hecho = "No Activado";break;
			case 1: 	$hecho  = "Activado";break;
			default : $hecho ="Activado";break;
		}
		
		$objetos[] = array($accion,$mcalendario[$i]->getfecha(),$hecho,"<a href=\"modificarcalendario.php?idcalendario=".$mcalendario[$i]->getid()."\">Modificar</a>","<a href=\"planificacion.php?action=1&idcalendario=".$mcalendario[$i]->getid()."\">Eliminar</a>");
			
	}	
	$BG->close();
	return $objetos;
}