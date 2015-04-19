<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("selpersonaje",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("selpersonaje.html", "r") or exit("Unable to open file!");
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
	
	$series = new seriepar($BG->con);
	$series->setidtorneo($torneoActual[0]->getid());
	$series = $series->read(true,1,array("idtorneo"));
	$text="";
	
	$participaciones = new participacion($BG->con);
	$participaciones->setidbatalla($_GET["idbatalla"]);
	$participaciones = $participaciones->read(true,1,array("idbatalla"));
	$participacionesid = array("");
	for($i=0;$i<count($participaciones);$i++)
		$participacionesid[] = $participaciones[$i]->getidpersonaje();
	for($i=0;$i<count($series);$i++)
	{
		$personajes = new personajepar($BG->con);	
		$personajes->setidserie($series[$i]->getid());
		$personajes = $personajes->read(true,1,array("idserie"));
		$id = array();
		$nombres = array();
		for($j=0;$j<count($personajes);$j++)
		{
			$id[] = $personajes[$j]->getid();
			$nombres[] = $personajes[$j]->getnombre();
		}
		$text.=collapsecheckbox($series[$i]->getid(),$series[$i]->getnombre(),$id,$nombres,$participacionesid);
	}
	
	$pagina = ingcualpag($pagina,"tabla_contenidos",$text);
	$pagina = ingcualpag($pagina,"idbatalla",inputform("idbatalla","",$_GET["idbatalla"],"hidden"));
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$borrarparticipaciones = new participacion($BG->con);
	$borrarparticipaciones->setidbatalla($_POST["idbatalla"]);
	$borrarparticipaciones->delete(1,array("idbatalla"));
	
	$idpersonajes = $_POST['personajes'];
	if(!empty($idpersonajes)) 
	{
		$N = count($idpersonajes);
		for($i=0; $i < $N; $i++)
		{
			$nuevospersonajes = new participacion($BG->con);
			$nuevospersonajes->setidbatalla($_POST["idbatalla"]);
			$nuevospersonajes->setidpersonaje($idpersonajes[$i]);
			$nuevospersonajes->save();
		}
	} 
	$BG->close();
	redireccionar("batalla.php");
}
?>