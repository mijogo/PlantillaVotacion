<?php
require_once "usuarioBD.php";
class usuario extends usuarioBD
{
	function usuario($db,$id="",$username="",$password="",$poder="",$email="",$verificacion="",$edad="",$sexo="",$pais="",$fecharegistro="",$imagen="",$facecode="",$facecodeex="",$twittercode="",$twittercodeex="",$extracode="",$extracodeex="" )
	{
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->poder = $poder;
		$this->email = $email;
		$this->verificacion = $verificacion;
		$this->edad = $edad;
		$this->sexo = $sexo;
		$this->pais = $pais;
		$this->fecharegistro = $fecharegistro;
		$this->imagen = $imagen;
		$this->facecode = $facecode;
		$this->facecodeex = $facecodeex;
		$this->twittercode = $twittercode;
		$this->twittercodeex = $twittercodeex;
		$this->extracode = $extracode;
		$this->extracodeex = $extracodeex;
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
	function setusername($username)
	{
		$this->username=$username;
	}
	function getusername()
	{
		return $this->username;
	}
	function setpassword($password)
	{
		$this->password=$password;
	}
	function getpassword()
	{
		return $this->password;
	}
	function setpoder($poder)
	{
		$this->poder=$poder;
	}
	function getpoder()
	{
		return $this->poder;
	}
	function setemail($email)
	{
		$this->email=$email;
	}
	function getemail()
	{
		return $this->email;
	}
	function setverificacion($verificacion)
	{
		$this->verificacion=$verificacion;
	}
	function getverificacion()
	{
		return $this->verificacion;
	}
	function setedad($edad)
	{
		$this->edad=$edad;
	}
	function getedad()
	{
		return $this->edad;
	}
	function setsexo($sexo)
	{
		$this->sexo=$sexo;
	}
	function getsexo()
	{
		return $this->sexo;
	}
	function setpais($pais)
	{
		$this->pais=$pais;
	}
	function getpais()
	{
		return $this->pais;
	}
	function setfecharegistro($fecharegistro)
	{
		$this->fecharegistro=$fecharegistro;
	}
	function getfecharegistro()
	{
		return $this->fecharegistro;
	}
	function setimagen($imagen)
	{
		$this->imagen=$imagen;
	}
	function getimagen()
	{
		return $this->imagen;
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

