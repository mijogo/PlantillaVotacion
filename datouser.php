<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("datouser",false,-1);
	if(!$ClaseMaestra->VerificacionIdentidad(3))
		Redireccionar("home.php");
	$file = fopen("datouser.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	//$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Menu",arreglomenu()));
	$ClaseMaestra->Pagina("",$pagina);
}