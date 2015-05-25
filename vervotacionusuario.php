<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("vervotacionusuario",false,-2);
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("vervotacionusuario.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	
	$text ="";

	
	$idperfil=$_GET["iduser"];
	$useranalizar = new usuario($BG->con);
	$useranalizar->setid($idperfil);
	$useranalizar = $useranalizar->read(false,1,array("id"));
	$pagina = ingcualpag($pagina,"Username",$useranalizar->getusername());
	$pagina = ingcualpag($pagina,"img_avatar",$useranalizar->getimagen());
			
	$batallasactivas = new batalla($BG->con);	
	$batallasactivas->setfecha($_GET["fecha"]);
	$batallasactivas = $batallasactivas->read(true,1,array("fecha"));
	$cantidadBatallas = count($batallasactivas);
				
	$peronajesparticipantes = new personajepar($BG->con);
	$peronajesparticipantes = $peronajesparticipantes->read();
				
	for($i=0;$i<$cantidadBatallas;$i++)
	{
		$arrawpersonaje = array();	
		$datos = "";
					
		$configuracionuso = new configuracion($BG->con);
		$configuracionuso->setid($batallasactivas[$i]->getronda());
		$configuracionuso = $configuracionuso->read(false,1,array("id"));
					
		$votoactual = new votouser($BG->con);
		$votoactual->setidbatalla($batallasactivas[$i]->getid());
		$votoactual->setiduser($useranalizar->getid());
		$votoactual = $votoactual->read(true,2,array("idbatalla","AND","iduser"));
		foreach($votoactual as $participante)
		{
			$arrawpersonaje[] = arrayobjeto($peronajesparticipantes,"id",$participante->getidpersonaje());
		}
		$arrawpersonaje = ordenarpersonajes($arrawpersonaje);
		foreach($arrawpersonaje as $participante)
			$datos.=panelvotar($participante->getnombre(),$participante->getid(),$participante->getimagenpeq(),$participante->getserie(),false);	
		$text .= lugarvotacion($configuracionuso->getnombre()." ".$batallasactivas[$i]->getgrupo(),$datos);

	}
	$pagina = ingcualpag($pagina,"votausuario",$text);
	$ClaseMaestra->Pagina("",$pagina);
}