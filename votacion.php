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
	
	$this->BG = new DataBase();
	$this->BG->connect();
	$torneoActual = new torneo($this->BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));
	
	
	if($torneoActual->getestado()==2)
	{
		$text ="";
		$personajesarray = array();
		$batallaarray = array();
		$batallasactivas = new batalla($this->BG->con);	
		$batallasactivas->setestado(0);
		$batallasactivas = $batallasactivas->read(true,1,array("estado"));
		$cantidadBatallas = count($batallasactivas);
		
		$eventoactivo = new evento($this->BG->con);
		$eventoactivo->setestado(1);
		$eventoactivo = $eventoactivo->read(false,1,array("estado"));
		
		$peronajesparticipantes = new personajepar($this->BG->con);
		$peronajesparticipantes = $peronajesparticipantes->read();
		
		for($i=0;$i<$cantidadBatallas;$i++)
		{
			$arraysolabatalla = array();	
			$arrawpersonaje = array();	
			$batallaarray[] = $batallasactivas[$i]->getid();
			$datos = "";
			
			$configuracionuso = new configuracion($this->BG->con);
			$configuracionuso->setid($batallasactivas[$i]->getronda());
			$configuracionuso = $configuracionuso->read(false,1,array("id"));
			
			$participaciones = new participacion($this->BG->con);
			$participaciones->setidbatalla($batallasactivas[$i]->getid());
			$participaciones = $participaciones->read(true,1,array("idbatalla"));
			
			foreach($participaciones as $participante)
			{
				$arraysolabatalla[] = $participante->getidpersonaje();
				$arrawpersonaje[] = arrayobjeto($peronajesparticipantes,"id",$participante->getidpersonaje());
			}
			$arrawpersonaje = ordenarpersonajes($arrawpersonaje);
			foreach($arrawpersonaje as $participante)
				$datos.=panelvotar($arrawpersonaje->getnombre(),$arrawpersonaje->getid(),$arrawpersonaje->getimagenpeq(),$arrawpersonaje->getserie());	
			$text .= lugarvotacion($configuracionuso->getnombre()." ".$batallasactivas[$i]->getgrupo(),$datos);
			$personajesarray[] = $arraysolabatalla;
		}
		text.= input("evento".$eventoactivo->getid(),"","hidden");
		$pagina = ingcualpag($pagina,"votacion",$text);
		$ClaseMaestra->Pagina(scriptvotacion($personajesarray,$batallaarray,$configuracionuso->getlimitevotos(),$eventoactivo->getid(),4322),$pagina);
	}
	

}
else
{
		echo $_POST['evento432'];
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