<?php
include 'include/masterclass.php';
$BG = new DataBase();
$BG->connect();
for($r=0;$r<40;$r++)
{
$batallaactivas = new batalla($BG->con);
$batallaactivas->setestado(0);
$batallaactivas = $batallaactivas->read(true,1,array("estado"));


$fecha = $batallaactivas[0]->getfecha()." 00:00:00";
$fecha = cambioFecha($fecha,rand(10,1200));
	
foreach($batallaactivas as $probar)
{
	$buscapar = new participacion($BG->con);
	$buscapar->setidbatalla($probar->getid());
	$buscapar=$buscapar->read(true,1,array("idbatalla"));
	for($i=0;$i<1;$i++)
	{
		$personaje = rand(0,count($buscapar)-1);
		$votonuevo = new voto($BG->con,$fecha,$probar->getid(),$buscapar[$personaje]->getidpersonaje(),"varios","varios",4);
		$votonuevo->save();
	}
}
}
/*
$total =288;
$personajesprimera = new personajepar($BG->con);
$personajesprimera->setronda(10);
$personajesprimera = $personajesprimera->read(true,1,array("ronda"));
$total -=count($personajesprimera);
for($i=0;$i<$total;$i++)
{
	$personajesusar = new personajepar($BG->con);	
	$personajesusar->setronda(12);
	$personajesusar = $personajesusar->read(true,1,array("ronda"));
	
	$rando = rand(0,count($personajesusar)-1);
	
	$buscpart = new participacion($BG->con);
	$buscpart->setidpersonaje($personajesusar[$rando]->getid());
	$buscpart = $buscpart->read(false,1,array("idpersonaje"));
	$votos = rand(3,40);
	$guardarpelea = new pelea($BG->con,$personajesusar[$rando]->getid(),$buscpart->getidbatalla(),$votos);
	$guardarpelea->save();
	
	$personajesusar[$rando]->setronda(10);
	$personajesusar[$rando]->setgrupo("N");
	$personajesusar[$rando]->update(2,array("ronda","grupo"),1,array("id"));
}*/
$BG->close();
?>