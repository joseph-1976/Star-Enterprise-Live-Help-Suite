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

session_start();
$login_id = $_SESSION['GUEST_LOGIN_ID'];
session_write_close();

$rating = $_POST['RATING'];

$query_update_rating = "UPDATE " . $table_prefix . "sessions SET rating = '$rating' WHERE login_id = '$login_id'";
$SQL->miscquery($query_update_rating);

header('Location: ./logout_index.php?COMPLETE=true&' . SID);
?>