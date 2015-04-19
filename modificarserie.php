<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("modificarserie",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("modificarserie.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$serieactual = new serie($BG->con);
	$serieactual->setid($_GET["idserie"]);
	$serieactual = $serieactual->read(false,1,array("id"));
	
	$pagina = ingcualpag($pagina,"input_1",inputform("nombreserie","Nombre de la serie",$serieactual->getnombre()));
	$pagina = ingcualpag($pagina,"input_2",inputform("nombrecorto","Nombre Corto",$serieactual->getnombrecorto()));
	$pagina = ingcualpag($pagina,"input_3",inputimage("imagenserie","Imagen de la serie","Introduzca una imagen que represente la serie"));
		
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	$textextra="";
	$seriespar = new seriepar($BG->con);
	$seriespar->setidserie($serieactual->getid());
	$seriespar->setidtorneo($torneoActual[0]->getid());
	$seriespar = $seriespar->read(true,2,array("idserie","AND","idtorneo"));
	$pagina = ingcualpag($pagina,"input_4",inputform("Nombretemporada","Nombre de la termporada",$seriespar[0]->getnombre()));
	$pagina = ingcualpag($pagina,"input_6",inputform("anoserie","Año",$seriespar[0]->getano()));
	$valores = array("OVA","ONA","MOVIE","SERIE");
	$opciones = array("OVA","ONA","Pelicula","Serie");
	$pagina = ingcualpag($pagina,"input_7",inputselected("formatoserie","Formato en que salio dicho anime",$valores,$opciones,$seriespar[0]->gettipoformato()));
	$valores = array("1","2","3","4");
	$opciones = array("Invierno","Primavera","Verano","Otoño");
	$pagina = ingcualpag($pagina,"input_8",inputselected("tcours","Temporada de salida del anime",$valores,$opciones,$seriespar[0]->gettcours()));
	$valores = array("1","2","3","4","5");
	$opciones = array("1","2","3","4","4+");
	$pagina = ingcualpag($pagina,"input_9",inputselected("ncours","Duracion del anime en cours",$valores,$opciones,$seriespar[0]->getncours()));
	$pagina = ingcualpag($pagina,"input_10",inputform("idserie","",$_GET["idserie"],"hidden"));
	$BG->close();
	
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$serieactual = new serie($BG->con);
	$serieactual->setid($_POST["idserie"]);
	$serieactual = $serieactual->read(false,1,array("id"));
	$serieactual->setnombre($_POST["nombreserie"]);
	$archivo = uploadimage($_FILES["imagenserie"]);
	if($archivo[0])
		$serieactual->setimagen($archivo[1]);
	$serieactual->setnombrecorto($_POST["nombrecorto"]);
	$serieactual->update(3,array("nombre","imagen","nombrecorto"),1,array("id"));
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$seriespar = new seriepar($BG->con);
	$seriespar->setidserie($serieactual->getid());
	$seriespar->setidtorneo($torneoActual[0]->getid());
	$seriespar = $seriespar->read(true,2,array("idserie","AND","idtorneo"));
	$seriespar[0]->setnombre($_POST["Nombretemporada"]);
	if($archivo[0])
		$seriespar[0]->setimagen($archivo[1]);
	$seriespar[0]->setano($_POST["anoserie"]);
	$seriespar[0]->settipoformato($_POST["formatoserie"]);
	$seriespar[0]->settcours($_POST["tcours"]);
	$seriespar[0]->setncours($_POST["ncours"]);
	$seriespar[0]->update(6,array("nombre","imagen","ano","tipoformato","tcours","ncours"),1,array("id"));
	
	$BG->close();
	Redireccionar("revserie.php");
}