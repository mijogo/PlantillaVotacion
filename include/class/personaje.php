<?php
require_once "personajeBD.php";
class personaje extends personajeBD
{
	function personaje($db,$id="",$nombre="",$serie="",$imagen="",$idserie="",$nparticipaciones="",$mejorpos="" )
	{
		$this->id = $id;
		$this->nombre = $nombre;
		$this->serie = $serie;
		$this->imagen = $imagen;
		$this->idserie = $idserie;
		$this->nparticipaciones = $nparticipaciones;
		$this->mejorpos = $mejorpos;
		$this->con = $db;
	}
	function setid($id)
	{
		$this->id=$id;
	}
	function getid()
	{
		return $this->id;
	}
	function setnombre($nombre)
	{
		$this->nombre=$nombre;
	}
	function getnombre()
	{
		return $this->nombre;
	}
	function setserie($serie)
	{
		$this->serie=$serie;
	}
	function getserie()
	{
		return $this->serie;
	}
	function setimagen($imagen)
	{
		$this->imagen=$imagen;
	}
	function getimagen()
	{
		return $this->imagen;
	}
	function setidserie($idserie)
	{
		$this->idserie=$idserie;
	}
	function getidserie()
	{
		return $this->idserie;
	}
	function setnparticipaciones($nparticipaciones)
	{
		$this->nparticipaciones=$nparticipaciones;
	}
	function getnparticipaciones()
	{
		return $this->nparticipaciones;
	}
	function setmejorpos($mejorpos)
	{
		$this->mejorpos=$mejorpos;
	}
	function getmejorpos()
	{
		return $this->mejorpos;
	}
}?>

