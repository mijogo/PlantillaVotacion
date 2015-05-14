<?php
require_once "votouserBD.php";
class votouser extends votouserBD
{
	function votouser($db,$iduser="",$idbatalla="",$idpersonaje="",$fecha="" )
	{
		$this->iduser = $iduser;
		$this->idbatalla = $idbatalla;
		$this->idpersonaje = $idpersonaje;
		$this->fecha = $fecha;
		$this->con = $db;
	}
	function setiduser($iduser)
	{
		$this->iduser=$iduser;
	}
	function getiduser()
	{
		return $this->iduser;
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
	function setfecha($fecha)
	{
		$this->fecha=$fecha;
	}
	function getfecha()
	{
		return $this->fecha;
	}
}?>

