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
include('../include/settings_default.php');

$database_config_include = include('../include/config_database.php');
if ($database_config_include == 'true') {
	include('../include/class.mysql.php');
	include('../include/config.php');
	$installed = 'true';
}
else {
	$installed = 'false';
}

$language_file = '../locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('../locale/en/lang_en.php');
}

$install_dir = '../install';

if (isset($_COOKIE['LiveHelpFlashDetection']) && ($_COOKIE['LiveHelpFlashDetection'] == 'true')) {
	header('Location: /livehelp/admin/index_login.php');
	exit();
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name) ?>- Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="refresh" content="20;URL=/livehelp/admin/index_login.php?FLASH=false">
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body link="#000000" vlink="#000000" alink="#000000"> 
<br> 
<div align="center"> 
  <p><img src="../images/help_logo_admin.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="252" height="81" border="0" /></p>
  <p class="heading"> <strong><?php echo($language['administration']); ?></strong> </p> 
  <p> <span class="heading">Please Note - Detecting Media Player</span><br> 
    Detecting version for required media player Installation.<br> 
    The Live Help Web Based client requires Macromedia Flash. <a href="http://www.macromedia.com/go/getflashplayer/" class="normlink">Download</a> the Macromedia Flash Player.<br> 
    Please stand by for 10-20 seconds while detecting... </p> 
  <p><img src="../images/flash.gif" alt="macromedia Flash Enabled" width="100" height="28"><img src="../images/shockwave.gif" alt="macromedia Shockwave Enabled" width="120" height="28"><br> 
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" name="soundsDetection" width="2" height="2" id="soundsDetection"> 
      <param name="movie" value="../include/soundsDetection.swf"> 
      <param name="quality" value="high"> 
      <param name="BGCOLOR" value="#FFFFFF"> 
      <embed src="../include/soundsDetection.swf?TIME=<?php echo(rand(0, 10000)); ?>" width="2" height="2" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" bgcolor="#FFFFFF" name="soundsDetection"></embed> 
    </object> 
    <script language="JavaScript" type="text/JavaScript">
<!--
if (null == document.soundsDetection) {
	setTimeout("location.href='index_login.php?FLASH=false'", 10000);
}
// -->
    </script> 
    <br> 
    <span class="small"><?php echo($language['stardevelop_copyright']); ?></span></p> 
</div> 
</body>
</html>
