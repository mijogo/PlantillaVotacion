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
else
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
			if(empty($_POST["recordar"]))
				setcookie("id_user",$VerificarUsuario->getid(),time()+(120*60*60*24));
			else
				setcookie("id_user",$VerificarUsuario->getid(),time()+(60*60));
			$BG->close();
			Redireccionar("home.php");
		}
		else
		{
			$BG->close();
			echo "mal pass<br>";
			echo $VerificarUsuario->getpassword()."<br>";
			echo crypt($_POST["pass"],'$6$rounds=5000$DFgGfDd43$')."<br>";
			//Redireccionar("login.php");
			
		}
	}
	else
	{
		$BG->close();
		echo "mal user";
		//Redireccionar("login.php");
		
	}
}
?>