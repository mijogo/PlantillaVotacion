<?php
require_once "seguimientoBD.php";
class seguimiento extends seguimientoBD
{
	function seguimiento($db,$iduser="",$idtorneo="",$idpersonaje="" )
	{
		$this->iduser = $iduser;
		$this->idtorneo = $idtorneo;
		$this->idpersonaje = $idpersonaje;
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
	function setidtorneo($idtorneo)
	{
		$this->idtorneo=$idtorneo;
	}
	function getidtorneo()
	{
		return $this->idtorneo;
	}
	function setidpersonaje($idpersonaje)
	{
		$this->idpersonaje=$idpersonaje;
	}
	function getidpersonaje()
	{
		return $this->idpersonaje;
	}
}?>

