<?php
require_once "include.php";
class MasterClass
{
	function MasterClass($NamePage,$Esta_Menu=true,$nivel=-1)
	{
		$this->paisesabr=array("","AF","AL","DE","AD","AO","AI","AQ","AG","AN","SA","DZ","AR","AM","AW","AU","AT","AZ","BS","BH","BD","BB","BE","BZ","BJ","BM","BY","MM","BO","BA","BW","BR","BN","BG","BF","BI","BT","CV","KH","CM","CA","TD","CL","CN","CY","VA","CO","KM","CG","CD","KR","KP","CI","CR","HR","CU","DK","DJ","DM","EC","EG","SV","AE","ER","SI","ES","US","EE","ET","FJ","PH","FI","FR","GA","GM","GE","GH","GI","GD","GR","GL","GP","GU","GT","GY","GF","GN","GQ","GW","HT","HN","HU","IN","ID","IQ","IR","IE","BV","CX","IS","KY","CK","CC","FO","HM","FK","MP","MH","UM","PW","SB","SJ","TK","TC","VI","VG","WF","IL","IT","JM","JP","JO","KZ","KE","KG","KI","KW","LA","LS","LV","LB","LR","LY","LI","LT","LU","MK","MG","MY","MW","MV","ML","MT","MA","MQ","MU","MR","YT","MX","FM","MD","MC","MN","MS","MZ","NA","NR","NP","NI","NE","NG","NU","NF","NO","NC","NZ","OM","NL","PA","PG","PK","PY","PE","PN","PF","PL","PT","PR","QA","UK","CF","CZ","ZA","DO","SK","RE","RW","RO","RU","EH","KN","WS","AS","SM","VC","SH","LC","ST","SN","SC","SL","SG","SY","SO","LK","PM","SZ","SD","SE","CH","SR","TH","TW","TZ","TJ","TF","TP","TG","TO","TT","TN","TM","TR","TV","UA","UG","UY","UZ","VU","VE","VN","YE","YU","ZM","ZW");
		$this->paisesnom=array("","Afganistán","Albania","Alemania","Andorra","Angola","Anguilla","Antártida","Antigua y Barbuda","Antillas Holandesas","Arabia Saudí","Argelia","Argentina","Armenia","Aruba","Australia","Austria","Azerbaiyán","Bahamas","Bahrein","Bangladesh","Barbados","Bélgica","Belice","Benin","Bermudas","Bielorrusia","Birmania","Bolivia","Bosnia y Herzegovina","Botswana","Brasil","Brunei","Bulgaria","Burkina Faso","Burundi","Bután","Cabo Verde","Camboya","Camerún","Canadá","Chad","Chile","China","Chipre","Ciudad del Vaticano (Santa Sede)","Colombia","Comores","Congo","Congo, República Democrática del","Corea","Corea del Norte","Costa de Marfíl","Costa Rica","Croacia (Hrvatska)","Cuba","Dinamarca","Djibouti","Dominica","Ecuador","Egipto","El Salvador","Emiratos Ãrabes Unidos","Eritrea","Eslovenia","España","Estados Unidos","Estonia","Etiopía","Fiji","Filipinas","Finlandia","Francia","Gabón","Gambia","Georgia","Ghana","Gibraltar","Granada","Grecia","Groenlandia","Guadalupe","Guam","Guatemala","Guayana","Guayana Francesa","Guinea","Guinea Ecuatorial","Guinea-Bissau","Haití","Honduras","Hungría","India","Indonesia","Irak","Irán","Irlanda","Isla Bouvet","Isla de Christmas","Islandia","Islas Caimán","Islas Cook","Islas de Cocos o Keeling","Islas Faroe","Islas Heard y McDonald","Islas Malvinas","Islas Marianas del Norte","Islas Marshall","Islas menores de Estados Unidos","Islas Palau","Islas Salomón","Islas Svalbard y Jan Mayen","Islas Tokelau","Islas Turks y Caicos","Islas Vírgenes (EEUU)","Islas Vírgenes (Reino Unido)","Islas Wallis y Futuna","Israel","Italia","Jamaica","Japón","Jordania","Kazajistán","Kenia","Kirguizistán","Kiribati","Kuwait","Laos","Lesotho","Letonia","Líbano","Liberia","Libia","Liechtenstein","Lituania","Luxemburgo","Macedonia, Ex-República Yugoslava de","Madagascar","Malasia","Malawi","Maldivas","Malí","Malta","Marruecos","Martinica","Mauricio","Mauritania","Mayotte","México","Micronesia","Moldavia","Mónaco","Mongolia","Montserrat","Mozambique","Namibia","Nauru","Nepal","Nicaragua","Níger","Nigeria","Niue","Norfolk","Noruega","Nueva Caledonia","Nueva Zelanda","Omán","Países Bajos","Panamá","Papúa Nueva Guinea","Paquistán","Paraguay","Perú","Pitcairn","Polinesia Francesa","Polonia","Portugal","Puerto Rico","Qatar","Reino Unido","República Centroafricana","República Checa","República de Sudáfrica","República Dominicana","República Eslovaca","Reunión","Ruanda","Rumania","Rusia","Sahara Occidental","Saint Kitts y Nevis","Samoa","Samoa Americana","San Marino","San Vicente y Granadinas","Santa Helena","Santa Lucía","Santo Tomé y Príncipe","Senegal","Seychelles","Sierra Leona","Singapur","Siria","Somalia","Sri Lanka","St Pierre y Miquelon","Suazilandia","Sudán","Suecia","Suiza","Surinam","Tailandia","Taiwán","Tanzania","Tayikistán","Territorios franceses del Sur","Timor Oriental","Togo","Tonga","Trinidad y Tobago","Túnez","Turkmenistán","Turquía","Tuvalu","Ucrania","Uganda","Uruguay","Uzbekistán","Vanuatu","Venezuela","Vietnam","Yemen","Yugoslavia","Zambia","Zimbabue");
		
		$this->user="";
		$BG = new DataBase();
		$BG->connect();
		$a_menu = new menu($BG->con);
		$a_menu->setnamepage($NamePage);
		$a_menu = $a_menu->read(true,1,array("namepage"));
		$this->ipcontext="";

		if(count($a_menu) == 0 && $Esta_Menu)
			Redireccionar("home.php");
		elseif($Esta_Menu)
		{
			$this->id_pagina = $a_menu[0]->getid();
			$b_menu = new menu($BG->con);
			$b_menu->setid($a_menu[0]->getdependencia());
			$b_menu = $b_menu->read(true,1,array("id"));
			if(count($b_menu) == 0)
				$this->nivel=$a_menu[0]->getdependencia();
			else
			$this->nivel=$b_menu[0]->getdependencia();
		}
		else
		{
			$this->nivel=$nivel;
			$this->id_pagina=-1;
		}
		$BG->close();
		$this->useradmin ="";
	}
	// id numero pagina, tipo a que pagina se refiere, action, tipo de accion
	
	function VerificacionIdentidad($nivel)
	{
		$this->BG = new DataBase();
		$this->BG->connect();
		if($this->NivelUsuario() >= $nivel)
		{
			if($this->torneoActual())
				$this->usuarioACC();
			$this->BG->close();
			return true;
		}
		else
		{
			$this->BG->close();
			return false;
		}
	}
	
	function devip()
	{
		if(isset($_COOKIE['uniqueCode']))
		{
			$this->BG = new DataBase();
			$this->BG->connect();
			
			$ipactual = new ip($this->BG->con);
			$ipactual->setuniquecode($_COOKIE['uniqueCode']);
			$ipactual = $ipactual->read(false,1,array("uniquecode"));
			$this->ipcontext = $ipactual;
			$this->BG->close();
		}
	}
	
	function Pagina($script,$pagina)
	{
		$this->BG = new DataBase();
		$this->BG->connect();
		$file = fopen("include/estructura.html", "r") or exit("Unable to open file!");
		$masterpagina="";
		while(!feof($file))
		{
			$masterpagina .= fgets($file);
		}
		//$logicaU = new logicv();
		//$datos = $logicaU->logicaView($this->id_pagina,$this->tipo);
		$msg="";
		if(isset($_GET["msg"]))
		{
			$msg = "<div class=\"alert alert-info\">".$_GET["msg"]."</div>";
		}
		echo ingPagina($masterpagina,$this->menu_u(),$script,$pagina,widget(),$msg);
		$this->BG->close();
	}
	
	function menu_u()
	{
		$b_menu = new menu($this->BG->con);
		$b_menu->setdependencia($this->nivel);
		$b_menu = $b_menu->read(true,1,array("dependencia"));
		$k=0;
		$datos = array("");
		for($i=0;$i<count($b_menu);$i++)
		{
			$datos[$k][0] = $b_menu[$i]->getdependencia();
			$datos[$k][1] = $b_menu[$i]->getid();
			$datos[$k][2] = $b_menu[$i]->gettitulo();
			if($this->id_pagina == $b_menu[$i]->getid())
				$datos[$k][3] = 1;
			else
				$datos[$k][3] = 0;
			$datos[$k][5] = $b_menu[$i]->geturl();
			$c_menu = new menu($this->BG->con);
			$c_menu->setdependencia($b_menu[$i]->getid());
			$c_menu = $c_menu->read(true,1,array("dependencia"));
			if(count($c_menu)==0)
			{
				$datos[$k][4] = 0;
				$k++;
			}
			else
			{
				$datos[$k][4] = 1;
				$k++;
				for($j=0;$j<count($c_menu);$j++)
				{
					$datos[$k][0] = $c_menu[$j]->getdependencia();
					$datos[$k][1] = $c_menu[$j]->getid();
					$datos[$k][2] = $c_menu[$j]->gettitulo();
					if($this->id_pagina == $c_menu[$j]->getid())
						$datos[$k][3] = 1;
					else
						$datos[$k][3] = 0;
					$datos[$k][5] = $c_menu[$j]->geturl();
					$datos[$k][4] = 0;
					$k++;
				}
			}
		}
		return menu_html($datos,$this->nivel,$this->useractivo,$this->user,$this->useradmin);
	}
	
	function torneoActual()
	{
		$torneoActual = new torneo($this->BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(true,1,array("activo"));		
		if(count($torneoActual)>0)
		{
			$this->torneoActivo=true;
			$this->torneoActual = $torneoActual[0];
			return true;
		}
		else
			$this->torneoActivo=false;
			return false;
	} 
	
	function NivelUsuario()
	{
		if(isset($_COOKIE['id_user']))
		{
			$userpage = new usuario($this->BG->con);
			$userpage->setid($_COOKIE['id_user']);
			$userpage = $userpage->read(true,1,array("id"));
			if(count($userpage)>0)
			{
				$this->user = $userpage[0];
				$this->useractivo=true;
				if($this->user->getpoder()>3)
					$this->useradmin=true;
				else
					$this->useradmin=false;
				return $this->user->getpoder();
			}
			else
			{
				$this->useractivo=false;
				return 1;
			}
		}
		else
		{
			$this->useractivo=false;
			return 1;
		}
	}
	
	function usuarioACC()
	{
		if(isset($_COOKIE['CodePassVote']))
		{
			$this->userAnterior=true;
			$this->cookies=$_COOKIE['CodePassVote'];
			setcookie("CodePassVote",$this->cookies,time()+(14*60*60*24));
		}
		else
		{
			$this->userAnterior=false;
			$this->cookies=setCookies();
		}
		
		$evetoActual = new evento($this->BG->con);
		$evetoActual->setestado(1);
		$evetoActual = $evetoActual->read(true,1,array("estado"));
		
		if(count($evetoActual)>0)
		{
			$ipcreada = false;
			$this->evetoActual = $evetoActual[0];
			if(isset($_COOKIE['uniqueCode']))
			{
				$estaIp = new ip($this->BG->con);
				$estaIp->setuniquecode($_COOKIE['uniqueCode']);
				$estaIp = $estaIp->read(false,1,array("uniquecode"));
				if($estaIp->getidevento()==$this->evetoActual->getid())
				{
					$ipcreada=true;
					if($this->useractivo && $estaIp->gettiempo()>0)
					{
						$estaIp->settiempo(0);
						$estaIp->setuser($this->user->getidusuario());
						$estaIp->update(1,array("tiempo","user"),1,array("uniquecode"));
						
					}
				}
			}
			if(!$ipcreada)
			{
				$this->newUniqueCode = $this->cookies."-".$this->evetoActual->getid();
				$this->ip = getRealIP();
				$this->crearIp();
			}
			//analizar datos anteriores
			$ipUsadas=new ip($this->BG->con);
			$ipUsadas->setcodepass($this->cookies);
			$ipUsadas->setip($this->ip);
			$ipUsadas->setidevento($this->evetoActual->getid());
			if($this->useractivo)
			{
				$ipUsadas->setuser($this->user->getid());
				$ipUsadas = $ipUsadas->read(true,0,"",1,array("fecha","ASC")," idevento = ".$ipUsadas->getidevento()." AND (codepass = '".$ipUsadas->getcodepass()."' OR ip = '".$ipUsadas->getip()."' OR user = ".$ipUsadas->getuser().") ");
			}
			else
				$ipUsadas = $ipUsadas->read(true,0,"",1,array("fecha","ASC")," idevento = ".$ipUsadas->getidevento()." AND (codepass = '".$ipUsadas->getcodepass()."' OR ip = '".$ipUsadas->getip()."') ");
			$mastercode="";
			$masterip="";
			$usado=0;
			for($i=0;$i<count($ipUsadas);$i++)
			{
				if($i==0)
				{
					$mastercode = $ipUsadas[$i]->getmastercode();
					$masterip = $ipUsadas[$i]->getmasterip();			
				}
				if($ipUsadas[$i]->getusada()>0)
					 $usado=1;
			}
			for($i=0;$i<count($ipUsadas);$i++)
			{
				if($ipUsadas[$i]->getusada()==0 && ($ipUsadas[$i]->getmastercode() !=  $mastercode||$ipUsadas[$i]->getmasterip() !=  $masterip))
				{
					$ipUsadas[$i]->setmastercode($mastercode);
					$ipUsadas[$i]->setmasterip($masterip);
					if($usado==0)
						$ipUsadas[$i]->update(2,array("mastercode","masterip"),1,array("uniquecode"));
					else
					{
						$ipUsadas[$i]->setusada(3);
						$ipUsadas[$i]->update(3,array("mastercode","masterip","usada"),1,array("uniquecode"));
					}
				}
			}
		}
	}
	
	function crearIp($MasterCode="",$MasterIp="",$tipoUsada="")
	{
		$creaIp = new ip($this->BG->con);
		$creaIp->setfecha(fechaHoraActual());
		$creaIp->setip($this->ip);
		if($this->userAnterior)
			$creaIp->setTiempo(30);
		else
		{
			$buscIP = new ip($this->BG->con);
			$buscIP->setip($this->ip);
			$buscIP->setcodepass($this->cookies);
			$buscIP->setusada(1);
			$buscIP = $buscIP->read(true,3,array("ip","AND","codepass","AND","usada"));
			if(count($buscIP)>0)
				$creaIp->setTiempo(0);
			else
			{
				$buscIP = new ip($this->BG->con);
				$buscIP->setcodepass($this->cookies);
				$buscIP->setusada(1);
				$buscIP = $buscIP->read(true,2,array("codepass","AND","usada"));
				$valor = count($buscIP);
				if($valor == 0)
					$creaIp->settiempo(25);
				else if($valor>15)
					$creaIp->settiempo(rand(5,20));
				else
					$creaIp->settiempo(rand(20-$valor,20));
			}
		}
		if($tipoUsada=="")
			$creaIp->setUsada(0);
		else
			$creaIp->setUsada($tipoUsada);

		$creaIp->setCodePass($this->cookies);	
			
		if($MasterCode=="")
			$creaIp->setMasterCode($this->cookies);
		else
			$creaIp->setMasterCode($MasterCode);
						
		if($MasterIp=="")
			$creaIp->setMasterIP($this->ip);
		else
			$creaIp->setMasterIP($MasterIp);
		
		$ipBan = new ip($this->BG->con);
		$ipBan->setusada(8);
		$ipBan = $ipBan->read(true,1,array("usada"));
		for($i=0;$i<count($ipBan);$i++)
		{
			if($ipBan[$i]->getCodePass()==$creaIp->getCodePass()||$ipBan[$i]->getIp()==$creaIp->getIp())
			{
				$creaIp->setTiempo(720);
			}
		}
		$extraInfo = $_SERVER['HTTP_USER_AGENT'];
		$creaIp->setinfo($extraInfo);
		if($this->useractivo)
			$creaIp->setuser($this->user->getid());
		else
			$creaIp->setuser(-1);
		$creaIp->setuniquecode($this->newUniqueCode);
		$creaIp->save();
		setcookie("uniqueCode",$this->newUniqueCode,time()+(2*60*60*24));
		return $creaIp;
	}
}
?>