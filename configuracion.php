<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("configuracion");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("configuracion.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Configuraciones",arregloconfiguraciones()));
	$ClaseMaestra->Pagina("",$pagina);
}
elseif($_GET['action']==3)
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));	
	
	$cambiargrupo = new personajepar($BG->con);
	$cambiargrupo->setidtorneo($torneoActual->getid());
	$cambiargrupo->setestado(1);
	$cambiargrupo->setronda($torneoActual->getopcionpartida());
	$cambiargrupo->setgrupo("N");
	
	$cambiargrupo->update(2,array("ronda","grupo"),2,array("idtorneo","AND","estado"));
	
	$BG->close();
	Redireccionar("configuracion.php");	
}
elseif($_GET['action']==2)
{
	$BG = new DataBase();
	$BG->connect();
	$configuracionelmi = new configuracion($BG->con);
	$configuracionelmi->setid($_GET["idconfiguracion"]);
	$configuracionelmi->delete(1,array("id"));
	$BG->close();
	Redireccionar("configuracion.php");	
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$configuraciones = new configuracion($BG->con);
	$configuraciones->setidtorneo($torneoActual[0]->getid());
	$configuraciones = $configuraciones->read(true,1,array("idtorneo"));
	
	for($i=0;$i<count($configuraciones);$i++)
	{
		if($configuraciones[$i]->gettipo()=="ELIMI")
		{
			$numerogrupos = $configuraciones[$i]->getnumerogrupos();
			for($j=0;$j<$numerogrupos;$j++)
			{
				$nuevabatalla = new batalla($BG->con);
				$nuevabatalla->setfecha("2012-01-01");
				$nuevabatalla->setronda($configuraciones[$i]->getid());
				$nuevabatalla->setgrupo($j+1);
				$nuevabatalla->setidtorneo($torneoActual[0]->getid());
				$nuevabatalla->setestado(-1);
				$nuevabatalla->save();
			}
		}
		elseif($configuraciones[$i]->gettipo()=="ELGRU")
		{
			$numerogrupos = $configuraciones[$i]->getnumerogrupos();
			$numerobatallas = $configuraciones[$i]->getnumerobatallas();
			for($j=0;$j<$numerogrupos;$j++)
			{
				for($k=0;$k<$numerobatallas;$k++)
				{
					$nuevabatalla = new batalla($BG->con);
					$nuevabatalla->setfecha("2012-01-01");
					$nuevabatalla->setronda($configuraciones[$i]->getid());
					$nuevabatalla->setgrupo("".cambiarletra($j+1)."-".($k+1));
					$nuevabatalla->setidtorneo($torneoActual[0]->getid());
					$nuevabatalla->setestado(-1);
					$nuevabatalla->save();
				}
			}			
		}	
	}
	
	$BG->close();
	redireccionar("configuracion.php");
}
function arregloconfiguraciones()
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$configuraciones = new configuracion($BG->con);
	$configuraciones->setidtorneo($torneoActual[0]->getid());
	$configuraciones = $configuraciones->read(true,1,array("idtorneo"));
	

	$objetos[] = array("Nombre","Tipo","Siguiente","","","");
		
	for($i=0;$i<count($configuraciones);$i++)
	{
		$presenta=false;
		
		switch($configuraciones[$i]->gettipo())
		{
			case "ELIMI":	$Tipo = "Eliminacion";break;
			case "ELGRU": 	$Tipo = "Por Grupos";break;
			case "EXHIB": 	$Tipo = "Exhibicion";break;
			default : $Tipo="Exhibicion";break;
		}
		$siguiente="";
		for($j=0;$j<count($configuraciones);$j++)
		{
			if($configuraciones[$j]->getid()==$configuraciones[$i]->getprimproxronda())	
				$siguiente=$configuraciones[$j]->getnombre();
		}
		
		$objetos[] = array($configuraciones[$i]->getnombre(),$Tipo,$siguiente,"<a href=\"verconfiguracion.php?idconfiguracion=".$configuraciones[$i]->getid()."\">Ver</a>","<a href=\"modificarconfiguracion.php?idconfiguracion=".$configuraciones[$i]->getid()."\">Modificar</a>","<a href=\"configuracion.php?action=2&idconfiguracion=".$configuraciones[$i]->getid()."\">Eliminar</a>");
			
	}	
	$BG->close();
	return $objetos;
}