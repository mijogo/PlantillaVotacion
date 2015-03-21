<?php
require_once "DataBase.php";
class torneoBD extends DataBase
{
	function torneoBD(){}
	
	function save()
	{		$sql = "INSERT INTO torneo (id,ano,version,nombre,activo,estado,duracionbatalla,extraconteo,nominaciones,intervalo,horainicio,duracionlive,maxmiembrosgraf,opcionpartida) VALUES 
		(
		'".$this->id."',
		'".$this->ano."',
		'".$this->version."',
		'".$this->nombre."',
		'".$this->activo."',
		'".$this->estado."',
		'".$this->duracionbatalla."',
		'".$this->extraconteo."',
		'".$this->nominaciones."',
		'".$this->intervalo."',
		'".$this->horainicio."',
		'".$this->duracionlive."',
		'".$this->maxmiembrosgraf."',
		'".$this->opcionpartida."')";
		return $this->insert($sql);
	}

	function read($multi=true , $cantConsulta = 0 , $Consulta = "" , $cantOrden = 0 , $Orden = "" , $consultaextra="")
	{
		$sql="SELECT * FROM torneo ";
		if($consultaextra=="")
		{
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
			$torneos = array();
			while($row = $this->fetch($result))
			{
				$i=0;
				$torneos[]=new torneo($this->con,$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			}
			return $torneos;
		}
		else
		{
			$result = $this->select($sql);
			$row = $this->fetch($result);
			$i=0;
			$torneos= new torneo($this->con,$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			return $torneos;
		}
	}
	
	function update($cantSet = 0 , $Set = "" , $cantConsulta = 0 , $Consulta= "")
	{
		$sql="UPDATE torneo ";
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
		$sql = "DELETE FROM torneo ";
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