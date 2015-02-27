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

if (isset($_POST['ONLINE_LOGO'])){ $online_logo = stripslashes($_POST['ONLINE_LOGO']); }
if (isset($_POST['OFFLINE_LOGO'])){ $offline_logo = stripslashes($_POST['OFFLINE_LOGO']); }
if (isset($_POST['ONLINE_BRB_LOGO'])){ $online_brb_logo = stripslashes($_POST['ONLINE_BRB_LOGO']); }
if (isset($_POST['ONLINE_AWAY_LOGO'])){ $online_away_logo = stripslashes($_POST['ONLINE_AWAY_LOGO']); }
if (isset($_POST['DISABLE_LOGIN_DETAILS'])){ $disable_login_details = $_POST['DISABLE_LOGIN_DETAILS']; }
if (isset($_POST['DISABLE_OFFLINE_EMAIL'])){ $disable_offline_email = $_POST['DISABLE_OFFLINE_EMAIL']; }
if (isset($_POST['OFFLINE_LOGO_WITHOUT_EMAIL'])){ $offline_logo_without_email = stripslashes($_POST['OFFLINE_LOGO_WITHOUT_EMAIL']); }

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
  <form name="UPDATE_SETTINGS" method="post" action="settings_links.php?<?php echo(SID); ?>"> 
    <table width="400" border="0" align="center"> 
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['links']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['links']); ?></em> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><?php include('./settings_toolbar.php'); ?> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['online_logo']); ?>: </div></td> 
        <td><input name="ONLINE_LOGO" type="text" id="ONLINE_LOGO" value="<?php echo($online_logo); ?>" size="25"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['online_logo']); ?>: <?php echo($language['online_logo_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['offline_logo']); ?>:</div></td> 
        <td><input name="OFFLINE_LOGO" type="text" id="OFFLINE_LOGO" value="<?php echo($offline_logo); ?>" size="25"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['offline_logo']); ?>: <?php echo($language['offline_logo_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['brb_logo']); ?>:</div></td> 
        <td><input name="ONLINE_BRB_LOGO" type="text" id="ONLINE_BRB_LOGO" value="<?php echo($online_brb_logo); ?>" size="25"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['brb_logo']); ?>: <?php echo($language['brb_logo_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['away_logo']); ?>:</div></td> 
        <td><input name="ONLINE_AWAY_LOGO" type="text" id="ONLINE_AWAY_LOGO" value="<?php echo($online_away_logo); ?>" size="25"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['away_logo']); ?>: <?php echo($language['away_logo_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['disable_login_details']); ?>:</div></td> 
        <td> <input name="DISABLE_LOGIN_DETAILS" type="radio" value="false" <?php if ($disable_login_details == "false") { echo("checked"); }?>> 
          <?php echo($language['on']); ?> 
          <input name="DISABLE_LOGIN_DETAILS" type="radio"  value="true" <?php if ($disable_login_details == "true") { echo("checked"); }?>> 
          <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['disable_login_details']); ?>: <?php echo($language['disable_login_details_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['disable_offline_email']); ?>:</div></td> 
        <td> <input name="DISABLE_OFFLINE_EMAIL" type="radio" value="false" <?php if ($disable_offline_email == "false") { echo("checked"); }?>> 
          <?php echo($language['on']); ?> 
          <input type="radio" name="DISABLE_OFFLINE_EMAIL" value="true" <?php if ($disable_offline_email == "true") { echo("checked"); }?>> 
          <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['disable_offline_email']); ?>: <?php echo($language['disable_offline_email_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['offline_logo_without_email']); ?>:</div></td> 
        <td><input name="OFFLINE_LOGO_WITHOUT_EMAIL" type="text" id="OFFLINE_LOGO_WITHOUT_EMAIL" value="<?php echo($offline_logo_without_email); ?>" size="25"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -175px;width: 225px;"><?php echo($language['offline_logo_without_email']); ?>: <?php echo($language['offline_logo_without_email_tooltip']); ?>.</span></a></td> 
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
