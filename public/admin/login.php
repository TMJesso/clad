<?php
require_once "../../includes/path.php";
if ($session->is_logged_in()) {
	redirect_to("index.php");
} else {
	$session->logout();
}

?>
<!DOCTYPE html>
<html>
	<head>
		
	</head>
	<body>
		
	</body>
</html>