<?php
require_once "participacionBD.php";
class participacion extends participacionBD
{
	function participacion($idpersonaje="",$idbatalla="")
	{
		$this->idpersonaje = $idpersonaje;
		$this->idbatalla = $idbatalla;
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

