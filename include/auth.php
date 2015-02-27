<?php
/*
Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007


You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/
session_start();
$current_session = session_id();
$current_login_id = $_SESSION['OPERATOR_LOGIN_ID'];
$current_win32_client = $_SESSION['WIN32_CLIENT'];
session_write_close();

$query_select = "SELECT user_id, username, department, access_level FROM " . $table_prefix . "users WHERE last_login_id = '$current_login_id'";
$row = $SQL->selectquery($query_select);
if (is_array($row)) {
	$current_user_id = $row['user_id'];
	$current_username = $row['username'];
	$current_department = $row['department'];
	$current_access_level = $row['access_level'];
}
else {
		header('Location: /livehelp/include/auth_error.php?' . SID);
		exit;
}

if ($current_login_id == '' || $current_username == '' || $current_access_level == '') {
	if ($current_win32_client == 'true') {
		header('HTTP/1.0 403 Forbidden');
		exit;
	}
	elseif ($current_win32_client == 'false')  {
		header('Location: /livehelp/include/auth_error.php?' . SID);
		exit;
	}
}

?>