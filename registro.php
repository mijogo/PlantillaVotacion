<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("registro");
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("registro.html", "r") or exit("Unable to open file!");
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
	$NuevoUsuario = new Usuario($BG->con);	
	$NuevoUsuario->setusername($_POST["username"]);
	$NuevoUsuario->setpassword(crypt($_POST["password"],'$6$rounds=5000$DFgGfDd43$'));
	$NuevoUsuario->setemail($_POST["email"]);
	$NuevoUsuario->setpoder(2);
	$NuevoUsuario->setfecharegistro(fechaHoraActual());
	$NuevoUsuario->save();
	$BG->close();
	Redireccionar("home.php");
}
?>