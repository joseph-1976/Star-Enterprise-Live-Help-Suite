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
include('../include/config_database.php');
include('../include/class.mysql.php');
include('../include/config.php');

if (!isset($_POST['USER_NAME'])){ $_POST['USER_NAME'] = ""; }
if (!isset($_POST['PASSWORD'])){ $_POST['PASSWORD'] = ""; }

$username = $_POST['USER_NAME'];
$password = $_POST['PASSWORD'];

session_start();
$current_login_id = $_SESSION['OPERATOR_LOGIN_ID'];
$current_win32_client = $_SESSION['WIN32_CLIENT'];
session_write_close();

// Update session table active field to -1 Disconnected Flag status
if ($current_win32_client == 'true') {
	
	$query_select_login_id = "SELECT last_login_id FROM " . $table_prefix . "users WHERE username = '$username' AND password = '$password'";
	$row = $SQL->selectquery($query_select_login_id);
	if (is_array($query_select_login_id)) {
		$current_login_id = $row['last_login_id'];
	}
	
}

// Else If NOT Windows Client Update -1 Disconnection Flag with session details
$query_update_user_login = "UPDATE " . $table_prefix . "sessions SET active = '-1' WHERE login_id = '$current_login_id'";
$SQL->miscquery($query_update_user_login);

header('Location: index_popup.php');
?>