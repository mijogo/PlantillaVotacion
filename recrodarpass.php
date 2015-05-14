<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("recrodarpass",false,-1);
	if(!$ClaseMaestra->VerificacionIdentidad(1))
		Redireccionar("home.php");
	$file = fopen("recrodarpass.html", "r") or exit("Unable to open file!");
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
	$recusuario = new usuario($BG->con);
	$recusuario->setemail($_POST["email"]);
	$recusuario=$recusuario->read(true,1,array("email"));
	if(count($recusuario)>0)
	{
		$pass = crypt(fechaHoraActual());
		$recusuario[0]->setpassword(crypt($pass,'$6$rounds=5000$DFgGfDd43$'));	
		$recusuario[0]->update(1,array("email"),1,array("id"));
		$mensaje = "Usuario ".$recusuario[0]->getusername()." su nueva contraseña es\"".$pass."\"";
		mail($recusuario->getemail(), 'Nueva contraseña', $mensaje);
		Redireccionar("recrodarpass.php?msg=Se le envio una nueva contraseña a su correo");
	}
	else
	{
		Redireccionar("recrodarpass.php?msg=No hay ninguna cuenta que tenga relacion con este correo");
		
	}
	$BG->close();	
}