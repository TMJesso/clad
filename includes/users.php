<?php
class Users extends ChangeObject {
	static $table_name = "users";
	
	public $id;
	
	public $username;
	
	public $passcode;
	
	public $fname;
	
	public $lname;
	
	public $security;
	
	public $clearance;
	
	function __construct() {
		global $cldb;
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " LIMIT 1";
		$result_set = $cldb->query($sql);
		$fields = $cldb->fetch_fields($result_set);
		foreach ($fields as $field) {
			static::$db_fields[] = "{$field->name}";
		}
	}
	
	// *********************************************************
	// GETTERS
	
	public function get_id() {
		return $this->id;
	}
	
	public function get_username() {
		return $this->username;
	}
	
	public function get_passcode() {
		return $this->passcode;
	}
	
	public function get_fname() {
		return $this->fname;
	}
	
	public function get_lname() {
		return $this->lname;
	}
	
	public function get_security() {
		return $this->security;
	}
	
	public function get_clearance() {
		return $this->clearance;
	}
	
	// END GETTERS
	// *********************************************************
	
	// *********************************************************
	// SETTERS
	
	public function set_id($id) {
		if (validate_value($id)) {
			$this->id = $id;
		} else {
			return false;
		}
		return true;
	}
	
	public function set_username($un) {
		if (validate_value($un)) {
			$this->username = $un;
		} else {
			return false;
		}
		return true;
	}
	
	public function set_passcode($pc) {
		if (validate_value($pc) && validate_value($this->get_username())) {
			$passcode = password_encrypt($password.$this->get_username(), $this->get_username());
			$this->passcode = $passcode;
		} else {
			return false;
		}
		return true;
	}
	
	public function set_fname($fn) {
		if (validate_value($fn)) {
			$this->fname = $fn;
		} else {
			return false;
		}
		return true;
	}
	
	public function set_lname($ln) {
		if (validate_value($ln)) {
			$this->lname = $ln;
		} else {
			return false;
		}
		return true;
	}
	
	public function set_security($sec) {
		if (validate_value($sec)) {
			$this->security = $sec;
		} else {
			return false;
		}
		return true;
	}
	
	public function set_clearance($clr) {
		if (validate_value($clr)) {
			$this->clearance = $clr;
		} else {
			return false;
		}
		return true;
	}
	
	// END SETTERS
	// *********************************************************

	// *********************************************************
	// General Methods
	
	/** get the full name of user 
	 * 
	 * fname lname
	 * 
	 * e.g. "George Bush"
	 * @return string
	 */
	public function get_name() {
		return $this->fname . " ". $this->lname;
	}
	
	/** get the full name of user
	 * 
	 * lname, fname
	 * 
	 * e.g. "Bush, George"
	 * @return string
	 */
	public function get_reverse_name() {
		return $this->lname . ", " . $this->fname;
	}
	
	/** $id must be an interger
	 * 
	 * @param integer $id size 11
	 * @return object single object
	 */
	public static function find_user_by_id($id=0) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE id = {$id}";
		$sql .= " LIMIT 1";
		$results = self::find_by_sql($sql);
		return array_shift($results);
	}
	
	/** username is unique and will only return one
	 * 
	 * @param string $user
	 * @return object single object
	 */
	public static function find_user_by_username($user="") {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE username = '{$user}'";
		$sql .= " LIMIT 1";
		$results = self::find_by_sql($sql);
		return array_shift($results);
	}
	
	/** return a group of objects to be iterated through
	 * 
	 * @return array of objects
	 */
	public static function find_all_users() {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " ORDER BY lname, fname";
		return self::find_by_sql($sql);
	}
	
	public function generate_Admin() {
		$this->new_Admin_user();
	}
	
	public static function count_Admin() {
		global $cldb;
		$sql  = "SELECT COUNT(*) FROM " . self::$table_name;
		$results = $cldb->query($sql);
		$row = $cldb->fetch_array($results);
		return array_shift($row);
	}
	
	public function count_OOP_Admin() {
		global $cldb;
		$sql  = "SELECT COUNT(*) FROM " . self::$table_name;
		$results = $cldb->query($sql);
		$row = $cldb->fetch_array($results);
		return array_shift($row);
	}
	
	// End General Methods
	// *********************************************************
	
	// *********************************************************
	// Private Methods OOP
	
	private function new_Admin_user() {
		$obj = new self;
		$obj->fname = "Theral";
		$obj->lname = "Jessop";
		$obj->username = "TJAdmin";
		$obj->passcode = password_encrypt("6I123-tutor", $obj->username);
		$obj->security = 0;
		$obj->clearance = 0;
		$obj->save();
	}
}



?>