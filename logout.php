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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

$language_file = './locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('./locale/en/lang_en.php');
}

ignore_user_abort(true);

session_start();
$from_login_id = $_SESSION['GUEST_LOGIN_ID'];
session_write_close();

$query_select_to_login_id = "SELECT active FROM " . $table_prefix . "sessions WHERE login_id = '$from_login_id'";
$row_to_login_id = $SQL->selectquery($query_select_to_login_id);
if (is_array($row_to_login_id)) {
	$to_login_id = $row_to_login_id["active"];
}
else {
	$to_login_id = 0;
}

if ($to_login_id != '-3') {

	$send_message = addslashes($language['logout_user_message']);

	// Send message to notify user they have logged out
	$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','$from_login_id','$to_login_id',NOW(),'$send_message','0','0','2')";
	$SQL->insertquery($query);
	
	$query_update_user_login = "UPDATE " . $table_prefix . "sessions SET active = '-1' WHERE login_id = '$from_login_id'";
	$SQL->miscquery($query_update_user_login);

}
header('Location: logout_frame.php?' . SID);
?>