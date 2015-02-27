<?php
include 'include/masterclass.php';
$ClaseMaestra = new MasterClass("login");
if(!$ClaseMaestra->VerificacionIdentidad(1))
	Redireccionar("home.php");
$file = fopen("login.html", "r") or exit("Unable to open file!");
$pagina="";
while(!feof($file))
{
	$pagina .= fgets($file);
}
$ClaseMaestra->Pagina("",$pagina);
?>