<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("verserie",false,-3);
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("verserie.html", "r") or exit("Unable to open file!");
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
	
	$serieactual = new seriepar($BG->con);
	$serieactual->setid($_GET["idserie"]);
	$serieactual->setidtorneo($torneoActual[0]->getid());
	$serieactual = $serieactual->read(false,2,array("id","AND","idtorneo"));
	
	$seriegeneral = new serie($BG->con);
	$seriegeneral->setid($serieactual->getidserie());
	$seriegeneral = $seriegeneral->read(false,1,array("id"));
	
	$pagina = ingcualpag($pagina,"nombre_serie",$seriegeneral->getnombre());
	$pagina = ingcualpag($pagina,"nombre_corto",$seriegeneral->getnombrecorto());
	
	$textextra="";
	
	$pagina = ingcualpag($pagina,"imagen",$serieactual->getimagen());
	switch($serieactual->gettipoformato())
	{
				case "SERIE":
					$formato = "Serie";	
					break;
				case 2:
					$formato = "Primavera";	
					break;
				case 3:
					$formato = "Verano";	
					break;
				case 4:
					$formato = "Otoño";	
					break;
				default:
					$formato = "Invierno";	
					break;
			}
			
			switch($serieactual->gettcours())
			{
				case 1:
					$temporada = "Invierno";	
					break;
				case 2:
					$temporada = "Primavera";	
					break;
				case 3:
					$temporada = "Verano";	
					break;
				case 4:
					$temporada = "Otoño";	
					break;
				default:
					$temporada = "Invierno";	
					break;
			}
			$ntemo = $serieactual->getncours();
			if($ntemo == 5)
			{
				$ntemo="4+";
			}
			$textextra =serieparticiapndo($serieactual->getnombre(),$formato,$serieactual->getano(),$temporada,$ntemo);

	$pagina = ingcualpag($pagina,"participacion",$textextra);
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
}
function serieparticiapndo($nombre_temp,$formato_temp,$anio_temp,$temporadaemision,$cours)
{
	$text="<h3>Participacion actual</h3>
<table>
<tr>
<td width=\"300\"><h4>Nombre actual</h4></td>
<td><h4>".$nombre_temp."<h4></td>
</tr>
<tr>
<td width=\"300\"><h4>Tipo formato</h4></td>
<td><h4>".$formato_temp."<h4></td>
</tr>
<tr>
<td width=\"300\"><h4>Año</h4></td>
<td><h4>".$anio_temp."<h4></td>
</tr>
<tr>
<td width=\"300\"><h4>Temporada de emision</h4></td>
<td><h4>".$temporadaemision."<h4></td>
</tr>
<tr>
<td width=\"300\"><h4>Cours</h4></td>
<td><h4>".$cours."<h4></td>
</tr>
</table>";
return $text;
}