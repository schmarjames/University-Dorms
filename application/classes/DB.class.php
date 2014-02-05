<?php

class DB {
	
	// connection proporties
	private $host	= 'localhost';
	private $user	= 'root';
	private $pass	= 'root';
	private $dbname	= 'university_dorms';
	
	private $dbh;
	private $error;
	private $stmt;
	
	// __construct()
	public function __construct() {
		// set dsn
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		
		//set options
		$options = array(
			PDO::ATTR_PERSISTENT	=> true,
			PDO::ATTR_ERRMODE		=> PDO::ERRMODE_EXCEPTION
		);
		
		// create a new pdo instance
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		} catch(PDOException $e) {
			// catch any errors
			$this->error = $e->getMessage();
		}
	}
	
	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}
	
	public function bind($param, $value, $type = null) {
		if(is_null($type)) {
			switch(true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}
	
	public function execute() {
		return $this->stmt->execute();
	}
	
	public function result_set() {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function single() {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function row_count() {
		return $this->stmt->rowCount();
	}
	
	public function last_insert_id() {
		return $this->dbh->lastInsertId();
	}
	
	public function begin_transaction() {
		return $this->dbh->beginTransaction();
	}
	
	public function end_transaction() {
		return $this->dbh->commit();
	}
	
	public function cancel_transaction() {
		return $this->dbh->rollBack();
	}
	
	public function debug_dump_params() {
		return $this->stmt->debugDumpParams();
	}
}

?>