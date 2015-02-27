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

if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = ""; }
if (!isset($_GET['REFRESH_STATUS'])){ $_GET['REFRESH_STATUS'] = ""; }

$connection_status = $_GET['STATUS'];
$refresh_status = $_GET['REFRESH_STATUS'];

if ($connection_status == '') { $connection_status = 'online'; }
if ($refresh_status == '') { $refresh_status = 'false'; }

if ($connection_status == 'online') {
	$connection_status = $language['online'];
}
elseif ($connection_status == 'offline') {
	$connection_status = $language['offline'];
}
elseif ($connection_status == 'brb') {
	$connection_status = $language['brb'];
}

// Find if the User-Agent HTTP Header is the Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 Win32 Client
if ($_SERVER['HTTP_USER_AGENT'] == 'Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007') {
	$current_win32_client = 'true';
}
else {
	$current_win32_client = 'false';
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body text="#000000" link="#333333" vlink="#000000" alink="#000000" marginwidth="0" leftmargin="0" topmargin="0"> 
<table height="100%" border="0" cellpadding="0" cellspacing="0"> 
  <tr> 
    <td><img src="../icons/users_messenger.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 Messenger" width="30" height="300" align="bottom"></td>
  </tr> 
</table> 
</body>
</html>
