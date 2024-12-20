<?php

namespace Hotel;

use PDO;
use PDOException;
use Exception;
use Support\Configuration\Configuration;

class DBConnect
{
	private static $pdo;
	
	public function __construct()
	{
		$this->initializePdo();
	}
	
	protected function initializePdo()
	{
		//Check if pdo is already initialized
		if(!empty(self::$pdo)) {
			return;
		}
		
		// Load database configuration
		$config = Configuration::getInstance();
		$databaseConfig = $config->getConfig()['database'];

		try {
		// Connect to database
		self::$pdo = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=UTF8', $databaseConfig['host'], $databaseConfig['dbname']),$databaseConfig['username'],$databaseConfig['password'], [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]);
		} catch (PDOException $ex) {
			throw new Exception(sprintF('Database unavailable. Error: %s', $ex->getMessage()));
		}
	}
		
	
	protected function getPdo()
	{
		return self::$pdo;
	}
	protected function execute($sql, $parameters)
	{
		// Prepare statement
		$statement = $this->getPdo()->prepare($sql);

			// Execute
			$status = $statement->execute($parameters);
			if (!$status ) {
				throw new Exception($statement->errorInfo()[2]);
			}
			return $status;
	}
	protected function fetchAll($sql, $parameters = [], $multi = false, $type = PDO::FETCH_ASSOC)
	{
		// Prepare statement
		$statement = $this->getPdo()->prepare($sql);
		
		// Execute
		$status = $statement->execute($parameters);
		if (!$status) {
			throw new Exception($statement->errorInfo()[2]);
		}
		$multiArray = []; 
		if($multi) {
			do{
				$multiArray[] = $statement->fetchAll($type);
			} while ($statement->nextRowset());
			return $multiArray;
		}
		
		// Fetch All
		return $statement->fetchAll($type);
	}
	
	protected function fetch($sql, $parameters = [], $type = PDO::FETCH_ASSOC)
	{
		// Prepare statement
		$statement = $this->getPdo()->prepare($sql);
		
		// Execute
		$status = $statement->execute($parameters);
		if (!$status) {
			throw new Exception($statement->errorInfo()[2]);
		}
		
		// Fetch All
		return $statement->fetch($type);
	}
	
}