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

ignore_user_abort(true);

if (isset($_SERVER['PATH_TRANSLATED'])) { $full_path = str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"]); } else { $full_path = str_replace("\\\\", "/", $_SERVER["SCRIPT_FILENAME"]); }
$livehelp_path = '/livehelp/admin/online_refresher.php';
if (strpos($full_path, '/') === false) { $livehelp_path = str_replace("/", "\\", $livehelp_path); }
$install_path = substr($full_path, 0, strpos($full_path, $livehelp_path));

// Update last refresh field so loged in user stays online
$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW() WHERE login_id = '$current_login_id'";
$SQL->miscquery($query_update_refresh);

// Output 1x1 pixel to the admin users_header.php JavaScript page
header('Content-type: image/gif');
readfile($install_path . '/livehelp/include/image_tracker.gif');
?>