<?php
require_once "peleaBD.php";
class pelea extends peleaBD
{
	function pelea($db,$idpersonaje="",$idbatalla="",$votos="",$posicion="",$clasifico="" )
	{
		$this->idpersonaje = $idpersonaje;
		$this->idbatalla = $idbatalla;
		$this->votos = $votos;
		$this->posicion = $posicion;
		$this->clasifico = $clasifico;
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
	function setvotos($votos)
	{
		$this->votos=$votos;
	}
	function getvotos()
	{
		return $this->votos;
	}
	function setposicion($posicion)
	{
		$this->posicion=$posicion;
	}
	function getposicion()
	{
		return $this->posicion;
	}
	function setclasifico($clasifico)
	{
		$this->clasifico=$clasifico;
	}
	function getclasifico()
	{
		return $this->clasifico;
	}
}?>

