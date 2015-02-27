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

$connection_status = $_GET['STATUS'];

if ($connection_status == '') { $connection_status = 'online'; }

if ($connection_status == 'online') {
	$connection_status = $language['online'];
}
elseif ($connection_status == 'offline') {
	$connection_status = $language['offline'];
}
elseif ($connection_status == 'brb') {
	$connection_status = $language['brb'];
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
<body text="#000000" link="#333333" vlink="#000000" alink="#000000" topmargin="0"> 
<table width="200" border="0" align="center" cellpadding="2" cellspacing="2"> 
  <tr> 
    <td width="22"><div align="center"><a href="online_mode.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/staff.gif" alt="<?php echo($language['online_connected_mode']); ?>" width="22" height="22" border="0"></a></div></td> 
    <td width="22"><div align="center"><a href="offline_mode.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/disconnected.gif" alt="<?php echo($language['offline_hidden_mode']); ?>" width="22" height="22" border="0"></a></div></td> 
    <td width="22"><div align="center"><a href="brb_mode.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($current_login_id); ?>"><img src="../icons/brb.gif" alt="<?php echo($language['brb_hidden_mode']); ?>" width="22" height="22" border="0"></a></div></td> 
    <td><div align="right" class="small"></div></td> 
  </tr> 
</table> 
<table width="200" border="0" align="center" cellpadding="2" cellspacing="2"> 
  <tr> 
    <td><div align="right"><em><?php echo($language['currently_logged_in'] . ' ' . $current_username); ?> <?php echo($language['using_mode']); ?> '<strong><?php echo($connection_status); ?></strong>'</em></div></td> 
  </tr> 
</table> 
</body>
</html>
