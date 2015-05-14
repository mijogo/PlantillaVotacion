<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("moduser",false,-1);
	if(!$ClaseMaestra->VerificacionIdentidad(3))
		Redireccionar("home.php");
	$file = fopen("moduser.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$BG = new DataBase();
	$BG->connect();
	$info = $ClaseMaestra->user;
	
	$text ="";
	$text.=agregaelem("Username",$info->getusername());
	$text.=agregaelem("E-mail",inputalone("mailmod",$info->getemail()));
	$text.=agregaelem("Contraseña",inputalone("pass","","password"));
	$text.=agregaelem("Repita la contraseña",inputalone("repass","","password"));
	$text.=agregaelem("Avatar",inputimagealone("avatarmod","suba la imagen que sera su avatar"));
	$text.=agregaelem("Edad",inputalone("edadmod",$info->getedad()));	
	$valores = array("","mas","fem");
	$opciones = array("","Masculino","Femenimo");
	$text.=agregaelem("Sexo",inputselectedalone("sexomod",$valores,$opciones,$info->getsexo()));
	
	
	$text.=agregaelem("Pais",inputselectedalone("paismod",$ClaseMaestra->paisesabr,$ClaseMaestra->paisesnom,$info->getpais()));
	
	$text.=agregaelem("",inputalone("isuser",$info->getusername(),"hidden"));


	$pagina = ingcualpag($pagina,"info",$text);
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$alluser = new usuario($BG->con);
	$alluser=$alluser->read();
	
	$cambiarUsuario = new usuario($BG->con);
	$cambiarUsuario->setusername($_POST["isuser"]);
	$cambiarUsuario = $cambiarUsuario->read(false,1,array("username"));
	if($_POST["pass"] == $_POST["repass"] && $_POST["pass"]!="")
	{
		$cambiarUsuario->setpassword(crypt($_POST["pass"],'$6$rounds=5000$DFgGfDd43$'));
	}
	if(!comprobararray($alluser,"email",$_POST["mailmod"]))
		$cambiarUsuario->setemail($_POST["mailmod"]);
	
	$archivo = uploadimage($_FILES["avatarmod"],"avatar");
	if($archivo[0])
	{
		$cambiarUsuario->setimagen($archivo[1]);
	}
	$cambiarUsuario->setedad($_POST["edadmod"]);
	$cambiarUsuario->setsexo($_POST["sexomod"]);
	$cambiarUsuario->setpais($_POST["paismod"]);
	
	$cambiarUsuario->update(6,array("password","email","imagen","edad","sexo","pais"),1,array("id"));
	$BG->close();
	Redireccionar("datouser.php");

}