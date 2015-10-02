<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("perfil");
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("perfil.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$idperfil=0;
	if(isset($_GET["idperfil"]))
	{
		$idperfil=$_GET["idperfil"];
		$useranalizar = new usuario($BG->con);
		$useranalizar->setid($idperfil);
		$useranalizar = $useranalizar->read(false,1,array("id"));
	}
	else
		$useranalizar=$ClaseMaestra->user;
		
	$datosuser["user"]=$useranalizar->getusername();
	switch($useranalizar->getpoder())
	{
		case 1:$datosuser["nivel"]="Anonimo";break;
		case 2:$datosuser["nivel"]="Usuario Inactivo";break;
		case 3:$datosuser["nivel"]="Usuario normal";break;
		case 4:$datosuser["nivel"]="Administrador";break;
		case 5:$datosuser["nivel"]="Super Administrador";break;
		default:$datosuser["nivel"]="Super Administrador";break;
	}
	if($useranalizar->getsexo()=="mas")
		$datosuser["sexo"]="Masculino";
	if($useranalizar->getsexo()=="fem")
		$datosuser["sexo"]="Femenino";
	if($useranalizar->getedad()!=0)
		$datosuser["edad"]=$useranalizar->getedad()." años";
	else
		$datosuser["edad"]="";
		
	$datosuser["pais"]=nuevonombre($useranalizar->getpais(),$ClaseMaestra);
	
	$datosuser["imgavatar"]=$useranalizar->getimagen();
	$datosuser["banner"]="";
	//$datosuser["linkperfil"]="http://localhost/PlantillaVotacion/perfil.php?idperfil=".$useranalizar->getid();
	$datosuser["linkperfil"]="<div class=\"fb-share-button\" data-href=\"http://msat.moe/perfil.php?idperfil=".$useranalizar->getid()."\" data-layout=\"button\"></div>
	
	<div><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"http://msat.moe/perfil.php?idperfil=".$useranalizar->getid()."\" data-text=\"Mi perfil en Miss Anime Tournament\" data-via=\"msat_2015\" data-lang=\"es\" data-count=\"none\" data-hashtags=\"missanime\">Twittear</a></div>";
	//$datosuser["linkperfil"]="<div class="fb-share-button" data-href="http://msat.uphero.com/home.php" data-layout="button"></div>";
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$todasactividad = new calendario($BG->con);
	$todasactividad->setaccion("ACTBA");
	$todasactividad->sethecho(1);
	$todasactividad=$todasactividad->read(true,2,array("accion","AND","hecho"),1,array("targetdate","DESC"));
	
	$rondarev = new configuracion($BG->con);
	$rondarev = $rondarev->read();
	$revisarpersonaje = new personajepar($BG->con);
	$revisarpersonaje = $revisarpersonaje->read();
	
	$datosactividad = array();
	for($i=0;$i<count($todasactividad);$i++)
	{
		$leerbatallas = new batalla($BG->con);	
		$leerbatallas->setfecha($todasactividad[$i]->gettargetdate());
		$leerbatallas = $leerbatallas->read(true,1,array("fecha"));
		
		$usados = 0;
		$personajes = array();
		$info = "";
		for($j=0;$j<count($leerbatallas);$j++)
		{
			if($j==0)
			{
				$ronda = arrayobjeto($rondarev,"id",$leerbatallas[$j]->getronda());
				$info .= $ronda->getnombre();
			}
			$votos = new votouser($BG->con);	
			$votos->setiduser($useranalizar->getid());
			$votos->setidbatalla($leerbatallas[$j]->getid());
			$votos=$votos->read(true,2,array("iduser","AND","idbatalla"));
			$info .= " ".$leerbatallas[$j]->getgrupo();
			for($k=0;$k<count($votos);$k++)
			{
				$estepersonaje = arrayobjeto($revisarpersonaje,"id",$votos[$k]->getidpersonaje());
				//echo $estepersonaje->getimagenpeq();
				$personajes[]=$estepersonaje->getimagenpeq();
			}
			$usados+=count($votos);
		}
		if($usados>0)
		{
			$nuevo = array();
			$nuevo["datos"]=$todasactividad[$i]->gettargetdate()." ".$info;
			$nuevo["imagenes"]=$personajes;
			$nuevo["link"]="vervotacionusuario.php?iduser=".$useranalizar->getid()."&fecha=".$todasactividad[$i]->gettargetdate();
			$datosactividad[]=$nuevo;
		}
	}
	
	$pagina = ingcualpag($pagina,"tabla_perfil",panelperfil($datosuser,$datosactividad));
	
	
	
	$ClaseMaestra->Pagina("",$pagina);
}

function nuevonombre($nomabr,$clasmas)
{
		$arrabr=$clasmas->paisesabr;
		$arrnom=$clasmas->paisesnom;
		for($i=0;$i<count($arrabr);$i++)
		{
			if($arrabr[$i]==$nomabr)
				return $arrnom[$i];
		}
}