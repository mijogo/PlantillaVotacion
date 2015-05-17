<?php
function widget()
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	$text = "";
	/*$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Widget 1 
            </div>
            <div class=\"panel-body\">
				<ul >
					<li>
						Texto del widget
					</li>                
                </ul>
             </div>
             <div class=\"panel-footer\">
			  	<a href=\"#\" class=\"btn btn-default btn-block\"> 
				<i class=\"glyphicon glyphicon-repeat\"></i> Boton opcional</a>
             </div>
		</div>
    </div>";
	
		$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Widget 2
            </div>
            <div class=\"panel-body\">
				<ul >
					<li>
						Texto del widget
					</li>                
                </ul>
             </div>
		</div>
    </div>";*/
	
	$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Redes Sociales
            </div>
            <div class=\"panel-body\">

                        <a href=\"https://www.facebook.com/MissAnimeTournament\" target=\"_blank\">
                            <img class=\"img-rounded\" src=\"img/facebook-icon.png\" width=\"60\" alt=\"Facebook\"></a>
&nbsp;
                        <a href=\"https://twitter.com/MsAT_2014\" target=\"_blank\">
                            <img class=\"img-rounded\" src=\"img/twittericon.png\" width=\"60\" alt=\"Twitter\"></a>

             </div>
		</div>
    </div>";
	
	$personajesparticipando = new personajepar($BG->con);
	$personajesparticipando->setestado(1);
	$personajesparticipando->setidtorneo($torneoActual->getid());
	$personajesparticipando = $personajesparticipando->read(true,2,array("estado","AND","idtorneo"));
	$seccionpersonaje=array();
	if(count($personajesparticipando)>10)
	{
		for($i=0;$i<10;$i++)
		{
			$agreper = array();	
			$randagre = rand(0,count($personajesparticipando)-1);
			$agreper["img"]=$personajesparticipando[$randagre]->getimagenpeq();
			$agreper["link"]="verpersonaje.php?idpersonaje=".$personajesparticipando[$randagre]->getid();
			$agreper["nombre"]=$personajesparticipando[$randagre]->getnombre();
			$seccionpersonaje[]=$agreper;
		}
	}
	else
	{
		for($i=0;$i<count($personajesparticipando);$i++)
		{
			$agreper = array();	
			$agreper["img"]=$personajesparticipando[$i]->getimagenpeq();
			$agreper["link"]="verpersonaje.php?idpersonaje=".$personajesparticipando[$i]->getid();
			$agreper["nombre"]=$personajesparticipando[$i]->getnombre();
			$seccionpersonaje[]=$agreper;
		}			
	}
			$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Participantes
            </div>
            <div class=\"panel-body\">
<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\">


  <!-- Wrapper for slides -->
  <div class=\"carousel-inner\" role=\"listbox\">";
  
  		for($i=0;$i<count($seccionpersonaje);$i++)
		{
			$active="";
			if($i==0)
				$active=" active";
			$text.="    <div class=\"item".$active."\">
      <a href=\"".$seccionpersonaje[$i]["link"]."\"><img src=\"".$seccionpersonaje[$i]["img"]."\" alt=\"".$seccionpersonaje[$i]["nombre"]."\"></a>
    </div>";
		}



   $text.="</div>

  <!-- Left and right controls -->
  <a class=\"left carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"prev\">
    <span class=\"glyphicon glyphicon-chevron-left\" aria-hidden=\"true\"></span>
    <span class=\"sr-only\">Previous</span>
  </a>
  <a class=\"right carousel-control\" href=\"#myCarousel\" role=\"button\" data-slide=\"next\">
    <span class=\"glyphicon glyphicon-chevron-right\" aria-hidden=\"true\"></span>
    <span class=\"sr-only\">Next</span>
  </a>
</div>
             </div>
		</div>
    </div>";
	
	
	$todasactividad = new calendario($BG->con);
	$todasactividad->setaccion("ACTBA");
	$todasactividad->sethecho(-1);
	$todasactividad=$todasactividad->read(true,1,array("accion","AND","hecho"),1,array("targetdate","ASC"));
	
	$rondarev = new configuracion($BG->con);
	$rondarev = $rondarev->read();
	$revisarpersonaje = new personajepar($BG->con);
	$revisarpersonaje = $revisarpersonaje->read();
	if(count($todasactividad)>0)
	{
		if($torneoActual->getestado()==2)
		{
			$batallasactivas = new batalla($BG->con);	
			$batallasactivas->setestado(0);
			$batallasactivas = $batallasactivas->read(true,1,array("estado"));
			$cantidadBatallas = count($batallasactivas);
			$estaronda = arrayobjeto($rondarev,"id",$batallasactivas[0]->getronda());
			$contenidowid = "<li>Enfrentamientos activos</li><li>".$estaronda->getnombre()."</li>";
			$contenidowid .="<li>";
			foreach($batallasactivas as $batallaleer)
				$contenidowid .=$batallaleer->getgrupo()." ";
			$contenidowid .="</li>";
			$link = "
             <div class=\"panel-footer\">
			  	<a href=\"resultados.php?tipo=fecha&fecha=".$batallasactivas[0]->getfecha()."\" class=\"btn btn-default btn-block\">Ver mas</a>
             </div>";
		}
		else
		{
			$batallasactivas = new batalla($BG->con);	
			$batallasactivas->setfecha($todasactividad[0]->gettargetdate());
			$batallasactivas = $batallasactivas->read(true,1,array("fecha"));
			$cantidadBatallas = count($batallasactivas);
			$estaronda = arrayobjeto($rondarev,"id",$batallasactivas[0]->getronda());
			$contenidowid = "<li>Proximos enfrentamientos</li><li>".$estaronda->getnombre()."</li>";
			$contenidowid .="<li>";
			foreach($batallasactivas as $batallaleer)
				$contenidowid .=$batallaleer->getgrupo()." ";
			$contenidowid .="</li>";
			$link = "
             <div class=\"panel-footer\">
			  	<a href=\"resultados.php?tipo=fecha&fecha=".$batallasactivas[0]->getfecha()."\" class=\"btn btn-default btn-block\">Ver mas</a>
             </div>";			
		}
	}
	else
	{
		$contenidowid="<li>No hay enfrentamientos agendados</li>";
		$link = "";
	}
		
		$text .= "                    
	<div class=\"notice-board\">
     	<div class=\"panel panel-default\">
         	<div class=\"panel-heading\">
              	Batallas 
            </div>
            <div class=\"panel-body\">
				<ul >
					".$contenidowid."              
                </ul>
             </div>
			 ".$link."
		</div>
    </div>";
	return $text;	
}
?>