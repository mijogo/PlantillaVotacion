<?php
include 'include/masterclass.php';
$BG = new DataBase();
$BG->connect();
$letra="D";
$ponderacion = 0;

$primeramitad =0;
$cantprimera =0;
$segundamitad =0;
$cantsegunda =0;

$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-1",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-1 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$primeramitad += $ponderacion;
$cantprimera +=count($personajenuevo);
$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-2",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-2 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$primeramitad += $ponderacion;
$cantprimera +=count($personajenuevo);

$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-3",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-3 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$primeramitad += $ponderacion;
$cantprimera +=count($personajenuevo);

$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-4",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-4 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$primeramitad += $ponderacion;
$cantprimera +=count($personajenuevo);

$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-5",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-5 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$segundamitad += $ponderacion;
$cantsegunda +=count($personajenuevo);

$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-6",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-6 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$segundamitad += $ponderacion;
$cantsegunda +=count($personajenuevo);

$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-7",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-7 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$segundamitad += $ponderacion;
$cantsegunda +=count($personajenuevo);

$ponderacion = 0;
$personajenuevo = new personajepar($BG->con,"","","","","","",4,"","$letra-8",14,"","");
$personajenuevo = $personajenuevo->read(true,2,array("ronda","AND","grupo"));
for($i=0;$i<count($personajenuevo);$i++)
	$ponderacion += $personajenuevo[$i]->getponderacion();
echo "$letra-8 :".$ponderacion/count($personajenuevo)."  ".count($personajenuevo)."<br>";
$segundamitad += $ponderacion;
$cantsegunda +=count($personajenuevo);
echo "ponderacion promedio primera mitad : ".($primeramitad/$cantprimera)."<br>";
echo "ponderacion promedio segunda mitad : ".($segundamitad/$cantsegunda);
$BG->close();
?>