<?php
require_once "serieparBD.php";
class seriepar extends serieparBD
{
	function seriepar($db,$id="",$nombre="",$nombrecorto="",$imagen="",$idtorneo="",$idserie="",$ano="",$tipoformato="",$tcours="",$ncours="" )
	{
		$this->id = $id;
		$this->nombre = $nombre;
		$this->nombrecorto = $nombrecorto;
		$this->imagen = $imagen;
		$this->idtorneo = $idtorneo;
		$this->idserie = $idserie;
		$this->ano = $ano;
		$this->tipoformato = $tipoformato;
		$this->tcours = $tcours;
		$this->ncours = $ncours;
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
	function setnombre($nombre)
	{
		$this->nombre=$nombre;
	}
	function getnombre()
	{
		return $this->nombre;
	}
	function setnombrecorto($nombrecorto)
	{
		$this->nombrecorto=$nombrecorto;
	}
	function getnombrecorto()
	{
		return $this->nombrecorto;
	}
	function setimagen($imagen)
	{
		$this->imagen=$imagen;
	}
	function getimagen()
	{
		return $this->imagen;
	}
	function setidtorneo($idtorneo)
	{
		$this->idtorneo=$idtorneo;
	}
	function getidtorneo()
	{
		return $this->idtorneo;
	}
	function setidserie($idserie)
	{
		$this->idserie=$idserie;
	}
	function getidserie()
	{
		return $this->idserie;
	}
	function setano($ano)
	{
		$this->ano=$ano;
	}
	function getano()
	{
		return $this->ano;
	}
	function settipoformato($tipoformato)
	{
		$this->tipoformato=$tipoformato;
	}
	function gettipoformato()
	{
		return $this->tipoformato;
	}
	function settcours($tcours)
	{
		$this->tcours=$tcours;
	}
	function gettcours()
	{
		return $this->tcours;
	}
	function setncours($ncours)
	{
		$this->ncours=$ncours;
	}
	function getncours()
	{
		return $this->ncours;
	}
}?>

