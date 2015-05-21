<?php
require_once "coloresBD.php";
class colores extends coloresBD
{
	function colores($db,$idpersonaje="",$idbatalla="",$color="" )
	{
		$this->idpersonaje = $idpersonaje;
		$this->idbatalla = $idbatalla;
		$this->color = $color;
		$this->con = $db;
	}
	function setidpersonaje($idpersonaje)
	{
		$this->idpersonaje=$idpersonaje;
	}
	function getidpersonaje()
	{
		return $this->idpersonaje;
	}
	function setidbatalla($idbatalla)
	{
		$this->idbatalla=$idbatalla;
	}
	function getidbatalla()
	{
		return $this->idbatalla;
	}
	function setcolor($color)
	{
		$this->color=$color;
	}
	function getcolor()
	{
		return $this->color;
	}
}?>

