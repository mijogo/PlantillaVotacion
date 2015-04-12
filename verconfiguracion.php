<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("verconfiguracion",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("verconfiguracion.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	
	$BG = new DataBase();
	$BG->connect();
	$configuracionahora = new configuracion($BG->con);
	$configuracionahora->setid($_GET["idconfiguracion"]);
	$configuracionahora = $configuracionahora->read(false,1,array("id"));
	
	$configuracionseguiente = new configuracion($BG->con);
	$configuracionseguiente->setid($configuracionahora->getprimproxronda());
	$configuracionseguiente = $configuracionseguiente->read(false,1,array("id"));
	if($configuracionahora->getsegundo() == 1)
	{
		$configuracionsegseguiente = new configuracion($BG->con);
		$configuracionsegseguiente->setid($configuracionsegseguiente->getsegproxronda());
		$configuracionsegseguiente = $configuracionsegseguiente->read(false,1,array("id"));		
	}
	
	$pagina = ingcualpag($pagina,"nombre",$configuracionahora->getnombre());
	if($configuracionahora->gettipo()=="ELIMI")
		$pagina = ingcualpag($pagina,"tipo",ingresardatos("Tipo","Eliminacion").ingresardatos("Numero de grupos",$configuracionahora->getnumerogrupos()));
	elseif($configuracionahora->gettipo()=="ELGRU")
		$pagina = ingcualpag($pagina,"tipo",ingresardatos("Tipo","Por Grupos").ingresardatos("Numero de grupos",$configuracionahora->getnumerogrupos()).ingresardatos("Numero de batallas",$configuracionahora->getnumerobatallas()));
	else
		$pagina = ingcualpag($pagina,"tipo",ingresardatos("Tipo","Exhibicion"));
	
	$pagina = ingcualpag($pagina,"primclass",ingresardatos("Numero de clasificados",$configuracionahora->getprimclas()).ingresardatos("Siguiente ronda para los primeros clasificados",$configuracionseguiente->getnombre()));
	if($configuracionahora->getsegundo() == 1)
		$pagina = ingcualpag($pagina,"segclass",ingresardatos("Numero de segundos clasificados",$configuracionahora->getsegclas()).ingresardatos("Siguiente ronda para los segundos clasificados",$$configuracionseguiente->getnombre()));
	else
		$pagina = ingcualpag($pagina,"segclass","");
	
	if($configuracionahora->getsorteo() == 1)
		$pagina = ingcualpag($pagina,"sorteo",ingresardatos("Sorteo","Si"));
	else
		$pagina = ingcualpag($pagina,"sorteo",ingresardatos("Sorteo","No"));
		
	$pagina = ingcualpag($pagina,"limite",ingresardatos("Limite de votos por persona",$configuracionahora->getlimitevotos()));
	$pagina = ingcualpag($pagina,"id_configuracion",$_GET["idconfiguracion"]);
	$BG->close();
	$ClaseMaestra->Pagina("",$pagina);
}