<?php
include 'include/masterclass.php';
$BG = new DataBase();
$BG->connect();
echo "Llega?";
$resencabezado = array("Nombre","Serie","Grupo","Posicion","Votos","Clasificacion");
$superarreglo=array();
echo "Llega?";
$batallaepic = new batalla($BG->con);
$batallaepic->setronda(10);
$batallaepic = $batallaepic->read(true,1,array("ronda"),1,array("grupo","ASC"));

$personajesusar = new personajepar($BG->con);
$personajesusar = $personajesusar->read();

$serieusar = new seriepar($BG->con);
$serieusar = $serieusar->read();
foreach($batallaepic as $unibatalla)
{
	$revpeleaepic = new pelea($BG->con);
	$revpeleaepic->setidbatalla($unibatalla->getid());
	$revpeleaepic = $revpeleaepic->read(true,1,array("idbatalla"),1,array("votos","DESC"));
	foreach($revpeleaepic as $peleapersonaje)
	{
		$buscper = arrayobjeto($personajesusar,"id",$peleapersonaje->getidpersonaje());	
		$buscser = arrayobjeto($serieusar,"id",$buscper->getidserie());	
		$newdato="";
		$newdato[0]=$peleapersonaje->getidpersonaje();
		$newdato[1]=$buscper->getnombre();
		$newdato[2]=$buscser->getnombre();
		$newdato[3]=$unibatalla->getgrupo();
		$newdato[4]=$peleapersonaje->getposicion();
		$newdato[5]=$peleapersonaje->getvotos();
		$newdato[6]=$peleapersonaje->getclasifico();
		$superarreglo[]=$newdato;
	}
}
echo "Llega?";
crearxml($resencabezado,$superarreglo);
$BG->close();

function crearxml($encabezado,$datos)
{
$interior="";
foreach($datos as $cadauno)
{
$busqueda="";
for($i=0;$i<count($encabezado);$i++)
if($i==count($encabezado)-1)
$busqueda.="		<".$encabezado[$i].">".$cadauno[$i+1]."</".$encabezado[$i].">";
else
$busqueda.="		<".$encabezado[$i].">".$cadauno[$i+1]."</".$encabezado[$i].">\n";
$interior.="	<Personaje idpersonaje=\"".$cadauno[0]."\">
".$busqueda."
	</Personaje>\n";	
}
$text = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Preliminares>
".$interior."
</Preliminares>";
$fp = fopen("Preliminares.xml", 'w');
fwrite($fp,$text);
fclose($fp);
}
?>