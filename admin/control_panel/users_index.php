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

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="MSThemeCompatible" content="Yes">
<link href="../../styles/styles.php" rel="stylesheet" type="text/css">
<?php
if ($current_win32_client == 'true') {
?>
<style type="text/css">
<!--
.background {
	background-image: url(../../images/background_users.gif);
	background-repeat: no-repeat;
	background-position: right bottom;
}
-->
</style>
<?php
}
?>
</head>
<body<?php if($current_win32_client == 'true') { ?> class="background"<?php } ?>> 
<table width="425" border="0" align="center"> 
  <tr> 
    <td width="22"><img src="../../icons/staff.gif" alt="<?php echo($language['manage_accounts']); ?>" width="22" height="22"></td> 
    <td colspan="5"><em class="heading"><?php echo($language['manage_accounts']); ?></em></td> 
  </tr> 
  <tr> 
    <td>&nbsp;</td> 
    <td><strong><?php echo($language['username']); ?></strong></td> 
    <td><strong><?php echo($language['name']); ?></strong></td> 
    <td><strong><?php echo($language['department']); ?></strong></td> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
  </tr> 
  <?php
$query_select_users = "SELECT u.user_id,u.username,u.first_name,u.last_name,u.department,u.account_status,s.active,(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) AS timeout FROM " . $table_prefix . "users As u LEFT JOIN " . $table_prefix . "sessions AS s ON u.last_login_id = s.login_id";
$rows_msgs = $SQL->selectall($query_select_users);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {
			$department = $row['department'];
			// Display first department ONLY
			$multi_departments = split(';', $row['department']);
			if (is_array($multi_departments) && count($multi_departments) > 1) {
				$department = $multi_departments[0] . "..";
			}		
?> 
  <tr> 
    <td> <?php
if ($row['account_status'] == 1){
?> 
      <img src="../../icons/account_disabled.gif" alt="<?php echo($language['account_disabled']); ?>"> 
      <?php
}
else {
	// Operator is Online and connected to Live Help
	if($row['timeout'] < $connection_timeout) {
		if($row['active'] == '-1') { // Hidden
?> 
      <img src="../../icons/disconnected_small.gif" alt="<?php echo($language['offline']); ?>"> 
      <?php
		}
		elseif($row['active'] == '-2') { // Online
?> 
      <img src="../../icons/staff_small.gif" alt="<?php echo($language['online']); ?>"> 
      <?php
		}
		elseif($row['active'] == '-4') { // BRB
?> 
      <img src="../../icons/brb_small.gif" alt="<?php echo($language['brb']); ?>"> 
      <?php
		}
	}
	// Operator is not Online connected to Live Help
	else{
?> 
      <img src="../../icons/disconnected_small.gif" alt="<?php echo($language['offline']); ?>"> 
      <?php
	}
}
?> </td> 
    <td><?php echo($row['username']); ?></td> 
    <td><?php echo($row['first_name'] . ' ' . $row['last_name']); ?></td> 
    <td><?php echo($department); ?></td> 
    <td width="22"><input name="Edit" type="button" onClick="document.location = './users_edit.php?UID=<?php echo($row['user_id']); ?>&<?php echo(SID); ?>'" value="<?php echo($language['edit']); ?>"></td> 
    <td width="22"><input name="Delete" type="button" onClick="document.location = './users_delete.php?UID=<?php echo($row['user_id']); ?>&<?php echo(SID); ?>'" value="<?php echo($language['delete']); ?>"></td> 
  </tr> 
  <?php
		}
	}
}

?> 
  <tr> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
  </tr> 
  <tr> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
    <td>&nbsp;</td> 
    <td colspan="2"><div align="right"><a href="./users_add.php?<?php echo(SID); ?>" class="normlink"><?php echo($language['add_user']); ?></a></div></td> 
    <td><a href="./users_add.php?<?php echo(SID); ?>"><img src="../../icons/user_add.gif" alt="<?php echo($language['add_user']); ?>" width="22" height="22" border="0"></a></td> 
  </tr> 
</table> 
</body>
</html>
