<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("revtorneo");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("revtorneo.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Torneo",arregloTorneo()));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
}

function arregloTorneo()
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneos = new torneo($BG->con);
	$torneos = $torneos->read();
	$objetos = array();
	$objetos[] = array("Nombre","Año","Version","Activo","");
	for($i=0;$i<count($torneos);$i++)
	{
		if($torneos[$i]->getactivo()==1)
			$activo = "SI";
		else
			$activo = "NO";
		$objetos[] = array($torneos[$i]->getnombre(),$torneos[$i]->getano(),$torneos[$i]->getversion(),$activo,"<a href=\"modificartorneo.php?idtorneo=".$torneos[$i]->getid()."\">Modificar</a>");
	}	
	$BG->close();
	return $objetos;
}
?>