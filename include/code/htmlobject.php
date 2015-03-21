<?php
function refresh($time)
{
	return "<meta http-equiv=\"refresh\" content=\"".$time."\">";
}

function div($content ="",$id="",$class="",$style="")
{
	$text = "<div";
	if($id!="")
		$text .= " id=\"$id\" ";
	if($class!="")
		$text .= " class=\"$class\" ";
	if($style!="")
		$text .= " style=\"$style\" ";
	$text .= ">\n";
	$text .= $content;
	$text .= "\n</div>";
	return $text;
}

function form($content="",$name="",$action="",$extra="",$method="POST")
{
	$text = "<form name=\"".$name."\" action=\"".$action."\" method=\"".$method."\" ".$extra.">
	".$content."
	</form>";
	return $text;
}

function table($datos,$width="")
{
	$text ="<table>\n";
	if($width!="")
	{
		$cuantos=explode(";",$width);
		for($i=0;$i<count($cuantos);$i++)
			$cuantos[$i]=explode("-",$cuantos[$i]);
	}
	for($i=0;$i<count($datos);$i++)
	{
		$text .="<tr>\n";
		for($j=0;$j<count($datos[$i]);$j++)
		{
			if($i==0 && $width!="")
			{
				$hay=0;
				for($k=0;$k<count($cuantos);$k++)
				{
					if($cuantos[$k][0]==$j)
					{
						$text .="<td width=\"".$cuantos[$k][1]."px\">".$datos[$i][$j]."</td>\n";
						$hay++;
					}
				}
				if($hay==0)
					$text .="<td>".$datos[$i][$j]."</td>\n";
			}
			else
				$text .="<td>".$datos[$i][$j]."</td>\n";
		}
		$text .="</tr>\n";
	}
	$text .="</table>\n";
	return $text;
}
function input($nombre,$tipo,$value="",$class="",$extra="")
{
	$text = "<input type=\"".$tipo."\" name=\"".$nombre."\"";
	if($value != "")
		$text .= " value=\"".$value."\"";
	if($class!= "")
		$text .= " class=\"".$class."\"";
	$text .=" ".$extra.">";
	return $text;
}

function selected($name ="",$values="",$extra="")
{
	$text = "";
	$text .="<SELECT NAME=\"".$name."\" ".$extra." >";
	for($i=0;$i<count($values);$i++)
	{
		$text .="<OPTION VALUE=\"".$values[$i][0]."\">".$values[$i][1];
	}
	$text .="</SELECT>";
	return $text;
}

function botonVoto($idBatalla,$idPersonaje,$idPersonajesBatalla,$content)
{
	$text = "";
	$text .="<div id=\"B".$idBatalla."-".$idPersonaje."\" class=\"botoncito\">
<button class=\"button\" id =\"R".$idPersonaje."\" onclick=\"change('B".$idBatalla."-".$idPersonaje."','".$idPersonajesBatalla."')\">
".$content."
</button>
</div>";
	return $text;
}

function botonAct($content)
{
	$text = "";
	$text .="<div class=\"botoncito\">
<button class=\"buttonAct\">
".$content."
</button>
</div>";
	return $text;
}

function botonEscoger($content,$instancia,$cantidad)
{
	$text = "";
	$text .="<div class=\"botoncito\">
<button class=\"button\" onclick=\"Instancia('".$instancia."','".$cantidad."')\">
".$content."
</button>
</div>";
	return $text;
}

function formVoto($action,$batallas,$limite,$activado=true)
{
	$text = "";
	$nameForm = "Votar";
	$text .="<form name=\"".$nameForm."\" action=\"".$action."\" method=\"post\">
<input type=\"hidden\" value=\"";
for($i=0;$i<count($batallas);$i++)
{
	$text .="B".$batallas[$i]."-L0/".$limite."-V";
	if($i+1!=count($batallas))
		$text .=";";
}
$text .="\" name=\"votacion\" />";
if($activado)
	$text .= input("Enviar","submit","Votar","subboto");
$text .="</form>
";
	return $text;
}

function Nominaciones($cant,$Admin=false)
{
	$text ="";
	$text .="
<h1>Nominaciones</h1>
<div class=\"fight\">
";
	$datos[0][0]="Nombre";
	$datos[0][1]="Serie";
for($i=0;$i<$cant;$i++)
{
	$datos[$i+1][0]=input("Nombre[]","text","","","size=\"25\"");
	$datos[$i+1][1]=input("Serie[]","text","","","size=\"35\"");
}
$datos[$cant+1][0]="";
$datos[$cant+1][1]=input("Enviar","submit","Enviar","subboto");
$text1 = table($datos,"0-200");
if($Admin)
	$text .= form($text1,"inscipcion","?id=5&action=2&trato=1");
else
	$text .= form($text1,"inscipcion","?id=5&action=2&trato=1");
$text .="</div>";
return $text;
}

function img($src,$height="",$width="",$id="",$class="",$alt="")
{
	$text = "<img src=\"".$src."\" alt=\"".$alt."\" ";
	if($id!="")
		$text .= " id=\"$id\" ";
	if($class!="")
		$text .= " class=\"$class\" ";
	if($height!="")
		$text .= " height=\"$height\" ";
	if($width!="")
		$text .= " width=\"$width\" ";

	$text .= "/>\n";
	return $text;
}

function menu_html($datos,$nivel,$existeusuario,$usuarioactual,$esadmin)
{
	$text = "";
	$text .= " <div class=\"navbar navbar-inverse navbar-fixed-top\">
      <div class=\"container\">
        <div class=\"navbar-header\">
          <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
            <span class=\"icon-bar\"></span>
          </button>
          <a class=\"navbar-brand\" href=\"home.php\">Project name</a>
        </div>
        <div class=\"navbar-collapse collapse\">
		  <ul class=\"nav navbar-nav\">";

		 $abierto1=false;
		 $num=-1;
		 for($i=0;$i<count($datos);$i++)
		 {
			 if($datos[$i][4] == 0)
			 {
				if($datos[$i][5]=="")
					$url = "?id=".$datos[$i][1];
				else
					$url =  $datos[$i][5];
				$activo = "";
				if($datos[$i][3]==1)
					$activo = " class=\"active\"";
				$text .= "<li".$activo."><a href=\"".$url."\">".$datos[$i][2]."</a></li>";
			 }
			 else
			 {
			 	if(!$abierto1)
				{
					if($datos[$i][5]=="")
						$url = "?id=".$datos[$i][1]."&nivel=".$nivel;
					else
						$url =  $datos[$i][5];
					$text .= "            <li class=\"dropdown\">
              <a href=\"".$url."\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">".$datos[$i][2]." <b class=\"caret\"></b></a>
              <ul class=\"dropdown-menu\">";
			  		$abierto1=true;
					$num = $i;
				}
			 }
			 if($num != $i && ($abierto1 && ($i+1 == count($datos) || $datos[$i][0] != $datos[$i+1][0])))
			 {
				$text .="              </ul>
            </li>";
				$abierto1=false; 
			 }
		 }

          $text .= "</ul>
 		<div style=\"height: 1px;\" class=\"navbar-collapse collapse\">
          <ul class=\"nav navbar-nav navbar-right\">
            ";
			if($existeusuario)
			{
				$MenuAdmin="";
				if($esadmin)
					$MenuAdmin="<li><a href=\"admin.php\">Administración</a></li>   ";
				$text .=	"
		  	<li class=\"dropdown\">
		  	 <a href=\"perfil.php\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">".img("./image/".$usuarioactual->getimagen(),"20px","","","","perfil")."<b class=\"caret\"></b></a>
              	<ul class=\"dropdown-menu\">
					<li><a href=\"perfil.php\">Perfil</a></li>   
					<li><a href=\"configuracion.php\">Configuración</a></li> 
					".$MenuAdmin."  
					<li><a href=\"logout.php\">Cerrar Sesión</a></li>              
				</ul>
            </li>";
			}
			else
			{
				$text .="<li><a href=\"login.php\">Iniciar Sesión</a></li>";
			}
			$text .="
          </ul>
          <form class=\"navbar-form navbar-right\">
            <input class=\"form-control\" placeholder=\"Buscar...\" type=\"text\">
          </form>
        </div>
       
	    </div>
      </div>
    </div>";
	return $text;	
	

}

function tablaobjetos($titulo,$array)
{
	$text = "";
	$text.="
    <div class=\"panel panel-default\">\n";
	$text.="
      <div class=\"panel-heading\">".$titulo."</div>
	        <table class=\"table\">\n";
	for($i=0;$i<count($array);$i++)
	{
		$text.="<tr>\n";
		for($j=0;$j<count($array[$i]);$j++)
			$text.="<td>".$array[$i][$j]."</td>\n";	
		$text.="</tr>\n";
	}
	$text.="</table>
	</div>	\n";
	return $text;
}
?>