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

if (isset($_POST['BACKGROUND_COLOR'])){ $background_color = stripslashes($_POST['BACKGROUND_COLOR']); }
if (isset($_POST['BACKGROUND_IMAGE'])){ $background_image = stripslashes($_POST['BACKGROUND_IMAGE']); }
if (isset($_POST['GUEST_CHAT_FONT_SIZE'])){ $guest_chat_font_size = $_POST['GUEST_CHAT_FONT_SIZE']; }
if (isset($_POST['ADMIN_CHAT_FONT_SIZE'])){ $admin_chat_font_size = $_POST['ADMIN_CHAT_FONT_SIZE']; }
if (isset($_POST['LANGUAGE_TYPE'])){ $language_type = stripslashes($_POST['LANGUAGE_TYPE']); }
if (isset($_POST['GUEST_SMILIES'])){ $guest_smilies = stripslashes($_POST['GUEST_SMILIES']); }
if (isset($_POST['ADMIN_SMILIES'])){ $admin_smilies = stripslashes($_POST['ADMIN_SMILIES']); }
if (isset($_POST['DISABLE_POPUP_HELP'])){ $disable_popup_help = stripslashes($_POST['DISABLE_POPUP_HELP']); }

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
  <form name="UPDATE_SETTINGS" method="post" action="settings_display.php?<?php echo(SID); ?>"> 
    <table width="400" border="0" align="center"> 
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['display']); ?>" width="22" height="22"></td> 
        <td colspan="3"><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['display']); ?></em> </td> 
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
        <td><div align="right"><?php echo($language['background_color']); ?>:</div></td> 
        <td><input name="BACKGROUND_COLOR" type="text" id="BACKGROUND_COLOR" value="<?php echo($background_color); ?>" size="7" maxlength="7"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -30px;"><?php echo($language['background_color']); ?>: <?php echo($language['background_color_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['background_image']); ?>:</div></td> 
        <td><input name="BACKGROUND_IMAGE" type="text" id="BACKGROUND_IMAGE" value="<?php echo($background_image); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['background_image']); ?>: <?php echo($language['background_image_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['guest_chat_font_size']); ?>:</div></td> 
        <td><select name="GUEST_CHAT_FONT_SIZE" id="GUEST_CHAT_FONT_SIZE" style="z-index:1;"> 
            <option value="1" <?php if ($guest_chat_font_size == '1') { echo('selected'); } ?>>1</option> 
            <option value="2" <?php if ($guest_chat_font_size == '2') { echo('selected'); } ?>>2</option> 
            <option value="3" <?php if ($guest_chat_font_size == '3') { echo('selected'); } ?>>3</option> 
            <option value="4" <?php if ($guest_chat_font_size == '4') { echo('selected'); } ?>>4</option> 
            <option value="5" <?php if ($guest_chat_font_size == '5') { echo('selected'); } ?>>5</option> 
            <option value="6" <?php if ($guest_chat_font_size == '6') { echo('selected'); } ?>>6</option> 
            <option value="7" <?php if ($guest_chat_font_size == '7') { echo('selected'); } ?>>7</option> 
          </select> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="top: -30px; left:15px;"><?php echo($language['guest_chat_font_size']); ?>: <?php echo($language['guest_chat_font_size_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['admin_chat_font_size']); ?>:</div></td> 
        <td><select name="ADMIN_CHAT_FONT_SIZE" id="ADMIN_CHAT_FONT_SIZE"> 
            <option value="1" <?php if ($admin_chat_font_size == '1') { echo('selected'); } ?>>1</option> 
            <option value="2" <?php if ($admin_chat_font_size == '2') { echo('selected'); } ?>>2</option> 
            <option value="3" <?php if ($admin_chat_font_size == '3') { echo('selected'); } ?>>3</option> 
            <option value="4" <?php if ($admin_chat_font_size == '4') { echo('selected'); } ?>>4</option> 
            <option value="5" <?php if ($admin_chat_font_size == '5') { echo('selected'); } ?>>5</option> 
            <option value="6" <?php if ($admin_chat_font_size == '6') { echo('selected'); } ?>>6</option> 
            <option value="7" <?php if ($admin_chat_font_size == '7') { echo('selected'); } ?>>7</option> 
          </select> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="top: -58px; left:15px;"><?php echo($language['admin_chat_font_size']); ?>: <?php echo($language['admin_chat_font_size_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><?php echo($language['locale']); ?>:</div></td>
        <td><select name="LANGUAGE_TYPE">
            <?php
$languages = file('../../locale/i18n.txt');
foreach ($languages as $line) {
	$i18n = split(',', $line);
	$language_code = trim($i18n[0]);
	$language_name = trim($i18n[1]);
	$charset = trim($i18n[2]);
	$available = file_exists('../../locale/' . $language_code . '/lang_' . $language_code . '.php');
	if ($available) {
?>
            <option value="<?php echo($language_code . ', ' . $charset); ?>"<?php if (LANGUAGE_TYPE == $language_code) { echo(' selected'); } ?>><?php echo($language_name . ' [' . $language_code . ' - ' . $charset . ']'); ?></option>
            <?php
	}
}
?>
        </select> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -95px;"><?php echo($language['locale']); ?>: <?php echo($language['locale_tooltip']); ?>.</span></a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><?php echo($language['guest_smilies']); ?>:</div></td>
        <td><input name="GUEST_SMILIES" type="radio" value="true" <?php if ($guest_smilies == "true") { echo("checked"); }?>>
            <?php echo($language['on']); ?>
            <input type="radio" name="GUEST_SMILIES" value="false" <?php if ($guest_smilies == "false") { echo("checked"); }?>>
            <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['guest_smilies']); ?>: <?php echo($language['guest_smilies_tooltip']); ?>.</span></a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><?php echo($language['admin_smilies']); ?>:</div></td>
        <td><input name="ADMIN_SMILIES" type="radio" value="true" <?php if ($admin_smilies == "true") { echo("checked"); }?>>
            <?php echo($language['on']); ?>
            <input type="radio" name="ADMIN_SMILIES" value="false" <?php if ($admin_smilies == "false") { echo("checked"); }?>>
            <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="width: 200px;"><?php echo($language['admin_smilies']); ?>: <?php echo($language['admin_smilies_tooltip']); ?>.</span></a></td> 
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><?php echo($language['popup_help']); ?>:</div></td>
        <td><input name="DISABLE_POPUP_HELP" type="radio" value="false" <?php if ($disable_popup_help == "false") { echo("checked"); }?>>
            <?php echo($language['on']); ?>
            <input type="radio" name="DISABLE_POPUP_HELP" value="true" <?php if ($disable_popup_help == "true") { echo("checked"); }?>>
            <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="width: 200px;"><?php echo($language['popup_help']); ?>: <?php echo($language['popup_help_tooltip']); ?>.</span></a></td>
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
