<?php
require_once "serieBD.php";
class serie extends serieBD
{
	function serie($id="",$nombre="",$imagen="")
	{
		$this->id = $id;
		$this->nombre = $nombre;
		$this->imagen = $imagen;
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
	function setimagen($imagen)
	{
		$this->imagen=$imagen;
	}
	function getimagen()
	{
		return $this->imagen;
	}
}?>