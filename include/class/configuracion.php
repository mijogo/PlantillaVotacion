<?php
require_once "configuracionBD.php";
class configuracion extends configuracionBD
{
	function configuracion($id="",$nombre="",$idtorneo="",$numerogrupos="",$tipo="",$segundo="",$primclas="",$primproxronda="",$segclas="",$segproxronda="",$sorteo="",$limitevotos="",$extra="")
	{
		$this->id = $id;
		$this->nombre = $nombre;
		$this->idtorneo = $idtorneo;
		$this->numerogrupos = $numerogrupos;
		$this->tipo = $tipo;
		$this->segundo = $segundo;
		$this->primclas = $primclas;
		$this->primproxronda = $primproxronda;
		$this->segclas = $segclas;
		$this->segproxronda = $segproxronda;
		$this->sorteo = $sorteo;
		$this->limitevotos = $limitevotos;
		$this->extra = $extra;
	}
	function setid($id)
	{
		$this->id=$id;
	}
	function getid()
	{
		return $this->id;
	}
	function setnombre($nombre)
	{
		$this->nombre=$nombre;
	}
	function getnombre()
	{
		return $this->nombre;
	}
	function setidtorneo($idtorneo)
	{
		$this->idtorneo=$idtorneo;
	}
	function getidtorneo()
	{
		return $this->idtorneo;
	}
	function setnumerogrupos($numerogrupos)
	{
		$this->numerogrupos=$numerogrupos;
	}
	function getnumerogrupos()
	{
		return $this->numerogrupos;
	}
	function settipo($tipo)
	{
		$this->tipo=$tipo;
	}
	function gettipo()
	{
		return $this->tipo;
	}
	function setsegundo($segundo)
	{
		$this->segundo=$segundo;
	}
	function getsegundo()
	{
		return $this->segundo;
	}
	function setprimclas($primclas)
	{
		$this->primclas=$primclas;
	}
	function getprimclas()
	{
		return $this->primclas;
	}
	function setprimproxronda($primproxronda)
	{
		$this->primproxronda=$primproxronda;
	}
	function getprimproxronda()
	{
		return $this->primproxronda;
	}
	function setsegclas($segclas)
	{
		$this->segclas=$segclas;
	}
	function getsegclas()
	{
		return $this->segclas;
	}
	function setsegproxronda($segproxronda)
	{
		$this->segproxronda=$segproxronda;
	}
	function getsegproxronda()
	{
		return $this->segproxronda;
	}
	function setsorteo($sorteo)
	{
		$this->sorteo=$sorteo;
	}
	function getsorteo()
	{
		return $this->sorteo;
	}
	function setlimitevotos($limitevotos)
	{
		$this->limitevotos=$limitevotos;
	}
	function getlimitevotos()
	{
		return $this->limitevotos;
	}
	function setextra($extra)
	{
		$this->extra=$extra;
	}
	function getextra()
	{
		return $this->extra;
	}
}?>

