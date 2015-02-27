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
include('../../include/auth.php');

if ($current_access_level > 1){
	header('Location: ../../include/access_denied.php' . SID);
	exit();
}

$current_date = date('D M j G:i:s Y');
$dump_buffer = '';

$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# star develop LiveHelp SQL dump\n";
$dump_buffer .= "# Created: $current_date\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "commands table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_commands = "SELECT * FROM " . $table_prefix . "commands";
$rows_select_commands = $SQL->selectall($query_select_commands);
if (is_array($rows_select_commands)) {
	foreach ($rows_select_commands as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "commands` VALUES ('" . $row['command_id'] . "',  '" . $row['type'] . "',  '" . $row['description'] . "',  '" . $row['contents'] . "'" . ' );' . "\n";
		}
	}
}

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "messages table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_messages = "SELECT * FROM " . $table_prefix . "messages";
$rows_select_messages = $SQL->selectall($query_select_messages);
if (is_array($rows_select_messages)) {
	foreach ($rows_select_messages as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "messages` VALUES ('" . $row['message_id'] . "',  '" . $row['from_login_id'] . "',  '" . $row['to_login_id'] . "',  '" . $row['message_datetime'] . "',  '" . $row['message'] . "',  '" . $row['dest_flag'] . "',  '" . $row['src_flag'] . "',  '" . $row['display_time'] . "',  '" . $row['display_username'] . "',  '" . $row['display_align'] . "',  '" . $row['hidden'] . "'" . ' );' . "\n";
		}
	}
}

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "requests table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_requests = "SELECT * FROM " . $table_prefix . "requests";
$rows_select_requests = $SQL->selectall($query_select_requests);
if (is_array($rows_select_requests)) {
	foreach ($rows_select_requests as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "requests` VALUES ('" . $row['request_id'] . "',  '" . $row['session_id'] . "',  '" . $row['ip_address'] . "',  '" . $row['user_agent'] . "',  '" . $row['first_request'] . "',  '" . $row['last_request'] . "',  '" . $row['last_refresh'] . "',  '" . $row['current_page'] . "',  '" . $row['current_page_title'] . "',  '" . $row['referrer'] . "',  '" . $row['page_path'] . "',  '" . $row['request_flag'] . "',  '" . $row['status'] . "'" . ' );' . "\n";
		}
	}
}

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "responses table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_responses = "SELECT * FROM " . $table_prefix . "responses";
$rows_select_responses = $SQL->selectall($query_select_responses);
if (is_array($rows_select_responses)) {
	foreach ($rows_select_responses as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "responses` VALUES ('" . $row['response_id'] . "',  '" . $row['contents'] . "'" . ' );' . "\n";
		}
	}
}

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "sessions table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_sessions = "SELECT * FROM " . $table_prefix . "sessions";
$rows_select_sessions = $SQL->selectall($query_select_sessions);
if (is_array($rows_select_sessions)) {
	foreach ($rows_select_sessions as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "sessions` VALUES ('" . $row['login_id'] . "',  '" . $row['session_id'] . "',  '" . $row['username'] . "',  '" . $row['datetime'] . "',  '" . $row['email'] .  "',  '" . $row['ip_address'] . "',  '" . $row['server'] . "',  '" . $row['support_department' ]. "',  '" . $row['support_username'] . "',  '" . $row['rating'] . "',  '" . $row['typing_status'] . "',  '" . $row['support_typing_status'] . "',  '" . $row['active'] . "',  '" . $row['transfer_id'] . "',  '" . $row['timeout'] . "',  '" . $row['last_refresh'] . "'" . ' );' . "\n";
		}
	}
}

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "settings table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_settings = "SELECT * FROM " . $table_prefix . "settings";
$rows_select_settings = $SQL->selectall($query_select_settings);
if (is_array($rows_select_settings)) {
	foreach ($rows_select_settings as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "settings` VALUES ('" . $row['setting_id'] . "',  '" . $row['setting_name'] . "',  '" . $row['setting_value'] . "'" . ' );' . "\n";
		}
	}
}

$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "statistics table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_statistics = "SELECT * FROM " . $table_prefix . "statistics";
$rows_select_statistics = $SQL->selectall($query_select_statistics);
if (is_array($rows_select_statistics)) {
	foreach ($rows_select_statistics as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "statistics` VALUES ('" . $row['statistics_id'] . "',  '" . $row['login_id'] . "',  '" . $row['user_agent'] . "',  '" . $row['resolution'] . "',  '" . $row['hostname'] . "',  '" . $row['referer_url'] . "'" . ' );' . "\n";
		}
	}
}


$dump_buffer .= "\n";
$dump_buffer .= "##################################\n";
$dump_buffer .= "#\n";
$dump_buffer .= "# $table_prefix" . "users table data dump\n";
$dump_buffer .= "#\n";
$dump_buffer .= "##################################\n";

$query_select_users = "SELECT * FROM " . $table_prefix . "users";
$rows_select_users = $SQL->selectall($query_select_users);
if (is_array($rows_select_users)) {
	foreach ($rows_select_users as $row) {
		if (is_array($row)) {
		$dump_buffer .= "INSERT INTO `" . $table_prefix . "users` VALUES ('" . $row["user_id"] . "',  '" . $row["username"] . "',  '" . $row["password"] . "',  '" . $row["first_name"] . "',  '" . $row["last_name"] . "',  '" . $row["email"] . "',  '" . $row["department"] . "',  '" . $row["last_login_id"] . "',  '" . $row["account_status"] . "',  '" . $row["access_level"] . "'" . ' );' . "\n";
		}
	}
}

if ($_GET['SQL_DUMP'] == "true") {
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $database_name . '_dump.sql"');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Pragma: no-cache');
	echo $dump_buffer;
}
?>
