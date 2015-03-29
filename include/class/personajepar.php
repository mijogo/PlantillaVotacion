<?php
require_once "personajeparBD.php";
class personajepar extends personajeparBD
{
	function personajepar($db,$id="",$nombre="",$idpersonaje="",$idserie="",$imagenpeq="",$imagen="",$idtorneo="",$estado="",$grupo="",$ronda="",$seiyuu="",$ponderacion="" )
	{
		$this->id = $id;
		$this->nombre = $nombre;
		$this->idpersonaje = $idpersonaje;
		$this->idserie = $idserie;
		$this->imagenpeq = $imagenpeq;
		$this->imagen = $imagen;
		$this->idtorneo = $idtorneo;
		$this->estado = $estado;
		$this->grupo = $grupo;
		$this->ronda = $ronda;
		$this->seiyuu = $seiyuu;
		$this->ponderacion = $ponderacion;
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
	function setidpersonaje($idpersonaje)
	{
		$this->idpersonaje=$idpersonaje;
	}
	function getidpersonaje()
	{
		return $this->idpersonaje;
	}
	function setidserie($idserie)
	{
		$this->idserie=$idserie;
	}
	function getidserie()
	{
		return $this->idserie;
	}
	function setimagenpeq($imagenpeq)
	{
		$this->imagenpeq=$imagenpeq;
	}
	function getimagenpeq()
	{
		return $this->imagenpeq;
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
	function setestado($estado)
	{
		$this->estado=$estado;
	}
	function getestado()
	{
		return $this->estado;
	}
	function setgrupo($grupo)
	{
		$this->grupo=$grupo;
	}
	function getgrupo()
	{
		return $this->grupo;
	}
	function setronda($ronda)
	{
		$this->ronda=$ronda;
	}
	function getronda()
	{
		return $this->ronda;
	}
	function setseiyuu($seiyuu)
	{
		$this->seiyuu=$seiyuu;
	}
	function getseiyuu()
	{
		return $this->seiyuu;
	}
	function setponderacion($ponderacion)
	{
		$this->ponderacion=$ponderacion;
	}
	function getponderacion()
	{
		return $this->ponderacion;
	}
}?>

