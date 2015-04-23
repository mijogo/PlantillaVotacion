<?php
include 'include/masterclass.php';
$BG = new DataBase();
$BG->connect();
$cantpond = 8;
$j=0;
$pond=1000;
$pondprom=0;
$nuevopersonaje = new personajepar($BG->con,"","","","","","",4,1,"N",14,"","");
$nuevopersonaje = $nuevopersonaje->read(true,1,array("ronda"));
for($i=0;$i<214;$i++)
{
	/*$ponderado = rand($pond-100,$pond);
	if($ponderado<0)
		$ponderado *=-1;
	$j++;
	if($j==$cantpond)
	{
		$j=0;
		$pond /=2;
		$pond = floor($pond);
		$cantpond *=2;
	}
	$nuevopersonaje = new personajepar($BG->con,"","personaje".($i+1),($i+212),($i%12),"","",4,1,"N",14,"",$ponderado);	
	$nuevopersonaje->save();*/
	$pondprom += $nuevopersonaje[$i]->getponderacion();
}
echo $pondprom/214;
$BG->close();
?>