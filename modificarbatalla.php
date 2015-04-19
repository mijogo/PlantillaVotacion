<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("modificarbatalla",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("modificarbatalla.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	
	$batallamod = new batalla($BG->con);
	$batallamod->setid($_GET["idbatalla"]);
	$batallamod = $batallamod->read(false,1,array("id"));
	
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$configuraciones = new configuracion($BG->con);
	$configuraciones->setidtorneo($torneoActual->getid());
	$configuraciones = $configuraciones->read(true,1,array("idtorneo"));
	
	
	for($i=0;$i<count($configuraciones);$i++)
	{
		$valoresR[] = $configuraciones[$i]->getid();
		$opcionesR[] = $configuraciones[$i]->getnombre();
	}
	
	$pagina = ingcualpag($pagina,"input_1",inputselected("ronda","Ronda",$valoresR,$opcionesR,$batallamod->getronda()));
	
	$pagina = ingcualpag($pagina,"input_2",inputform("grupo","Grupo",$batallamod->getgrupo()));
	
	$valores = array("-1","0","1");
	$opciones = array("Pendiente","Activa","Finalizada");
	
	$pagina = ingcualpag($pagina,"input_3",inputselected("estado","Estado",$valores,$opciones,$batallamod->getestado()));
	
	$pagina = ingcualpag($pagina,"input_4",inputform("idbatalla","",$_GET["idbatalla"],"hidden"));
	
	$ClaseMaestra->Pagina("",$pagina);
	$BG->close();
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$batallacambiar = new batalla($BG->con);
	$batallacambiar->setid($_POST["idbatalla"]);
	$batallacambiar->setronda($_POST["ronda"]);
	$batallacambiar->setgrupo($_POST["grupo"]);
	$batallacambiar->setestado($_POST["estado"]);
	
	$batallacambiar->update(3,array("ronda","grupo","estado"),1,array("id"));
	$BG->close();
	Redireccionar("batalla.php");
}