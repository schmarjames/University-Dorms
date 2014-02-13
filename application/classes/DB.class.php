<?php

/**
 * DB class
 *
 * This class contains all of the necessary database function
 * which enables the application to run queries.
 *
 * @package    University Dorms
 * @subpackage DB
 * @author     Schmar James <loyd.slj@gmail.com>
 */
class DB {
	
	// connection proporties
	private $host	= DB_HOST;
	private $user	= DB_USER;
	private $pass	= DB_PASS;
	private $dbname	= DB_NAME;
	
	private $dbh;
	private $error;
	private $stmt;
	
	
	/**
 	 * Constructor
 	 *
 	 * @access	public
 	 */
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
	
	
	/**
 	 * query
 	 *
 	 * prepares sql statement
 	 * 
 	 * @param string $query
 	 * @return string
 	 */
	public function query($query) {
		$this->stmt = $this->dbh->prepare($query);
	}
	
	/**
 	 * bind
 	 *
 	 * applies binded variables with its paired data
 	 * to add to append to the sql query
 	 * 
 	 * @param string $param
 	 * @param string $value
 	 * @param function
 	 */
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
	
	/**
 	 * execute
 	 *
 	 * run sql query
 	 * 
 	 * @return function
 	 */
	public function execute() {
		return $this->stmt->execute();
	}
	
	/**
 	 * result_set
 	 *
 	 * returns an array of queried data
 	 * 
 	 * @return array
 	 */
	public function result_set() {
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
 	 * single
 	 *
 	 * returns a single row of data
 	 * 
 	 * @return array
 	 */
	public function single() {
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
 	 * row_count
 	 *
 	 * returns a count of the amount of rows
 	 * that existed in the chosen table
 	 * 
 	 * @return int
 	 */
	public function row_count() {
		return $this->stmt->rowCount();
	}
	
	/**
 	 * last_insert_id
 	 *
 	 * returns the last id of the inserted row
 	 * within a chosen table
 	 * 
 	 * @return int
 	 */
	public function last_insert_id() {
		return $this->dbh->lastInsertId();
	}
}

?>