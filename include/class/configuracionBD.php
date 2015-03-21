<?php
require_once "DataBase.php";
class configuracionBD extends DataBase
{
	function configuracionBD(){}
	
	function save()
	{		$sql = "INSERT INTO configuracion (id,nombre,idtorneo,numerogrupos,numerobatallas,tipo,segundo,primclas,primproxronda,segclas,segproxronda,sorteo,limitevotos,extra) VALUES 
		(
		'".$this->id."',
		'".$this->nombre."',
		'".$this->idtorneo."',
		'".$this->numerogrupos."',
		'".$this->numerobatallas."',
		'".$this->tipo."',
		'".$this->segundo."',
		'".$this->primclas."',
		'".$this->primproxronda."',
		'".$this->segclas."',
		'".$this->segproxronda."',
		'".$this->sorteo."',
		'".$this->limitevotos."',
		'".$this->extra."')";
		return $this->insert($sql);
	}

	function read($multi=true , $cantConsulta = 0 , $Consulta = "" , $cantOrden = 0 , $Orden = "" , $consultaextra="")
	{
		$sql="SELECT * FROM configuracion ";
		if($consultaextra=="")
			if($cantConsulta != 0)
			{
				$sql .= "WHERE ";
				for($i=0;$i<$cantConsulta;$i++)
				{
					$sql .= $Consulta[$i*2]." = '".$this->$Consulta[$i*2]."' ";
					if($i != $cantConsulta-1)
						$sql .= $Consulta[$i*2+1]." ";
				}
			}
		else
			$sql="WHERE ".$consultaextra;
		
		if($cantOrden != 0)
		{
			$sql .= "ORDER BY ";
			for($i=0;$i<$cantOrden;$i++)
			{
				$sql .= $Orden [$i*2]." ".$Orden [$i*2+1]." ";
				if($i != $cantOrden-1)
					$sql .= ",";
			}
		}
		if($multi)
		{
			$result = $this->multiselect($sql);
			$configuracions = array();
			while($row = $this->fetch($result))
			{
				$i=0;
				$configuracions[]=new configuracion($this->con,$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			}
			return $configuracions;
		}
		else
		{
			$result = $this->select($sql);
			$row = $this->fetch($result);
			$i=0;
			$configuracions= new configuracion($this->con,$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			return $configuracions;
		}
	}
	
	function update($cantSet = 0 , $Set = "" , $cantConsulta = 0 , $Consulta= "")
	{
		$sql="UPDATE configuracion ";
		if($cantSet != 0)
		{
			$sql .= "SET ";
			for($i=0;$i<$cantSet;$i++)
			{
				$sql .= $Set[$i]." = '".$this->$Set[$i]."' ";
				if($i != $cantSet-1)
					$sql .= ",";
			}
		}
		
		if($cantConsulta != 0)
		{
			$sql .= "WHERE ";
			for($i=0;$i<$cantConsulta;$i++)
			{
				$sql .= $Consulta[$i*2]." = '".$this->$Consulta[$i*2]."' ";
				if($i != $cantConsulta-1)
					$sql .= $Consulta[$i*2+1]." ";
			}
		}
		return $this->insert($sql);
	}
	
	function delete($cantConsulta = 0 , $Consulta = "")
	{
		$sql = "DELETE FROM configuracion ";
		if($cantConsulta != 0)
		{
			$sql .= "WHERE ";
			for($i=0;$i<$cantConsulta;$i++)
			{
				$sql .= $Consulta[$i*2]." = '".$this->$Consulta[$i*2]."' ";
				if($i != $cantConsulta-1)
					$sql .= $Consulta[$i*2+1]." ";
			}
		}
		return $this->insert($sql);
	}
}
?>