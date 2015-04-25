<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("web");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("web.html", "r") or exit("Unable to open file!");
	$pagina="";
	while(!feof($file))
	{
		$pagina .= fgets($file);
	}
	$pagina = ingcualpag($pagina,"tabla_objetos_1",tablaobjetos("Menu",arreglomenu()));
	
	$BG = new DataBase();
	$BG->connect();
	
	$menusel = new menu($BG->con);
	$menusel = $menusel->read();
	
	$pagina = ingcualpag($pagina,"input_1",inputform("titulo","Titulo del Menu"));
	$pagina = ingcualpag($pagina,"input_2",inputform("namepage","Namepage"));
	
	$valoresR[] = "R";
	$opcionesR[] = "Primario";
	$menor=0;
	for($i=0;$i<count($menusel);$i++)
	{
		if($menusel[$i]->getdependencia()<0)
		{
			$valoresR[] = $menusel[$i]->getid();
			$opcionesR[] =$menusel[$i]->gettitulo();
			if($menor>$menusel[$i]->getdependencia())
				$menor=$menusel[$i]->getdependencia();
		}
	}
	for($i=-1;$i>=$menor;$i--)
	{
			$primero=0;
			for($j=0;$j<count($menusel);$j++)
			{
				if($menusel[$j]->getdependencia()==$i&$primero==0)
				{
					$dependencia = $menusel[$j]->gettitulo();
					$primero=1;
				}	
			}
			$valoresR[] =$i;
			$opcionesR[] =$dependencia."(Primario)";
	}
	
	$pagina = ingcualpag($pagina,"input_3",inputselected("dependencia","Dependencia",$valoresR,$opcionesR));
	$pagina = ingcualpag($pagina,"input_4",inputform("url","Url"));
	
	$ClaseMaestra->Pagina("",$pagina);
}
else
{
	$BG = new DataBase();
	$BG->connect();
	
	$nuevomenu = new menu($BG->con);
	$nuevomenu->settitulo($_POST["titulo"]);
	$nuevomenu->setnamepage($_POST["namepage"]);
	$nuevomenu->seturl($_POST["url"]);
	if($_POST["dependencia"]=="R")
	{
		$menor=0;
		for($i=0;$i<count($menusel);$i++)
			if($menusel[$i]->getdependencia()<0)
				if($menor>$menusel[$i]->getdependencia())
					$menor=$menusel[$i]->getdependencia();
		$nuevomenu->setdependencia($menor-1);
	}
	else
		$nuevomenu->setdependencia($_POST["dependencia"]);
	$nuevomenu->save();
	$BG->close();
	Redireccionar("web.php");
	
}

function arreglomenu()
{
	$BG = new DataBase();
	$BG->connect();
	
	$menusel = new menu($BG->con);
	$menusel = $menusel->read();
	

	$objetos[] = array("Titulo","Dependencia","Namepage","Url","","");
		
	for($i=0;$i<count($menusel);$i++)
	{
		if($menusel[$i]->getdependencia()>-1)
			$dependencia = arrayobjeto($menusel,"id",$menusel[$i]->getdependencia())->gettitulo();
		else
		{
			$primero=0;
			for($j=0;$j<count($menusel);$j++)
			{
				if($menusel[$j]->getdependencia()==$menusel[$i]->getdependencia()&$primero==0)
				{
					$dependencia = $menusel[$j]->gettitulo();
					$primero=1;
				}	
			}
		}
		$objetos[] = array($menusel[$i]->gettitulo(),$dependencia,$menusel[$i]->getnamepage(),$menusel[$i]->geturl(),"<a href=\"modificarmenu.php?idmenu=".$menusel[$i]->getid()."\">Modificar</a>","<a href=\"web.php?action=1&idmenu=".$menusel[$i]->getid()."\">Eliminar</a>");
			
	}	
	$BG->close();
	return $objetos;
}