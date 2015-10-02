<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("revserie");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("revserie.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Series",arregloseries()));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
}
function arregloseries()
{
	$BG = new DataBase();
	$BG->connect();
	
	$series = new serie($BG->con);
	$series = $series->read(true,0,"",1,array("nombre","ASC"));
	$objetos="";
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	if(count($torneoActual)>0)
	{
		$seriespar = new seriepar($BG->con);
		$seriespar->setidtorneo($torneoActual[0]->getid());
		$seriespar = $seriespar->read(true,1,array("idtorneo"),1,array("nombre","ASC"));
	$objetos[] = array("Nombre","Nombre Corto","participantes","exhibicion","","");
		
	for($i=0;$i<count($seriespar);$i++)
	{
		$presenta=false;
		$part = 0;
		$exhpart = 0;
		
					$perpart = new personajepar($BG->con);
					$perpart->setidserie($seriespar[$i]->getid());
					$perpart = $perpart->read(true,1,array("idserie"));
					$part = count($perpart);
					$perpart = new personajepar($BG->con);
					$perpart->setidserie($seriespar[$i]->getid());
					$perpart->setestado(2);
					$perpart = $perpart->read(true,2,array("idserie","AND","estado"));
					$exhpart = count($perpart);
					$part = $part - $exhpart;
					
					$namserie = arrayobjeto($series,"id",$seriespar[$i]->getidserie());
					
		$objetos[] = array($seriespar[$i]->getnombre(),$namserie->getnombrecorto(),$part,$exhpart,"<a href=\"verserie.php?idserie=".$seriespar[$i]->getid()."\">Ver</a>","<a href=\"modificarserie.php?idserie=".$seriespar[$i]->getid()."\">Modificar</a>");
	}	
	}
	$BG->close();
	return $objetos;
}