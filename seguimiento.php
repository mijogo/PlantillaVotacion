<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("seguimiento");
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("seguimiento.html", "r") or exit("Unable to open file!");
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
		
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$todoseguimiento = new seguimiento($BG->con);
	$todoseguimiento->setiduser($useranalizar->getid());
	$todoseguimiento->setidtorneo($torneoActual->getid());
	$todoseguimiento = $todoseguimiento->read(true,2,array("iduser","AND","idtorneo"));
	
	$todospersonajes = new personajepar($BG->con);
	$todospersonajes=$todospersonajes->read(true,0,"",2,array("serie","ASC","nombre","ASC")," estado=1 OR estado=3 ");
	
	$batallascap = new batalla($BG->con);
	$batallascap=$batallascap = $batallascap->read();
	$ronda = new configuracion($BG->con);
	$ronda = $ronda->read();
	$arrayseguimiento=array();
	for($i=0;$i<count($todoseguimiento);$i++)
	{
		$datosoersonaje = array();
		$estepersonaje = arrayobjeto($todospersonajes,"id",$todoseguimiento[$i]->getidpersonaje());
		$datosoersonaje["nombre"]=$estepersonaje->getnombre();
		$datosoersonaje["serie"]=$estepersonaje->getserie();
		$datosoersonaje["idpersonaje"]=$estepersonaje->getid();
		
		$participacionesrev = new participacion($BG->con);
		$participacionesrev->setidpersonaje($estepersonaje->getid());
		$participacionesrev = $participacionesrev->read(true,1,array("idpersonaje"));
		for($j=1;$j<9;$j++)
			$datosoersonaje[$j]="";
		for($j=0;$j<count($participacionesrev);$j++)
		{
			$estabatalla = arrayobjeto($batallascap,"id",$participacionesrev[$j]->getidbatalla());
			$estaronda = arrayobjeto($ronda,"id",$estabatalla->getronda());
			if($estabatalla->getestado()==1)
			{
				$peleaver = new pelea($BG->con);
				$peleaver->setidpersonaje($estepersonaje->getid());
				$peleaver->setidbatalla($participacionesrev[$j]->getidbatalla());
				$peleaver = $peleaver->read(false,2,array("idpersonaje","AND","idbatalla"));
				$icono = "icon_close_alt2";
				if($peleaver->getclasifico()==1)
					$icono = "icon_check_alt2";
				$datosoersonaje[rondapos($estabatalla->getronda())]="[".$estabatalla->getgrupo()."] <br>".$peleaver->getposicion()."(".$peleaver->getvotos().")<i class=\"".$icono."\">";
			}
			else
			{
				$datosoersonaje[rondapos($estabatalla->getronda())]=$estabatalla->getgrupo();
			}
		}
		$arrayseguimiento[]=$datosoersonaje;
	}
	$agregar=false;
	
	$listapersonaje = array();
	if(count($todoseguimiento)<10)
	{
		
		$agregar = true;
		foreach($todospersonajes as $esteper)
		{
			if(!comprobararray($todoseguimiento,"idpersonaje",$esteper->getid()))
			{
				$odenamiento = array();
				//$estepersonaje = arrayobjeto($todospersonajes,"id",$esteper->getidpersonaje());
				$odenamiento["nombre"]=$esteper->getnombre()." (".$esteper->getserie().")";
				$odenamiento["id"]=$esteper->getid();
				$listapersonaje[]=$odenamiento;
			}
		}
	}
	$pagina=ingcualpag($pagina,"tablapersonaje",tablaseguimiento($arrayseguimiento,$agregar,$listapersonaje)."Significado <br>[Grupo] <br> Posicion(votos)Clasificacion");
	$ClaseMaestra->Pagina("",$pagina);
	$BG->close();
}
elseif($_GET['action']==2)
{
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	$idpersonaje = $_POST["nuevoseguimiento"];
	$idusuario = $_COOKIE["id_user"];
	$borrarseguimiento = new seguimiento($BG->con);
	$borrarseguimiento->setiduser($idusuario);
	$borrarseguimiento->setidpersonaje($idpersonaje);
	$borrarseguimiento->setidtorneo($torneoActual->getid());
	$borrarseguimiento->save();
	$BG->close();	
	Redireccionar("seguimiento.php");
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	$idpersonaje = $_GET["idperonaje"]	;
	$idusuario = $_COOKIE["id_user"];
	$borrarseguimiento = new seguimiento($BG->con);
	$borrarseguimiento->setiduser($idusuario);
	$borrarseguimiento->setidpersonaje($idpersonaje);
	$borrarseguimiento->setidtorneo($torneoActual->getid());
	$borrarseguimiento->delete(3,array("iduser","AND","idpersonaje","AND","idtorneo"));
	$BG->close();
	Redireccionar("seguimiento.php");
}