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

$initiate_session = $_GET['SESSION'];
$current_record = $_GET['RECORD'];

// Update active field of user to the login id of the supporter that initated support
$query_update_status = "UPDATE " . $table_prefix . "requests SET request_flag = '$current_login_id' WHERE session_id = '$initiate_session'";
$SQL->miscquery($query_update_status);

header('Location: visitors_index.php?' . SID . '&RECORD=' . $current_record);
?>