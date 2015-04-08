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
	$serieactual = new serie($BG->con);
	$serieactual->setid($_GET["idserie"]);
	$serieactual = $serieactual->read(false,1,array("id"));
	
	$pagina = ingcualpag($pagina,"nombre_serie",$serieactual->getnombre());
	$pagina = ingcualpag($pagina,"nombre_corto",$serieactual->getnombrecorto());
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(true,1,array("activo"));	
	$textextra="";
	if(count($torneoActual)>0)
	{
		$seriespar = new seriepar($BG->con);
		$seriespar->setidserie($serieactual->getid());
		$seriespar->setidtorneo($torneoActual[0]->getid());
		$seriespar = $seriespar->read(true,2,array("idserie","AND","idtorneo"));
		if(count($seriespar)>0)
		{
			$pagina = ingcualpag($pagina,"imagen",$seriespar[0]->getimagen());
			switch($seriespar[0]->gettipoformato())
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
			
			switch($seriespar[0]->gettcours())
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
			$ntemo = $seriespar[0]->getncours();
			if($ntemo == 5)
			{
				$ntemo="4+";
			}
			$textextra =serieparticiapndo($seriespar[0]->getnombre(),$formato,$seriespar[0]->getano(),$temporada,$ntemo);
		}
		else
			$pagina = ingcualpag($pagina,"imagen",$serieactual->getimagen());
	}
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