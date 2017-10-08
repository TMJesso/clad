<?php
	class Session {
		private $logged_in;
		private $user_id;
		private $name;
		private $security;
		private $clearance;
		public $message;
		public $errors;
		
		function __construct() {
			session_start();
			$this->check_message();
			$this->check_errors();
			$this->check_login();
		}
		// ********************************************
		// GETTERS
		
		public function get_security() {
			return $this->security;
		}
		
		public function get_clearance() {
			return $this->clearance;
		}
		
		public function get_name() {
			return $this->name;
		}
		
		public function get_user_id() {
			return $this->user_id;
		}
		
		// End GETTERS
		// ********************************************
		
		// ********************************************
		// Private Methods OOP
		
		private function check_login() {
			if (isset($_SESSION['user_id'])) {
				$this->user_id = $_SESSION['user_id'];
				if (isset($_SESSION['name'])) {
					$this->name = $_SESSION['name'];
				}
				if (isset($_SESSION['security'])) {
					$this->security = $_SESSION['security'];
				}
				if (isset($_SESSION['clearance'])){
					$this->clearance = $_SESSION['clearance'];
				}
				
				$this->logged_in = true;
			} else {
				unset($this->user_id);
				unset($this->name);
				unset($this->security);
				unset($this->clearance);
				$this->security = 9;
				$this->clearance = 9;
				$this->name = "";
				$this->logged_in = false;
			}
		}

		private function check_message() {
			// is there a message stored in the session?
			if (isset($_SESSION['message'])) {
				// add it as an attribute and erase the stored version
				$this->message = htmlentities($_SESSION['message']);
				unset($_SESSION['message']);
			} else {
				$this->message = "";
			}
		}
		
		private function check_errors() {
			// is there an error stored in the session?
			if (isset($_SESSION["errors"])) {
				// add it as an attribute and erase the stored version
				$this->errors = $this->form_errors($_SESSION["errors"]);
				unset($_SESSION["errors"]);
			} else {
				$this->errors = "";
			}
		}
		
		// End Private Methods OOP
		// ********************************************
		
		// ********************************************
		// Public Methods OOP
		
		public function is_logged_in() {
			return $this->logged_in;
		}
		
		public function message($msg="") {
			if (!empty($msg)) {
				// then this is "set message"
				$_SESSION["message"] = $msg;
			} else {
				// then this is "get message"
				return $this->message;
			}
		}
		
		public function errors($err=array()) {
			if (!empty($err)) {
				// then this is "set error"
				$_SESSION["errors"] = $err;
			} else {
				return $this->errors;
			}
		}

		public function logout() {
			if (isset($this->user_id)) {
				$activity = "User ID: " . $this->user_id;
				$activity .= " :: Security: " . $this->convert_security_to_string($this->security);
				$activity .= " :: Clearance: " . $this->convert_clerance_to_string($this->clearance);
				$activity .= " :: Logged Out";
				Activity::user_log($this->user_id, $activity, $this->convert_clerance_to_string($this->clearance));
			}
			unset($_SESSION['user_id']);
			unset($this->user_id);
			unset($this->name);
			unset($this->security);
			unset($this->clearance);
			unset($this->logged_in);
			$this->logged_in = false;
			$this->clearance = 9;
			$this->security = 9;
		}
		
		/**
		 * This is used to get the clearance string value for users logged in
		 *
		 * it's primary use is for adding to the user_log table;
		 * 
		 * @param integer $sec size 1
		 * @return string
		 */
		public function convert_security_to_string($sec) {
			$sec_value = "";
			switch ($sec) {
				case 0:
					$sec_value= "Tier 0";
					break;
				case 1:
					$sec_value= "Tier 1";
					break;
				case 2:
					$sec_value= "Tier 2";
					break;
				case 3:
					$sec_value= "Tier 3";
					break;
				case 4:
					$sec_value= "Tier 4";
					break;
				case 5:
					$sec_value= "Tier 5";
					break;
				case 6:
					$sec_value= "Tier 6";
					break;
				case 7:
					$sec_value= "Tier 7";
					break;
				case 8:
					$sec_value= "Tier 8";
					break;
				case 9:
					$sec_value= "Tier 9";
					break;
				default :
					$sec_value = "Tier 9";
					break;
			}
			return $sec_value;
		}
		
		/**
		 * This is used to get the clearance string value for users logged in
		 *
		 * it's primary use is for adding to the user_log table;
		 *
		 * @param integer $sheblon size 1
		 * @return string
		 */
		public function convert_clerance_to_string($clr) {
			switch ($clr) {
				case 0:
					$menu = "Owner";
					break;
				case 1:
					$menu = "Board";
					break;
				case 2:
					$menu = "President";
					break;
				case 3:
					$menu = "Finance";
					break;
				case 4:
					$menu = "Marketing";
					break;
				case 5:
					$menu = "Human Resources";
					break;
				case 6:
					$menu = "Accounting";
					break;
				case 7:
					$menu = "IT";
					break;
				case 8:
					$menu = "Department";
					break;
				case 9:
					$menu = "Individual";
					break;
				default :
					$menu = "Public";
					break;
			}
			return $menu;
		}
		
		// End Public Methods OOP
		// ********************************************
		
		
		// ********************************************
		// General functions non-oop
		function logged_in() {
			return isset($this->user_id);
		}
		
		function confirm_logged_in() {
			if (!logged_in()) {
				redirect_to("login.php");
			}
		}
		
		// End General Functions non-oop
		// ********************************************
		
		
	}
	$session = new Session();
	$message = $session->message();
	$errors = $session->errors();

?>
