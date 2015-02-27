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

if (isset($_POST['FONT_TYPE'])){ $font_type = stripslashes($_POST['FONT_TYPE']); }
if (isset($_POST['FONT_SIZE'])){ $font_size = $_POST['FONT_SIZE']; }
if (isset($_POST['FONT_COLOR'])){ $font_color = $_POST['FONT_COLOR']; }
if (isset($_POST['SENT_FONT_COLOR'])){ $sent_font_color = $_POST['SENT_FONT_COLOR']; }
if (isset($_POST['RECEIVED_FONT_COLOR'])){ $received_font_color = $_POST['RECEIVED_FONT_COLOR']; }
if (isset($_POST['FONT_LINK_COLOR'])){ $font_link_color = stripslashes($_POST['FONT_LINK_COLOR']); }
if (isset($_POST['BACKGROUND_COLOR'])){ $background_color = stripslashes($_POST['BACKGROUND_COLOR']); }
if (isset($_POST['BACKGROUND_IMAGE'])){ $background_image = stripslashes($_POST['BACKGROUND_IMAGE']); }
if (isset($_POST['GUEST_CHAT_FONT_SIZE'])){ $guest_chat_font_size = $_POST['GUEST_CHAT_FONT_SIZE']; }
if (isset($_POST['ADMIN_CHAT_FONT_SIZE'])){ $admin_chat_font_size = $_POST['ADMIN_CHAT_FONT_SIZE']; }

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
  <form name="UPDATE_SETTINGS" method="post" action="settings_fonts.php?<?php echo(SID); ?>"> 
    <table width="400" border="0" align="center"> 
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['display']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['fonts']); ?></em> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><?php include("./settings_toolbar.php"); ?></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['font_type']); ?>: </div></td> 
        <td><input name="FONT_TYPE" type="text" id="FONT_TYPE" value="<?php echo($font_type); ?>" size="30"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px;"><?php echo($language['font_type']); ?>: <?php echo($language['font_type_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['font_size']); ?>:</div></td> 
        <td><input name="FONT_SIZE" type="text" id="FONT_SIZE" value="<?php echo($font_size); ?>" size="7" maxlength="7"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['font_size']); ?>: <?php echo($language['font_size_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['font_color']); ?>:</div></td> 
        <td> <input name="FONT_COLOR" type="text" id="FONT_COLOR" value="<?php echo($font_color); ?>" size="7" maxlength="7"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['font_color']); ?>: <?php echo($language['font_color_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['font_link_color']); ?>:</div></td> 
        <td> <input name="FONT_LINK_COLOR" type="text" id="FONT_LINK_COLOR" value="<?php echo($font_link_color); ?>" size="7" maxlength="7"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['font_link_color']); ?>: <?php echo($language['font_link_color_tooltip']); ?>.</span></a></td> 
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><?php echo($language['sent_font_color']); ?>:</div></td>
        <td><input name="SENT_FONT_COLOR" type="text" id="SENT_FONT_COLOR" value="<?php echo($sent_font_color); ?>" size="7" maxlength="7"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="width: 200px;"><?php echo($language['sent_font_color']); ?>: <?php echo($language['sent_font_color_tooltip']); ?>.</span></a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><?php echo($language['received_font_color']); ?>:</div></td>
        <td><input name="RECEIVED_FONT_COLOR" type="text" id="RECEIVED_FONT_COLOR" value="<?php echo($received_font_color); ?>" size="7" maxlength="7"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="width: 200px;"><?php echo($language['received_font_color']); ?>: <?php echo($language['received_font_color_tooltip']); ?>.</span></a></td>
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
