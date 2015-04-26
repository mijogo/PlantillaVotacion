<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	if($_GET['idronda']==12)
		$ClaseMaestra = new MasterClass("preliminar");
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("resultados.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$ClaseMaestra->Pagina("",$pagina);
}