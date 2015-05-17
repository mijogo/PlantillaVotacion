<?php
require_once "estadisticaBD.php";
class estadistica extends estadisticaBD
{
	function estadistica($db,$idpersonaje="",$idbatalla="",$fecha="",$votos="" )
	{
		$this->idpersonaje = $idpersonaje;
		$this->idbatalla = $idbatalla;
		$this->fecha = $fecha;
		$this->votos = $votos;
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
	function setfecha($fecha)
	{
		$this->fecha=$fecha;
	}
	function getfecha()
	{
		return $this->fecha;
	}
	function setvotos($votos)
	{
		$this->votos=$votos;
	}
	function getvotos()
	{
		return $this->votos;
	}
}?>

