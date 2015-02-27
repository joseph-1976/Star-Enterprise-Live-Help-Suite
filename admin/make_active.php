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
include('../include/auth.php');

$language_file = '../locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('../locale/en/lang_en.php');
}

ignore_user_abort(true);

if (!isset($_GET['MULTILOAD'])){ $_GET['MULTILOAD'] = ""; }

$login_id = $_GET['LOGIN_ID'];
$support_login_id = $_GET['SLOGIN_ID'];
$multiload = $_GET['MULTILOAD'];

// Check if already assigned to a Support operator
$query_select_active = "SELECT username,active FROM " . $table_prefix . "sessions WHERE login_id = '$login_id'";
$row_select_active = $SQL->selectquery($query_select_active);
if (is_array($row_select_active)) {
	// If the site visitor is Pending 0 or Transferred -2 Then assign an operator, else do nothing.
	if ($row_select_active['active'] == '0' || $row_select_active['active'] == '-2') {

		$username = $row_select_active['username'];
		$send_message = "<link href=\'/livehelp/styles/styles.php\' rel=\'stylesheet\' type=\'text/css\'><script language=\'JavaScript\'>if (top.document.message_form) { top.document.message_form.MESSAGE.disabled = false; }</script>";

		// Select supporters Full name
		$query_select_supporters_details = "SELECT first_name,last_name FROM " . $table_prefix . "users WHERE last_login_id = '$support_login_id'";
		$row_select_supporters_details = $SQL->selectquery($query_select_supporters_details);
		if (is_array($row_select_supporters_details)) {
			$first_name = $row_select_supporters_details['first_name'];
			$last_name = $row_select_supporters_details['last_name'];
			
			if (!($first_name == '' || $last_name == '')) {
				// Send message to notify user they are out of Pending status
				$send_message .= addslashes($language['now_chatting_with'] . " $first_name $last_name");
			}
		}

		$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','-1','$login_id',NOW(),'$send_message','0','0','2')";
		$SQL->insertquery($query);

		// Update the active flag of the guest user to the ID of their supporter and update the support_user to the username of their supporter
		$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '$support_login_id', support_username = '$current_username' WHERE login_id = '$login_id'";
		$SQL->miscquery($query_update_status);

		$query_update_messages = "UPDATE " . $table_prefix . "messages SET to_login_id = '$support_login_id' WHERE from_login_id = '$login_id'";
		$SQL->miscquery($query_update_messages);
		
		if ($multiload == 'true') {
			header('Location: users.php?MULTILOAD=true&USERNAME=' . $username . '&LOGIN_ID=' . $login_id . '&' . SID);
			exit();
		}

	}
}

header('Location: users.php?' . SID);
?>