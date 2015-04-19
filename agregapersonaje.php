<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("agregapersonaje");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("agregapersonaje.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$BG = new DataBase();
	$BG->connect();
	$serie = new seriepar($BG->con);
	$serie = $serie->read();
	for($i=0;$i<count($serie);$i++)
	{
		$valores[] = $serie[$i]->getid();
		$opciones[] = $serie[$i]->getnombre();
	}
	$pagina = ingcualpag($pagina,"input_1",inputselected("serie","Serie de anime",$valores,$opciones));
	$pagina = ingcualpag($pagina,"input_2",inputcheckbox("exhibicion","Este personaje participara solo en las exhibiciones"));
	$ClaseMaestra->Pagina("",$pagina);
	$BG->close();
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$nuevopersonaje = new personaje($BG->con);
	$nuevopersonajepar = new personajepar($BG->con);
	
	$busseriepar = new seriepar($BG->con);
	$busseriepar->setid($_POST["serie"]);
	$busseriepar = $busseriepar->read(false,1,array("id"));
	
	$nuevopersonaje->setnombre($_POST["nombrepersonaje"]);
	$nuevopersonaje->setserie($busseriepar->getnombre());
	$nuevopersonaje->setidserie($busseriepar->getidserie());
	$archivo = uploadimage($_FILES["imagenpequepersonaje"]);
	if($archivo[0])
		$nuevopersonaje->setimagen($archivo[1]);
	$nuevopersonaje->setnparticipaciones(1);
	$nuevopersonaje->save();
	$nuevopersonaje = $nuevopersonaje->read(false,1,array("nombre"));
	
	$nuevopersonajepar->setnombre($_POST["nombrepersonaje"]);
	$nuevopersonajepar->setidpersonaje($nuevopersonaje->getid());
	$nuevopersonajepar->setidserie($busseriepar->getid());
	if($archivo[0])
		$nuevopersonajepar->setimagenpeq($archivo[1]);
	$torneoActual = new torneo($BG->con);
	if(isset($_POST["exhibicion"]))
		$torneoActual->setactivo(2);
	else	
		$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	$nuevopersonajepar->setidtorneo($torneoActual[0]->getid());
	$nuevopersonajepar->setestado(1);
	$nuevopersonajepar->setseiyuu($_POST["seiyuupersonaje"]);
	$nuevopersonajepar->save();
	$BG->close();
	Redireccionar("revpersonaje.php");
}
?>