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
function input($nombre,$tipo,$value="",$class="",$id="",$extra="")
{
	$text = "<input type=\"".$tipo."\" name=\"".$nombre."\"";
	if($value != "")
		$text .= " value=\"".$value."\"";
	if($id != "")
		$text .= " id=\"".$id."\"";
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
          <a class=\"navbar-brand\" href=\"home.php\">MSAT</a>
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
		  	 <a href=\"perfil.php\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">".img($usuarioactual->getimagen(),"22px","","","img-rounded","perfil")."<b class=\"caret\"></b></a>
              	<ul class=\"dropdown-menu\">
					<li><a href=\"perfil.php\">Perfil</a></li>   
					<li><a href=\"datouser.php\">Configuración</a></li> 
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

function inputform($id,$nombre,$value="",$type="text",$placeholder="")
{
	$text ="      <div class=\"form-group\">
        <label for=\"".$id."\">".$nombre."</label>\n";
	$text .="<input type=\"".$type."\" class=\"form-control\" id=\"".$id."\" name=\"".$id."\" placeholder=\"".$placeholder."\" value=\"".$value."\">\n";	
	$text .="</div>";
	return $text;
}

function inputalone($id,$value="",$type="text",$placeholder="")
{
	$text ="";
	$text .="<input type=\"".$type."\" class=\"form-control\" id=\"".$id."\" name=\"".$id."\" placeholder=\"".$placeholder."\" value=\"".$value."\">\n";
	return $text;
}

function inputimage($id,$nombre,$help)
{
	$text = "      <div class=\"form-group\">
        <label for=\"".$id."\">".$nombre."</label>
        <input type=\"file\" id=\"".$id."\" name=\"".$id."\">
        <p class=\"help-block\">".$help."</p>
      </div>";	
	  return $text;
}

function inputimagealone($id,$help)
{
	$text = "
        <input type=\"file\" id=\"".$id."\" name=\"".$id."\">
        <p class=\"help-block\">".$help."</p>";
	  return $text;
}

function inputselected($id,$nombre,$valores,$opciones,$value="")
{
	 $text = "<div class=\"form-group\">
        <label for=\"".$id."\">".$nombre."</label>
          <select class=\"form-control\" name=\"".$id."\" id=\"".$id."\">
		  ";
		  
		  for($i=0;$i<count($valores);$i++)
		  {
			  $extra = "";
			  
			  if($valores[$i]==$value)
			  	$extra = " selected";
		  	$text .= "<option value=\"".$valores[$i]."\"".$extra.">".$opciones[$i]."</option>\n";
		  }
		  
		  $text .= "
            </select>
     </div>";
	 return $text;
}

function inputselectedalone($id,$valores,$opciones,$value="")
{
		 $text = "
          <select class=\"form-control\" name=\"".$id."\" id=\"".$id."\">
		  ";
		  
		  for($i=0;$i<count($valores);$i++)
		  {
			  $extra = "";
			  
			  if($valores[$i]==$value)
			  	$extra = " selected";
		  	$text .= "<option value=\"".$valores[$i]."\"".$extra.">".$opciones[$i]."</option>\n";
		  }
		  
		  $text .= "
            </select>";
	 return $text;
}

function inputcheckbox($id,$nombre,$activo=false)
{
	 $extra = "";
	 if($activo)
	 	$extra=" checked";
	  $text = "<div class=\"checkbox\">
        <label>
          <input type=\"checkbox\" id=\"".$id."\" name=\"".$id."\" ".$extra."> ".$nombre."
        </label>
      </div>	";
	  return  $text;
}

function ingresardatos($titulo,$contenido)
{
	$text = "
    <label>".$titulo."</label>
    <p>".$contenido."</p>";
	return $text;
}

function inputcalendar($id, $nombre, $value,$datetime=false)
{
	$formato = "yyyy-MM-dd";
	if($datetime)
		$formato = "yyyy-MM-dd hh:mm:ss";
	
	$text ="      <div class=\"form-group\">
        <label for=\"".$id."\">".$nombre."</label>\n";
	$text .= " <div id=\"".$id."general\" class=\"input-append date\">
      <input type=\"text\" value=\"".$value."\" class=\"form-control inputtext\" id=\"".$id."\" name=\"".$id."\" ></input>
      <span class=\"add-on\">
        <i data-time-icon=\"icon-time\" data-date-icon=\"icon-calendar\"></i>
      </span>
 </div>
    <script type=\"text/javascript\">
      $('#".$id."general').datetimepicker({
        format: '".$formato."'
      });
    </script>";
	
	$text .="</div>";
	return $text;
}

function collapsecheckbox($idserie,$nombreserie,$idpersonaje,$nombrepersonaje,$peronajesparticipando)
{
		$text ="<button class=\"btn btn-primary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#Serie".$idserie."\">
	  $nombreserie
	</button>
	<div class=\"collapse\" id=\"Serie".$idserie."\">
	  <div class=\"well\">";
	 
		for($i=0;$i<count($idpersonaje);$i++)  
		{
			$change="";
			$change2="";
			for($j=0;$j<count($peronajesparticipando);$j++)
				if($peronajesparticipando[$j]==$idpersonaje[$i])
				{
					$change=" checked";
					$change2=" active";
				}
				$text .="    <div class=\"btn-group\" data-toggle=\"buttons\">
			  <label class=\"btn btn-default".$change2."\">
				<input type=\"checkbox\" name=\"personajes[]\" value=\"".$idpersonaje[$i]."\"".$change."> $nombrepersonaje[$i]
			  </label>
			</div>";
		}
			
	  $text .="</div>
	</div>";
	return $text;
}

function mostrarparticiapntes($id,$nombres)
{
	$text ="<div class=\"collapse\" id=\"".$id."\">
	  <div class=\"well\">";
	 
		for($i=0;$i<count($nombres);$i++)  
		{
			$text.= $nombres[$i];
			if($i<count($nombres)-1)
				$text.= ", ";
		}
			
	  $text .="</div>
	</div>";
	return $text;	
}

function botoncollapse($id,$nombre)
{
		$text ="<button class=\"btn btn-primary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#".$id."\">
	  ".$nombre."
	</button>";
	return $text;	
}

function panelcollapse($id,$nombre,$contenido)

{
		$text ="<div class=\"panel panel-default\">
	<div class=\"panel-heading\">
		<h4 class=\"panel-title\">
		".$nombre." 
                <button class=\"btn btn-default pull-right btn-xs\" type=\"button\" data-toggle=\"collapse\" data-target=\"#".$id."\">
                Ver Mas
                </button>
		</h4>
	</div>
    <div id=\"".$id."\" class=\"panel-collapse collapse\">
    	<div class=\"panel-body\">   
        ".$contenido."
				</div>
	</div>
</div>";
	return $text;	
}

function panelpersonaje($nombre,$img)
{
	$text ="            <div class=\"col-md-2\">
                <div class=\"panel panel-default\">
                    <div class=\"panel-heading\"><h3 class=\"text-center\">".$nombre."</h3></div>
                    <div class=\"panel-body text-center\">
        					<img src=\"".$img."\" ALT=\"Imagen\" width=100 class=\"img-rounded\">
                    </div>
                </div>
            </div>  ";
			return $text;
}
function panelvotar($nombre,$id,$img,$serie,$boton=true)
{
	$agregar = "";
	if($boton)
		$agregar = "
                    <div class=\"panel-footer\">
                    	<button id=\"idpersonaje".$id."\" type=\"button\" class=\"btn btn-lg btn-block btn-default\" onclick=\"votacion.votoactivar(".$id.")\">Votar</button>
                    </div>";
	$text ="			<div class=\"col-md-2\">
                <div class=\"panel panel-default\">
                    <div class=\"panel-heading\"><h3 class=\"text-center\">".$nombre."</h3></div>
                    <div class=\"panel-body text-center\">
        					<img src=\"".$img."\" ALT=\"Imagen\" width=100 class=\"img-rounded\">
                    </div>
                    <ul class=\"list-group list-group-flush text-center\">
                        <li class=\"list-group-item\"> ".$serie."</li>
                    </ul>
                   ".$agregar."
                </div>
            </div>     ";
			return $text;
}

function lugarvotacion($titulo,$contenido)
{
	$text = "
<div class=\"panel panel-default\">
	<div class=\"panel-heading\">
		<h4 class=\"panel-title\">
		".$titulo." 
		</h4>
	</div>
    <div class=\"panel-body\"> 
	".$contenido."
	</div>
</div>";	
return $text;
}

function panelvotos($titulo,$arrcontenido)
{
	$text ="<section class=\"panel\">
                          <div class=\"panel-body progress-panel\">
                            <div class=\"row\">
                              <div class=\"col-lg-8 task-progress pull-left\">
                                  <h1>".$titulo."</h1>                                  
                              </div>
                            </div>
                          </div>
						  <table class=\"table table-hover personal-task\">
                              <tbody>";
							  
	foreach($arrcontenido as $individual)
	{
		$text.="<tr>";
		$text.="<td>".$individual["pos"]."</td>";
		$text.="<td><div class=\"avatar\">
                          <img src=\"".$individual["img"]."\" width=\"50\" class=\"img-rounded\" alt=\"\"/>
                        </div></td>";
		$text.="<td>".$individual["nombre"]."</td>";
		$text.="<td>".$individual["serie"]."</td>";
		$text.="<td>".$individual["color"]."</td>";
		$text.="<td>".$individual["voto"]."</td>";
		$text.="</tr>";	
	}
	
	$text.= "
                              </tbody>
                          </table>
                      </section>";
					  
	return $text;
}

function agregaelem($titulo,$contenido)
{
	   $text = " <div class=\"form-group\">
                                      <label class=\"col-sm-2 control-label\">".$titulo."</label>
                                      <div class=\"col-sm-10\">
									  <p>".$contenido."</p>
                                      </div>
                                  </div>	";
		return $text;
}

function panelperfil($datos,$actividad)
{
	$text="
	<div class=\"row\">
	<div class=\"col-lg-12\">
		<div class=\"profile-widget profile-widget-info\">
			<div class=\"panel-body\">
				<div class=\"col-lg-2 col-sm-2\">
					<h4>".$datos["user"]."</h4>               
					<div class=\"follow-ava\">
						<img src=\"".$datos["imgavatar"]."\" width=\"120\" class=\"img-thumbnail\" alt=\"\">
					</div>
					<h6>".$datos["nivel"]."</h6>
				</div>
               	<div class=\"col-lg-2 col-sm-2 follow-info\">
                	<br>
                    <p>".$datos["sexo"]."</p>
                    <p>".$datos["edad"]."</p>
                    <p>".$datos["pais"]."</p>
                    <p>".$datos["linkperfil"]."</p>
               	</div>
                <div class=\"col-lg-3 col-sm-8\">
					<img src=\"".$datos["banner"]."\" height=\"200\" class=\"img-rounded\" alt=\"\">
				</div>
			</div>
		</div>
	</div>
</div>
<div class=\"row\">
	<div class=\"col-lg-12\">
		<section class=\"panel panel-default\">
			<div class=\"panel-heading\">
              	Actividad
            </div>
        	<div class=\"panel-body\">
			";
			
			for($i=0;$i<count($actividad);$i++)
			{
				if($i!=0)
					$text .= "<hr>";	
				
				$text .= "<div>
					<p>".$actividad[$i]["datos"]."</p>
					<p>";
					for($j=0;$j<count($actividad[$i]["imagenes"]);$j++)
						$text .= "<img class=\"avatar img-rounded\" height=\"80\" src=\"".$actividad[$i]["imagenes"][$j]."\" alt=\"\">";
				$text .= "</p>
           			<p><a href=\"".$actividad[$i]["link"]."\" class=\"btn btn-default btn-block\">Ver votacion</a></p>
				</div>";
			}
				
  			$text .= "</div>
      	</section>
   	</div>
</div>
            ";
			return $text;                    	
}

function tablaseguimiento($segpersonajes,$agrega=false,$listapersonaje="")
{
	$text="";
	
	$text .="
	<section class=\"panel\">
                          <header class=\"panel-heading\">
                              Seguimiento
                          </header>
                          
                          <table class=\"table table-striped table-advance table-hover\">
                           <tbody>
                              <tr>
                                 <th>Nombre</th>
                                 <th>Serie</th>
                                 <th>Preliminar</th>
                                 <th>Primera Ronda</th>
                                 <th>Segunda Ronda</th>
                                 <th>Tercera Ronda</th>
                                 <th>Final de Grupo</th>
                                 <th>Cuartos de Final</th>
                                 <th>Semifinal</th>
                                 <th>Final</th>
                                 <th></th>
                              </tr>";
							  
							  for($i=0;$i<count($segpersonajes);$i++)
							  {
								  $text .= "                              <tr>
                                 <td>".$segpersonajes[$i]["nombre"]."</td>
                                 <td>".$segpersonajes[$i]["serie"]."</td>
                                 <td>".$segpersonajes[$i][1]."</i></td>
                                 <td>".$segpersonajes[$i][2]."</i></td>
                                 <td>".$segpersonajes[$i][3]."</i></td>
                                 <td>".$segpersonajes[$i][4]."</td>
                                 <td>".$segpersonajes[$i][5]."</td>
                                 <td>".$segpersonajes[$i][6]."</td>
                                 <td>".$segpersonajes[$i][7]."</td>
                                 <td>".$segpersonajes[$i][8]."</td>
                                 <td><div class=\"btn-group\"> <a class=\"btn btn-danger\" href=\"seguimiento.php?action=1&idperonaje=".$segpersonajes[$i]["idpersonaje"]."\"><i class=\"icon_close_alt2\"></i></a></div></td>
                              </tr>";
								
								}
		if($agrega)
		{
			$text .="                              
							<tr>
							<form role=\"form\" action=\"seguimiento.php?action=2\" method=\"post\">
                                 <td>                                          
                                 		<select class=\"form-control m-bot15\" name=\"nuevoseguimiento\">";
										for($i=0;$i<count($listapersonaje);$i++)
											$text .="<option value=\"".$listapersonaje[$i]["id"]."\">".$listapersonaje[$i]["nombre"]."</option>";
                                      	$text .=" </select>
                                 </td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td>
								  <button class=\"btn btn-default btn-lg btn-block\" type=\"submit\"><i class=\"icon_plus_alt2\"></i></button>
                                 </td>
								 </form>
                              </tr>";
		
		
		
		}
	$text .=" 
                                                
                           </tbody>
                        </table>
                      </section>
	";
	
	return $text;	
}

function tablalinda($nombre,$arreglo)
{
	$text="";
	
	$text .="
	<section class=\"panel\">
                          <header class=\"panel-heading\">
                              ".$nombre."
                          </header>
                          
                          <table class=\"table table-striped table-advance table-hover\">
                           <tbody>";
						   $i=0;
	foreach($arreglo as $fila)
	{
		$text.="<tr>";
		$con ="td";
		if($i==0)
			$con ="th";
		foreach($fila as $columna)
			$text.="<".$con.">".$columna."</".$con.">";				
		$text.="</tr>";	
		$i++;	   
	}
     $text.="</tbody>
	 </table>
	  </section>";
	  return $text;
}
?>