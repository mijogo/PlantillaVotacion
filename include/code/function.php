<?php
function setCookies()
{
	$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$cad = "";
	for($i=0;$i<20;$i++) 
		$cad .= substr($str,rand(0,62),1);
	//setcookie("CodePassVote",$cad,time()+(14*60*60*24));
	CrearCookie("CodePassVote",$cad,2);
	return $cad;
}

function CrearCookie($nombre,$valor,$tipo)
{
	if($tipo==0)
		$duracion = -100000;
	elseif($tipo==1)
		$duracion = 100*24*60*60*1000;
	elseif($tipo==2)
		$duracion = 30*24*60*60*1000;
	elseif($tipo==3)
		$duracion = 20*60*1000;
	else
		$duracion = 2*24*60*60*1000;;
		echo "<head>
<script languaje=\"JavaScript\">
function setCookie(cname, cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + (".$duracion."));
    var expires = \"expires=\"+d.toUTCString();
    document.cookie = cname + \"=\" + cvalue + \"; \" + expires;
} 
setCookie(\"".$nombre."\",\"".$valor."\")
</script>
</head>";
}

function falta($limite)
{
	$horaactual = fechaHoraActual("H:i:s");
	return restarhora($limite,$horaactual);
}

function restarhora($firsthora,$secondhora)
{
	$finalhora=array();
	$firsthora = explode(":",$firsthora);
	$secondhora = explode(":",$secondhora);
	if($secondhora[2]>$firsthora[2])
	{
		$finalhora[2] = $firsthora[2]+60-$secondhora[2];
		$firsthora[1]--;
	}
	else
		$finalhora[2] = $firsthora[2]-$secondhora[2];
		
	if($secondhora[1]>$firsthora[1])
	{
		$finalhora[1] = $firsthora[1]+60-$secondhora[1];
		$firsthora[0]--;
	}
	else
		$finalhora[1] = $firsthora[1]-$secondhora[1];
	$finalhora[0] = $firsthora[0]-$secondhora[0];

	return $finalhora[0].":".$finalhora[1];
}

function fechaHoraActual($format="Y-m-d H:i:s")
{
 	date_default_timezone_set('UTC');
	return date($format);
}
function FechaMayor($fecha1,$fecha2)
{
	$corte1 = explode(" ",$fecha1);
	$Hora1 = explode(":",$corte1[1]);
	$Dia1 = explode("-",$corte1[0]);
	$corte2 = explode(" ",$fecha2);
	$Hora2 = explode(":",$corte2[1]);
	$Dia2 = explode("-",$corte2[0]);
	if($Hora1[0]==$Hora2[0]&&$Hora1[1]==$Hora2[1]&&$Hora1[2]==$Hora2[2]&&$Dia1[0]==$Dia2[0]&&$Dia1[1]==$Dia2[1]&&$Dia1[2]==$Dia2[2])
		return 0;
	
	if($Dia1[0]>$Dia2[0])
		return 1;
	else if($Dia1[0]<$Dia2[0])
		return -1;
	else if($Dia1[1]>$Dia2[1])
		return 1;
	else if($Dia1[1]<$Dia2[1])
		return -1;
	else if($Dia1[2]>$Dia2[2])
		return 1;
	else if($Dia1[2]<$Dia2[2])
		return -1;	
	else if($Hora1[0]>$Hora2[0])
		return 1;
	else if($Hora1[0]<$Hora2[0])
		return -1;
	else if($Hora1[1]>$Hora2[1])
		return 1;
	else if($Hora1[1]<$Hora2[1])
		return -1;
	else if($Hora1[2]>$Hora2[2])
		return 1;
	else if($Hora1[2]<$Hora2[2])
		return -1;	
}

function grafico($titulo="",$nombre="",$titulos="",$datos="")
{
	$grafic = "";
	$grafic .= "    <script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">
      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([";
        for($i=0;$i<count($titulos);$i++)
        {
        	if($i==0)
        		$grafic .= "[";
        	$grafic .= "'".$titulos[$i]."'";
        	if($i==count($titulos)-1)
        		$grafic.="]";
        	$grafic.=",";
        }
        $grafic.="\n";
        for($j=0;$j<count($datos);$j++)
        {
        	for($i=0;$i<count($titulos);$i++)
       	 	{
        		if($i==0)
        			$grafic .= "[";
        		if($i==0)
        			$grafic .= "'".$datos[$j][$i]."'";
        		else
        			$grafic .= $datos[$j][$i];   			
        		if($i==count($titulos)-1)
        			$grafic.="]";
        		if(!($i==count($titulos)-1&&$j==count($datos)-1))
        			$grafic.=",";
        	}
        	$grafic .= "\n";
        }
        $grafic .= "]);

        var options = {
          title: '".$titulo."'
        };

        var chart = new google.visualization.LineChart(document.getElementById('".$nombre."'));
        chart.draw(data, options);
      }
    </script>
";
	return $grafic;
}

function GenerarSiguiente($actual,$Ronda)
{
	$topo = explode("-",$Ronda);
	if($topo[0] == "Ronda")
	{
		$actual = explode("-",$actual);
		$cantidad = configuracion($Ronda,"NBatalla")/configuracion(configuracion($Ronda,"nextRonda1"),"NBatalla");
		$actual[1]=$actual[1]/$cantidad;
		$actual[1]=$actual[1];
		$actual[1]=ceil($actual[1]);
		if($actual[1]<10)
			$actual[1] = "0".$actual[1];
		return $actual[0]."-".$actual[1];
	}
	else
	{
		$cantidad = configuracion($Ronda,"NGrupos")/configuracion(configuracion($Ronda,"nextRonda1"),"NGrupos");
		$actual=$actual/$cantidad;
		$actual=$actual;
		$actual=ceil($actual);
		if($actual<10)
			$actual = "0".$actual;
		return $actual;

	}
}

function Redireccionar($url="")
{
	echo "<head>
<script languaje=\"JavaScript\">
location.href='".$url."';
</script>
</head>";
}

function fechaGenerador($nombreFecha="")
{
	for($i=2013;$i<2030;$i++)
	{
		
		$anio[$i-2013][0]=$i;
		$anio[$i-2013][1]=$i;
	}
	for($i=1;$i<13;$i++)
	{
		if($i<10)
		{
			$mes[$i-1][0]="0".$i;
			$mes[$i-1][1]="0".$i;
		}
		else
		{
			$mes[$i-1][0]=$i;
			$mes[$i-1][1]=$i;
		}
	}
	for($i=1;$i<32;$i++)
	{
		if($i<10)
		{
			$dis[$i-1][0]="0".$i;
			$dis[$i-1][1]="0".$i;
		}
		else
		{
			$dis[$i-1][0]=$i;
			$dis[$i-1][1]=$i;
		}
	}
	for($i=0;$i<24;$i++)
	{
		if($i<10)
		{
			$hora[$i][0]="0".$i;
			$hora[$i][1]="0".$i;
		}
		else
		{
			$hora[$i][0]=$i;
			$hora[$i][1]=$i;
		}
	}
	for($i=0;$i<60;$i++)
	{
		if($i<10)
		{
			$min[$i][0]="0".$i;
			$min[$i][1]="0".$i;
		}
		else
		{
			$min[$i][0]=$i;
			$min[$i][1]=$i;
		}
	}
	for($i=0;$i<60;$i++)
	{
		if($i<10)
		{
			$seg[$i][0]="0".$i;
			$seg[$i][1]="0".$i;
		}
		else
		{
			$seg[$i][0]=$i;
			$seg[$i][1]=$i;
		}
	}
	return selected($nombreFecha."Anio",$anio)."-".selected($nombreFecha."Mes",$mes)."-".selected($nombreFecha."Dia",$dis)." ".selected($nombreFecha."Hora",$hora).":".selected($nombreFecha."Min",$min)." ".selected($nombreFecha."Seg",$seg);
}

function fechaGeneradorwoHora($nombreFecha="")
{
	for($i=2010;$i<2030;$i++)
	{
		
		$anio[$i-2010][0]=$i;
		$anio[$i-2010][1]=$i;
	}
	for($i=1;$i<13;$i++)
	{
		if($i<10)
		{
			$mes[$i-1][0]="0".$i;
			$mes[$i-1][1]="0".$i;
		}
		else
		{
			$mes[$i-1][0]=$i;
			$mes[$i-1][1]=$i;
		}
	}
	for($i=1;$i<32;$i++)
	{
		if($i<10)
		{
			$dis[$i-1][0]="0".$i;
			$dis[$i-1][1]="0".$i;
		}
		else
		{
			$dis[$i-1][0]=$i;
			$dis[$i-1][1]=$i;
		}
	}
	return selected($nombreFecha."Anio",$anio)."-".selected($nombreFecha."Mes",$mes)."-".selected($nombreFecha."Dia",$dis);
}


function cambioFecha($actual,$min)
{
	$actual = explode(" ",$actual);
	$fecha1 = explode("-",$actual[0]);
	$fecha2 = explode(":",$actual[1]);
	$timestamp = mktime($fecha2[0], $fecha2[1]+$min,$fecha2[2], $fecha1[1],$fecha1[2], $fecha1[0]);
    return date('Y-m-d H:i:s', $timestamp);
}
function cambioFechaseg($actual,$min)
{
	$actual = explode(" ",$actual);
	$fecha1 = explode("-",$actual[0]);
	$fecha2 = explode(":",$actual[1]);
	$timestamp = mktime($fecha2[0], $fecha2[1],$fecha2[2]+$min, $fecha1[1],$fecha1[2], $fecha1[0]);
    return date('Y-m-d H:i:s', $timestamp);
}
function sacarhora($actual)
{
	$actual = explode(" ",$actual);
	$fecha1 = explode("-",$actual[0]);
	$fecha2 = explode(":",$actual[1]);
	return $fecha2[0].":".$fecha2[1];
}

function getRealIP() 
{
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
           
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
       
        return $_SERVER['REMOTE_ADDR'];
 }
 
function TransformDato($stringEntrada)
{
	$batallasB=explode(";",$stringEntrada);
	$valido=true;
	
	for($i=0;$i<count($batallasB)&&$valido;$i++)
	{
		$datosNa=explode("-",$batallasB[$i]);
		$nBatalla=substr($datosNa[0],1);
		$valoCom = explode("/",substr($datosNa[1],1));
		if($valoCom[0]>$valoCom[1])
			$valido=false;
		$contar[$nBatalla]["nVotos"]=$valoCom[0];
		if(($contar[$nBatalla]["nVotos"]!=0&&count($datosNa)!=$contar[$nBatalla]["nVotos"]+2)||($contar[$nBatalla]["nVotos"]==0&&count($datosNa)!=3))
			$valido=false;
		for($j=2;$j<count($datosNa)&&$valido;$j++)
		{
			if($j==2)
				$contar[$nBatalla][$j-2]=substr($datosNa[$j],1);
			else
				$contar[$nBatalla][$j-2]=$datosNa[$j];
		}
	}
	$regresar=array();
	$regresar[]=$valido;
	$regresar[]=$contar;
	return $regresar;
}

function fechaCorta($fecha)
{
	$fecha = explode(" ",$fecha);
	$fecha2=explode("-",$fecha[0]);
	return $fecha2[2]."/".$fecha2[1];
}

function cambioGrupo($grupo,$nActual,$nSiguiente,$tipo)
{
	if($tipo=="ELIMI")
	{
		$cantidad = $nActual/$nSiguiente;
		$actual=$grupo/$cantidad;
		$actual=ceil($actual);
		return $actual;
	}
	elseif($tipo=="ELGRU")
	{
		$actual = explode("-",$grupo);
		$cantidad = $nActual/$nSiguiente;
		$actual[1]=$actual[1]/$cantidad;
		$actual[1]=ceil($actual[1]);
		return $actual[0]."-".$actual[1];
	}
}

function arrayobjeto($arreglo,$tipoDato,$dato)
{
	$objeto = false;
	for($i=0;$i<count($arreglo);$i++)
		if($arreglo[$i]->$tipoDato == $dato)
			$objeto = $arreglo[$i];
	return $objeto;
}
function comprobararray($arreglo,$tipoDato,$dato)
{
	$objeto = false;
	for($i=0;$i<count($arreglo);$i++)
		if($arreglo[$i]->$tipoDato == $dato)
			$objeto = true;
	return $objeto;
}

function ingPagina($estructura,$menu,$script,$body,$widget,$extra="")
{
	$estructura = explode("[[menu]]",$estructura);
	$estructura = $estructura[0].$menu.$estructura[1];
	$estructura = explode("[[script]]",$estructura);
	$estructura = $estructura[0].$script.$estructura[1];
	$estructura = explode("[[body]]",$estructura);
	$estructura = $estructura[0].$body.$estructura[1];
	$estructura = explode("[[widget]]",$estructura);
	$estructura = $estructura[0].$widget.$estructura[1];
	$estructura = explode("[[info]]",$estructura);
	$estructura = $estructura[0].$extra.$estructura[1];
	return $estructura;
}

function ingcualpag($pagina,$posicion,$ingresar)
{
	$estructura	= explode("[[".$posicion."]]",$pagina);
	return $estructura[0].$ingresar.$estructura[1];
}

function uploadimage($archivo,$carpeta="perimage")
{
	if($archivo[ 'tmp_name' ] !="" )
	{
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$cad = "";
		for($i=0;$i<18;$i++) 
		{
			$cad .= substr($str,rand(0,62),1);
		}
		$tamano = $archivo[ 'size' ];
		$tamaño_max="512000";
		if( $tamano < $tamaño_max)
		{ 
			$destino = $carpeta ;
			$sep=explode('image/',$archivo["type"]);
			$tipo=$sep[1];
			$Stringcompleto =  $destino . '/' .$cad.'.'.$tipo;
			if($tipo == "gif" || $tipo == "pjpeg" || $tipo == "bmp" || $tipo == "png" || $tipo == "jpg" || $tipo == "jpeg")
			{
				move_uploaded_file ($archivo[ 'tmp_name' ], $Stringcompleto);
				return array(true,$Stringcompleto);
			}
			else return array(false,"No Tipo");
		}
		else return array(false,"No Tamaño");	
	}
	else return array(false,"No Imagen");	
}

function cambiarletra($char,$aletra=true)
{
	$letras=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O");	
	if($aletra)
		return $letras[$char-1];
	else
	{
		for($i=0;$i<count($letras);$i++)
			if($letras[$i]==$char)
				return $i+1;
	}
	return 0;
}

function agregarcalendario($titulo,$fechainicio,$url="",$fechafin="",$allday = true)
{
	$textallday ="";
	if(!$allday)
		$textallday=",allDay: false";
	$texturl ="";
	if($url!="")
		$texturl=",url: '".$url."'";
	$fecha1 = explode(" ",$fechainicio);
	$fecha1[0] = explode("-",$fecha1[0]);
	if(count($fecha1>1))
	{
		$fecha1[1] = explode(":",$fecha1[1]);
		$tectdia1=",start: new Date(".$fecha1[0][0].",".($fecha1[0][1]-1).",".$fecha1[0][2].",".$fecha1[1][0].",".$fecha1[1][1].")";
	}
	else
		$tectdia1=",start: new Date(".$fecha1[0][0].",".($fecha1[0][1]-1).",".$fecha1[0][2].",".$fecha1[1][0].",".$fecha1[1][1].")";
	$tectdia2="";
	if($fechafin!="")
	{
		$fecha2 = explode(" ",$fechafin);
		$fecha2[0] = explode("-",$fecha2[0]);
		if(count($fecha2>1))
		{
			$fecha2[1] = explode(":",$fecha2[1]);
			$tectdia2=",end: new Date(".$fecha2[0][0].",".($fecha2[0][1]-1).",".$fecha2[0][2].",".$fecha2[1][0].",".$fecha2[1][1].")";
		}
		else
			$tectdia2=",end: new Date(".$fecha2[0][0].",".($fecha2[0][1]-1).",".$fecha2[0][2].",".$fecha2[1][0].",".$fecha2[1][1].")";
	}	
	
	
	$text = "
            {
                title: '".$titulo."'
               	".$tectdia1."
                ".$tectdia2."
                ".$textallday."
            	".$texturl."
            }";
	
	return $text;	
}

function ordenarpersonajes($arreglo)
{
	for($i=0;$i<count($arreglo);$i++)
		for($j=0;$j<count($arreglo)-1;$j++)
		{
			if(strcmp($arreglo[$j]->getserie(),$arreglo[$j+1]->getserie())==1)
			{
				$temp = $arreglo[$j];
				$arreglo[$j] = $arreglo[$j+1];
				$arreglo[$j+1] = $temp;
			}
			elseif(strcmp($arreglo[$j]->getserie(),$arreglo[$j+1]->getserie())==0)
				if(strcmp($arreglo[$j]->getnombre(),$arreglo[$j+1]->getnombre())==1)
				{
					$temp = $arreglo[$j];
					$arreglo[$j] = $arreglo[$j+1];
					$arreglo[$j+1] = $temp;
				}
		}
	return $arreglo;
}


function arrvoto($voto)
{
	$arrayvoto = array();
	$stringvoto = explode(";",$voto);
	$metadatos = explode("-",$stringvoto[0]);
	$arrayvoto["metadatos"]["iduser"]=$metadatos[0];
	$arrayvoto["metadatos"]["idevento"]=$metadatos[1];
	$arrayvoto["metadatos"]["ip"]=$metadatos[2];
	$arrayvoto["metadatos"]["cantidadmatch"]=$metadatos[3];
	for($i=1;$i<=$arrayvoto["metadatos"]["cantidadmatch"];$i++)
	{
		$matchdatos = explode("-",$stringvoto[$i]);
		$arrayvoto["metadatosbatalla".$i]["idbatalla"]=$matchdatos[0];
		$arrayvoto["metadatosbatalla".$i]["votosemitidos"]=$matchdatos[1];
		$arrayvoto["metadatosbatalla".$i]["maximo"]=$matchdatos[2];
		for($j=3;$j<$arrayvoto["metadatosbatalla".$i]["votosemitidos"]+3;$j++)
			$arrayvoto["votos".$i][]=$matchdatos[$j];
	}
	return $arrayvoto;
}

function coloresgraf($primdato,$tipo=true)
	{
		if($tipo)
		{
			switch($primdato)
			{
				case 0:return "993333";break;
				case 1:return "6071ae";break;
				case 2:return "52a068";break;
				case 3:return "65acaa";break;
				case 4:return "d664ab";break;
				case 5:return "825f9d";break;
				case 6:return "91b75a";break;
				case 7:return "cc9966";break;
				case 8:return "509e4c";break;
				case 9:return "999966";break;
				default:return "aaaaaa";break;	
			}	
		}
		else
		{
			$text = "";
			$color=substr($primdato,0,2); 
			$number=hexadecitoint($color);
			$text.=$number.",";
			$color=substr($primdato,2,2); 
			$number=hexadecitoint($color);
			$text.=$number.",";
			$color=substr($primdato,4,2); 
			$number=hexadecitoint($color);
			$text.=$number;		
			return $text;
		}
	}
	
	function hexadecitoint($valor)
	{
		$valores["0"]=0;
		$valores["1"]=1;
		$valores["2"]=2;
		$valores["3"]=3;
		$valores["4"]=4;
		$valores["5"]=5;
		$valores["6"]=6;
		$valores["7"]=7;
		$valores["8"]=8;
		$valores["9"]=9;
		$valores["a"]=10;
		$valores["b"]=11;
		$valores["c"]=12;
		$valores["d"]=13;
		$valores["e"]=14;
		$valores["f"]=15;
		return $valores[$valor[0]]*16+$valores[$valor[1]];
	}
	function rondapos($i)
	{
		$rondapos[10]=1;
		$rondapos[8]=2;
		$rondapos[7]=3;
		$rondapos[6]=4;
		$rondapos[5]=5;
		$rondapos[4]=6;
		$rondapos[3]=7;
		$rondapos[2]=8;
		return	$rondapos[$i];
	}
?>