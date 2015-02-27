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

include('settings_include.php');

if (isset($_POST['SITE_NAME'])){ $site_name = stripslashes($_POST['SITE_NAME']); }
if (isset($_POST['SITE_ADDRESS'])){ $site_address_original = stripslashes($_POST['SITE_ADDRESS']); }
if (isset($_POST['OFFLINE_EMAIL'])){ $offline_email = stripslashes($_POST['OFFLINE_EMAIL']); }
if (isset($_POST['LIVEHELP_NAME'])){ $livehelp_name = stripslashes($_POST['LIVEHELP_NAME']); }
if (isset($_POST['LIVEHELP_LOGO'])){ $livehelp_logo = stripslashes($_POST['LIVEHELP_LOGO']); }
if (isset($_POST['WELCOME_NOTE'])){ $welcome_note = stripslashes($_POST['WELCOME_NOTE']); }
if (isset($_POST['DEPARTMENTS'])){ $departments = stripslashes($_POST['DEPARTMENTS']); }

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
	background-image: url(../../images/background_settings.gif);
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
<div align="center"> 
  <form name="UPDATE_SETTINGS" method="post" action="settings_index.php?<?php echo(SID); ?>"> 
    <table width="400" border="0" align="center"> 
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['general']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['general']); ?></em> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><?php include("./settings_toolbar.php"); ?> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['site_name']); ?>:</div></td> 
        <td><input name="SITE_NAME" type="text" id="SITE_NAME" value="<?php echo($site_name); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['site_name']); ?>: <?php echo($language['site_name_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['site_address']); ?>:</div></td> 
        <td><input name="SITE_ADDRESS" type="text" id="SITE_ADDRESS" value="<?php echo($site_address_original); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['site_address']); ?>: <?php echo($language['site_address_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['offline_email']); ?>:</div></td> 
        <td><input name="OFFLINE_EMAIL" type="text" id="OFFLINE_EMAIL" value="<?php echo($offline_email); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['offline_email']); ?>: <?php echo($language['offline_email_tooltip']); ?></span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['live_help_name']); ?>:</div></td> 
        <td><input name="LIVEHELP_NAME" type="text" id="LIVEHELP_NAME" value="<?php echo($livehelp_name); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['live_help_name']); ?>: <?php echo($language['live_help_name_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['live_help_logo']); ?>:</div></td> 
        <td><input name="LIVEHELP_LOGO" type="text" id="LIVEHELP_LOGO" value="<?php echo($livehelp_logo); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['live_help_logo']); ?>: <?php echo($language['live_help_logo_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['welcome_note']); ?>: </div></td> 
        <td><input name="WELCOME_NOTE" type="text" id="WELCOME_NOTE" value="<?php echo($welcome_note); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['welcome_note']); ?>: <?php echo($language['welcome_note_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['departments']); ?>:</div></td> 
        <td> <input name="DEPARTMENTS" type="radio" value="true" <?php if ($departments == "true") { echo("checked"); }?>> 
          <?php echo($language['on']); ?> 
          <input name="DEPARTMENTS" type="radio" value="false" <?php if ($departments == "false") { echo("checked"); }?>> 
          <?php echo($language['off']); ?>  <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="width: 200px;"><?php echo($language['departments']); ?>: <?php echo($language['departments_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><div align="center"> 
            <input name="SAVE" type="hidden" id="SAVE" value="true"> 
            <input name="Submit" type="submit" id="Submit" value="<?php if ($_POST['SAVE'] == 'true') { echo($config_status); } else { echo($language['save']); } ?>" <?php if ($_POST['SAVE'] == 'true') { echo('disabled="true"'); } ?>>
          </div></td> 
      </tr> 
    </table> 
  </form> 
</div> 
</body>
</html>
