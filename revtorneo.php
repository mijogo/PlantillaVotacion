<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("revtorneo");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("revtorneo.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Torneo",arregloTorneo()));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	if(!isset($_GET['accion']))
			$_GET['accion']=1;
	if($_GET['accion']==1)
	{	
		$BG = new DataBase();
		$BG->connect();
		$ActivarTorneo = new Torneo($BG->con);
		$ActivarTorneo->setactivo(1);
		$ActivarTorneo = $ActivarTorneo->read(false,1,array("activo"));
		$ActivarTorneo->setactivo(0);
		$ActivarTorneo->update(1,array("activo"),1,array("id"));
		$ActivarTorneo->setid($_GET['idtorneo']);
		$ActivarTorneo = $ActivarTorneo->read(false,1,array("id"));
		$ActivarTorneo->setactivo(1);
		$ActivarTorneo->update(1,array("activo"),1,array("id"));
		$BG->close();
		redireccionar("revtorneo.php");
	}
	elseif($_GET['accion']==2)
	{
		$BG = new DataBase();
		$BG->connect();
		$BorrarTorneo = new Torneo($BG->con);
		$BorrarTorneo->setid($_GET['idtorneo']);
		$BorrarTorneo->delete("1",array("id"));
		$BG->close();
		redireccionar("revtorneo.php");
	}
}

function arregloTorneo()
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneos = new torneo($BG->con);
	$torneos = $torneos->read();
	$objetos = array();
	$objetos[] = array("Nombre","Año","Version","Activo","");
	for($i=0;$i<count($torneos);$i++)
	{
		if($torneos[$i]->getactivo()==1)
			$activo = "SI";
		else
			$activo = "NO";
		$objetos[] = array($torneos[$i]->getnombre(),$torneos[$i]->getano(),$torneos[$i]->getversion(),$activo,"<a href=\"revtorneo.php?idtorneo=".$torneos[$i]->getid()."&action=1&accion=1\">Activar</a>","<a href=\"modificartorneo.php?idtorneo=".$torneos[$i]->getid()."\">Modificar</a>","<a href=\"revtorneo.php?idtorneo=".$torneos[$i]->getid()."&action=1&accion=2\">Eliminar</a>");
	}	
	$BG->close();
	return $objetos;
}
?>