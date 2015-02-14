<?php
require_once "votoBD.php";
class voto extends votoBD
{
	function voto($fecha="",$idbatalla="",$idpersonaje="",$uniquecode="",$codepass="")
	{
		$this->fecha = $fecha;
		$this->idbatalla = $idbatalla;
		$this->idpersonaje = $idpersonaje;
		$this->uniquecode = $uniquecode;
		$this->codepass = $codepass;
	}
	function setfecha($fecha)
	{
		$this->fecha=$fecha;
	}
	function getfecha()
	{
		return $this->fecha;
	}
	function setidbatalla($idbatalla)
	{
		$this->idbatalla=$idbatalla;
	}
	function getidbatalla()
	{
		return $this->idbatalla;
	}
	function setidpersonaje($idpersonaje)
	{
		$this->idpersonaje=$idpersonaje;
	}
	function getidpersonaje()
	{
		return $this->idpersonaje;
	}
	function setuniquecode($uniquecode)
	{
		$this->uniquecode=$uniquecode;
	}
	function getuniquecode()
	{
		return $this->uniquecode;
	}
	function setcodepass($codepass)
	{
		$this->codepass=$codepass;
	}
	function getcodepass()
	{
		return $this->codepass;
	}
}
?>