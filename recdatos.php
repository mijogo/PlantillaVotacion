<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("recdatos",false,-1);
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("recdatos.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	
	$recoletardato = new recodato($BG->con);
	$recoletardato->setfecha(fechaHoraActual());
	if(!isset($_COOKIE["id_user"]))
		$recoletardato->setiduser("No user");
	else
		$recoletardato->setiduser($_COOKIE["id_user"]);
	if(!isset($_COOKIE["CodePassVote"]))
		$recoletardato->setcodepass("No code");
	else
		$recoletardato->setcodepass($_COOKIE["CodePassVote"]);
	if(!isset($_COOKIE["uniqueCode"]))
		$recoletardato->setuniquecode("No unique");
	else
		$recoletardato->setuniquecode($_COOKIE["uniqueCode"]);
	$recoletardato->setip(getRealIP());
	if(!isset($_SERVER['HTTP_USER_AGENT']))
		$recoletardato->setinfo("No info");
	else
		$recoletardato->setinfo( $_SERVER['HTTP_USER_AGENT']);
	$recoletardato->save();
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}