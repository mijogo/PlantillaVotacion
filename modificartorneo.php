<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("modificartorneo",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("modificartorneo.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$BG = new DataBase();
	$BG->connect();
	$torneoleer = new torneo($BG->con);
	$torneoleer->setid($_GET['idtorneo']);
	$torneoleer = $torneoleer->read(false,1,array("id"));
	$text ="<input type=\"text\" class=\"form-control\" id=\"nombretorneo\" name=\"nombretorneo\" placeholder=\"Introduzca el nombre del torneo\" value=\"".$torneoleer->getnombre()."\">";
	$pagina = ingcualpag($pagina,"input_1",$text);
	$text ="<input type=\"text\" class=\"form-control\" id=\"anotorneo\"  name=\"anotorneo\" placeholder=\"Introduzca el año del torneo\" value=\"".$torneoleer->getano()."\">";
	$pagina = ingcualpag($pagina,"input_2",$text);
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$TorneoNuevo = new torneo($BG->con);
	$TorneoNuevo->setano($_POST["anotorneo"]);
	$TorneoNuevo->setnombre($_POST["nombretorneo"]);
	$TorneoNuevo->setversion($_POST["versiontorneo"]);
	if($_POST["activotroneo"])
	{
		$torneoactivo = new torneo($BG->con);
		$torneoactivo->setactivo(1);
		$torneoactivo = $torneoactivo->read(true,1,array("activo"));
		if(count($torneoactivo)>0)
		{
			$torneoactivo[0]->setactivo(0);
			$torneoactivo[0]->update(1,array("activo"),1,array("id"));
		}
		$TorneoNuevo->setactivo(1);
	}
	else
		$TorneoNuevo->setactivo(0);
	$TorneoNuevo->save();
	$BG->close();
	Redireccionar("revtorneo.php");
}
?>