<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("crearconfiguraciones",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("crearconfiguraciones.html", "r") or exit("Unable to open file!");
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
	
	$configuraciones = new configuracion($BG->con);
	$configuraciones->setidtorneo($torneoActual[0]->getid());
	$configuraciones = $configuraciones->read(true,1,array("idtorneo"));	
	
	$BG->close();
	for($i=0;$i<count($configuraciones);$i++)
	{
		$valoresR[] = $configuraciones[$i]->getid();
		$opcionesR[] = $configuraciones[$i]->getnombre();
	}
	$valoresR[] = "-1";
	$opcionesR[] = "Campeon";
	$valoresR[] = "-2";
	$opcionesR[] = "Sin Union";
	
	$pagina = ingcualpag($pagina,"input_1",inputform("nombre","Nombre"));
	$pagina = ingcualpag($pagina,"input_2",inputform("numerogrupos","Numero de grupos"));
	$pagina = ingcualpag($pagina,"input_3",inputform("numerobatallas","Numero de batallas"));
	$valores = array("ELIMI","ELGRU","EXHIB");
	$opciones = array("Eliminacion","Por Grupos","Exhibicion");
	$pagina = ingcualpag($pagina,"input_4",inputselected("tipoconfiguracion","Tipo",$valores,$opciones));
	$pagina = ingcualpag($pagina,"input_5",inputcheckbox("segundo","Dos grupos de clasificacion"));
	
	$pagina = ingcualpag($pagina,"input_6",inputform("primclas","Cantidad clasificados primer grupo"));
	$pagina = ingcualpag($pagina,"input_7",inputselected("primproxronda","Ronda de clasificacion del primer grupo",$valoresR,$opcionesR));
	$pagina = ingcualpag($pagina,"input_8",inputform("segclas","Cantidad clasificados segundo grupo"));
	$pagina = ingcualpag($pagina,"input_9",inputselected("segproxronda","Ronda de clasificacion del segundo grupo",$valoresR,$opcionesR));
	
	$pagina = ingcualpag($pagina,"input_10",inputcheckbox("sorteo","Esta Ronda tiene sorteo"));
	$pagina = ingcualpag($pagina,"input_11",inputform("limitevotos","Limite de votos por persona"));
	$pagina = ingcualpag($pagina,"input_12",inputcheckbox("seed","Esta Ronda utiliza Seed"));
	
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	
	$nuevaconfiguracion = new configuracion($BG->con);
	$nuevaconfiguracion->setidtorneo($torneoActual[0]->getid());
	$nuevaconfiguracion->setnombre($_POST["nombre"]);
	$nuevaconfiguracion->setnumerogrupos($_POST["numerogrupos"]);
	$nuevaconfiguracion->setnumerobatallas($_POST["numerobatallas"]);
	$nuevaconfiguracion->settipo($_POST["tipoconfiguracion"]);
	if(isset($_POST["segundo"]))
		$nuevaconfiguracion->setsegundo(1);
	else
		$nuevaconfiguracion->setsegundo(0);
	$nuevaconfiguracion->setprimclas($_POST["primclas"]);
	$nuevaconfiguracion->setprimproxronda($_POST["primproxronda"]);
	$nuevaconfiguracion->setsegclas($_POST["segclas"]);
	$nuevaconfiguracion->setsegproxronda($_POST["segproxronda"]);
	if(isset($_POST["sorteo"]))
		$nuevaconfiguracion->setsorteo(1);
	else
		$nuevaconfiguracion->setsorteo(0);
	if(isset($_POST["seed"]))
		$nuevaconfiguracion->setextra(1);
	else
		$nuevaconfiguracion->setextra(0);
	$nuevaconfiguracion->setlimitevotos($_POST["limitevotos"]);
	$nuevaconfiguracion->save();
	$BG->close();
	redireccionar("configuracion.php");
}
?>