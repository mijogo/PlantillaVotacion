<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("agregaserie");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("agregaserie.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$nuevaserie = new serie($BG->con);
	$nuevaserie->setnombre($_POST["nombreserie"]); 
	$archivo = uploadimage($_FILES [ 'imagenserie' ]);
	if($archivo[0])
		$nuevaserie->setimagen($archivo[1]);
	$nuevaserie->setnombrecorto($_POST["nombrecorto"]); 
	$nuevaserie->save();
	
	$nuevaserie = $nuevaserie->read(false,1,array("nombre"));
	$nuevaseriepar = new seriepar($BG->con);
	$nuevaseriepar->setnombre($_POST["nombreseriepar"]);
	if($archivo[0])
		$nuevaseriepar->setimagen($archivo[1]);
	$torneoActual = new torneo($BG->con);
	
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));	
		
	$nuevaseriepar->setidtorneo($torneoActual->getid());
	$nuevaseriepar->setidserie($nuevaserie->getid());
	$nuevaseriepar->setano($_POST["anoserie"]);
	$nuevaseriepar->settipoformato($_POST["tipoformato"]);
	$nuevaseriepar->settcours($_POST["tcours"]);
	$nuevaseriepar->setncours($_POST["ncours"]);
	$nuevaseriepar->save();
	$BG->close();
	Redireccionar("revserie.php");
}
?>