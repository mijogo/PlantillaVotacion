<?php
require_once "participacionBD.php";
class participacion extends participacionBD
{
	function participacion($db,$idpersonaje="",$idbatalla="" )
	{
		$this->idpersonaje = $idpersonaje;
		$this->idbatalla = $idbatalla;
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
}?>

