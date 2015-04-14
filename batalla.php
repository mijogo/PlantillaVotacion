<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("batalla");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("batalla.html", "r") or exit("Unable to open file!");
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
	
	$muchasBatallas = new batalla($BG->con);
	$muchasBatallas->setidtorneo($torneoActual[0]->getid());
	$muchasBatallas = $muchasBatallas->read(true,1,array("idtorneo"),1,array("fecha","ASC"));
	
	$text ="";
	for($i=0;$i<count($muchasBatallas);$i++)
	{
		if($muchasBatallas[$i]->getestado()==-1)
			$estado = "pendiente";
		elseif($muchasBatallas[$i]->getestado()==0)
			$estado = "activa";
		else
			$estado = "finalizada";

		$text .= inputcalendar("batalla-".$muchasBatallas[$i]->getid(),$muchasBatallas[$i]->getronda()." ".$muchasBatallas[$i]->getgrupo()."<p>Estado : ".$estado."</p>",$muchasBatallas[$i]->getfecha());
	}
	
	$pagina = ingcualpag($pagina,"tabla_contenidos",$text);
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));
	
	$muchasBatallas = new batalla($BG->con);
	$muchasBatallas->setidtorneo($torneoActual[0]->getid());
	$muchasBatallas = $muchasBatallas->read(true,1,array("idtorneo"),1,array("fecha","ASC"));
	
	for($i=0;$i<count($muchasBatallas);$i++)
	{
		$muchasBatallas[$i]->setfecha($_POST["batalla-".$muchasBatallas[$i]->getid()]);
		$muchasBatallas[$i]->update(1,array("fecha"),1,array("id"));
	}
	$BG->close();
	redireccionar("batalla.php");
}