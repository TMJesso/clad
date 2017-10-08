<?php
require_once(LIB_PATH.DS.'database.php');
class Activity extends ChangeObject {
	protected static $table_name = "user_log";
//	protected static $db_fields = array('id', 'user_id', 'user_type', 'date_stamp', 'activity');
	
	protected $id;
	protected $user_id;
	protected $user_type;
	protected $date_stamp;
	protected $activity;
	
	function __construct() {
		global $db;
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " LIMIT 1";
		$result_set = $db->query($sql);
		$fields = $db->fetch_fields($result_set);
		foreach ($fields as $field) {
			static::$db_fields[] = "{$field->name}";
		}
	
	}
	
	// ********************************************
	// GETTERS
	
	public function get_id(){
		return $this->id;
	}
	
	public function get_user_id() {
		return $this->user_id;
	}
	
	public function get_user_type() {
		return $this->user_type;
	}
	
	public function get_date_stamp() {
		return $this->date_stamp;
	}
	
	public function get_activity() {
		return $this->activity;
	}
	
	// End GETTERS
	// ********************************************
	
	// ********************************************
	// Public Methods OOP
	
	public static function find_by_user_id($id) {
		$sql  = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE user_id = {$id}";
	}
	
	public static function user_log($id, $activity, $user_type) {
		$obj = new self;
		$obj->user_id = $id;
		$obj->activity = $activity;
		$obj->user_type = $user_type;
		$obj->date_stamp = strftime("%Y-%m-%d %H:%M:%S", strtotime($obj->now(), time()));
		$obj->save();
	}
	
	// End Public Methods OOP
	// ********************************************
	
	// ********************************************
	// Private Methods OOP
	
	private function now() {
		return date($this->now_format());
	}
	
	private function now_format() {
		//     "m-d-Y H:i:s"
		//m - month
		//d - day
		//Y - year
		//H - 24 hour
		//i - minutes
		//s - seconds
		return "Y-m-d H:i:s";
	}
	
	// End Private Methods OOP
	// ********************************************
	
}
	
	
	