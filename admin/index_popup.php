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

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name) ?> - Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body link="#000000" vlink="#000000" alink="#000000"> 
<br> 
<div align="center"> 
  <p><img src="../images/help_logo_admin.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="252" height="81" border="0" /></p>
  <p class="heading"> <strong><?php echo($language['administration']); ?></strong> </p> 
  <p><?php echo($language['welcome_message']); ?> 
    <?php
  if ($installed == 'true') {
  ?> 
    <br> 
    <?php
  echo($language['enter_user_pass']);
  }
  ?> 
  </p> 
  <?php
if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = ""; }
$login_status = $_GET['STATUS'];
if($login_status == "error") {
?> 
  <p><strong><?php echo($language['access_denied']); ?></strong> 
    <?php
}
if($login_status == "disabled") {
?> 
  <p><strong><?php echo($language['access_denied']); ?><br> 
    <?php echo($language['access_denied_account_disabled']); ?></strong> 
    <?php
}
if($login_status == "authentication") {
?> 
  <p><strong><?php echo($language['authentication_session_error']); ?></strong> 
    <?php
}
if ($installed == 'true') {
?> 
  <form name="login" method="POST" action="frames.php"> 
    <table width="300" border="0"> 
      <tr> 
        <td><div align="right"><?php echo($language['username']); ?>:</div></td> 
        <td> <input name="USER_NAME" type="text" value="<?php echo($cookie_login['username']); ?>" style="width:150px;"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><?php echo($language['password']); ?>:</div></td> 
        <td> <input name="PASSWORD" type="password" value="<?php echo($cookie_login['password']); ?>" style="width:150px;"></td> 
      </tr> 
      <tr> 
        <td colspan="2"><div align="center"> 
            <input name="REMEMBER_LOGIN" type="checkbox" value="true" <?php if (isset($_COOKIE['cookie_login'])) { echo('checked'); } ?>> 
            <span class="small"> 
            <?php
		  	echo($language['remember_login_details']);
	?> 
            </span></div></td> 
      </tr> 
    </table> 
    <p class="small"><em><?php echo($language['admin_supports_line_one']); ?><br> 
      <?php echo($language['admin_supports_line_two']); ?></em><br> 
      <em><?php echo($language['admin_supports_line_three']); ?></em></p> 
    <p> 
      <input type="hidden" name="SERVER" value="<?php echo($_SERVER['HTTP_HOST']); ?>"> 
    </p> 
    <p> 
      <input name="Submit" type="submit" id="Submit" value="<?php echo($language['login']); ?>"> 
&nbsp; 
      <input type="Reset" name="Reset" value="<?php echo($language['reset']); ?>"> 
    </p> 
  </form> 
  <?php
}

if ($installed == 'false') {
?> 
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
  <p class="small"><?php echo($language['stardevelop_copyright']); ?></p> 
</div> 
</body>
</html>
