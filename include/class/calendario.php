<?php
require_once "calendarioBD.php";
class calendario extends calendarioBD
{
	function calendario($db,$id="",$accion="",$fecha="",$hecho="",$targetstring="",$targetdate="",$targetint="" )
	{
		$this->id = $id;
		$this->accion = $accion;
		$this->fecha = $fecha;
		$this->hecho = $hecho;
		$this->targetstring = $targetstring;
		$this->targetdate = $targetdate;
		$this->targetint = $targetint;
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
	function setaccion($accion)
	{
		$this->accion=$accion;
	}
	function getaccion()
	{
		return $this->accion;
	}
	function setfecha($fecha)
	{
		$this->fecha=$fecha;
	}
	function getfecha()
	{
		return $this->fecha;
	}
	function sethecho($hecho)
	{
		$this->hecho=$hecho;
	}
	function gethecho()
	{
		return $this->hecho;
	}
	function settargetstring($targetstring)
	{
		$this->targetstring=$targetstring;
	}
	function gettargetstring()
	{
		return $this->targetstring;
	}
	function settargetdate($targetdate)
	{
		$this->targetdate=$targetdate;
	}
	function gettargetdate()
	{
		return $this->targetdate;
	}
	function settargetint($targetint)
	{
		$this->targetint=$targetint;
	}
	function gettargetint()
	{
		return $this->targetint;
	}
}?>

