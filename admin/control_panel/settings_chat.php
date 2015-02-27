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

if (isset($_POST['INITIATE_CHAT_VALIGN'])){ $initiate_chat_valign = $_POST['INITIATE_CHAT_VALIGN']; }
if (isset($_POST['INITIATE_CHAT_HALIGN'])){ $initiate_chat_halign = $_POST['INITIATE_CHAT_HALIGN']; }
if (isset($_POST['DISABLE_CHAT_TIME'])){ $disable_chat_time = $_POST['DISABLE_CHAT_TIME']; }
if (isset($_POST['DISABLE_CHAT_USERNAME'])){ $disable_chat_username = $_POST['DISABLE_CHAT_USERNAME']; }
if (isset($_POST['CAMPAIGN_IMAGE'])){ $campaign_image = stripslashes($_POST['CAMPAIGN_IMAGE']); }
if (isset($_POST['CAMPAIGN_LINK'])){ $campaign_link = stripslashes($_POST['CAMPAIGN_LINK']); }

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
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
  <form name="UPDATE_SETTINGS" method="post" action="settings_chat.php?<?php echo(SID); ?>"> 
    <table width="400" border="0" align="center"> 
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['display']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['chat']); ?></em> </td> 
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
        <td><div align="right"><?php echo($language['initiate_vert_align']); ?>: </div></td> 
        <td><select name="INITIATE_CHAT_VALIGN" id="INITIATE_CHAT_VALIGN" style="width:60px;"> 
            <option value="top" <?php if ($initiate_chat_valign == 'top') { echo('selected'); } ?>>Top</option> 
            <option value="center" <?php if ($initiate_chat_valign == 'center') { echo('selected'); } ?>>Center</option> 
            <option value="bottom" <?php if ($initiate_chat_valign == 'bottom') { echo('selected'); } ?>>Bottom</option> 
          </select> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="top: 40px;"><?php echo($language['initiate_vert_align']); ?>: <?php echo($language['initiate_vert_align_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['initiate_horz_align']); ?>:</div></td> 
        <td><select name="INITIATE_CHAT_HALIGN" id="INITIATE_CHAT_HALIGN" style="width:60px;"> 
            <option value="left" <?php if ($initiate_chat_halign == 'left') { echo('selected'); } ?>>Left</option> 
            <option value="center" <?php if ($initiate_chat_halign == 'center') { echo('selected'); } ?>>Center</option> 
            <option value="right" <?php if ($initiate_chat_halign == 'right') { echo('selected'); } ?>>Right</option> 
          </select> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['initiate_horz_align']); ?>: <?php echo($language['initiate_horz_align_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['disable_chat_time']); ?>:</div></td> 
        <td> <input type="radio" name="DISABLE_CHAT_TIME" value="true" <?php if ($disable_chat_time == "true") { echo("checked"); }?>> 
          <?php echo($language['on']); ?> 
          <input type="radio" name="DISABLE_CHAT_TIME" value="false" <?php if ($disable_chat_time == "false") { echo("checked"); }?>> 
          <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['disable_chat_time']); ?>: <?php echo($language['disable_chat_time_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['disable_chat_username']); ?>:</div></td> 
        <td> <input type="radio" name="DISABLE_CHAT_USERNAME" value="true" <?php if ($disable_chat_username == "true") { echo("checked"); }?>> 
          <?php echo($language['on']); ?> 
          <input type="radio" name="DISABLE_CHAT_USERNAME" value="false" <?php if ($disable_chat_username == "false") { echo("checked"); }?>> 
          <?php echo($language['off']); ?> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['disable_chat_username']); ?>: <?php echo($language['disable_chat_username_tooltip']); ?>.</span></a></td> 
      </tr>
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['campaign_image']); ?>:</div></td> 
        <td><input name="CAMPAIGN_IMAGE" type="text" id="CAMPAIGN_IMAGE" value="<?php echo($campaign_image); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['campaign_image']); ?>: <?php echo($language['campaign_image_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['campaign_link']); ?>:</div></td> 
        <td><input name="CAMPAIGN_LINK" type="text" id="CAMPAIGN_LINK" value="<?php echo($campaign_link); ?>"> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px"><?php echo($language['campaign_link']); ?>: <?php echo($language['campaign_link_tooltip']); ?>.</span></a></td> 
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
