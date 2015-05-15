<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("seguimiento");
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("seguimiento.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$BG = new DataBase();
	$BG->connect();
	$idperfil=0;
	if(isset($_GET["idperfil"]))
	{
		$idperfil=$_GET["idperfil"];
		$useranalizar = new usuario($BG->con);
		$useranalizar->setid($idperfil);
		$useranalizar = $useranalizar->read(false,1,array("id"));
	}
	else
		$useranalizar=$ClaseMaestra->user;
		
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$todoseguimiento = new seguimiento($BG->con);
	$todoseguimiento->setiduser($useranalizar->getid());
	$todoseguimiento->setidtorneo($torneoActual->getid());
	$todoseguimiento = $todoseguimiento->read(true,2,array("iduser","AND","idtorneo"));
	$ClaseMaestra->Pagina("",$pagina);
}