<?php
require_once "menuBD.php";
class menu extends menuBD
{
	function menu($db,$id="",$dependencia="",$titulo="",$namepage="",$url="",$descripcion="" )
	{
		$this->id = $id;
		$this->dependencia = $dependencia;
		$this->titulo = $titulo;
		$this->namepage = $namepage;
		$this->url = $url;
		$this->descripcion = $descripcion;
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
	function setdependencia($dependencia)
	{
		$this->dependencia=$dependencia;
	}
	function getdependencia()
	{
		return $this->dependencia;
	}
	function settitulo($titulo)
	{
		$this->titulo=$titulo;
	}
	function gettitulo()
	{
		return $this->titulo;
	}
	function setnamepage($namepage)
	{
		$this->namepage=$namepage;
	}
	function getnamepage()
	{
		return $this->namepage;
	}
	function seturl($url)
	{
		$this->url=$url;
	}
	function geturl()
	{
		return $this->url;
	}
	function setdescripcion($descripcion)
	{
		$this->descripcion=$descripcion;
	}
	function getdescripcion()
	{
		return $this->descripcion;
	}
}?>

