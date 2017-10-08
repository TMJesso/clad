<?php
require_once LIB_PATH.DS.'cl_config.php';

class ChangeSQL extends ChangeObject {
	private $cldb;
	
	function __construct() {
		$this->open_connection();
		$this->check_database();
	}
	
	private function open_connection() {
		$this->db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
		if (mysqli_connect_errno()) {
			die ("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
		}
	}
	
	public function close_connection() {
		if (isset($this->db)) {
			mysqli_close($this->db);
			unset($this->db);
		}
	}
	
	public function query($sql="") {
		$result = mysqli_query( $this->db, $sql);
		// test if there was a query error
		// $this->confirm_query($result);
		return $result;
	}
	
	public function prevent_injection($string) {
		$escaped_string = mysqli_real_escape_string($this->db, $string);
		return $escaped_string;
	}
	
	// "database neutral" functions
	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}
	
	public function fetch_assoc_array($result_set) {
		return mysqli_fetch_assoc($result_set);
	}
	
	public function fetch_fields($results) {
		return mysqli_fetch_fields($results);
	}
	
	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}
	
	public function insert_id() {
		// get the last id inserted over the current db connection
		return mysqli_insert_id($this->db);
	}
	
	public function affected_rows() {
		return mysqli_affected_rows($this->db);
	}
	
	// private functions
	private function check_database() {
		$sql  = 'CREATE TABLE IF NOT EXISTS users (';
		$sql .= ' id int(11) not null auto_increment, ';
		$sql .= ' username varchar(22) not null, ';
		$sql .= ' passcode varchar(75) not null, ';
		$sql .= ' fname varchar(15) not null, ';
		$sql .= ' lname varchar(15) not null, ';
		$sql .= ' security int(1) not null, ';
		$sql .= ' clearance int(1) not null, ';
		$sql .= ' primary key (id), ';
		$sql .= ' unique index username (username), ';
		$sql .= ' index name (fname, lname), ';
		$sql .= ' index alt_name (lname, fname))';
		$this->query($sql);
		
		$sql  = 'CREATE TABLE IF NOT EXISTS code_group (';
		$sql .= ' id int(11) not null auto_increment, ';
		$sql .= ' name varchar(50) not null unique, ';
		$sql .= ' updated datetime not null, ';
		$sql .= ' title varchar(50) not null, ';
		$sql .= ' description varchar(150) not null, ';
		$sql .= ' primary key (id))';
		$this->query($sql);
		
		$sql  = 'CREATE TABLE IF NOT EXISTS author (';
		$sql .= ' id int(11) not null auto_increment, ';
		$sql .= ' fname varchar(20) not null, ';
		$sql .= ' lname varchar(20) not null, ';
		$sql .= ' description varchar(250) null, ';
		$sql .= ' primary key (id))';
		$this->query($sql);
		
		$sql  = 'CREATE TABLE IF NOT EXISTS details (';
		$sql .= ' id int(11) not null auto_increment, ';
		$sql .= ' code_id int(11) not null, ';
		$sql .= ' name varchar(30) not null, ';
		$sql .= ' lastupdate datetime not null, ';
		$sql .= ' folders varchar(75) not null, ';
		$sql .= ' author_id int(11) not null, ';
		$sql .= ' primary key (id), ';
		$sql .= ' index code_id (code_id), ';
		$sql .= ' index author_id (author_id), ';
		$sql .= ' unique index name (name), ';
		$sql .= ' foreign key author_id (author_id) references author (id), ';
		$sql .= ' foreign key code_id (code_id) references code_group (id))';
		$this->query($sql);
		
		$sql  = 'CREATE TABLE IF NOT EXISTS subdetails (';
		$sql .= ' id int(11) not null auto_increment, ';
		$sql .= ' details_id int(11) not null, ';
		$sql .= ' position int(1) not null, ';
		$sql .= ' description varchar(250) not null, ';
		$sql .= ' primary key (id), ';
		$sql .= ' index details_id (details_id), ';
		$sql .= ' foreign key details_id (details_id) references details (id))';
		$this->query($sql);
		
		$sql  = 'CREATE TABLE IF NOT EXISTS user_log (';
		$sql .= ' id int(11) NOT NULL AUTO_INCREMENT, ';
		$sql .= ' user_id int(11) NOT NULL, ';
		$sql .= ' user_type varchar(15) NOT NULL, ';
		$sql .= ' date_stamp datetime NOT NULL , '; //DEFAULT CURRENT_TIMESTAMP ON UPDATE CURENT_TIMESTAMP
		$sql .= ' activity text,';
		$sql .= ' PRIMARY KEY (id), ';
		$sql .= ' INDEX user_id (user_id))';
		$this->query($sql);
		
	}
	
	public function drop_available_tables($tables=array()) {
		//$tables = array('users', 'code_group', 'author', 'details', 'subdetails');
		if (empty($tables)) {
			return false;
		} else {
			for ($x = 0; $x < count($tables); $x++) {
				$this->drop_tables($tables[$x]);
			}
		}
		return true;
	}
	
	private function drop_tables($table) {
		$sql  = "DROP TABLE {$table} IF EXISTS";
		$this->query($sql);
	}
}

$cldb = new ChangeSQL();


?>