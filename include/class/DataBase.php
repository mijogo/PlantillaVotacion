<?php
class DataBase
{
	function DataBase(){}
	function connect()
	{
		$this->con = new mysqli(SERVER,USER,PASS,MYDB);
		if ($this->con->connect_error)
		{
		    die('Connect Error (' . $this->con->connect_errno . ') '. $this->con->connect_error);
		}
	}
	
	function close()
	{
		$this->con->close();
	}
	
	function fetch($result)
	{
		return $result->fetch_row();
	}
	
	function multiselect($sql)
	{
		$this->con->multi_query($sql);
		return $this->con->store_result(); 
	}
	
	function select($sql)
	{
		$this->result = $this->con->query($sql);
		return $this->result;
	}
	
	function insert($sql)
	{
		$this->result = $this->querry($sql);
		return $this->result;
	}
}
?>