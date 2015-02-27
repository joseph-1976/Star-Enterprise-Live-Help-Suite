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

$language_file = './locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('./locale/en/lang_en.php');
}

session_start();
$current_login_id = $_SESSION['GUEST_LOGIN_ID'];
session_write_close();

// Find current department selected
$query_select_department = "SELECT support_department FROM " . $table_prefix . "sessions WHERE login_id = '$current_login_id'";
$row_department = $SQL->selectquery($query_select_department);
if (is_array($row_department)) {
	$current_department = $row_department['support_department'];
	$query_select = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = 0 AND s.support_department LIKE '%$current_department%'";
}
else {
	$query_select = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < '" . $connection_timeout . "' AND s.active = 0";
}
$row = $SQL->selectquery($query_select);

if (is_array($row)) {
	$users_online = $row['count(login_id)'];
}
else {
	$users_online = '1';
}
header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<link href="styles/styles.php" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.background {
	background-image: url(./locale/<?php echo(LANGUAGE_TYPE); ?>/images/connecting.gif);
	background-repeat: no-repeat;
	background-position: center center;
}
-->
</style>
</head>
<body bgcolor="#FFFFFF" class="background" topmargin="0">
<div align="center">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td align="center"><?php echo($language['welcome_to']); ?>&nbsp;<?php echo($livehelp_name); ?><br>
        <span class="small"><?php echo($language['thank_you_patience']); ?></span> </td>
    </tr>
    <tr>
      <td height="76">&nbsp;</td>
    </tr>
    <tr>
      <td align="center"><div align="right"><span class="small">Currently <?php echo($users_online); ?> users waiting for Live Help. [<a href="#" class="normlink" onClick="window.location.reload(true);">Refresh</a>] </span></div></td>
    </tr>
  </table>
  </div>
</body>
</html>
