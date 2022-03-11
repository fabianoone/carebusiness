<?php 

/** DB name */
define('DB_NAME', 'carebusiness');

/** DB user */
define('DB_USER', 'admin');

/** DB Password */
define('DB_PASSWORD', 'admin123');

define('DB_HOST', 'localhost');

// Error reporting 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function open_database() {
	try {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		return $conn;
	} catch (Exception $e) {
		echo $e->getMessage();
		return null;
	}
}

function close_database($conn) {
	try {
		mysqli_close($conn);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

?>