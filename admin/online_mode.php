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

if (!isset($_GET['LOGIN_ID'])){ $_GET['LOGIN_ID'] = ""; }

$login_id = $_GET['LOGIN_ID'];

// Update active field of admin session to enter online staff mode ie. -2
$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '-2' WHERE login_id = '$login_id'";
$SQL->miscquery($query_update_status);

header('Location: status_controls.php?STATUS=online&' . SID);
?>