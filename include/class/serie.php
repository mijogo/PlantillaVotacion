<?php
require_once "serieBD.php";
class serie extends serieBD
{
	function serie($db,$id="",$nombre="",$imagen="",$nombrecorto="" )
	{
		$this->id = $id;
		$this->nombre = $nombre;
		$this->imagen = $imagen;
		$this->nombrecorto = $nombrecorto;
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
	function setimagen($imagen)
	{
		$this->imagen=$imagen;
	}
	function getimagen()
	{
		return $this->imagen;
	}
	function setnombrecorto($nombrecorto)
	{
		$this->nombrecorto=$nombrecorto;
	}
	function getnombrecorto()
	{
		return $this->nombrecorto;
	}
}?>

