<?php
require_once "eventoBD.php";
class evento extends eventoBD
{
	function evento($db,$id="",$estado="",$idtorneo="",$fechainicio="",$fechatermino="" )
	{
		$this->id = $id;
		$this->estado = $estado;
		$this->idtorneo = $idtorneo;
		$this->fechainicio = $fechainicio;
		$this->fechatermino = $fechatermino;
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
	function setestado($estado)
	{
		$this->estado=$estado;
	}
	function getestado()
	{
		return $this->estado;
	}
	function setidtorneo($idtorneo)
	{
		$this->idtorneo=$idtorneo;
	}
	function getidtorneo()
	{
		return $this->idtorneo;
	}
	function setfechainicio($fechainicio)
	{
		$this->fechainicio=$fechainicio;
	}
	function getfechainicio()
	{
		return $this->fechainicio;
	}
	function setfechatermino($fechatermino)
	{
		$this->fechatermino=$fechatermino;
	}
	function getfechatermino()
	{
		return $this->fechatermino;
	}
}?>

