<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("login",false);
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("login.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$ClaseMaestra->Pagina("",$pagina);
}

if($_GET['action']==3)
{
	$mail = $_GET["email"];
	$token = $_GET["token"];
	$BG = new DataBase();
	$BG->connect();
	$NuevoUsuario = new Usuario($BG->con);	
	$NuevoUsuario->setemail($mail);
	$NuevoUsuario->setverificacion($token);
	$NuevoUsuario->setpoder(2);
	$NuevoUsuario = $NuevoUsuario->read(true,3,array("email","AND","verificacion","AND","poder"));
	if(count($NuevoUsuario)>0)
	{
		if(FechaMayor(fechaHoraActual(),cambioFechaseg($NuevoUsuario[0]->getfecharegistro(),10))==1)
		{
			$NuevoUsuario[0]->setpoder(3);
			$NuevoUsuario[0]->update(1,array("poder"),1,array("id"));
			//setcookie("id_user",$NuevoUsuario[0]->getid(),time()+(60*60));
			CrearCookie("id_user",$NuevoUsuario[0]->getid(),3);
			
			Redireccionar("home.php?msg=Su cuenta ya se ha activado");
		}
		else
			Redireccionar("home.php?msg=Intentelo de nuevo");
	}
	else
	{
		Redireccionar("home.php?msg=La activación a sido erronea, contactese con el administrador");
	}
}

if($_GET['action']==2)
{
	$BG = new DataBase();
	$BG->connect();
	$cont = 0;
	if($_POST["username"]=="")
	{
		Redireccionar("login.php?msg=Ingrese un usuario");
		
		$cont=1;
	}
	elseif($_POST["password"]!=$_POST["reppassword"])
	{
		Redireccionar("login.php?msg=Las contraseña deben ser iguales");
		$cont=1;			
	}
	elseif($_POST["email"]=="")
	{
		Redireccionar("login.php?msg=Ingrese una dirección de correo electronico");
		$cont=1;			
	}
	elseif($cont==0)
	{
		$email = $_POST["email"];
		$estr = explode("@",$email);
		if(count($estr)==2)	
		{
			$estr2 = explode(".",$estr[1]);
			
			if(count($estr2)!=2)
			{
				Redireccionar("login.php?msg=El correo electronico no es valido");
				$cont=1;
			}
		}
		else
		{
			Redireccionar("login.php?msg=El correo electronico no es valido");
			$cont=1;	
		}
	}
	
	if($cont==0)
	{
		
		$NuevoUsuario = new usuario($BG->con);	
		$NuevoUsuario->setusername($_POST["username"]);
		$NuevoUsuario = $NuevoUsuario->read(true,1,array("username"));
		if(count($NuevoUsuario)>0)
			Redireccionar("login.php?msg=El usuario ya existe");
		else
		{
			$NuevoUsuario = new Usuario($BG->con);	
			$NuevoUsuario->setemail($_POST["email"]);
			$NuevoUsuario = $NuevoUsuario->read(true,1,array("email"));
			if(count($NuevoUsuario)>0)
				Redireccionar("login.php?msg=Ese e-mail ya ha sido usado");
			else
			{
				$NuevoUsuario = new Usuario($BG->con);	
				$NuevoUsuario->setusername($_POST["username"]);
				$NuevoUsuario->setpassword(crypt($_POST["password"],'$6$rounds=5000$DFgGfDd43$'));
				$NuevoUsuario->setemail($_POST["email"]);
				$NuevoUsuario->setpoder(2);
				$NuevoUsuario->setfecharegistro(fechaHoraActual());
				$NuevoUsuario->setverificacion(crypt(fechaHoraActual())."fin");
				
				$link = "http://msat.moe/login.php?action=3&email=".$_POST["email"]."&token=".$NuevoUsuario->getverificacion();
				$mensaje ="Saludos, usted acaba de registrarse en MSAT, para completar esta accion, haga click en el siguiente link \n ".$link;
				mail($NuevoUsuario->getemail(), 'Activación de la cuenta en MSAT', $mensaje);
				$NuevoUsuario->save();
				$BG->close();
				Redireccionar("login.php?msg=El registro se ha realizado con exito, revise su correo para la activación");
			}
		}
	}
}
if($_GET['action']==1)
{
	$BG = new DataBase();
	$BG->connect();
	$VerificarUsuario = new Usuario($BG->con);
	$VerificarUsuario->setusername($_POST["user"]);
	$VerificarUsuario = $VerificarUsuario->read(true,1,array("username"));
	if(count($VerificarUsuario)>0)
	{
		$VerificarUsuario = $VerificarUsuario[0];
		if($VerificarUsuario->getpassword() == crypt($_POST["pass"],'$6$rounds=5000$DFgGfDd43$'))
		{
			if($VerificarUsuario->getpoder()<3)
			{
				Redireccionar("login.php?msg=La cuenta aun no ha sido activada");
			}
			else
			{/*
				ob_start();
				if(empty($_POST["recordar"]))
					setcookie("id_user",$VerificarUsuario->getid(),time()+(120*60*60*24));
				else
					setcookie("id_user",$VerificarUsuario->getid(),time()+(60*60));
				ob_end_flush();*/
				if(empty($_POST["recordar"]))
					CrearCookie("id_user",$VerificarUsuario->getid(),3);
				else
					CrearCookie("id_user",$VerificarUsuario->getid(),1);
				$BG->close();
				Redireccionar("home.php");
			}
		}
		else
		{
			$BG->close();
			Redireccionar("login.php?msg=La contraseña esta mal puesta");
			
		}
	}
	else
	{
		$BG->close();
		Redireccionar("login.php?msg=El usuario no existe");
		
	}
}
?>