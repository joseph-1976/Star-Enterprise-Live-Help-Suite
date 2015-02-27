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

ignore_user_abort(true);

if (!isset($_POST['FROM_LOGIN_ID'])){ $_POST['FROM_LOGIN_ID'] = ""; }
if (!isset($_POST['TO_LOGIN_ID'])){ $_POST['TO_LOGIN_ID'] = ""; }
if (!isset($_POST['MESSAGE'])){ $_POST['MESSAGE'] = ""; }
if (!isset($_POST['RESPONSE'])){ $_POST['RESPONSE'] = ""; }
if (!isset($_POST['COMMAND'])){ $_POST['COMMAND'] = ""; }

$from_login_id = $_POST['FROM_LOGIN_ID'];
$to_login_id = $_POST['TO_LOGIN_ID'];
$message = $_POST['MESSAGE'];
$response = $_POST['RESPONSE'];
$command = $_POST['COMMAND'];

if ($disable_chat_time == 'true') { $chat_time = '0'; } else { $chat_time = '1'; }
if ($disable_chat_username == 'true') { $chat_username = '0'; } else { $chat_username = '1'; }

// Check if the message contains any content else return headers
if ($message == '' && $response == '' && $command == '') { exit(); }

// Get current supporter to send msgs to the supporters login id
if ($to_login_id == "") {
	$query_select_to_login_id = "SELECT active FROM " . $table_prefix . "sessions WHERE login_id = '$from_login_id'";
	$row_to_login_id = $SQL->selectquery($query_select_to_login_id);
	if (is_array($row_to_login_id)) {
		$to_login_id = $row_to_login_id["active"];
	}
	else {
		$to_login_id = 0;
	}
}

$message = nl2br(preg_replace("/(\r\n�\n�\r)/", "\n", trim($message)));
$message = addslashes($message);

if ($message != '') {
	// Send messages from POSTed data
	$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','$from_login_id','$to_login_id',NOW(),'$message','$chat_time','$chat_username','1')";
	$SQL->insertquery($query);
}

// Check if the FROM_LOGIN_ID is NOT an admin user then remove tags else process commands and responses
$query_select_users_online = "SELECT s.login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND u.last_login_id = '$from_login_id'";
$rows_users_online = $SQL->selectall($query_select_users_online);
if(!is_array($rows_users_online)) {
	$message = str_replace("<", "&lt;", $message);
	$message = str_replace(">", "&gt;", $message); 
}
else {

	// Replace URL's with hyperlinks
	$message = preg_replace("/(?:^|\b)(?<!=)(?<!=\")(?<!=\')(?<!>)((((http|https|ftp):\/\/))([\w\.]+)([,:%#&\/?~=\w+\.-]+))(?<!\")(?<!\')(?<!<)(?:\b|$)/is", "<a href=\"$1\"target=\"_blank\" class=\"normlink\">$1</a>", $message);
	$message = preg_replace("/(?<!http:\/\/)(?:^|\b)(?<!=)(?<!=\")(?<!=\')(?<!>)(((www\.))([\w\.]+)([,:%#&\/?~=\w+\.-]+))(?<!\")(?<!\')(?<!<)(?:\b|$)/is", "<a href=\"http://$1\" target=\"_blank\" class=\"normlink\">$1</a>", $message);
	$message = preg_replace("/(?:^|\b)(?<!mailto:)(?<!>)(([\w\.]+))(@)([\w\.]+)(?<!<)\b/i", "<a href=\"mailto:$0\" class=\"normlink\">$0</a>", $message);

	if ($response != '') {
		// Send messages from POSTed response data
		$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','$from_login_id','$to_login_id',NOW(),'$response','$chat_time','$chat_username','1')";
		$SQL->insertquery($query);
	}
	if ($command != '') {
		$query_select_command = "SELECT * FROM " . $table_prefix . "commands WHERE command_id = '$command'";
		$row_command = $SQL->selectquery($query_select_command);
		if (is_array($row_command)) {
			$contents = $row_command['contents'];
			$type = $row_command['type'];
			$send_command = addslashes($contents);
			if ($type == 'PUSH' || $type == 'JAVASCRIPT') {
				// Send PUSH/JAVASCRIPT command from POSTed response data
				$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align,hidden) VALUES('','-1','$to_login_id',NOW(),'$send_command','0','0','1','1')";
				$SQL->insertquery($query);
			}
			elseif ($type == 'LINK' || $type == 'IMAGE') {
				// Send LINK/IMAGE command from POSTed response data
				$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','$from_login_id','$to_login_id',NOW(),'$send_command','0','0','2')";
				$SQL->insertquery($query);
			}
		}
	}
}
header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE BLANK PUBLIC>