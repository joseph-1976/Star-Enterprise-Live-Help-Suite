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

if (!isset($_SERVER['DOCUMENT_ROOT'])){ $_SERVER['DOCUMENT_ROOT'] = ""; }

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
  <table width="400" border="0" align="center">
    <tr>
      <td width="22"><img src="../../icons/configure_small.gif" alt="<?php echo($language['manage_settings']); ?> :: <?php echo($language['general']); ?>" width="22" height="22"></td>
      <td><em class="heading"><?php echo($language['manage_settings']); ?> :: <?php echo($language['code']); ?></em> </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php include("./settings_toolbar.php"); ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php echo($language['online_tracker']); ?>:</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center">
          <textarea name="textarea" cols="35" rows="2" style="width:300px;height:35px;">
<?php include('../../include/tracking_code.php'); ?>
          </textarea>
        </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center" class="small"><em><?php echo($language['online_tracker_details']); ?></em></div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php echo($language['status_indicator']); ?>:</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center">
        <div align="center">
<textarea name="textarea" cols="35" rows="2" style="width:300px;height:35px;">
<?php include('../../include/status_code.php'); ?>
</textarea></textarea></div></td></tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="center" class="small"><em><?php echo($language['status_indicator_details']); ?></em></div></td>
    </tr>
  </table>
</div></body></html>