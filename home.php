<?php
include 'include/masterclass.php';
$ClaseMaestra = new MasterClass("home");
$file = fopen("home.html", "r") or exit("Unable to open file!");
$pagina="";
while(!feof($file))
{
	$pagina .= fgets($file);
}
$ClaseMaestra->Pagina("",$pagina);
?>