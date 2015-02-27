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

$username = $_POST['USER_NAME'];
$md5_password = $_POST['PASSWORD'];
$popup_timeout = "20";
	
// Get username, password and email from database and authorise the login details
$query_select = "SELECT first_name,last_name,last_login_id,department FROM " . $table_prefix . "users WHERE username = '$username' AND password = '$md5_password'";
$row = $SQL->selectquery($query_select);
if (!is_array($row)) {
	if (strpos(php_sapi_name(), 'cgi') === false ) { header('HTTP/1.0 403 Forbidden'); } else { header('Status: 403 Forbidden'); }
	exit;
}
else {
	$current_login_id = $row['last_login_id'];
	$current_department = $row['department'];
	$current_first_name = $row['first_name'];
	$current_last_name = $row['last_name'];
	
	// Update the username, password and make no mode changes
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW() WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}

header('Content-type: text/xml; charset=utf-8');
echo('<?xml version="1.0" encoding="utf-8"?>' . "\n");
?>
<Login xmlns="urn:LiveHelp" ID="<?php echo($current_login_id); ?>" Name="<?php echo($current_first_name . " " . $current_last_name); ?>" Pending="<?php echo(pendingUsersPopup($popup_timeout)); ?>" Browsing="<?php echo(browsingUsersPopup($popup_timeout)); ?>" Transferred="<?php echo(transferredUsersPopup($popup_timeout)); ?>"/>
