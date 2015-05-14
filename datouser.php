<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("datouser",false,-1);
	if(!$ClaseMaestra->VerificacionIdentidad(3))
		Redireccionar("home.php");
	$file = fopen("datouser.html", "r") or exit("Unable to open file!");
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
	$text.=agregaelem("E-mail",$info->getemail());
	$text.=agregaelem("Avatar",img($info->getimagen(),60));
	if($info->getedad() == 0)
		$text.=agregaelem("Edad","");
	else
		$text.=agregaelem("Edad",$info->getedad());
	if($info->getsexo()=="mas")
		$text.=agregaelem("Sexo","Masculino");
	elseif($info->getsexo()=="fem")
		$text.=agregaelem("Sexo","Femenino");
	else
		$text.=agregaelem("Sexo","");
	$text.=agregaelem("Pais",nuevonombre($info->getpais(),$ClaseMaestra));
	$text.=agregaelem("Fecha de registro",$info->getfecharegistro());
	if($info->getpoder()==1)
		$text.=agregaelem("Estado","Anonimo");
	elseif($info->getpoder()==2)
		$text.=agregaelem("Estado","No activado");
	elseif($info->getpoder()==3)
		$text.=agregaelem("Estado","Usuario Activado");
	elseif($info->getpoder()==4)
		$text.=agregaelem("Estado","Administrador");
	elseif($info->getpoder()==5)
		$text.=agregaelem("Estado","Super Administrador");
	else
		$text.=agregaelem("Estado","");
	$pagina = ingcualpag($pagina,"info",$text);
	$ClaseMaestra->Pagina("",$pagina);
}
function nuevonombre($nomabr,$clasmas)
{
		$arrabr=$clasmas->paisesabr;
		$arrnom=$clasmas->paisesnom;
		for($i=0;$i<count($arrabr);$i++)
		{
			if($arrabr[$i]==$nomabr)
				return $arrnom[$i];
		}
}