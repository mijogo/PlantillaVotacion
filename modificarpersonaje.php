<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("modificarpersonaje",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("modificarpersonaje.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$personajeantiguo = new personaje($BG->con);
	$personajeantiguo->setid($_GET["idpersonaje"]);
	$personajeantiguo = $personajeantiguo->read(false,1,array("id"));
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$personajeactual = new personajepar($BG->con);
	$personajeactual->setidpersonaje($personajeantiguo->getid());
	$personajeactual->setidtorneo($torneoActual[0]->getid());
	$personajeactual = $personajeactual->read(false,2,array("idpersonaje","AND","idtorneo"));
	
	$pagina = ingcualpag($pagina,"input_1",inputform("nombrepersonaje","Nombre",$personajeactual->getnombre()));
	$serie = new seriepar($BG->con);
	$serie = $serie->read();
	for($i=0;$i<count($serie);$i++)
	{
		$valores[] = $serie[$i]->getid();
		$opciones[] = $serie[$i]->getnombre();
	}
	$pagina = ingcualpag($pagina,"input_2",inputselected("nombreserie","Serie",$valores,$opciones,$personajeactual->getidserie()));
	$pagina = ingcualpag($pagina,"input_3",inputimage("imagenpersonaje","Imagen","Introduzca una imagen del personaje"));
	
	$valores = array("0","1");
	$opciones = array("Eliminado","Participando");
	$pagina = ingcualpag($pagina,"input_4",inputselected("estado","Estado",$valores,$opciones,$personajeactual->getestado()));
	
	//$pagina = ingcualpag($pagina,"input_5",inputform("ronda","Ronda",$personajeactual->getnombre()));
	//$pagina = ingcualpag($pagina,"input_6",inputform("grupo","Grupo",$personajeactual->getnombre()));
	$pagina = ingcualpag($pagina,"input_7",inputform("seiyuu","Seiyuu",$personajeactual->getseiyuu()));
		
	$pagina = ingcualpag($pagina,"input_8",inputform("idpersonaje","",$_GET["idpersonaje"],"hidden"));
		$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}
else{
		$BG = new DataBase();
	$BG->connect();
	$personajeantiguo = new personaje($BG->con);
	$personajeantiguo->setid($_POST["idpersonaje"]);
	$personajeantiguo = $personajeantiguo->read(false,1,array("id"));
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$personajeactual = new personajepar($BG->con);
	$personajeactual->setidpersonaje($personajeantiguo->getid());
	$personajeactual->setidtorneo($torneoActual[0]->getid());
	$personajeactual = $personajeactual->read(false,2,array("idpersonaje","AND","idtorneo"));
	
	$personajeantiguo->setnombre($_POST["nombrepersonaje"]);
	$personajeactual->setnombre($_POST["nombrepersonaje"]);
	
	$Serieparusar = new seriepar($BG->con);
	$Serieparusar->setid($_POST["nombreserie"]);
	$Serieparusar = $Serieparusar->read(false,1,array("id"));
	
	$Serieusar = new serie($BG->con);
	$Serieusar->setid($Serieparusar->getidserie());
	$Serieusar = $Serieusar->read(false,1,array("id"));
	
	$personajeantiguo->setidserie($Serieusar->getid());
	$personajeantiguo->setserie($Serieusar->getnombre());
	$personajeactual->setidserie($Serieparusar->getid());
	
	
	$archivo = uploadimage($_FILES["imagenpersonaje"]);
	if($archivo[0])
	{
		$personajeantiguo->setimagen($archivo[1]);
		$personajeactual->setimagenpeq($archivo[1]);
	}
	$personajeactual->setestado($_POST["estado"]);
	$personajeactual->setseiyuu($_POST["seiyuu"]);
	
	$personajeantiguo->update(4,array("imagen","serie","idserie","nombre"),1,array("id"));
	$personajeactual->update(5,array("seiyuu","estado","imagenpeq","idserie","nombre"),1,array("id"));
	$BG->close();
	Redireccionar("admin.php");

}