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
include('../../include/config_database.php');
include('../../include/class.mysql.php');
include('../../include/config.php');
include('../../include/functions.php');

if (!isset($_POST['USER_NAME'])){ $_POST['USER_NAME'] = ""; }
if (!isset($_POST['PASSWORD'])){ $_POST['PASSWORD'] = ""; }
if (!isset($_POST['USER'])){ $_POST['USER'] = ""; }
if (!isset($_POST['LOGIN_ID'])){ $_POST['LOGIN_ID'] = ""; }

$current_username = $_POST['USER_NAME'];
$current_md5_password = $_POST['PASSWORD'];
$guest_username = stripslashes($_POST['USER']);
$guest_login_id = $_POST['LOGIN_ID'];
$current_server = '';
$current_typing = '';
$current_active = '';

// Get username, password from database and authorise the login details
$query_select = "SELECT last_login_id,department FROM " . $table_prefix . "users WHERE username = '$current_username' AND password = '$current_md5_password'";
$row = $SQL->selectquery($query_select);
if (!is_array($row)) {
	if (strpos(php_sapi_name(), 'cgi') === false ) { header('HTTP/1.0 403 Forbidden'); } else { header('Status: 403 Forbidden'); }
	exit;
}

$current_login_id = $row['last_login_id'];
$current_department = $row['department'];

$query_select_chat_details = "SELECT server,typing_status,active FROM " . $table_prefix . "sessions WHERE login_id = '$guest_login_id'";
$row_details = $SQL->selectquery($query_select_chat_details);
if (is_array($row_details)) {
	$current_server = $row_details['server'];
	$current_typing = $row_details['typing_status'];
	$current_active = $row_details['active'];
}

if ($current_active > 0) { 
	// Update the active flag of the guest user to the ID of their supporter and update the support_user to the username of their supporter
	$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '$current_login_id', support_username = '$current_username' WHERE login_id = '$guest_login_id'";
	$SQL->miscquery($query_update_status);
}

// Update the flags of the messages so that user can now read all previous messages from the previous sessions.
$query_update_sent_messages = "UPDATE " . $table_prefix . "messages SET to_login_id = '$current_login_id' WHERE from_login_id = '$guest_login_id'";
$SQL->miscquery($query_update_sent_messages);
$query_update_recv_messages = "UPDATE " . $table_prefix . "messages SET from_login_id = '$current_login_id' WHERE to_login_id = '$guest_login_id' AND from_login_id > 0";
$SQL->miscquery($query_update_recv_messages);

header('Content-type: text/xml; charset=utf-8');
echo('<?xml version="1.0" encoding="utf-8"?>' . "\n");
?>
<Messages xmlns="urn:LiveHelp" Typing="<?php echo($current_typing); ?>" Server="<?php echo($current_server); ?>">
<?php

$query_select_msgs = "SELECT message_id,from_login_id,to_login_id,DATE_FORMAT(DATE_ADD(message_datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE),'%H:%i:%s') AS message_time,message,src_flag,dest_flag,display_time,display_username,display_align,hidden,server,username AS from_username FROM " . $table_prefix . "messages," . $table_prefix . "sessions WHERE from_login_id = login_id AND (from_login_id = '$guest_login_id' OR to_login_id = '$guest_login_id') ORDER BY message_datetime";
$rows_msgs = $SQL->selectall($query_select_msgs);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {

			$support_username = $row['from_username'];		
			$message = $row['message'];
			$message = str_replace("\r\n", "\\r\\n", $message); 
			$displayMessage = htmlspecialchars($message);

			$align = 'left';
			if ($row['display_align'] == '1') { $align = 'left'; } elseif ($row['display_align'] == '2') { $align = 'center'; } elseif ($row['display_align'] == '3') { $align = 'right'; }
			
			// Outputs sent message
			if(($row['from_login_id'] == "$current_login_id")){
?>
<Message Visible="<?php echo($row['hidden']); ?>" Align="<?php echo($align); ?>" Username="<?php if($row['display_username'] == '1') { echo($support_username); } ?>"><?php echo($displayMessage); ?></Message>
<?php
				// Updates displayed flag
				$query_update_displayed = "UPDATE " . $table_prefix . "messages SET src_flag=1 WHERE message_id = '" . $row['message_id'] . "'";
				$SQL->miscquery($query_update_displayed);
			}
			// Outputs received message
			if(($row['to_login_id'] == "$current_login_id")){	
?>
<Message Visible="<?php echo($row['hidden']); ?>" Align="<?php echo($align); ?>" Username="<?php if($row['display_username'] == '1') { echo($support_username); } ?>"><?php echo($displayMessage); ?></Message>
<?php
				// Updates displayed flag
				$query_update_displayed = "UPDATE " . $table_prefix . "messages SET dest_flag=1 WHERE message_id = '" . $row['message_id'] . "'";
				$SQL->miscquery($query_update_displayed);
			}
		}
	}
}
?>
</Messages>