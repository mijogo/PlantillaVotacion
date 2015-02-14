<?php
require_once "DataBase.php";
class votoBD extends DataBase
{
	function votoBD(){}
	
	function save()
	{		$sql = "INSERT INTO voto (fecha,idbatalla,idpersonaje,uniquecode,codepass) VALUES 
		(
		'".$this->fecha."',
		'".$this->idbatalla."',
		'".$this->idpersonaje."',
		'".$this->uniquecode."',
		'".$this->codepass."')";
		return $this->insert($sql);
	}

	function read($multi=true , $cantConsulta = 0 , $Consulta = "" , $cantOrden = 0 , $Orden = "" , , $consultaextra="")
	{
		$sql="SELECT * FROM voto ";
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
			$result = $this->select($sql);
			$votos = array();
			while($row = $this->fetch($result))
			{
				$i=0;
				$votos[]=new voto($row[$i++],$row[$i++],$row[$i++],$row[$i++],$row[$i++]);
			}
			return $votos;
		}
		else
		{
			$result = $this->select($sql);
			$row = $this->fetch($result);
			$i=0;
			$votos= new voto(";
			for($i=0;$i<count($ElementosTabla);$i++)
			{
				$text.= "$row[$i++]";
				if($i!=count($ElementosTabla)-1)
					$text.= ",";
			}
			$text.=");
			return $votos;
		}
	}
	
	function update($cantSet = 0 , $Set = "" , $cantConsulta = 0 , $Consulta= "")
	{
		$sql="UPDATE voto ";
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
		$sql = "DELETE FROM voto ";
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