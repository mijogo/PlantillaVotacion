<?php
require_once "usuarioBD.php";
class usuario extends usuarioBD
{
	function usuario($db,$idusuario="",$poder="",$facecode="",$facecodeex="",$twittercode="",$twittercodeex="",$extracode="",$extracodeex="" )
	{
		$this->idusuario = $idusuario;
		$this->poder = $poder;
		$this->facecode = $facecode;
		$this->facecodeex = $facecodeex;
		$this->twittercode = $twittercode;
		$this->twittercodeex = $twittercodeex;
		$this->extracode = $extracode;
		$this->extracodeex = $extracodeex;
		$this->con = $db;
	}
	function setidusuario($idusuario)
	{
		$this->idusuario=$idusuario;
	}
	function getidusuario()
	{
		return $this->idusuario;
	}
	function setpoder($poder)
	{
		$this->poder=$poder;
	}
	function getpoder()
	{
		return $this->poder;
	}
	function setfacecode($facecode)
	{
		$this->facecode=$facecode;
	}
	function getfacecode()
	{
		return $this->facecode;
	}
	function setfacecodeex($facecodeex)
	{
		$this->facecodeex=$facecodeex;
	}
	function getfacecodeex()
	{
		return $this->facecodeex;
	}
	function settwittercode($twittercode)
	{
		$this->twittercode=$twittercode;
	}
	function gettwittercode()
	{
		return $this->twittercode;
	}
	function settwittercodeex($twittercodeex)
	{
		$this->twittercodeex=$twittercodeex;
	}
	function gettwittercodeex()
	{
		return $this->twittercodeex;
	}
	function setextracode($extracode)
	{
		$this->extracode=$extracode;
	}
	function getextracode()
	{
		return $this->extracode;
	}
	function setextracodeex($extracodeex)
	{
		$this->extracodeex=$extracodeex;
	}
	function getextracodeex()
	{
		return $this->extracodeex;
	}
}?>

