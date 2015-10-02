<?php
include 'include/masterclass.php';
$ClaseMaestra = new MasterClass("home");
if(!$ClaseMaestra->VerificacionIdentidad(1))
	Redireccionar("home.php");
$file = fopen("home.html", "r") or exit("Unable to open file!");
$pagina="";
while(!feof($file))
{
	$pagina .= fgets($file);
}

	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	if($torneoActual->getestado()==2)
	{
		$text = "";
		$batallasusar = new batalla($BG->con);
		$batallasusar->setfecha(fechaHoraActual("Y-m-d"));
		$batallasusar->setidtorneo($torneoActual->getid());
		$batallasusar = $batallasusar->read(true,2,array("fecha","AND","idtorneo"));
		
		$todopersonaje = new personajepar($BG->con);
		$todopersonaje = $todopersonaje->read();
		
		if(count($batallasusar)>0)
		{
			$rondarev = new configuracion($BG->con);
			$rondarev->setid($batallasusar[0]->getronda());
			$rondarev = $rondarev->read(false,1,array("id"));
			$text .= "	<div class=\"row\">
		<div class=\"col-sm-10 col-md-12\">
        	
     		<div class=\"panel panel-default\">
         		<div class=\"panel-heading\">
              		Enfrentamientos activos
            	</div>
            	<div class=\"panel-body\">";
			foreach($batallasusar as $estabatalla)
			{
				if($estabatalla->getestado() == 0)
				{
					$participantes = new participacion($BG->con);
					$participantes->setidbatalla($estabatalla->getid());
					$participantes = $participantes->read(true,1,array("idbatalla"));
					
					$personajesagrega = array();
					$cantpersonajepri = 0;
					
					$votoanterior=0;

					foreach($participantes as $votoparticipante)
					{
						$datospersonaje = array();
						$personaje = arrayobjeto($todopersonaje,"id",$votoparticipante->getidpersonaje());
						$datospersonaje["img"]=$personaje->getimagenpeq();
						$datospersonaje["nombre"]=$personaje->getnombre();
						$datospersonaje["serie"]=$personaje->getserie();
						
						$votocontar = new voto($BG->con);
						$votocontar->setidbatalla($estabatalla->getid());
						$votocontar->setidpersonaje($personaje->getid());
						//$votocontar = $votocontar->read(true,2,array("idbatalla","AND","idpersonaje"));
						$fechahoraactual = fechaHoraActual();
						$fechalimite = $estabatalla->getfecha()." 00:00:00";
						$fechalimite = cambioFecha($fechalimite,$torneoActual->getduracionlive());
						if(FechaMayor($fechahoraactual,$fechalimite)==1)
							$fechausar = $fechalimite;
						else
							$fechausar = $fechahoraactual;
						
						$votocontar = $votocontar->read(true,0,"",0,""," idpersonaje=".$personaje->getid()." AND fecha <= \"".$fechausar."\" AND idbatalla=".$estabatalla->getid()." ");
						$datospersonaje["voto"]=count($votocontar);
						
						if($datospersonaje["voto"]>	$votoanterior)
						{
							$cantpersonajepri=1;
							$votoanterior=$datospersonaje["voto"];
							$personajesagrega[0]=$datospersonaje;
						}
						elseif($datospersonaje["voto"]==$votoanterior)
						{
							$personajesagrega[$cantpersonajepri]=$datospersonaje;
							$cantpersonajepri++;
						}
					}
					$text .= "<h2>".$rondarev->getnombre()." ".$estabatalla->getgrupo()."</h2>";
					$text.=" <table class=\"table table-hover personal-task\">
                             <tbody>";
							  	for($i=0;$i<$cantpersonajepri;$i++)
							  	{
									$text.="<tr>";
									$text.="<td><div class=\"avatar\">
													  <img src=\"".$personajesagrega[$i]["img"]."\" width=\"50\" class=\"img-rounded\" alt=\"\"/>
													</div></td>";
									$text.="<td>".$personajesagrega[$i]["nombre"]."</td>";
									$text.="<td>".$personajesagrega[$i]["serie"]."</td>";
									$text.="<td>".$personajesagrega[$i]["voto"]."</td>";
									$text.="</tr>";	
								}
							  $text.=" </tbody>
                          </table>";
					}

			}
								$text .="           </div>
					
					             <div class=\"panel-footer\">
			  	<a href=\"resultados.php?tipo=fecha&fecha=".fechaHoraActual("Y-m-d")."\" class=\"btn btn-default btn-block\">Ver mas</a>
             </div>
            </div>
        </div> </div>";
			$pagina = ingcualpag($pagina,"marcador",$text);	
		}
		else
		{
			$pagina = ingcualpag($pagina,"marcador","");	
		}	
	}
	else
	{
		$pagina = ingcualpag($pagina,"marcador","");	
	}
	
$ClaseMaestra->Pagina("",$pagina);
?>