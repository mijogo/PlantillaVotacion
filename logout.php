<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("login",false);
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	//setcookie("id_user","",time()-3600);
	CrearCookie("id_user","",0);
	Redireccionar("home.php");
}