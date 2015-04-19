<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("revpersonaje");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("revpersonaje.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Personajes",arreglopersonaje()));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	$personaeeliminar = new personajepar($BG->con);
	$personaeeliminar->setid($_GET["idpersonaje"]);
	$personaeeliminar->delete(1,array("id"));
	$BG->close();
	Redireccionar("revpersonaje.php");
}
function arreglopersonaje()
{
	$BG = new DataBase();
	$BG->connect();
	
	$personajes = new personaje($BG->con);
	$personajes = $personajes->read(true,0,"",1,array("serie","DESC"));
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	if(count($torneoActual)>0)
	{
		$personajespar = new personajepar($BG->con);
		$personajespar->setidtorneo($torneoActual[0]->getid());
		$personajespar = $personajespar->read(true,1,array("idtorneo"),1,array("nombre","DESC"));
	}
	$objetos[] = array("Nombre","Serie","Actual Torneo","","");
		
	for($i=0;$i<count($personajes);$i++)
	{
		$presenta=false;
		if(count($torneoActual)>0)
			for($j=0;$j<count($personajespar);$j++)
			{
				if($personajespar[$j]->getidpersonaje()==$personajes[$i]->getid())	
				{
					$idpar = $j;
					$presenta=true;
				}
			}
		if($presenta)
			$objetos[] = array($personajes[$i]->getnombre(),$personajes[$i]->getserie(),"Si","<a href=\"verpersonaje.php?idpersonaje=".$personajes[$i]->getid()."\">Ver</a>","<a href=\"modificarpersonaje.php?idpersonaje=".$personajes[$i]->getid()."\">Modificar</a>","<a href=\"revpersonaje.php?action=1&idpersonaje=".$personajespar[$idpar]->getid()."\">Eliminar</a>");
		/*else
			$objetos[] = array($personajes[$i]->getnombre(),$personajes[$i]->getserie(),"No","","");*/
			
	}	
	$BG->close();
	return $objetos;
}