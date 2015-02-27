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
$from_login_id = $_SESSION['GUEST_LOGIN_ID'];
session_write_close();

if (!isset($_GET['COMPLETE'])){ $_GET['COMPLETE'] = ""; }
$complete = $_GET['COMPLETE'];
if ($complete == '') { $complete = 'false'; }

$query_select = "SELECT server FROM " . $table_prefix . "sessions WHERE login_id = '$from_login_id'";
$row = $SQL->selectquery($query_select);
if (is_array($row)) {
	$server = $row['server'];
}
header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<link href="styles/styles.php" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function loadOfflineSupport() {
	top.document.location.href = 'index_offline.php?<?php echo(SID); ?>';
}

var timer = setTimeout('loadOfflineSupport()', 15000);
//-->
</script>
</head>
<body text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>" topmargin="0"> 
<div align="center"> 
  <table width="100%" border="0" cellpadding="2" cellspacing="2"> 
    <tr> 
      <td><div align="center"><span class="heading"><em><?php echo($language['please_note']); ?></em></span><br> 
          <em><?php echo($language['please_wait_heavy_load']); ?>:</em> 
          <table border="0" align="center" cellpadding="2" cellspacing="2"> 
            <tr> 
              <td><a href="blank.php" onClick="clearTimeout(timer);" class="normlink"><?php echo($language['continue_waiting']); ?></a></td> 
              <td>::</td> 
              <td><a href="index_offline.php" target="_top" class="normlink"><?php echo($language['offline_support']); ?></a></td> 
            </tr> 
          </table> 
          <span class="small"><?php echo($language['redirecting']); ?>... </span></div></td> 
    </tr> 
  </table> 
</div> 
</body>
</html>
