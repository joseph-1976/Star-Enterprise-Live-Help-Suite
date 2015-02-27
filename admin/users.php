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
include('../include/functions.php');

$language_file = '../locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('../locale/en/lang_en.php');
}

ignore_user_abort(true);

if (!isset($_GET['MULTILOAD'])){ $_GET['MULTILOAD'] = ""; }
if (!isset($_GET['USERNAME'])){ $_GET['USERNAME'] = ""; }
if (!isset($_GET['LOGIN_ID'])){ $_GET['LOGIN_ID'] = ""; }

$multiload = $_GET['MULTILOAD'];
$username = $_GET['USERNAME'];
$login_id = $_GET['LOGIN_ID'];

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
<!--

function multiLoad(doc1,doc2) {
	parent.displayFrame.location.href = doc1;
	parent.messengerFrame.location.href = doc2;
}

<?php
if ($multiload == 'true') {
?>
multiLoad('./displayer_frame.php?<?php echo(SID); ?>&USER=<?php echo(addslashes($username)); ?>&LOGIN_ID=<?php echo($login_id); ?>', './messenger.php?<?php echo(SID); ?>&USER=<?php echo(addslashes($username)); ?>&LOGIN_ID=<?php echo($login_id); ?>');
window.setTimeout('document.location.href = "./users.php?<?php echo(SID); ?>";', 10000);

<?php
}
?>
//-->
</script>
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body link="#000000" vlink="#000000" alink="#000000" onFocus="parent.document.title = 'Admin <?php echo($livehelp_name); ?>'" marginwidth="0" leftmargin="0" topmargin="0"> 
<?php

$query_select = "SELECT s.datetime, u.department FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND login_id = '$current_login_id'";
$rows = $SQL->selectall($query_select);
if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
		$login_datetime = $row['datetime'];
		$department = $row['department'];
		}
	}
}

?> 
<table border="0" cellpadding="0" cellspacing="1"> 
  <tr valign="middle"> 
    <td width="24" valign="middle"><div align="center"><img src="../icons/staff.gif" alt="<?php echo($language['staff']); ?>" name="StaffIcon" width="22" height="22"> </div></td> 
    <td width="125" class="headingusers"><?php echo($language['staff']); ?></td> 
    <td width="20" valign="middle"></td> 
    <td width="20" valign="middle"></td> 
    <td width="20" valign="middle"></td> 
  </tr> 
  <?php
//ONLINE ADMIN USERS QUERY
$query_select = "SELECT s.username,s.login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2' ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
  <tr> 
    <td width="24"> <p align="center"> 
        <?php if ($current_login_id != $login_id) { ?> 
        <a href="<?php echo("javascript:multiLoad('./displayer_frame.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id . "')"); ?>"> 
        <?php } ?> 
        <img src="../icons/red_staff.gif" alt="<?php echo($language['online_staff']); ?>" width="16" height="16" border="0"> 
        <?php if ($current_login_id != $login_id) { ?> 
        </a> 
        <?php } ?> 
      </p></td> 
    <td width="125"> <?php if ($current_login_id != $login_id) { ?> 
      <a href="<?php echo("javascript:multiLoad('./displayer_frame.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id . "')"); ?>" class="normlink"> 
      <?php } ?> 
      <?php echo(stripslashes($username)); ?> 
      <?php if ($current_login_id != $login_id) { ?> 
      </a> 
      <?php } ?></td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
		}
	}
}
else {
?> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/red_staff_grey.gif" width="16" height="16"></div></td> 
    <td width="125" class="smallusers">No online staff. </td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
  }
  ?> 
  <tr> 
    <td width="24" height="10"></td> 
    <td width="125" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
  </tr> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/online.gif" alt="<?php echo($language['online']); ?>" name="OnlineIcon" width="22" height="22"> </div></td> 
    <td width="125" class="headingusers"><?php echo($language['online']); ?></td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
//ONLINE GUEST USERS QUERY
$query_select = "SELECT s.username,s.login_id FROM " . $table_prefix . "sessions AS s WHERE s.support_username = '$current_username' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) < " . $connection_timeout . " AND s.active > '0' ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
  <tr> 
    <td width="24"> <p align="center"><a href="#" onClick="<?php echo("multiLoad('./displayer_frame.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id ."')"); ?>"><img src="../icons/green.gif" alt="<?php echo($language['online_guest']); ?>" width="16" height="16" border="0"></a> </p></td> 
    <td width="125"><a href="#" onClick="<?php echo("multiLoad('./displayer_frame.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id . "', './messenger.php?" . SID . "&USER=" . addslashes($username) . "&LOGIN_ID=" . $login_id ."')"); ?>" class="normlink"><?php echo(stripslashes($username)); ?></a></td> 
    <td width="20"><div align="center"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/user_info.gif" alt="<?php echo($language['information']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20"><div align="center"><a href="./././close_request.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" alt="<?php echo($language['close_request']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20">&nbsp;</td> 
  </tr> 
  <?php 
		}
	}
}
else {
?> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/green_grey.gif" width="16" height="16"></div></td> 
    <td width="125" class="smallusers">No online users. </td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
  }
  ?> 
  <tr> 
    <td height="10"></td> 
    <td width="125" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
  </tr> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/pending.gif" alt="<?php echo($language['pending']); ?>" width="22" height="22"> </div></td> 
    <td width="125" class="headingusers"><?php echo($language['pending']); ?></td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
//PENDING USERS QUERY displays pending users not logged in on users users table depending on department settings
if ($departments == 'true') {
 	$departments_sql = departmentsSQL($department);
	$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < " . $connection_timeout . " AND s.active = '0' AND $departments_sql ORDER BY s.username";
}
elseif ($departments == 'false') {
	$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < " . $connection_timeout . " AND s.active = '0' ORDER BY s.username";
}
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
<?php
if ($current_win32_client == 'false') {
?>
<script language="JavaScript" type="text/JavaScript">
<!--
if (top.soundControlsFrame) {
	if (top.soundControlsFrame.window.soundControl) {
		top.soundControlsFrame.window.soundControl.Play();
	}
	else if (top.soundControlsFrame.window.document.scoundControl) {
		top.soundControlsFrame.window.document.soundControl.Play();
	}
}
//-->
</script>
<?php
}
?>
  <tr> 
    <td width="24"> <p align="center"><a href="./././make_active.php?<?php echo(SID); ?>&USER=<?php echo($username); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/blue.gif" alt="<?php echo($language['pending_user']); ?>" width="16" height="16" border="0"></a> </p></td> 
    <td width="125"><a href="./././make_active.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>&MULTILOAD=true" class="normlink"><?php echo($username); ?></a></td> 
    <td width="20"><div align="center"><a href="./././make_active.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/add_user.gif" alt="<?php echo($language['add_user']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20"><div align="center"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/user_info.gif" alt="<?php echo($language['information']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20"><div align="center"><a href="./././close_request.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" alt="<?php echo($language['close_request']); ?>" width="16" height="16" border="0"></a></div></td> 
  </tr> 
  <?php
		}
	}
}
else {
?> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/blue_grey.gif" width="16" height="16"></div></td> 
    <td width="125" class="smallusers">No pending users.</td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
  }
  ?> 
  <tr> 
    <td height="10"></td> 
    <td width="125" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
  </tr> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/transferred.gif" alt="<?php echo($language['transferred']); ?>" name="OnlineIcon" width="22" height="22"> </div></td> 
    <td width="125" class="headingusers"><?php echo($language['transferred']); ?></td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
//TRANFERRED USERS QUERY displays transferred users not logged in on users users table depending on department settings
$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < " . $connection_timeout . " AND s.active = '-2' AND s.transfer_id = '$current_login_id' ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
<?php
if ($current_win32_client == 'false') {
?>
<script language="JavaScript" type="text/JavaScript">
<!--
if (top.soundControlsFrame) {
	if (top.soundControlsFrame.window.soundControl) {
		top.soundControlsFrame.window.soundControl.Play();
	}
	else if (top.soundControlsFrame.window.document.scoundControl) {
		top.soundControlsFrame.window.document.soundControl.Play();
	}
}
//-->
</script>
<?php
}
?>
  <tr> 
    <td width="24"> <p align="center"><a href="./././make_active.php?<?php echo(SID); ?>&USER=<?php echo($username); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/orange.gif" alt="<?php echo($language['transferred_user']); ?>" width="16" height="16" border="0"></a> </p></td> 
    <td width="125"><a href="./././make_active.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>" class="normlink"><?php echo(stripslashes($username)); ?></a></td> 
    <td width="20"><div align="center"><a href="./././make_active.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&SLOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/add_user.gif" alt="<?php echo($language['add_user']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20"><div align="center"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/user_info.gif" alt="<?php echo($language['information']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20"><div align="center"><a href="./././close_request.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" alt="<?php echo($language['close_request']); ?>" width="16" height="16" border="0"></a></div></td> 
  </tr> 
  <?php
		}
	}
}
else {
?> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/orange_grey.gif" width="16" height="16"></div></td> 
    <td width="125" class="smallusers">No transferred users. </td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
}	
?> 
  <tr> 
    <td height="10"></td> 
    <td width="125" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
    <td width="20" height="10"></td> 
  </tr> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/offline.gif" alt="<?php echo($language['offline']); ?>" width="22" height="22"> </div></td> 
    <td width="125" class="headingusers"><?php echo($language['offline']); ?></td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
//OFFLINE USERS QUERY
$query_select = "SELECT DISTINCT s.login_id,s.username FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE s.datetime > '$login_datetime' AND (active = '$current_login_id' OR active = '0' OR active = '-1') AND u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) > " . $connection_timeout . " ORDER BY s.username";
$rows = $SQL->selectall($query_select);

if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
			$username = $row['username'];
			$login_id = $row['login_id'];
?> 
  <tr> 
    <td width="24"> <p align="center"><a href="view_transcript.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&USER=<?php echo($username); ?>" target="displayFrame"><img src="../icons/red.gif" alt="<?php echo($language['offline_user']); ?>" width="16" height="16" border="0"></a> </p></td> 
    <td width="125"><a href="view_transcript.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>&USER=<?php echo($username); ?>" target="displayFrame" class="normlink"><?php echo(stripslashes($username)); ?></a></td> 
    <td width="20"><div align="center"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($login_id); ?>" target="displayFrame"><img src="../icons/user_info.gif" alt="<?php echo($language['information']); ?>" width="16" height="16" border="0"></a></div></td> 
    <td width="20"><a href="./././close_request.php?<?php echo(SID); ?>&TO_LOGIN_ID=<?php echo($login_id); ?>&FROM_LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/ignore_user.gif" alt="<?php echo($language['close_request']); ?>" width="16" height="16" border="0"></a></td> 
    <td width="20"></td> 
  </tr> 
  <?php
		}
	}
}
else {
?> 
  <tr> 
    <td width="24"><div align="center"><img src="../icons/red_grey.gif" width="16" height="16"></div></td> 
    <td width="125" class="smallusers">No offline users. </td> 
    <td width="20"></td> 
    <td width="20"></td> 
    <td width="20"></td> 
  </tr> 
  <?php
}

?> 
</table> 
</body>
</html>
