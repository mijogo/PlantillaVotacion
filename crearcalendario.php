<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("crearcalendario",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("crearcalendario.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$valores = array("SORTE","ACTVA","CONVO","CHTOR","CHEVE","CALPO","INMAT");
	$opciones = array("Sorteo","Activacion de la batalla","Conteo de Votos","Cambio de estado del Torneo","Cambio de evento","Calculo de la ponderacion","Exhibicion");
	
	$pagina = ingcualpag($pagina,"input_1",inputselected("accion","Tipo de Accion",$valores,$opciones));
	$pagina = ingcualpag($pagina,"input_2",inputcalendar("fecha","Fecha","",true));
	
	
	
	$pagina = ingcualpag($pagina,"input_4",inputform("intdat","configuracion entero"));
	$pagina = ingcualpag($pagina,"input_5",inputcalendar("datedat","configuracion fecha",""));
	$pagina = ingcualpag($pagina,"input_6",inputform("stringdat","configuracion string"));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
		
	$calendarioactual = new calendario($BG->con);
	$calendarioactual->setaccion($_POST["accion"]);
	$calendarioactual->setfecha($_POST["fecha"]);
	$calendarioactual->settargetstring($_POST["stringdat"]);
	$calendarioactual->settargetdate($_POST["datedat"]);
	$calendarioactual->settargetint($_POST["intdat"]);
	
	$calendarioactual->setidtorneo($torneoActual->getid());
	$calendarioactual->sethecho(-1);
	
	$calendarioactual->save();
	$BG->close();
	
	Redireccionar("planificacion.php");
	
}