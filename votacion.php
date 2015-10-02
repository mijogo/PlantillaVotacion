<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("votacion");
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("votacion.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$horafinal="22:00:00";
	$BG = new DataBase();
	$BG->connect();
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	if($torneoActual->getestado()==2)
	{
		$ClaseMaestra->devip();
		$ipactual = $ClaseMaestra->ipcontext;
		if($ipactual =="")
		{
			$text ="<div class=\"alert alert-info\">Para poder participar de Miss Anime Tournament, usted debe tener las cookies del navegador activas</div>";
		}
		else
		{
			$useractual = $ClaseMaestra->user;
			
			if($ipactual->getusada()==0)
			{
				$activafecha = cambioFecha($ipactual->getfecha(),$ipactual->gettiempo());
				$fechaactual = fechaHoraActual();
				if(FechaMayor($activafecha,$fechaactual)==1)
				{
					$datetime1 = new DateTime($activafecha);
					$datetime2 = new DateTime($fechaactual);
					$interval = $datetime1->diff($datetime2);
					$minutosfaltantes =  $interval->format("%i");
					$text ="<div class=\"alert alert-info\">Por favor, espere ".($minutosfaltantes+1)." minutos para votar, por mientras puede revisar los enfrentamientos que se llevaran a cabo hoy,Si no quiere esperar, puede registrarse y votar de inmediato</div>";
					
					$batallasactivas = new batalla($BG->con);	
					$batallasactivas->setestado(0);
					$batallasactivas = $batallasactivas->read(true,1,array("estado"));
					$cantidadBatallas = count($batallasactivas);
					
					$peronajesparticipantes = new personajepar($BG->con);
					$peronajesparticipantes = $peronajesparticipantes->read();
					
					for($i=0;$i<$cantidadBatallas;$i++)
					{
						$arrawpersonaje = array();
						$datos = "";
						
						$configuracionuso = new configuracion($BG->con);
						$configuracionuso->setid($batallasactivas[$i]->getronda());
						$configuracionuso = $configuracionuso->read(false,1,array("id"));
						
						$participaciones = new participacion($BG->con);
						$participaciones->setidbatalla($batallasactivas[$i]->getid());
						$participaciones = $participaciones->read(true,1,array("idbatalla"));
						
						foreach($participaciones as $participante)
						{
							$arrawpersonaje[] = arrayobjeto($peronajesparticipantes,"id",$participante->getidpersonaje());
						}
						$arrawpersonaje = ordenarpersonajes($arrawpersonaje);
						foreach($arrawpersonaje as $verpartpers)
							$datos.=panelvotar($verpartpers->getnombre(),$verpartpers->getid(),$verpartpers->getimagenpeq(),$verpartpers->getserie(),false);	
						$text .= lugarvotacion($configuracionuso->getnombre()." ".$batallasactivas[$i]->getgrupo(),$datos);
						
					}
					$pagina = ingcualpag($pagina,"votacion",$text);
					$ClaseMaestra->Pagina("",$pagina);
				}
				else
				{
					$text ="";
					$personajesarray = array();
					$batallaarray = array();
					$batallasactivas = new batalla($BG->con);	
					$batallasactivas->setestado(0);
					$batallasactivas = $batallasactivas->read(true,1,array("estado"));
					$cantidadBatallas = count($batallasactivas);
					
					if(count($batallasactivas)>0)
					{
						$configuracionuso = new configuracion($BG->con);
						$configuracionuso->setid($batallasactivas[0]->getronda());
						$configuracionuso = $configuracionuso->read(false,1,array("id"));
						$text .="<div class=\"alert alert-info\">Ahora usted puede votar por ".$configuracionuso->getlimitevotos()." personajes</div>";
					}
					
					$eventoactivo = new evento($BG->con);
					$eventoactivo->setestado(1);
					$eventoactivo = $eventoactivo->read(false,1,array("estado"));
					
					$peronajesparticipantes = new personajepar($BG->con);
					$peronajesparticipantes = $peronajesparticipantes->read();
					
					for($i=0;$i<$cantidadBatallas;$i++)
					{
						$arraysolabatalla = array();	
						$arrawpersonaje = array();	
						$batallaarray[] = $batallasactivas[$i]->getid();
						$datos = "";
						
						$configuracionuso = new configuracion($BG->con);
						$configuracionuso->setid($batallasactivas[$i]->getronda());
						$configuracionuso = $configuracionuso->read(false,1,array("id"));
						
						$participaciones = new participacion($BG->con);
						$participaciones->setidbatalla($batallasactivas[$i]->getid());
						$participaciones = $participaciones->read(true,1,array("idbatalla"));
						
						foreach($participaciones as $participante)
						{
							$arraysolabatalla[] = $participante->getidpersonaje();
							$arrawpersonaje[] = arrayobjeto($peronajesparticipantes,"id",$participante->getidpersonaje());
						}
						$arrawpersonaje = ordenarpersonajes($arrawpersonaje);
						foreach($arrawpersonaje as $participante)
							$datos.=panelvotar($participante->getnombre(),$participante->getid(),$participante->getimagenpeq(),$participante->getserie());	
						$text .= lugarvotacion($configuracionuso->getnombre()." ".$batallasactivas[$i]->getgrupo(),$datos);
						$personajesarray[] = $arraysolabatalla;
					}
					$text.= input("evento".$eventoactivo->getid(),"hidden","","","evento".$eventoactivo->getid());
					$text = "<form role=\"form\" action=\"votacion.php?action=1\" method=\"post\">".$text."      <button type=\"submit\" id=\"botonvoto\" class=\"btn btn-default\" disabled=\"disabled\">Votar</button>
		</form>";
					$pagina = ingcualpag($pagina,"votacion",$text);
					if($ClaseMaestra->useractivo)
						$ClaseMaestra->Pagina(scriptvotacion($personajesarray,$batallaarray,$configuracionuso->getlimitevotos(),$eventoactivo->getid(),$useractual->getid()),$pagina);
					else
						$ClaseMaestra->Pagina(scriptvotacion($personajesarray,$batallaarray,$configuracionuso->getlimitevotos(),$eventoactivo->getid(),0),$pagina);
				}
			}
			else
			{
				$text ="<h1>Votos emitidos</h1>";
				$faltanestahora = falta($horafinal);
				$text .="<h5>Faltan ".$faltanestahora." para que termine el match</h5>";
				//$ClaseMaestra->devip();duracionbatalla
				//$ipactual = $ClaseMaestra->ipcontext;
				$useractual = $ClaseMaestra->user;
				
				$ipvotante = new ip($BG->con);
				$ipvotante->setcodepass($ipactual->getmastercode());
				$ipvotante->setidevento($ipactual->getidevento());
				$ipvotante->setusada(1);
				$ipvotante = $ipvotante->read(true,3,array("codepass","AND","idevento","AND","usada"));
				
				$batallasactivas = new batalla($BG->con);	
				$batallasactivas->setestado(0);
				$batallasactivas = $batallasactivas->read(true,1,array("estado"));
				$cantidadBatallas = count($batallasactivas);
					
				$peronajesparticipantes = new personajepar($BG->con);
				$peronajesparticipantes = $peronajesparticipantes->read();
					
				for($i=0;$i<$cantidadBatallas;$i++)
				{
					$arrawpersonaje = array();	
					$datos = "";
						
					$configuracionuso = new configuracion($BG->con);
					$configuracionuso->setid($batallasactivas[$i]->getronda());
					$configuracionuso = $configuracionuso->read(false,1,array("id"));
						
					$votoactual = new voto($BG->con);
					$votoactual->setidbatalla($batallasactivas[$i]->getid());
					$votoactual->setcodepass($ipvotante[0]->getcodepass());
					$votoactual = $votoactual->read(true,2,array("idbatalla","AND","codepass"));
					foreach($votoactual as $participante)
					{
						$arrawpersonaje[] = arrayobjeto($peronajesparticipantes,"id",$participante->getidpersonaje());
					}
					$arrawpersonaje = ordenarpersonajes($arrawpersonaje);
					foreach($arrawpersonaje as $verpartpersonaje)
						$datos.=panelvotar($verpartpersonaje->getnombre(),$verpartpersonaje->getid(),$verpartpersonaje->getimagenpeq(),$verpartpersonaje->getserie(),false);	
					$text .= lugarvotacion($configuracionuso->getnombre()." ".$batallasactivas[$i]->getgrupo(),$datos);
	
				}
				$pagina = ingcualpag($pagina,"votacion",$text);
				$ClaseMaestra->Pagina("",$pagina);
			}//fin else de usadas
		}
	}
	else
	{
		$text = "";
		$revcalendario = new calendario($BG->con);
		$revcalendario->setidtorneo($torneoActual->getid());
		$revcalendario->setaccion("ACTBA");
		$revcalendario->sethecho(-1);
		$revcalendario = $revcalendario->read(true,3,array("idtorneo","AND","accion","AND","hecho"),1,array("fecha","ASC"));
		if(count($revcalendario)==0)
		{
			$text .="<div class=\"alert alert-info\">Aun no hay enfrentamientos programados</div>";
		}
		else
		{
			$text ="<div class=\"alert alert-info\">El dia ".$revcalendario[0]->gettargetdate()." se llevaran a cabo los siguientes enfrentamientos</div>";
				
			$batallasactivas = new batalla($BG->con);
			$batallasactivas->setfecha($revcalendario[0]->gettargetdate());
			$batallasactivas = $batallasactivas->read(true,1,array("fecha"));
			$cantidadBatallas = count($batallasactivas);
				
			$peronajesparticipantes = new personajepar($BG->con);
			$peronajesparticipantes = $peronajesparticipantes->read();
				
			for($i=0;$i<$cantidadBatallas;$i++)
			{
				$arrawpersonaje = array();
				$datos = "";
					
				$configuracionuso = new configuracion($BG->con);
				$configuracionuso->setid($batallasactivas[$i]->getronda());
				$configuracionuso = $configuracionuso->read(false,1,array("id"));
					
				$participaciones = new participacion($BG->con);
				$participaciones->setidbatalla($batallasactivas[$i]->getid());
				$participaciones = $participaciones->read(true,1,array("idbatalla"));
					
				foreach($participaciones as $participante)
				{
					$arrawpersonaje[] = arrayobjeto($peronajesparticipantes,"id",$participante->getidpersonaje());
				}
				$arrawpersonaje = ordenarpersonajes($arrawpersonaje);
				foreach($arrawpersonaje as $participante)
					$datos.=panelvotar($participante->getnombre(),$participante->getid(),$participante->getimagenpeq(),$participante->getserie(),false);	
				$text .= lugarvotacion($configuracionuso->getnombre()." ".$batallasactivas[$i]->getgrupo(),$datos);
			}
		}
		$pagina = ingcualpag($pagina,"votacion",$text);
		$ClaseMaestra->Pagina("",$pagina);
	}//else de que no hay batallas
	$BG->close();

}
else
{
	$ClaseMaestra = new MasterClass("votacion");
	$ClaseMaestra->VerificacionIdentidad(1);
	
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	if($torneoActual->getestado()==2)
	{
		$eventoactivo = new evento($BG->con);
		$eventoactivo->setestado(1);
		$eventoactivo = $eventoactivo->read(false,1,array("estado"));
		
		$voto = $_POST["evento".$eventoactivo->getid()];
		$changevoto = $voto;
		$arreglovotos = arrvoto($voto);
		
		$estaip = getRealIP();
		
		$revisarip = new ip($BG->con);
		$revisarip->setuniquecode($_COOKIE['uniqueCode']);
		$revisarip = $revisarip->read(false,1,array("uniquecode"));
		
		if($revisarip->getusada()==0 && $eventoactivo->getid()==$arreglovotos["metadatos"]["idevento"] && $estaip==$arreglovotos["metadatos"]["ip"])
		{
			for($i=1;$i<=$arreglovotos["metadatos"]["cantidadmatch"];$i++)
			{
				foreach($arreglovotos["votos".$i] as $voto)
				{
					if($ClaseMaestra->useractivo && $voto != 0)
					{
						$nuevovoto = new votouser($BG->con);
						$nuevovoto->setiduser($ClaseMaestra->user->getid());
						$nuevovoto->setidbatalla($arreglovotos["metadatosbatalla".$i]["idbatalla"]);
						$nuevovoto->setidpersonaje($voto);
						$nuevovoto->setfecha(fechaHoraActual());
						$nuevovoto->save();
					}
					if($voto != 0)
					{
						$nuevovotousar = new voto($BG->con);
						$nuevovotousar->setidbatalla($arreglovotos["metadatosbatalla".$i]["idbatalla"]);
						$nuevovotousar->setidpersonaje($voto);
						$nuevovotousar->setfecha(fechaHoraActual());
						$nuevovotousar->setuniquecode($_COOKIE['uniqueCode']);
						$nuevovotousar->setcodepass($_COOKIE['CodePassVote']);
						$nuevovotousar->setidevento($torneoActual->getid());
						$nuevovotousar->save();		
					}
				}
			}
			$cambiarip = new ip($BG->con);
			$cambiarip->setuniquecode($_COOKIE['uniqueCode']);
			$cambiarip = $cambiarip->read(false,1,array("uniquecode"));
			$cambiarip->setusada(1);
			$cambiarip->setforumcode($changevoto);
			$cambiarip->update(2,array("usada","forumcode"),1,array("uniquecode"));
			
			$nuevolog = new reg($BG->con,-1,"VOTO",fechaHoraActual(),1,$estaip,$changevoto);
			$nuevolog->save();
		}
	}
	Redireccionar("votacion.php");
}

function scriptvotacion($personaje,$batalla,$maximo,$idevento,$iduser)
{
	$text = "<script type=\"text/javascript\">\n";
	$text .= "var varpersonajes = [";
	$k = 0;
	foreach($personaje as $i)
	{
		if($k!=0)
			$text .= ",";
		$text .= "[";
		$l = 0;
		foreach($i as $j)
		{
			if($l!=0)
				$text .= ",";
			$text .= $j;
			$l++;
		}
		$text .= "]";
		$k++;
	}
	$text .= "];";
	
	$text .= "var varbatalla = [";
	$k = 0;
	foreach($batalla as $i)
	{
		if($k!=0)
			$text .= ",";
		$text .= $i;
		$k++;
	}
	$text .= "];";
	
	//$text .= "llenardatos(varpersonajes,".$maximo.",\"".getRealIP()."\",".$idevento.",".$iduser.",varbatalla,".count($batalla).");";
	
	$text .= /*"var personajes = new Array();
var inversabatalla = new Array();
var inversapersonaje = new Array();
var idbatalla = new Array();
var activado = new Array();
var ip;
var idevento;
var iduser;
var idbatalla;
var cantidadmatch;"*/"
var votacion = new votajs(varpersonajes,".$maximo.",\"".getRealIP()."\",".$idevento.",".$iduser.",varbatalla,".count($batalla).");votacion.init();";/*
function llenardatos(arrayidpersonaje,inmaximo,inip,inidevento,iniduser,arrayidbatalla,incantidadmatch)
{
	personajes = arrayidpersonaje;
	idbatalla = arrayidbatalla;
	cantidadmatch = incantidadmatch;
	maximo = inmaximo;
	
	ip = inip;
	idevento = inidevento;
	iduser = iniduser;
	
	for(var i=0;i<cantidadmatch;i++)
	{
		activado[i] = new Array();
		for(var j=0;j<personajes[i].length;j++)	
		{
			inversabatalla[personajes[i][j]] = i;	
			inversapersonaje[personajes[i][j]] = j;	
			activado[i][j] = 0;
		}
	}
}

function votoactivar(id) 
{
	var batallapersonaje = inversabatalla[id];
	if(contaractivos(batallapersonaje)<maximo-1)
	{
		$(\"#idpersonaje\"+id).addClass(\"active\");
		$(\"#idpersonaje\"+id).html(\"Listo\")
		activado[batallapersonaje][inversapersonaje[id]]=1;
		$(\"#idpersonaje\"+id).attr(\"onclick\", \"votodesactivar(\"+id+\")\");
	}
	else if(contaractivos(batallapersonaje)==maximo-1)
	{
		$(\"#idpersonaje\"+id).addClass(\"active\");
		$(\"#idpersonaje\"+id).html(\"Listo\")
		activado[batallapersonaje][inversapersonaje[id]]=1;
		$(\"#idpersonaje\"+id).attr(\"onclick\", \"votodesactivar(\"+id+\")\");
		for(var i=0;i<activado[batallapersonaje].length;i++)
		{
			if(activado[batallapersonaje][i]==0)
			{
				$(\"#idpersonaje\"+personajes[batallapersonaje][i]).attr(\"disabled\", \"disabled\");
			}
		}
	}
	datospost();
}
		
function votodesactivar(id)
{
	var batallapersonaje = inversabatalla[id];
	if(contaractivos(batallapersonaje)==maximo)
		for(var i=0;i<activado[batallapersonaje].length;i++)
			if(activado[batallapersonaje][i]==0)
				 $(\"#idpersonaje\"+personajes[batallapersonaje][i]).removeAttr(\"disabled\");
	$(\"#idpersonaje\"+id).removeClass(\"active\");
	$(\"#idpersonaje\"+id).html(\"Votar\")
	activado[batallapersonaje][inversapersonaje[id]]=0;
	$(\"#idpersonaje\"+id).attr(\"onclick\", \"votoactivar(\"+id+\")\");
	datospost();
}

function contaractivos(batallacontar)
{
	var cantidadactivos = 0;
	for(var i=0;i<activado[batallacontar].length;i++)
	{
		if(activado[batallacontar][i]==1)
			cantidadactivos++;
	}	
	return cantidadactivos;
}
		
function datospost()
{
	var eventocadena = iduser+\"-\"+idevento+\"-\"+ip+\";\";
	for(var j=0;j<cantidadmatch;j++)
	{
		eventocadena += \";\";
		eventocadena += idbatalla[j]+\"-\"+contaractivos(j)+\"-\"+maximo;
		for(var i=0;i<activado[j].length;i++)
			if(activado[j][i]==1)
				eventocadena += \"-\"+personajes[j][i];
	}
	$(\"#evento\"+idevento).attr(\"value\",eventocadena );
}";*/
	
    $text .= "</script>\n";	
	return $text;
}
?>