<?php
require_once "ipBD.php";
class ip extends ipBD
{
	function ip($db,$fecha="",$ip="",$codepass="",$forumcode="",$user="",$idevento="",$tiempo="",$usada="",$uniquecode="",$mastercode="",$masterip="",$info="" )
	{
		$this->fecha = $fecha;
		$this->ip = $ip;
		$this->codepass = $codepass;
		$this->forumcode = $forumcode;
		$this->user = $user;
		$this->idevento = $idevento;
		$this->tiempo = $tiempo;
		$this->usada = $usada;
		$this->uniquecode = $uniquecode;
		$this->mastercode = $mastercode;
		$this->masterip = $masterip;
		$this->info = $info;
		$this->con = $db;
	}
	function setfecha($fecha)
	{
		$this->fecha=$fecha;
	}
	function getfecha()
	{
		return $this->fecha;
	}
	function setip($ip)
	{
		$this->ip=$ip;
	}
	function getip()
	{
		return $this->ip;
	}
	function setcodepass($codepass)
	{
		$this->codepass=$codepass;
	}
	function getcodepass()
	{
		return $this->codepass;
	}
	function setforumcode($forumcode)
	{
		$this->forumcode=$forumcode;
	}
	function getforumcode()
	{
		return $this->forumcode;
	}
	function setuser($user)
	{
		$this->user=$user;
	}
	function getuser()
	{
		return $this->user;
	}
	function setidevento($idevento)
	{
		$this->idevento=$idevento;
	}
	function getidevento()
	{
		return $this->idevento;
	}
	function settiempo($tiempo)
	{
		$this->tiempo=$tiempo;
	}
	function gettiempo()
	{
		return $this->tiempo;
	}
	function setusada($usada)
	{
		$this->usada=$usada;
	}
	function getusada()
	{
		return $this->usada;
	}
	function setuniquecode($uniquecode)
	{
		$this->uniquecode=$uniquecode;
	}
	function getuniquecode()
	{
		return $this->uniquecode;
	}
	function setmastercode($mastercode)
	{
		$this->mastercode=$mastercode;
	}
	function getmastercode()
	{
		return $this->mastercode;
	}
	function setmasterip($masterip)
	{
		$this->masterip=$masterip;
	}
	function getmasterip()
	{
		return $this->masterip;
	}
	function setinfo($info)
	{
		$this->info=$info;
	}
	function getinfo()
	{
		return $this->info;
	}
}?>

