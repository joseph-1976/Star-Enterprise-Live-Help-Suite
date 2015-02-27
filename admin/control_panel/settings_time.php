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

if (isset($_POST['CONNECTION_TIMEOUT'])){ $connection_timeout = addslashes($_POST['CONNECTION_TIMEOUT']); }
if (isset($_POST['KEEP_ALIVE_TIMEOUT'])){ $keep_alive_timeout = addslashes($_POST['KEEP_ALIVE_TIMEOUT']); }
if (isset($_POST['GUEST_LOGIN_TIMEOUT'])){ $guest_login_timeout = addslashes($_POST['GUEST_LOGIN_TIMEOUT']); }
if (isset($_POST['USER_PANEL_REFRESH_RATE'])){ $user_panel_refresh_rate = addslashes($_POST['USER_PANEL_REFRESH_RATE']); }
if (isset($_POST['CHAT_REFRESH_RATE'])){ $chat_refresh_rate = addslashes($_POST['CHAT_REFRESH_RATE']); }
if (isset($_POST['TIMEZONE'])){ $timezone = $_POST['TIMEZONE']; }

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
  <form name="UPDATE_SETTINGS" method="post" action="settings_time.php?<?php echo(SID); ?>"> 
    <table width="400" border="0" align="center"> 
      <tr> 
        <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['time']); ?>" width="22" height="22"></td> 
        <td colspan="5"><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['time']); ?></em> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="5"><?php include('./settings_toolbar.php'); ?></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="5"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="5"> <table border="0" cellspacing="2" cellpadding="2"> 
            <tr> 
              <td><div align="right"><?php echo($language['connection_timeout']); ?>:</div></td> 
              <td width="52%"><em> 
                <input name="CONNECTION_TIMEOUT" type="text" value="<?php echo($connection_timeout); ?>" size="2"> 
                <?php echo($language['seconds']); ?></em> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['connection_timeout']); ?>: <?php echo($language['connection_timeout_tooltip']); ?>.</span></a></td> 
            </tr> 
            <tr> 
              <td><div align="right"><?php echo($language['keep_alive_timeout']); ?>:</div></td> 
              <td background=""><em> 
                <input name="KEEP_ALIVE_TIMEOUT" type="text" value="<?php echo($keep_alive_timeout); ?>" size="2"> 
                <?php echo($language['seconds']); ?></em> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['keep_alive_timeout']); ?>: <?php echo($language['keep_alive_timeout_tooltip']); ?>.</span></a></td> 
            </tr> 
            <tr> 
              <td><div align="right"><?php echo($language['guest_login_timeout']); ?>:</div></td> 
              <td><em> 
                <input name="GUEST_LOGIN_TIMEOUT" type="text" id="GUEST_LOGIN_TIMEOUT" value="<?php echo($guest_login_timeout); ?>" size="2"> 
                <?php echo($language['seconds']); ?></em> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span><?php echo($language['guest_login_timeout']); ?>: <?php echo($language['guest_login_timeout_tooltip']); ?>.</span></a></td> 
            </tr> 
            <tr> 
              <td><div align="right"><?php echo($language['chat_refresh']); ?>:</div></td> 
              <td><em> 
                <input name="CHAT_REFRESH_RATE" type="text" value="<?php echo($chat_refresh_rate); ?>" size="2"> 
                <?php echo($language['seconds']); ?></em> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -200px; top: -10px;"><?php echo($language['chat_refresh']); ?>: <?php echo($language['chat_refresh_tooltip']); ?>.</span></a></td> 
            </tr> 
            <tr> 
              <td><div align="right"><?php echo($language['user_refresh']); ?>:</div></td> 
              <td><em> 
                <input name="USER_PANEL_REFRESH_RATE" type="text" value="<?php echo($user_panel_refresh_rate); ?>" size="2"> 
                <?php echo($language['seconds']); ?></em> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -200px; top: -40px;"><?php echo($language['user_refresh']); ?>: <?php echo($language['user_refresh_tooltip']); ?>.</span></a></td> 
            </tr> 
          </table></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['timezone']); ?>:</div></td> 
        <td colspan="4"><select name="TIMEZONE" width="200" style="width: 275px;"> 
            <option value="-1200" <?php if($timezone == '-1200') { echo('selected'); } ?>>(GMT-12:00 <?php echo($language['hours']); ?>) Internat. Date Line West</option> 
            <option value="-1100" <?php if($timezone == '-1100') { echo('selected'); } ?>>(GMT-11:00 <?php echo($language['hours']); ?>) Midway Island, Samoa</option> 
            <option value="-1000" <?php if($timezone == '-1000') { echo('selected'); } ?>>(GMT-10:00 <?php echo($language['hours']); ?>) Hawaii</option> 
            <option value="-0900" <?php if($timezone == '-0900') { echo('selected'); } ?>>(GMT-09:00 <?php echo($language['hours']); ?>) Alaska</option> 
            <option value="-0800" <?php if($timezone == '-0800') { echo('selected'); } ?>>(GMT-08:00 <?php echo($language['hours']); ?>) Pacific Time</option> 
            <option value="-0700" <?php if($timezone == '-0700') { echo('selected'); } ?>>(GMT-07:00 <?php echo($language['hours']); ?>) Mountain Time</option> 
            <option value="-0600" <?php if($timezone == '-0600') { echo('selected'); } ?>>(GMT-06:00 <?php echo($language['hours']); ?>) Central Time</option> 
            <option value="-0500" <?php if($timezone == '-0500') { echo('selected'); } ?>>(GMT-05:00 <?php echo($language['hours']); ?>) Eastern Time</option> 
            <option value="-0400" <?php if($timezone == '-0400') { echo('selected'); } ?>>(GMT-04:00 <?php echo($language['hours']); ?>) Atlantic Time</option> 
            <option value="-0330" <?php if($timezone == '-0330') { echo('selected'); } ?>>(GMT-03:30 <?php echo($language['hours']); ?>) Newfoundland</option> 
            <option value="-0300" <?php if($timezone == '-0300') { echo('selected'); } ?>>(GMT-03:00 <?php echo($language['hours']); ?>) Brazil, Buenos Aires</option> 
            <option value="-0200" <?php if($timezone == '-0200') { echo('selected'); } ?>>(GMT-02:00 <?php echo($language['hours']); ?>) Mid-Atlantic.</option> 
            <option value="-0100" <?php if($timezone == '-0100') { echo('selected'); } ?>>(GMT-01:00 <?php echo($language['hours']); ?>) Cape Verde Islands</option> 
            <option value="0" <?php if($timezone == '0') { echo('selected'); } ?>>(GMT) Greenwich Mean Time: London</option> 
            <option value="+0100" <?php if($timezone == '+0100') { echo('selected'); } ?>>(GMT+01:00 <?php echo($language['hours']); ?>) Berlin, Paris, Rome</option> 
            <option value="+0200" <?php if($timezone == '+0200') { echo('selected'); } ?>>(GMT+02:00 <?php echo($language['hours']); ?>) South Africa</option> 
            <option value="+0300" <?php if($timezone == '+0300') { echo('selected'); } ?>>(GMT+03:00 <?php echo($language['hours']); ?>) Baghdad, Moscow</option> 
            <option value="+0330" <?php if($timezone == '+0330') { echo('selected'); } ?>>(GMT+03:30 <?php echo($language['hours']); ?>) Tehran</option> 
            <option value="+0400" <?php if($timezone == '+0400') { echo('selected'); } ?>>(GMT+04:00 <?php echo($language['hours']); ?>) Adu Dhabi, Baku</option> 
            <option value="+0430" <?php if($timezone == '+0430') { echo('selected'); } ?>>(GMT+04:30 <?php echo($language['hours']); ?>) Kabul</option> 
            <option value="+0500" <?php if($timezone == '+0430') { echo('selected'); } ?>>(GMT+05:00 <?php echo($language['hours']); ?>) Islamabad</option> 
            <option value="+0530" <?php if($timezone == '+0530') { echo('selected'); } ?>>(GMT+05:30 <?php echo($language['hours']); ?>) Calcutta, Madras</option> 
            <option value="+0600" <?php if($timezone == '+0600') { echo('selected'); } ?>>(GMT+06:00 <?php echo($language['hours']); ?>) Almaty, Colomba</option> 
            <option value="+0700" <?php if($timezone == '+0700') { echo('selected'); } ?>>(GMT+07:00 <?php echo($language['hours']); ?>) Bangkok, Jakarta</option> 
            <option value="+0800" <?php if($timezone == '+0800') { echo('selected'); } ?>>(GMT+08:00 <?php echo($language['hours']); ?>) Singapore, Perth</option> 
            <option value="+0900" <?php if($timezone == '+0900') { echo('selected'); } ?>>(GMT+09:00 <?php echo($language['hours']); ?>) Osaka, Seoul, Tokyo</option> 
            <option value="+0930" <?php if($timezone == '+0930') { echo('selected'); } ?>>(GMT+09:30 <?php echo($language['hours']); ?>) Adelaide, Darwin</option> 
            <option value="+1000" <?php if($timezone == '+1000') { echo('selected'); } ?>>(GMT+10:00 <?php echo($language['hours']); ?>) Melbourne, Sydney</option> 
            <option value="+1100" <?php if($timezone == '+1100') { echo('selected'); } ?>>(GMT+11:00 <?php echo($language['hours']); ?>) New Caledonia</option> 
            <option value="+1200" <?php if($timezone == '+1200') { echo('selected'); } ?>>(GMT+12:00 <?php echo($language['hours']); ?>) Auckland, Wellington, Fiji</option> 
          </select> <a href="#" class="tooltip"><img src="../../images/help_dialog.gif" width="9" height="11" border="0"><span style="left: -115px; top: -65px;"><?php echo($language['timezone']); ?>: <?php echo($language['timezone_tooltip']); ?>.</span></a></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="5"><div align="center"> 
            <input name="SAVE" type="hidden" id="SAVE" value="true"> 
            <input name="Submit" type="submit" id="Submit" value="<?php if ($_POST['SAVE'] == 'true') { echo($config_status); } else { echo($language['save']); } ?>" <?php if ($_POST['SAVE'] == 'true') { echo('disabled="true"'); } ?>>
          </div></td> 
      </tr> 
    </table> 
  </form> 
</div> 
</body>
</html>
