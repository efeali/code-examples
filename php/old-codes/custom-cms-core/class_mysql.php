<?php 


class MYSQL
{
	protected $dbhost;
	protected $dbuser;
	protected $dbpass;
	protected $dbname;
	protected $cnx;
	
	var $error;
	var $sql;
	var $result;
	var $text;
	var $insert_id;
	
	
	
	function __construct()
	{
		$dbhost = "localhost";
		$dbuser = "";
		$dbpass = "";
		$dbname = "";
		
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
	}

	function connect_db()
	{
		$link = mysqli_init();
		if(!$link)
		{
			die("mysqli_init failed");
		}
		if(!$link->options(MYSQLI_INIT_COMMAND,'SET AUTOCOMMIT = 0'))
		{
			die("Setting MYSQLI_INIT_COMMAND failed");
		}
		if (!$link->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) 
		{
			die("Setting MYSQLI_OPT_CONNECT_TIMEOUT failed");
		}
		
		if(!$link->real_connect($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname))
		{
			die("Connect error(".mysqli_connect_errno().")".mysqli_connect_error());
		}
		$this->cnx = $link;
		return $this->cnx;
	}
	
	function query($sql)
	{
		$this->sql = $sql;
		if(!$this->result = $this->cnx->query($this->sql))
		{	
			$this->error = $this->cnx->error;
			
		}
		return $this->result;
	}
	function last_insert_id()
	{
		return $this->cnx->insert_id;
	}
	
	function escape_chars($text)
	{
		$this->text = $this->cnx->real_escape_string($text);
		return $this->text;
	}
	
	function autoCommit($var)
	{
		$this->cnx->autocommit($var);
	}
	
	function Commit()
	{
		$this->cnx->commit();
	}
	
	function __destruct()
	{
		$this->cnx->close();
	}
	
}




?>