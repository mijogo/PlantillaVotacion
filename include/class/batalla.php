<?php
require_once "batallaBD.php";
class batalla extends batallaBD
{
	function batalla($id="",$fecha="",$ronda="",$grupo="",$idtorneo="",$estado="",$numerovotos="",$ganador="")
	{
		$this->id = $id;
		$this->fecha = $fecha;
		$this->ronda = $ronda;
		$this->grupo = $grupo;
		$this->idtorneo = $idtorneo;
		$this->estado = $estado;
		$this->numerovotos = $numerovotos;
		$this->ganador = $ganador;
	}
	function setid($id)
	{
		$this->id=$id;
	}
	function getid()
	{
		return $this->id;
	}
	function setfecha($fecha)
	{
		$this->fecha=$fecha;
	}
	function getfecha()
	{
		return $this->fecha;
	}
	function setronda($ronda)
	{
		$this->ronda=$ronda;
	}
	function getronda()
	{
		return $this->ronda;
	}
	function setgrupo($grupo)
	{
		$this->grupo=$grupo;
	}
	function getgrupo()
	{
		return $this->grupo;
	}
	function setidtorneo($idtorneo)
	{
		$this->idtorneo=$idtorneo;
	}
	function getidtorneo()
	{
		return $this->idtorneo;
	}
	function setestado($estado)
	{
		$this->estado=$estado;
	}
	function getestado()
	{
		return $this->estado;
	}
	function setnumerovotos($numerovotos)
	{
		$this->numerovotos=$numerovotos;
	}
	function getnumerovotos()
	{
		return $this->numerovotos;
	}
	function setganador($ganador)
	{
		$this->ganador=$ganador;
	}
	function getganador()
	{
		return $this->ganador;
	}
}?>

