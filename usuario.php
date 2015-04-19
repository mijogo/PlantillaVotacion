<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("usuario");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("usuario.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Usuarios",arreglousuario()));
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
}

function arreglousuario()
{
	$BG = new DataBase();
	$BG->connect();
	
	$torneoActual = new torneo($BG->con);
	$torneoActual->setactivo(1);
	$torneoActual = $torneoActual->read(false,1,array("activo"));	
	
	$musuario = new usuario($BG->con);
	$musuario = $musuario->read(true,0,"",1,array("fecharegistro","ASC"));

	$objetos[] = array("Username","Email","Pais","Fecha Registro","Rango","Ultimo Ingreso","IP","Info");
		
	for($i=0;$i<count($musuario);$i++)
	{
		switch($musuario[$i]->getpoder())
		{
			case 1:	$rangousuario = "Anonimo";break;
			case 2: $rangousuario = "No Activado";break;
			case 3: $rangousuario = "Activado";break;
			case 4: $rangousuario = "Administrador";break;
			case 5: $rangousuario = "Super Administrador";break;
			default : $rangousuario = "Anonimo";break;
		}
		
		$ipusuario = new ip($BG->con);
		$ipusuario->setuser($musuario[$i]->getid());
		$ipusuario = $ipusuario->read(true,1,array("user"),1,array("fecha","DESC"));
		
		if(count($ipusuario)>0)
		{
			$ulingr =$ipusuario[0]->getfecha();
			$ipingr =$ipusuario[0]->getip();
			$infngr =$ipusuario[0]->getinfo();	
		}
		else
		{
			$ulingr = "";
			$ipingr = "";
			$infngr = "";	
			
		}
		
		$objetos[] = array($musuario[$i]->getusername(),$musuario[$i]->getemail(),$musuario[$i]->getpais(),$musuario[$i]->getfecharegistro(),$rangousuario,$ulingr,$ipingr,$infngr);
			
	}	
	$BG->close();
	return $objetos;
}