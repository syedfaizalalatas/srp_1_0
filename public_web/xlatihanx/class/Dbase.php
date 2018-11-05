<?php 
	
/**
* Database configurations
*
* @ Author			shu4mi
* @ Date			20 May 2014
*
**/

class Dbase
{
	private $username 	= "myadmin"; 
	private $password 	= "zaq!12345@qwerty#"; 
	private $host 		= "localhost"; 
	private $dbname 	= "srp1_0_lat";
	private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
	private $_pdo;

	public function __construct()
	{
		$dbh = "mysql:host={$this->host};dbname={$this->dbname}";
		try
		{
			$this->_pdo = new PDO($dbh, $this->username, $this->password, $this->options);
			
			//Log any exceptions on fatal error			
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//Disable emulation of prepared statements, use real prepared statements instead
			$this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		}
		catch(PDOException $e)
		{
			die("Failed to connect to the database"); 
		}		
	}

	public function getDb() 
	{
		if ($this->_pdo instanceof PDO) 
		{
			return $this->_pdo;
		}
	}
}