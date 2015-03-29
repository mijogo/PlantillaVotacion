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
	$text ="<input type=\"text\" class=\"form-control\" id=\"versiontorneo\"  name=\"versiontorneo\" placeholder=\"Introduzca la version del torneo\" value=\"".$torneoleer->getversion()."\">";
	$pagina = ingcualpag($pagina,"input_3",$text);	
	$text ="<input type=\"text\" class=\"form-control\" id=\"horainicio\"  name=\"horainicio\" placeholder=\"Introduzca la hora de inicio\" value=\"".$torneoleer->gethorainicio()."\">";
	$pagina = ingcualpag($pagina,"input_4",$text);		
 	$text ="<input type=\"text\" class=\"form-control\" id=\"duracionbatalla\"  name=\"duracionbatalla\" placeholder=\"Introduzca la duración de la batalla en minutos\" value=\"".$torneoleer->getduracionbatalla()."\">";
	$pagina = ingcualpag($pagina,"input_5",$text);
	$text ="<input type=\"text\" class=\"form-control\" id=\"extraconteo\"  name=\"extraconteo\" placeholder=\"Introduzca la duración del tiempo extra en minutos\" value=\"".$torneoleer->getextraconteo()."\">";
	$pagina = ingcualpag($pagina,"input_6",$text);	
			
	$text ="<input type=\"text\" class=\"form-control\" id=\"intervaloconteo\"  name=\"intervaloconteo\" placeholder=\"Introduzca el intervalo del tiempo extra en minutos\" value=\"".$torneoleer->getintervalo()."\">";
	$pagina = ingcualpag($pagina,"input_7",$text);	
	$text ="<input type=\"text\" class=\"form-control\" id=\"duracionlive\"  name=\"duracionlive\" placeholder=\"Introduzca la duracion del contador en vivo en minutos\" value=\"".$torneoleer->getduracionlive()."\">";
	$pagina = ingcualpag($pagina,"input_8",$text);		
	$text ="<input type=\"text\" class=\"form-control\" id=\"maximagrafico\"  name=\"maximagrafico\" placeholder=\"Introduzca la maxima cantidad de participantes en el grafico\" value=\"".$torneoleer->getmaxmiembrosgraf()."\">";
	$pagina = ingcualpag($pagina,"input_9",$text);		
	$text ="<input type=\"text\" class=\"form-control\" id=\"opcionpartida\"  name=\"opcionpartida\" placeholder=\"Introduzca la primera ronda del torneo\" value=\"".$torneoleer->getopcionpartida()."\">";
	$pagina = ingcualpag($pagina,"input_10",$text);		
	
	$text ="<input type=\"hidden\" class=\"form-control\" id=\"idtorneo\"  name=\"idtorneo\" value=\"".$_GET['idtorneo']."\">";
	$pagina = ingcualpag($pagina,"input_11",$text);	
	  
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$TorneoNuevo = new torneo($BG->con);
	$TorneoNuevo->setid($_POST['idtorneo']);
	$TorneoNuevo=$TorneoNuevo->read(false,1,array("id"));
	$TorneoNuevo->setnombre($_POST['nombretorneo']);
	$TorneoNuevo->setano($_POST['anotorneo']);
	$TorneoNuevo->setversion($_POST['versiontorneo']);
	$TorneoNuevo->sethorainicio($_POST['horainicio']);
	$TorneoNuevo->setduracionbatalla($_POST['duracionbatalla']);
	$TorneoNuevo->setextraconteo($_POST['extraconteo']);
	$TorneoNuevo->setintervalo($_POST['intervaloconteo']);
	$TorneoNuevo->setduracionlive($_POST['duracionlive']);
	$TorneoNuevo->setmaxmiembrosgraf($_POST['maximagrafico']);
	$TorneoNuevo->setopcionpartida($_POST['opcionpartida']);
	$TorneoNuevo->update(10,array("nombre","ano","version","horainicio","duracionbatalla","extraconteo","intervalo","duracionlive","maxmiembrosgraf","opcionpartida"),1,array("id"));
	$BG->close();
	Redireccionar("revtorneo.php");
}
?>