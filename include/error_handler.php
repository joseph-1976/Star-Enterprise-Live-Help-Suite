<?php 
error_reporting(0); 

// User defined error handling function 
function userErrorHandler ($errno, $errmsg, $filename, $linenum, $vars) { 

	// Define an assoc array of error string 
	// in reality the only entries we should 
	// consider are 2,8,256,512 and 1024 
	$errortype = array ( 
		1   =>  "Error", 
		2   =>  "Warning", 
		4   =>  "Parsing Error", 
		8   =>  "Notice", 
		16  =>  "Core Error", 
		32  =>  "Core Warning", 
		64  =>  "Compile Error", 
		128 =>  "Compile Warning", 
		256 =>  "User Error", 
		512 =>  "User Warning", 
		1024=>  "User Notice" 
		); 

	// Set of errors for which a var trace will be saved 
	$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE); 
	$date = date("Y-m-d H:i:s"); 
	$err = "$date PHP " . $errortype[$errno] . ": $errmsg $filename at line $linenum\n"; 

	if (isset($_SERVER['PATH_TRANSLATED']) && $_SERVER['PATH_TRANSLATED'] != '') { $env_path = $_SERVER["PATH_TRANSLATED"]; } else { $env_path = $_SERVER["SCRIPT_FILENAME"]; }
	$full_path = str_replace("\\\\", "/", $env_path);
	$livehelp_path = $_SERVER['PHP_SELF'];
	if (strpos($full_path, '/') === false) { $livehelp_path = str_replace("\\", "/", $livehelp_path); }
	$pos = strpos($full_path, $livehelp_path);
	if ($pos === false) {
		$install_path = $full_path;
	}
	else {
		$install_path = substr($full_path, 0, $pos);
	}

	// Save to the error log, and e-mail me if there is a critical user error 
	$logfile_path = $install_path . '/livehelp/log/ERRORLOG.TXT';
	if (is_writable($logfile_path)) { error_log($err, 3, $logfile_path); }

} 

$error_handler = set_error_handler("userErrorHandler"); 

?>