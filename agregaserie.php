<?php
include 'include/masterclass.php';
if(!isset($_GET['action']))
			$_GET['action']=0;
if($_GET['action']==0)
{
	$ClaseMaestra = new MasterClass("agregaserie");
	if(!$ClaseMaestra->VerificacionIdentidad(4))
		Redireccionar("home.php");
	$file = fopen("agregaserie.html", "r") or exit("Unable to open file!");
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
	$nuevaserie = new serie($BG->con);
	$nuevaserie->setnombre($_POST["nombreserie"]); 
	if($_FILES['imagenserie']['tmp_name'] !="" )
	{
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$cad = "";
		for($i=0;$i<18;$i++) 
		{
			$cad .= substr($str,rand(0,62),1);
		}
		$tamano = $_FILES [ 'imagenserie' ][ 'size' ]; 
		$tamaño_max="50000000000";
		if( $tamano < $tamaño_max)
		{ 
			$destino = 'perimage' ;
			$sep=explode('image/',$_FILES["imagenserie"]["type"]);
			$tipo=$sep[1];
			if($tipo == "gif" || $tipo == "pjpeg" || $tipo == "bmp" || $tipo == "png" || $tipo == "jpg" || $tipo == "jpeg")
			{
				move_uploaded_file ( $_FILES [ 'imagenserie' ][ 'tmp_name' ], $destino . '/' .$cad.'.'.$tipo);
				$nuevaserie->setimagen($destino . '/' .$cad.'.'.$tipo);
				
			}
			else $nuevaserie->setimagen("NoTipo");
		}
		else $nuevaserie->setimagen("NoTamaño");
	}
	else $nuevaserie->setimagen("NoImagen");
	$nuevaserie->setnombrecorto($_POST["nombrecorto"]); 
	$nuevaserie->save();
	if($_POST["torneoactivar"])
	{
		$nuevaserie=$nuevaserie->read(false,1,array("nombre"));
		$nuevaseriepar = new seriepar($BG->con);
		$nuevaseriepar->setnombre($_POST["nombreseriepar"]);
		if($_POST["mismaimagen"])
			$nuevaseriepar->setimagen($nuevaserie->getimagen());
		else
		{
			if($_FILES[ 'imagenseriepar' ][ 'tmp_name' ] !="" )
			{
				$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
				$cad = "";
				for($i=0;$i<18;$i++) 
				{
					$cad .= substr($str,rand(0,62),1);
				}
				$tamano = $_FILES [ 'imagenseriepar' ][ 'size' ]; 
				$tamaño_max="50000000000";
				if( $tamano < $tamaño_max)
				{ 
					$destino = 'perimage' ;
					$sep=explode('image/',$_FILES["imagen"]["type"]);
					$tipo=$sep[1];
					if($tipo == "gif" || $tipo == "pjpeg" || $tipo == "bmp" || $tipo == "png" || $tipo == "jpg" || $tipo == "jpeg")
					{
						move_uploaded_file ( $_FILES [ 'imagenseriepar' ][ 'tmp_name' ], $destino . '/' .$cad.'.'.$tipo);
						$nuevaseriepar->setimagen($destino . '/' .$cad.'.'.$tipo);
						
					}
					else $nuevaseriepar->setimagen("NoTipo");
				}
				else $nuevaseriepar->setimagen("NoTamaño");
			}		
		}	
		$torneoActual = new torneo($BG->con);
		$torneoActual->setactivo(1);
		$torneoActual = $torneoActual->read(false,1,array("activo"));	
		
		$nuevaseriepar->setidtorneo($torneoActual->getid());
		$nuevaseriepar->setidserie($nuevaserie->getid());
		$nuevaseriepar->setano($_POST["anoserie"]);
		$nuevaseriepar->settipoformato($_POST["tipoformato"]);
		$nuevaseriepar->settcours($_POST["tcours"]);
		$nuevaseriepar->setncours($_POST["ncours"]);
		$nuevaseriepar->save();
	}
	$BG->close();
	Redireccionar("admin.php");
}
?>