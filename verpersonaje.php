<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("verpersonaje",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("verpersonaje.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$personajeantiguo = new personaje($BG->con);
	$personajeantiguo->setid($_GET["idpersonaje"]);
	$personajeantiguo = $personajeantiguo->read(false,1,array("id"));
	
	$personajeactual = new personajepar($BG->con);
	$personajeactual->setidpersonaje($personajeantiguo->getid());
	$personajeactual = $personajeactual->read(false,1,array("idpersonaje"));
	
	$pagina = ingcualpag($pagina,"imagen",$personajeactual->getimagenpeq());
	$pagina = ingcualpag($pagina,"nombre_personaje",$personajeactual->getnombre());
	$pagina = ingcualpag($pagina,"nombre_serie",$personajeantiguo->getserie());
	if($personajeactual->getestado()==1)
		$pagina = ingcualpag($pagina,"estado","Participando");
	else
		$pagina = ingcualpag($pagina,"estado","Eliminado");
	$pagina = ingcualpag($pagina,"ronda",$personajeactual->getronda());
	$pagina = ingcualpag($pagina,"grupo",$personajeactual->getgrupo());
	$pagina = ingcualpag($pagina,"seiyuu",$personajeactual->getseiyuu());
	
	$ClaseMaestra->Pagina("",$pagina);
}