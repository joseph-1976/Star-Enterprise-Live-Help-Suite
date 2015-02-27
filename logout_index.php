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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

$language_file = './locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('./locale/en/lang_en.php');
}

session_start();
$from_login_id = $_SESSION['GUEST_LOGIN_ID'];
session_write_close();

if (!isset($_GET['COMPLETE'])){ $_GET['COMPLETE'] = ""; }
$complete = $_GET['COMPLETE'];
if ($complete == '') { $complete = 'false'; }

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<link href="styles/styles.php" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.background {
	background-image: url(./images/livehelp_headset.gif);
	background-repeat: no-repeat;
	background-position: right bottom;
}
.header-background {
	background-image: url(./images/livehelp_chat_header_bg.gif);
	background-repeat: no-repeat;
	background-position: left top;
}
-->
</style>
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="background"> 
<div align="center"> 
  <table width="525" border="0" align="center" cellpadding="7" cellspacing="0" class="header-background">
    <tr>
      <td><div align="left"><img src="<?php echo($site_address . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0" /></div></td>
    </tr>
  </table>
  <table border="0" align="right" cellpadding="2" cellspacing="2"> 
    <tr> 
      <td width="30"><div align="center"><a href="print_display.php" target="printFrame"><img src="icons/fileprint.gif" alt="<?php echo($language['print_chat']); ?>" width="22" height="22" border="0"></a></div></td> 
      <td><a href="print_display.php" target="printFrame" class="normlink"><?php echo($language['print_chat']); ?></a></td> 
      <td>::</td> 
      <td width="30"><div align="center"><a href="#" onClick="parent.close();"><img src="icons/ignore_user.gif" alt="<?php echo($language['close_window']); ?>" width="16" height="16" border="0"></a></div></td>
      <td><a href="#" onClick="parent.close();" class="normlink"><?php echo($language['close_window']); ?></a></td> 
    </tr> 
  </table> 
  <p>&nbsp;</p>
  <p align="center"><?php echo($language['logout_message']); ?></p> 
  <p align="center">  
  <form name="rateSession" method="post" action="rate_session.php"> 
    <p><?php echo($language['please_rate_service']); ?>:</p> 
    <?php
if ($complete == 'true') {
?> 
    <strong><?php echo($language['rating_thank_you']); ?></strong> 
    <?php
}
else {
?> 
    <table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
        <td><?php echo($language['rate_service']); ?>: </td> 
        <td><select name="RATING" id="RATING"> 
            <option value="5"><?php echo($language['excellent']); ?></option> 
            <option value="4"><?php echo($language['very_good']); ?></option> 
            <option value="3"><?php echo($language['good']); ?></option> 
            <option value="2"><?php echo($language['fair']); ?></option> 
            <option value="1"><?php echo($language['poor']); ?></option> 
          </select> </td> 
        <td> <input type="submit" name="Submit" value="<?php echo($language['rate']); ?>"></td> 
      </tr> 
    </table> 
  </form> 
  </p> 
  <?php
}
?> 
  <p><?php echo($language['further_assistance']); ?></p> 
  <p><br> 
  </p> 
  <p class="small"><?php echo($language['stardevelop_copyright']); ?></p> 
</div> 
</body>
</html>
