<?php
	$errors = array();
	
	// General Purpose Function
	// callable from anywhere in the application
	
	function attempt_login($username, $password, $found_user) {
		if ($found_user) {
			// found user, now check password
			if (password_verify($password.$username, $found_user->passcode)) {
				// password matches
				return $found_user;
			} else {
				// password does not match
				return false;
			}
		} else {
			// user not found
			return false;
		}
	}
	
	function datetime_to_text($datetime="") {
		$unixdatetime = strtotime($datetime);
		return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
	}
	
	function generate_salt($length) {
		// Not 100% unique, not 100% random, but good enough for a salt
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));
		
		// Valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);
		
		// But not '+' which is valid in base64 encoding
		$modified_base64_string = str_replace('+', '.', $base64_string);
		
		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);
		
		
		return $salt;
	}
	
	function get_states() {
		$statenames = array(
				'AL'=>'ALABAMA',
				'AK'=>'ALASKA',
				'AZ'=>'ARIZONA',
				'AR'=>'ARKANSAS',
				'CA'=>'CALIFORNIA',
				'CO'=>'COLORADO',
				'CT'=>'CONNECTICUT',
				'DE'=>'DELAWARE',
				'FL'=>'FLORIDA',
				'GA'=>'GEORGIA',
				'GU'=>'GUAM GU',
				'HI'=>'HAWAII',
				'ID'=>'IDAHO',
				'IL'=>'ILLINOIS',
				'IN'=>'INDIANA',
				'IA'=>'IOWA',
				'KS'=>'KANSAS',
				'KY'=>'KENTUCKY',
				'LA'=>'LOUISIANA',
				'ME'=>'MAINE',
				'MD'=>'MARYLAND',
						'MI'=>'MICHIGAN',
				'MN'=>'MINNESOTA',
				'MS'=>'MISSISSIPPI',
				'MO'=>'MISSOURI',
				'MT'=>'MONTANA',
				'NE'=>'NEBRASKA',
				'NV'=>'NEVADA',
				'NH'=>'NEW HAMPSHIRE',
				'NJ'=>'NEW JERSEY',
				'NM'=>'NEW MEXICO',
				'NY'=>'NEW YORK',
				'NC'=>'NORTH CAROLINA',
				'ND'=>'NORTH DAKOTA',
				'OH'=>'OHIO',
				'OK'=>'OKLAHOMA',
				'OR'=>'OREGON',
				'PA'=>'PENNSYLVANIA',
				'RI'=>'RHODE ISLAND',
				'SC'=>'SOUTH CAROLINA',
				'SD'=>'SOUTH DAKOTA',
				'TN'=>'TENNESSEE',
				'TX'=>'TEXAS',
				'UT'=>'UTAH',
				'VT'=>'VERMONT',
				'VA'=>'VIRGINIA',
				'WA'=>'WASHINGTON',
				'WV'=>'WEST VIRGINIA',
				'WI'=>'WISCONSIN',
				'WY'=>'WYOMING',
		);
		return $statenames;
	}

	function include_layout_template($template="") {
		include(LIB_LAYOUT.$template);
	}
	
	function log_action($action, $message="") {
		$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
		$new = file_exists($logfile) ? false : true;
		if ($handle = fopen($logfile, 'a')) { // append
			$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
			$content = "{$timestamp} | {$action}: {$message}\n";
			fwrite($handle, $content);
			fclose($handle);
			if ($new) { chmod($logfile, 0755); }
		} else {
			echo "Could not open log file for writing.";
		}
	}
	
	function output_message($message="") {
		if (!empty($message)) {
			return "<br/><div class=\"success callout text-center\"><h4>{$message}</h4></div>";
		} else {
			return "";
		}
	}
	
	function output_errors($errors="") {
		if (!empty($errors)) {
			return "<br/><div class=\"alert callout text-center\"><h4>{$errors}</h4></div>";
		} else {
			return "";
		}
	}
	
	function password_check($password, $existing_hash) {
		// existing hash contains format and salt at start
		//$hash = crypt($password, $existing_hash);
		//if ($hash === $existing_hash) {
			//return true;
		//} else {
			//return false;
		//}
		return password_verify($password, $existing_hash);
		
	}
	
	function password_encrypt($password, $username) {
		//$hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
		//$salt_length = 22; 					// Blowfish salts should be 22-characters or more
		//$salt = generate_salt($salt_length);
		//$format_and_salt = $hash_format . $salt;
		//$hash = crypt($password.$username, $format_and_salt);
		$hash = password_hash($password.$username, PASSWORD_BCRYPT);
		return $hash;
	}
	
	function redirect_to($location = null) {
		if ($location != null) {
			header("Location: {$location}");
			exit;
		}
	}
	
	function strip_zeros_from_date( $marked_string = "") {
		// first remove the marked zeros
		$no_zeros = str_replace('*-', '', $marked_string);
		// then remove any remaining marks
		$cleaned_string = str_replace('*', '', $no_zeros);
		return $cleaned_string;
	}
	
	function validate_value($value) {
		return ((isset($value) && $value !== "") || is_numeric($value));
	}
	

?>
