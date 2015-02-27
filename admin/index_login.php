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

if (isset($_COOKIE['LiveHelpLogin'])) {
	$cookie_login = array();
	$cookie_login = $_COOKIE['LiveHelpLogin'];
}
else {
	$cookie_login['username'] = '';
	$cookie_login['password'] = '';
}

if (!isset($_GET['FLASH'])){ $_GET['FLASH'] = ""; }
$flash_installed = $_GET['FLASH'];

if ($flash_installed == 'true') {
	$domain = '.' . $_SERVER['HTTP_HOST'];
	setcookie('LiveHelpFlashDetection', 'true', time() + 7776000, '/', $domain, 0);
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name) ?>- Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if ($installed == 'true' && $flash_installed != 'false') {
?>
<script language="JavaScript" type="text/JavaScript">
<!--
var windowWidth = 800;
var windowHeight = 585;
var windowLeft = (screen.width - windowWidth) / 2;
var windowTop = (screen.height - windowHeight) / 2;
var size = 'height=' + windowHeight + ', width=' + windowWidth + ', top=' + windowTop + ', left=' + windowLeft + '';

function loadAdmin() {
	var status = window.open('index_popup.php', '', size);
	if (!status) {
		alert('Please disable your popup blocker or click the Open New Administration Window link');
	}
}

setTimeout('loadAdmin()', 3000);

var browser;
// Determine if browser supports mimeTypes array
if (navigator.mimeTypes && navigator.mimeTypes.length != 0) {
  browser = "mimeTypes"
}
else {
  browser = "noMimeTypes"
}

//-->
</script>
<?php
}
?>
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body link="#000000" vlink="#000000" alink="#000000"> 
<br> 
<div align="center"> 
  <p><img src="../images/help_logo_admin.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="252" height="81" border="0" /></p>
  <p class="heading"> <strong><?php echo($language['administration']); ?></strong> </p> 
  <p><?php echo($language['admin_welcome_message']); ?> <br> 
    If the Administration Window does not Automatically popup in 3 seconds please click the link below:</p> 
  <p><span class="heading">Please Note - Microsoft&#8482; Windows Users </span><br> 
    If you are using a Microsoft&#8482; Windows System please Install the Live Help Windows Client.<br> 
    The Live Help Windows Client features extended functionality to enhance your Live Support service. <br> 
    <a href="http://livehelp.stardevelop.com"><img src="../images/windows_client_info.gif" width="347" height="80" border="0"></a> </p> 
  <?php
  if ($installed == 'true' && $flash_installed != 'false') {
  ?> 
  <p><a href="#" onClick="javascript:loadAdmin();" class="normlink"><?php echo($language['new_window']); ?></a> 
    <?php
  }
?> 
  <p class="small"><em><?php echo($language['admin_supports_line_one']); ?><br> 
    <?php echo($language['admin_supports_line_two']); ?></em><br> 
    <em><?php echo($language['admin_supports_line_three']); ?></em></p> 
  <p><img src="../images/flash.gif" alt="macromedia Flash Enabled" width="100" height="28"><img src="../images/shockwave.gif" alt="macromedia Shockwave Enabled" width="120" height="28"></p> 
  <?php
if ($flash_installed == 'false') {
?> 
  <table width="300" border="0"> 
    <tr> 
      <td width="32"><a href="../install/index.php" target="_blank"><img src="../icons/media.gif" alt="<?php echo($language['install']); ?>" width="32" height="32" border="0"></a></td> 
      <td><div align="center"> 
          <p><span class="heading"><em>Media Player Required<strong></strong></em></span><em><strong><br> 
            </strong>Sounds will not currently function within the Live Help Web Based Client.&nbsp;<a href="http://www.macromedia.com/go/getflashplayer/" class="normlink">Download</a> the Macromedia Flash Player. </em></p> 
        </div></td> 
    </tr> 
  </table> 
  <?php
}
if ($installed == 'false') {
?> 
  <p>  
  <table width="300" border="0"> 
    <tr> 
      <td width="32"><a href="../install/index.php" target="_blank"><img src="../icons/setup.gif" alt="<?php echo($language['install']); ?>" width="32" height="32" border="0"></a></td> 
      <td><div align="center"> 
          <p><span class="heading"><em><?php echo($language['install']); ?><strong></strong></em></span><em><strong><br> 
            </strong><?php echo($language['install_instructions']); ?></em></p> 
        </div></td> 
    </tr> 
  </table> 
  <?php
}
else if ($installed == 'true' && file_exists($install_dir)) {
?> 
  <table width="300" border="0"> 
    <tr> 
      <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td> 
      <td><div align="center"> 
          <p><span class="heading"><em><?php echo($language['security_warning']); ?><strong></strong></em></span><em><strong><br> 
            </strong><?php echo($language['security_instructions']); ?></em></p> 
        </div></td> 
    </tr> 
  </table> 
  <?php
}
?> 
  <table width="300" border="0"> 
    <tr> 
      <td width="32"></td> 
      <td><div align="center"> 
          <p><span class="heading"><em><?php echo($language['documentation']); ?><strong></strong></em></span><em><strong><br> 
            </strong><?php echo($language['docs_instructions']); ?></em></p> 
        </div></td> 
    </tr> 
  </table> 
  <?php
if ($installed != 'true') {
?> 
  <br> 
  <table width="600" border="0" cellspacing="2" cellpadding="2"> 
    <tr> 
      <td> <p align="center"><em><strong><?php echo($language['please_note']); ?></strong>: <?php echo($language['install_first']); ?></em></p></td> 
    </tr> 
  </table> 
  <?php
}
?> 
  <p class="small"> <?php echo($language['stardevelop_copyright']); ?></p> 
</div> 
</body>
</html>
