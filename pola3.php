<?php
include 'include/masterclass.php';
$BG = new DataBase();
$BG->connect();

$varperosnjae = new personajepar($BG->con);
$varperosnjae = $varperosnjae->read();

$varserie = new seriepar($BG->con);
$varserie = $varserie->read();

$torneoActual = new torneo($BG->con);
$torneoActual->setactivo(1);
$torneoActual = $torneoActual->read(false,1,array("activo"));

for($i=0;$i<1024;$i++)
{
	$nuevopersonaje = new personajepar($BG->con);
	$nuevopersonaje->setnombre("personaje".($i+5));
	$nuevopersonaje->setidpersonaje(1);
	$cualserie = rand(0,count($varserie)-1);
	$cualpersonaje = rand(0,count($varperosnjae)-1);
	$nuevopersonaje->setidserie($varserie[$cualserie]->getid());
	$nuevopersonaje->setserie($varserie[$cualserie]->getnombre());
	$nuevopersonaje->setimagenpeq($varperosnjae[$cualpersonaje]->getimagenpeq());
	$nuevopersonaje->setidtorneo($torneoActual->getid());
	$nuevopersonaje->setestado(1);
	$nuevopersonaje->setseiyuu("aaaaaaaaaaa");
	$nuevopersonaje->save();
}
$BG->close();
?>