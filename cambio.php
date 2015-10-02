<?php
include 'include/masterclass.php';
$BG = new DataBase();
$BG->connect();
/*$arrayclas = array(435,739,1054,1077,1402,110,285,963,965,1150,1151,1243,1272,120,358,704,835,1676);
foreach($arrayclas as $modper)
{
	$modpelea = new pelea($BG->con);
	$modpelea->setidpersonaje($modper);
	$modpelea->setclasifico(1);
	$modpelea->update(1,array("clasifico"),1,array("idpersonaje"));
	
	$modpersonaje = new personajepar($BG->con);
	$modpersonaje->setid($modper);
	
	$modpersonaje->setestado(1);
	$modpersonaje->setgrupo("N");
	$modpersonaje->setronda("9");
	$modpersonaje->update(3,array("estado","grupo","ronda"),1,array("id"));
	
}*/
$personajeprimer = new personajepar($BG->con);
$personajeprimer->setronda(8);
$personajeprimer = $personajeprimer->read(true,1,array("ronda"));
$pos = 1;
echo "Numero Personaje Serie Grupo Posicion Votos<br>";
$batallausar = new batalla($BG->con);
$batallausar = $batallausar->read();
foreach($personajeprimer as $cadaper)
{
	$peleaind = new pelea($BG->con);
	$peleaind->setidpersonaje($cadaper->getid());
	$peleaind = $peleaind->read(false,1,array("idpersonaje"));
	$estabatalla = arrayobjeto($batallausar,"id",$peleaind->getidbatalla());
	//if($peleaind->getvotos()<5)
	echo $pos." ".$cadaper->getnombre()." ".$cadaper->getserie()." ".$estabatalla->getgrupo()." ".$peleaind->getposicion()." ".$peleaind->getvotos()."<br>";

	$pos++;
}
$BG->close();
?>