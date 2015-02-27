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

if (isset($_SERVER['PATH_TRANSLATED'])) { $full_path = str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"]); } else { $full_path = str_replace("\\\\", "/", $_SERVER["SCRIPT_FILENAME"]); }
$livehelp_path = '/livehelp/typing_status.php';
if (strpos($full_path, '/') === false) { $livehelp_path = str_replace("/", "\\", $livehelp_path); }
$install_path = substr($full_path, 0, strpos($full_path, $livehelp_path));

ignore_user_abort(true);

$login_id = $_GET['LOGIN_ID'];
$status = $_GET['STATUS'];

if ($status == 'true'){
	$status = 1;
}
else {
	$status = 0;
}

// Update the typing status of the specified login id
$query_update_status = "UPDATE " . $table_prefix . "sessions SET typing_status = $status WHERE login_id = '$login_id'";
$SQL->miscquery($query_update_status);

header('Content-type: image/gif');
readfile($install_path . '/livehelp/include/image_tracker.gif');
?>