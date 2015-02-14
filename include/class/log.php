<?php
require_once "logBD.php";
class log extends logBD
{
	function log($iduser="",$accion="",$fecha="",$estado="",$ip="",$accioncompleta="")
	{
		$this->iduser = $iduser;
		$this->accion = $accion;
		$this->fecha = $fecha;
		$this->estado = $estado;
		$this->ip = $ip;
		$this->accioncompleta = $accioncompleta;
	}
	function setiduser($iduser)
	{
		$this->iduser=$iduser;
	}
	function getiduser()
	{
		return $this->iduser;
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
	function setestado($estado)
	{
		$this->estado=$estado;
	}
	function getestado()
	{
		return $this->estado;
	}
	function setip($ip)
	{
		$this->ip=$ip;
	}
	function getip()
	{
		return $this->ip;
	}
	function setaccioncompleta($accioncompleta)
	{
		$this->accioncompleta=$accioncompleta;
	}
	function getaccioncompleta()
	{
		return $this->accioncompleta;
	}
}?>

