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
if (!isset($_POST['ACTION'])){ $_POST['ACTION'] = ""; }
if (!isset($_POST['LOGIN_ID'])){ $_POST['LOGIN_ID'] = ""; }
$current_username = $_POST['USER_NAME'];
$current_md5_password = $_POST['PASSWORD'];
$action = $_POST['ACTION'];
$login_id = $_POST['LOGIN_ID'];
$current_login_id = '';

// Get username, password from database and authorise the login details
$query_select = "SELECT last_login_id,department FROM " . $table_prefix . "users WHERE username = '$current_username' AND password = '$current_md5_password'";
$row = $SQL->selectquery($query_select);
if (!is_array($row)) {
	if (strpos(php_sapi_name(), 'cgi') === false ) { header('HTTP/1.0 403 Forbidden'); } else { header('Status: 403 Forbidden'); }
	exit;
}
else {
	$current_login_id = $row['last_login_id'];
	$current_department = $row['department'];
	
	// Update the username, password and make no mode changes
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW() WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}

// Check for actions and process
if ($action == 'Accept' && $login_id != '0') {

	// Check if already assigned to a Support operator
	$query_select_active = "SELECT active FROM " . $table_prefix . "sessions WHERE login_id = '$login_id'";
	$row_select_active = $SQL->selectquery($query_select_active);
	if (is_array($row_select_active)) {
		if ($row_select_active['active'] == '0' || $row_select_active['active'] == '-2') {

			// Select supporters Full name
			$query_select_supporters_details = "SELECT first_name,last_name FROM " . $table_prefix . "users WHERE last_login_id = '$current_login_id'";
			$row_select_supporters_details = $SQL->selectquery($query_select_supporters_details);
			if (is_array($row_select_supporters_details)) {
				$first_name = $row_select_supporters_details['first_name'];
				$last_name = $row_select_supporters_details['last_name'];
				
				$send_message = "You are now chatting with $first_name $last_name.";
				
				if ($first_name != '' || $last_name != '') {
					// Send message to notify user they are out of Pending status
					$send_message = "<link href=\'/livehelp/styles/styles.php\' rel=\'stylesheet\' type=\'text/css\'>You are now chatting with $first_name $last_name.<script language=\'JavaScript\'>top.document.message_form.MESSAGE.disabled = false;</script>";
					$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','-1','$login_id',NOW(),'$send_message','0','0','2')";
					$SQL->insertquery($query);					
				}
			}

			// Update the active flag of the guest user to the ID of their supporter and update the support_user to the username of their supporter
			$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '$current_login_id', support_username = '$current_username' WHERE login_id = '$login_id'";
			$SQL->miscquery($query_update_status);

			$query_update_messages = "UPDATE " . $table_prefix . "messages SET to_login_id = '$current_login_id' WHERE from_login_id = '$login_id'";
			$SQL->miscquery($query_update_messages);
	
		}
	}
}
elseif ($action == 'Close') {
	// Close the Chat
	$send_message = addslashes($language['closed_user_message']) . "  " . addslashes($language['improve_service']) . " <a href=\'logout.php\' target=\'_top\' class=\'normlink\'>" . addslashes($language['rate_operator']) . "</a>";
	$send_message_close = "<script language=\'JavaScript\'>top.document.message_form.MESSAGE.disabled = true;</script>";

	// Send message to notify user they are being ignored or declined and send JavaScript to disable input
	$query = "INSERT INTO " . $table_prefix . "messages (message_id,from_login_id,to_login_id,message_datetime,message,display_time,display_username,display_align) VALUES('','-1','$current_login_id',NOW(),'$send_message','0','0','2'), ('','-1','$current_login_id',NOW(),'$send_message_close','0','0','2')";
	$SQL->insertquery($query);

	// Update active of user to -3 to remove from users panel
	$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '-3' WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_status);
	
}
elseif ($action == 'Hidden') {
	// Update the username, password and change to hidden mode
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW(), active = '-1' WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}
elseif ($action == 'Online') {
	// Update the username, password and change to online mode
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW(), active = '-2' WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}
elseif ($action == 'Away') {
	// Update the username, password and change to be right back mode
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW(), active = '-3' WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}
elseif ($action == 'BRB') {
	// Update the username, password and change to be right back mode
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW(), active = '-4' WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}


$query_select = "SELECT s.datetime, u.department FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND login_id = '$current_login_id'";
$rows = $SQL->selectall($query_select);
if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
		$login_datetime = $row['datetime'];
		$department = $row['department'];
		}
	}
}

header('Content-type: text/xml; charset=utf-8');
echo('<?xml version="1.0" encoding="utf-8"?>' . "\n");
?>
<UserList xmlns="urn:LiveHelp">
	<Staff>
<?php
//ONLINE ADMIN USERS QUERY
$query_select = "SELECT s.username,s.login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
			
			// Count the current Staff's sent NEW messages to current staff member
			$query_select_messages = "SELECT count(message_id) FROM " . $table_prefix . "messages WHERE from_login_id = '$login_id' AND to_login_id ='$current_login_id' AND dest_flag = '0'";
			$row_query = $SQL->selectquery($query_select_messages);
			if (is_array($row_query)) {
				$count_msgs = $row_query['count(message_id)'];
			}
			else {
				$count_msgs = '0';
			}
?>
		<User ID='<?php echo($login_id); ?>' Messages='<?php echo($count_msgs); ?>'><?php echo($username); ?></User>
<?php
		}
	}
}
?>
	</Staff>
	<Online>
  <?php
//ONLINE GUEST USERS QUERY
$query_select = "SELECT s.username,s.login_id FROM " . $table_prefix . "sessions AS s WHERE s.support_username = '$current_username' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) < '" . $connection_timeout . "' AND s.active > 0 ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
		<User ID='<?php echo($login_id); ?>'><?php echo($username); ?></User>
<?php
		}
	}
}
?>
	</Online>
	<Pending>
<?php
//PENDING USERS QUERY displays pending users not logged in on users users table depending on department settings
if ($departments == 'true') {
 	$departments_sql = departmentsSQL($department);
	$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = 0 AND $departments_sql ORDER BY s.username";
}
elseif ($departments == 'false') {
	$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = 0 ORDER BY s.username";
}
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?>
		<User ID='<?php echo($login_id); ?>'><?php echo($username); ?></User>
<?php
		}
	}
}
?>
	</Pending>
	<Transferred>
<?php
//TRANFERRED USERS QUERY displays transferred users not logged in on users users table depending on department settings
if ($departments == 'true') {
 	$departments_sql = departmentsSQL($department);
	$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = -3 AND $departments_sql ORDER BY s.username";
}
elseif ($departments == 'false') {
	$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = -3 ORDER BY s.username";
}
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
		<User ID='<?php echo($login_id); ?>'><?php echo($username); ?></User>
<?php
		}
	}
}
?>
	</Transferred>
	<Offline>
<?php
//OFFLINE USERS QUERY
$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE s.datetime > '$login_datetime' AND (active = '$current_login_id' OR active = '0' OR active = '-1') AND u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) > '" . $connection_timeout . "' ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
		<User ID='<?php echo($login_id); ?>'><?php echo($username); ?></User>
<?php
		}
	}
}
?>
	</Offline>
</UserList>