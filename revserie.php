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
	$series = $series->read(true,0,"",1,array("nombre","DESC"));
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	if(count($torneoActual)>0)
	{
		$seriespar = new seriepar($BG->con);
		$seriespar->setidtorneo($torneoActual[0]->getid());
		$seriespar = $seriespar->read(true,1,array("idtorneo"),1,array("nombre","DESC"));
	}
	$objetos[] = array("Nombre","Nombre Corto","Actual Torneo","","");
		
	for($i=0;$i<count($series);$i++)
	{
		$presenta=false;
		if(count($torneoActual)>0)
			for($j=0;$j<count($seriespar);$j++)
			{
				if($seriespar[$j]->getidserie()==$series[$i]->getid())	
				{
					$presenta=true;
				}
			}
		if($presenta)
			$objetos[] = array($series[$i]->getnombre(),$series[$i]->getnombrecorto(),"Si","<a href=\"verserie.php?idserie=".$series[$i]->getid()."\">Ver</a>","<a href=\"modificarserie.php?idserie=".$series[$i]->getid()."\">Modificar</a>");
	}	
	$BG->close();
	return $objetos;
}