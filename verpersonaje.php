<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("verpersonaje",false,-1);
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("verpersonaje.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	
	$personajeactual = new personajepar($BG->con);
	$personajeactual->setid($_GET["idpersonaje"]);
	$personajeactual = $personajeactual->read(false,1,array("id"));
	
	$pagina = ingcualpag($pagina,"imagen",$personajeactual->getimagenpeq());
	$pagina = ingcualpag($pagina,"nombre_personaje",$personajeactual->getnombre());
	$pagina = ingcualpag($pagina,"nombre_serie",$personajeactual->getserie());
	if($personajeactual->getestado()==1)
		$pagina = ingcualpag($pagina,"estado","Participando");
	elseif($personajeactual->getestado()==2)
		$pagina = ingcualpag($pagina,"estado","Exhibicion");
	else
		$pagina = ingcualpag($pagina,"estado","Eliminado");
	//$pagina = ingcualpag($pagina,"ronda",$personajeactual->getronda());
	//$pagina = ingcualpag($pagina,"grupo",$personajeactual->getgrupo());
	$pagina = ingcualpag($pagina,"seiyuu",$personajeactual->getseiyuu());
	
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$batallascap = new batalla($BG->con);
	$batallascap=$batallascap = $batallascap->read();
	$ronda = new configuracion($BG->con);
	$ronda = $ronda->read();
	
	$datosoersonaje = array();
		
	$participacionesrev = new participacion($BG->con);
	$participacionesrev->setidpersonaje($_GET["idpersonaje"]);
	$participacionesrev = $participacionesrev->read(true,1,array("idpersonaje"));
		
	$datosoersonaje[0][0]="Ronda";
	$datosoersonaje[0][1]="Grupo";
	$datosoersonaje[0][2]="Posición";
	$datosoersonaje[0][3]="Votos";
	$datosoersonaje[0][4]="";
			
	for($j=0;$j<count($participacionesrev);$j++)
	{
		$estabatalla = arrayobjeto($batallascap,"id",$participacionesrev[$j]->getidbatalla());
		$estaronda = arrayobjeto($ronda,"id",$estabatalla->getronda());
		if($estabatalla->getestado()==1)
		{
			$peleaver = new pelea($BG->con);
			$peleaver->setidpersonaje($estepersonaje->getid());
			$peleaver->setidbatalla($participacionesrev[$i]->getidbatalla());
			$peleaver = $peleaver->read(false,2,array("idpersonaje","AND","idbatalla"));
			$guardarper[0]=$estaronda->getnombre();
			$guardarper[1]=$estabatalla->getgrupo();
			$guardarper[2]=$peleaver->getposicion();
			$guardarper[3]=$peleaver->getvotos();
			
			$guardarper[4] = "icon_close_alt2";
			if($peleaver->clasifico()==1)
				$guardarper[4] = "icon_check_alt2";
			$datosoersonaje[rondapos($estabatalla->getronda())]=$guardarper;
		}
		else
		{
			$guardarper[0]=$estaronda->getnombre();
			$guardarper[1]=$estabatalla->getgrupo();
			$guardarper[2]="";
			$guardarper[3]="";
			$guardarper[4]="";
			$datosoersonaje[rondapos($estabatalla->getronda())]=$guardarper;
		}
	}

	$pagina = ingcualpag($pagina,"tabla_actuacion",	tablalinda("Actuación",$datosoersonaje));
	$ClaseMaestra->Pagina("",$pagina);
}