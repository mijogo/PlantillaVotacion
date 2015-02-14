<?php
require_once "peleaBD.php";
class pelea extends peleaBD
{
	function pelea($idpersonaje="",$idbatalla="",$votos="")
	{
		$this->idpersonaje = $idpersonaje;
		$this->idbatalla = $idbatalla;
		$this->votos = $votos;
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
}?>

