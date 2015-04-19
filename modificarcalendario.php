<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("modificarcalendario",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("modificarcalendario.html", "r") or exit("Unable to open file!");
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
	
	$calendarioactual = new calendario($BG->con);
	$calendarioactual->setid($_GET["idcalendario"]);
	$calendarioactual = $calendarioactual->read(false,1,array("id"));
	$BG->close();
	
	$valores = array("SORTE","ACTVA","CONVO","CHTOR","CHEVE","CALPO","INMAT");
	$opciones = array("Sorteo","Activacion de la batalla","Conteo de Votos","Cambio de estado del Torneo","Cambio de evento","Calculo de la ponderacion","Exhibicion");
	
	$pagina = ingcualpag($pagina,"input_1",inputselected("accion","Tipo de Accion",$valores,$opciones,$calendarioactual->getaccion()));
	$pagina = ingcualpag($pagina,"input_2",inputcalendar("fecha","Fecha",$calendarioactual->getfecha(),true));
	
	$valores = array("-1","1");
	$opciones = array("Pendiente","Hecho");
	$pagina = ingcualpag($pagina,"input_3",inputselected("estado","Estado en el calendario",$valores,$opciones,$calendarioactual->gethecho()));
	
	
	$pagina = ingcualpag($pagina,"input_4",inputform("intdat","configuracion entero",$calendarioactual->gettargetint()));
	$pagina = ingcualpag($pagina,"input_5",inputcalendar("datedat","configuracion fecha",$calendarioactual->gettargetdate()));
	$pagina = ingcualpag($pagina,"input_6",inputform("stringdat","configuracion string",$calendarioactual->gettargetstring()));
	$pagina = ingcualpag($pagina,"input_7",inputform("idcalendario","",$_GET["idcalendario"],"hidden"));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));
		
	$calendarioactual = new calendario($BG->con);
	$calendarioactual->setid($_POST["idcalendario"]);
	$calendarioactual = $calendarioactual->read(false,1,array("id"));
	$calendarioactual->setaccion($_POST["accion"]);
	$calendarioactual->setfecha($_POST["fecha"]);
	$calendarioactual->sethecho($_POST["estado"]);
	$calendarioactual->settargetstring($_POST["stringdat"]);
	$calendarioactual->settargetdate($_POST["datedat"]);
	$calendarioactual->settargetint($_POST["intdat"]);
	
	$calendarioactual->update(6,array("accion","fecha","hecho","targetstring","targetdate","targetint"),1,array("id"));
	$BG->close();
	
	Redireccionar("calendario.php");
	
}
