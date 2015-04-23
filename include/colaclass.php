<?php
class colaclass
{
	function colaclass()
	{
		$this->var[] = array("",0,0);
		$this->cantidad=0;
	}
	
	function insert($elemento)
	{
		$first = $this->var[0];
		$last = $this->var[$first[1]];
		$this->var[] = array($elemento,$first[1],0);
		$this->var[0][1] = count($this->var)-1;
		$this->var[$first[1]][2] = count($this->var)-1;
		$this->cantidad++;
	}
	
	function randominit()
	{
		$n = rand(1,$this->cantidad);
		$this->inicial = 0;
		for($i=0;$i<$n;$i++)
			$this->inicial = $this->var[$this->inicial][2];
		$this->retirar = $this->var[$this->inicial][0];
		return $this->retirar;
	}
	
	function firstinit()
	{
		$this->inicial = $this->var[0][2];
		$this->retirar = $this->var[$this->inicial][0];
		return $this->retirar;
	}
	
	function randomquit()
	{
		$this->var[$this->var[$this->inicial][1]][2] = $this->var[$this->inicial][2];
		$this->var[$this->var[$this->inicial][2]][1] = $this->var[$this->inicial][1];
		$this->cantidad--;
	}	
	
	function cantidad()
	{
		return $this->cantidad;
	}
}
?>