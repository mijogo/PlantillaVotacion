<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("batalla");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("batalla.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));
	
	$muchasBatallas = new batalla($BG->con);
	$muchasBatallas->setidtorneo($torneoActual[0]->getid());
	$muchasBatallas = $muchasBatallas->read(true,1,array("idtorneo"),1,array("fecha","ASC"));
	
	$personajeCom = new personajepar($BG->con);
	$personajeCom = $personajeCom->read();
	
	$seriecom = new seriepar($BG->con);
	$seriecom = $seriecom->read();
	
	$confi = new configuracion($BG->con);
	$confi = $confi->read();
	
	$text ="";
	for($i=0;$i<count($muchasBatallas);$i++)
	{
		if($muchasBatallas[$i]->getestado()==-1)
			$estado = "pendiente";
		elseif($muchasBatallas[$i]->getestado()==0)
			$estado = "activa";
		else
			$estado = "finalizada";

		$confiactual = arrayobjeto($confi,"id",$muchasBatallas[$i]->getronda());
		
		$text .= inputcalendar("batalla-".$muchasBatallas[$i]->getid(),$confiactual->getnombre()." ".$muchasBatallas[$i]->getgrupo()."<p>Estado : ".$estado."</p>".botones($muchasBatallas[$i]->getid()),$muchasBatallas[$i]->getfecha());
		
		$text .= botoncollapse("serie".$i,"Mostrar Participantes");
		
		$participantes = new participacion($BG->con);
		$participantes->setidbatalla($muchasBatallas[$i]->getid());
		$participantes=$participantes->read(true,1,array("idbatalla"));
		$ppart = array();
		for($j=0;$j<count($participantes);$j++)
		{
			$actualper = arrayobjeto($personajeCom,"id",$participantes[$j]->getidpersonaje());
			$serieper = arrayobjeto($seriecom,"id",$actualper->getidserie());
			$ppart[]=$actualper->getnombre()." (".$serieper->getnombre().")";
		}
		$text .= mostrarparticiapntes("serie".$i,$ppart);
		
		$text .= "<hr>";
	}
	
	$pagina = ingcualpag($pagina,"tabla_contenidos",$text);
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}
elseif($_GET['action']==3)
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$aplicarbatalla = new batalla($BG->con);	
	$aplicarbatalla->setidtorneo($torneoActual->getid());
	$aplicarbatalla = $aplicarbatalla->read(true,1,array("idtorneo"));
	
	for($i=0;$i<count($aplicarbatalla);$i++)
	{
		$fecha = $aplicarbatalla[$i]->getfecha()." ".$torneoActual->gethorainicio();
	
		$revisacalendario = new calendario($BG->con);
		$revisacalendario->setfecha($fecha);
		$revisacalendario = $revisacalendario->read(true,1,array("fecha"));
		if(count($revisacalendario)==0)
		{
			$nuevoschedule = new calendario($BG->con);
			$nuevoschedule->setaccion("CHTOR");
			$nuevoschedule->setfecha($fecha);
			$nuevoschedule->sethecho(-1);
			$nuevoschedule->settargetint(2);
			$nuevoschedule->setidtorneo($torneoActual->getid());
			$nuevoschedule->save();
			
			$nuevoschedule = new calendario($BG->con);
			$nuevoschedule->setaccion("ACTBA");
			$nuevoschedule->setfecha($fecha);
			$nuevoschedule->sethecho(-1);
			$nuevoschedule->settargetdate($aplicarbatalla[$i]->getfecha());
			$nuevoschedule->setidtorneo($torneoActual->getid());
			$nuevoschedule->save();
			
			$nuevoschedule = new calendario($BG->con);
			$nuevoschedule->setaccion("CHEVE");
			$nuevoschedule->setfecha($fecha);
			$nuevoschedule->sethecho(-1);
			$nuevoschedule->settargetstring("CREAR");
			$nuevoschedule->setidtorneo($torneoActual->getid());
			$nuevoschedule->save();
			
			$fecha = cambioFecha($fecha,$torneoActual->getduracionbatalla());
			
			$nuevoschedule = new calendario($BG->con);
			$nuevoschedule->setaccion("CHTOR");
			$nuevoschedule->setfecha($fecha);
			$nuevoschedule->sethecho(-1);
			$nuevoschedule->settargetint(1);
			$nuevoschedule->setidtorneo($torneoActual->getid());
			$nuevoschedule->save();
			
			$fecha = cambioFecha($fecha,$torneoActual->getextraconteo());
				
			$nuevoschedule = new calendario($BG->con);
			$nuevoschedule->setaccion("CONVO");
			$nuevoschedule->setfecha($fecha);
			$nuevoschedule->sethecho(-1);
			$nuevoschedule->setidtorneo($torneoActual->getid());
			$nuevoschedule->save();	
		}
	}
	
	$BG->close();
	//redireccionar("batalla.php");
}
elseif($_GET['action']==2)
{
	$BG = new DataBase();
	$BG->connect();
	
	$batallaeliminar = new batalla($BG->con);
	$batallaeliminar->setid($_GET["idbatalla"]);
	$batallaeliminar = $batallaeliminar->read(false,1,array("id"));
	
	$masbatallas = new batalla($BG->con);
	$masbatallas->setfecha($batallaeliminar->getfecha());
	$masbatallas = $masbatallas->read(true,1,array("fecha"));
	
	if(count($masbatallas)>1)
	{
		$batallaeliminar->delete(1,array("id"));
	}
	else
	{
		$batallaeliminar->delete(1,array("id"));
	
		$torneoActual = new torneo($BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(true,1,array("activo"));
		
		$fecha = $batallaeliminar->getfecha()." ".$torneoActual[0]->gethorainicio();
		
		$nuevoschedule = new calendario($BG->con);
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->delete(1,array("fecha"));
		
		$fecha = cambioFecha($fecha,$torneoActual[0]->getduracionbatalla());
		
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->delete(1,array("fecha"));
		
		$fecha = cambioFecha($fecha,$torneoActual[0]->getextraconteo());
			
		$nuevoschedule->setfecha($fecha);
		$nuevoschedule->delete(1,array("fecha"));	
	}
	
	$BG->close();
	redireccionar("batalla.php");
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));
	
	$muchasBatallas = new batalla($BG->con);
	$muchasBatallas->setidtorneo($torneoActual[0]->getid());
	$muchasBatallas = $muchasBatallas->read(true,1,array("idtorneo"),1,array("fecha","ASC"));
	
	for($i=0;$i<count($muchasBatallas);$i++)
	{
		$muchasBatallas[$i]->setfecha($_POST["batalla-".$muchasBatallas[$i]->getid()]);
		$muchasBatallas[$i]->update(1,array("fecha"),1,array("id"));
	}
	$BG->close();
	redireccionar("batalla.php");
}
function botones($idbatalla)
{
	$text ="
<p>
  <a href=\"selpersonaje.php?idbatalla=".$idbatalla."\" class=\"btn btn-default btn-sm\">Seleccionar Personajes</a>
  <a href=\"batalla.php?action=2&idbatalla=".$idbatalla."\" class=\"btn btn-default btn-sm\">Eliminar</a>    
  <a href=\"modificarbatalla.php?idbatalla=".$idbatalla."\" class=\"btn btn-default btn-sm\">Modificar</a>      
</p>";	
return $text;
}
