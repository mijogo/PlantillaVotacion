<?php
require_once "DataBase.php";
class personajeBD extends DataBase
{
	function personajeBD(){}
	
	function save()
	{		$sql = "INSERT INTO personaje (id,nombre,serie,imagen,idserie,nparticipaciones,mejorpos) VALUES 
		(
		'".$this->id."',
		'".$this->nombre."',
		'".$this->serie."',
		'".$this->imagen."',
		'".$this->idserie."',
		'".$this->nparticipaciones."',
		'".$this->mejorpos."')";
		return $this->insert($sql);
	}

	function read($multi=true , $cantConsulta = 0 , $Consulta = "" , $cantOrden = 0 , $Orden = "" , $consultaextra="")
	{
		$sql="SELECT * FROM personaje ";
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
			$sql.="WHERE ".$consultaextra;
		
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
			$personajes = array();
			while($row = $this->fetch($result))
			{
				$i=0;
				$personajes[]=new personaje($this->con,$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			}
			return $personajes;
		}
		else
		{
			$result = $this->select($sql);
			$row = $this->fetch($result);
			$i=0;
			$personajes= new personaje($this->con,$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			return $personajes;
		}
	}
	
	function update($cantSet = 0 , $Set = "" , $cantConsulta = 0 , $Consulta= "")
	{
		$sql="UPDATE personaje ";
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
		$sql = "DELETE FROM personaje ";
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