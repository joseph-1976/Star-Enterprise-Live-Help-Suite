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

$language_file = '../../locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('../../locale/en/lang_en.php');
}

$query_truncate = "TRUNCATE " . $table_prefix . "messages";
$row = $SQL->miscquery($query_truncate);

$query_truncate = "TRUNCATE " . $table_prefix . "requests";
$row = $SQL->miscquery($query_truncate);

$query_truncate = "TRUNCATE " . $table_prefix . "statistics";
$row = $SQL->miscquery($query_truncate);

header('Location: ./db_index.php');
?>