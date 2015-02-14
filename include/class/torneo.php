<?php
require_once "torneoBD.php";
class torneo extends torneoBD
{
	function torneo($db,$id="",$ano="",$version="",$nombre="",$activo="",$estado="",$duracionbatalla="",$extraconteo="",$nominaciones="",$intervalo="",$horainicio="",$duracionlive="",$maxmiembrosgraf="",$opcionpartida="",$limitadoractivo="",$duracionlimitador="",$porcentajelimite="" )
	{
		$this->id = $id;
		$this->ano = $ano;
		$this->version = $version;
		$this->nombre = $nombre;
		$this->activo = $activo;
		$this->estado = $estado;
		$this->duracionbatalla = $duracionbatalla;
		$this->extraconteo = $extraconteo;
		$this->nominaciones = $nominaciones;
		$this->intervalo = $intervalo;
		$this->horainicio = $horainicio;
		$this->duracionlive = $duracionlive;
		$this->maxmiembrosgraf = $maxmiembrosgraf;
		$this->opcionpartida = $opcionpartida;
		$this->limitadoractivo = $limitadoractivo;
		$this->duracionlimitador = $duracionlimitador;
		$this->porcentajelimite = $porcentajelimite;
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
	function setano($ano)
	{
		$this->ano=$ano;
	}
	function getano()
	{
		return $this->ano;
	}
	function setversion($version)
	{
		$this->version=$version;
	}
	function getversion()
	{
		return $this->version;
	}
	function setnombre($nombre)
	{
		$this->nombre=$nombre;
	}
	function getnombre()
	{
		return $this->nombre;
	}
	function setactivo($activo)
	{
		$this->activo=$activo;
	}
	function getactivo()
	{
		return $this->activo;
	}
	function setestado($estado)
	{
		$this->estado=$estado;
	}
	function getestado()
	{
		return $this->estado;
	}
	function setduracionbatalla($duracionbatalla)
	{
		$this->duracionbatalla=$duracionbatalla;
	}
	function getduracionbatalla()
	{
		return $this->duracionbatalla;
	}
	function setextraconteo($extraconteo)
	{
		$this->extraconteo=$extraconteo;
	}
	function getextraconteo()
	{
		return $this->extraconteo;
	}
	function setnominaciones($nominaciones)
	{
		$this->nominaciones=$nominaciones;
	}
	function getnominaciones()
	{
		return $this->nominaciones;
	}
	function setintervalo($intervalo)
	{
		$this->intervalo=$intervalo;
	}
	function getintervalo()
	{
		return $this->intervalo;
	}
	function sethorainicio($horainicio)
	{
		$this->horainicio=$horainicio;
	}
	function gethorainicio()
	{
		return $this->horainicio;
	}
	function setduracionlive($duracionlive)
	{
		$this->duracionlive=$duracionlive;
	}
	function getduracionlive()
	{
		return $this->duracionlive;
	}
	function setmaxmiembrosgraf($maxmiembrosgraf)
	{
		$this->maxmiembrosgraf=$maxmiembrosgraf;
	}
	function getmaxmiembrosgraf()
	{
		return $this->maxmiembrosgraf;
	}
	function setopcionpartida($opcionpartida)
	{
		$this->opcionpartida=$opcionpartida;
	}
	function getopcionpartida()
	{
		return $this->opcionpartida;
	}
	function setlimitadoractivo($limitadoractivo)
	{
		$this->limitadoractivo=$limitadoractivo;
	}
	function getlimitadoractivo()
	{
		return $this->limitadoractivo;
	}
	function setduracionlimitador($duracionlimitador)
	{
		$this->duracionlimitador=$duracionlimitador;
	}
	function getduracionlimitador()
	{
		return $this->duracionlimitador;
	}
	function setporcentajelimite($porcentajelimite)
	{
		$this->porcentajelimite=$porcentajelimite;
	}
	function getporcentajelimite()
	{
		return $this->porcentajelimite;
	}
}?>

