<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("[NombrePagina]");
	if(!$ClaseMaestra->VerificacionIdentidad([NivelUsuario]))
		Redireccionar("home.php");
	$file = fopen("[NombreArchivoHtml]", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	Logica del Codigo	
}
?>