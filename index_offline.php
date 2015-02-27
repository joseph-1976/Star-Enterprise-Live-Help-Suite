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

if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
if (!isset($_POST['URL'])){ $_POST['URL'] = ""; }
if (!isset($_POST['TITLE'])){ $_POST['TITLE'] = ""; }
if (!isset($_POST['COMPLETE'])){ $_POST['COMPLETE'] = ""; }
if (!isset($_POST['SEND_COPY_TO_SELF'])){ $_POST['SEND_COPY_TO_SELF'] = ""; }

$error = '';
$invalid_email = '';
$email = '';
$name = '';
$message = '';
$status = '';

session_start();
$current_session = session_id();
session_write_close();

if($_POST['COMPLETE'] == "true") {

	if ($_POST['EMAIL'] == '' || $_POST['NAME'] == '' || $_POST['MESSAGE'] == '') {
		$error = 'true';
	}
	else {
		
		$name = stripslashes($_POST['NAME']);
		$email = stripslashes($_POST['EMAIL']);
		$message = stripslashes($_POST['MESSAGE']);
		$send_copy_to_self = $_POST['SEND_COPY_TO_SELF'];
		$url = $_POST['URL'];
		$title = $_POST['TITLE'];
		
		if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
					  '@'.
					  '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
					  '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
					  $invalid_email = 'true';
		}
		else {

			$current_page = 'Unavailable';
			$title = 'Unavailable'; 
			$referrer = 'Unavailable';
			
			$select_request_data = "SELECT current_page, current_page_title, referrer FROM " . $table_prefix . "requests WHERE session_id = '$current_session'";
			$row = $SQL->selectquery($select_request_data);
			if (is_array($row)) {
				$current_page = $row['current_page'];
				$title = $row['current_page_title'];
				$referrer = $row['referrer'];
				if ($current_page == '') { $current_page = 'Unavailable'; }
				if ($title == '') { $title = 'Unavailable'; }
				if ($referrer == '') { $referrer = 'Unavailable'; } elseif ($referrer == 'false') { $referrer = 'Direct Link / Bookmark'; }
			}
			
			if ($ip2country_installed == 'true') { 
				$ip_number = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
				
				$query_country = "SELECT c.code, c.country FROM " . $table_prefix . "countries AS c, " . $table_prefix . "ip2country AS i WHERE c.code = i.code AND i.ip_from <= " . $ip_number . " AND i.ip_to >= " . $ip_number;
				$row_country = $SQL->selectquery($query_country);
				if (is_array($row_country)){
					$code = $row_country['code'];
					$country = ucwords(strtolower($row_country['country']));
				}
				else {
					$country = $language['unavailable'];
				}
			}

			$from_name = "$name";
			$from_email = "$email";
			$to_email = "$offline_email";
			$subject = "$livehelp_name Offline Message";
			$headers = "From: ".$from_name." <".$from_email.">\n";
			$headers .= "Reply-To: ".$from_name." <".$from_email.">\n";
			$message .= "\n\n--------------------------\n";
			$message .= "IP Logged:  " . $_SERVER['REMOTE_ADDR'] . "\n";
			if ($ip2country_installed == 'true') { $message .= "Country:  $country\n"; }
			$message .= "URL:  $current_page\n";
			$message .= "URL Title:  $title\n";
			$message .= "Referrer:  $referrer\n";
			
			$sendmail_path = ini_get('sendmail_path');
			if ($sendmail_path == '') { $headers = str_replace("\n", "\r\n", $headers); $message = str_replace("\n", "\r\n", $message); }
			mail($to_email, $subject, $message, $headers);
			
			if ($send_copy_to_self == 'true') {
				$to_email = "$email";
				$headers = "From: ".$from_name." <".$from_email.">\n";
				$headers .= "Reply-To: ".$from_name." <".$from_email.">\n";
				$message = stripslashes($_POST['MESSAGE']);
				
				if ($sendmail_path == '') { $headers = str_replace("\n", "\r\n", $headers); $message = str_replace("\n", "\r\n", $message); }
				mail($to_email, $subject, $message, $headers);
			}
		}
	}
}
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
<table width="525" border="0" align="center" cellpadding="7" cellspacing="0" class="header-background"> 
  <tr> 
    <td><div align="left"><img src="<?php echo($_GET['SERVER'] . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0" /></div></td> 
  </tr> 
</table> 
<div align="center"> 
  <?php
  if($_POST['COMPLETE'] == "" || $error != "" || $invalid_email != "") {
  ?> 
  <?php echo($language['unfortunately_offline']); ?><br> 
  <?php echo($language['fill_details_below']); ?>:</div> 
<form action="index_offline.php" method="post" name="offline_message_form" id="offline_message_form"> 
  <table border="0" align="center" cellpadding="2" cellspacing="2"> 
    <tr> 
      <td><img src="icons/mail_send.gif" alt="<?php echo($language['leave_msg']); ?>" width="22" height="22"></td> 
      <td colspan="2" class="heading"><?php echo($language['leave_msg']); ?> ::</td> 
    </tr> 
    <?php
	  if ($error != "") {
	  ?> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2" valign="bottom"><div align="center"><strong><?php echo($language['complete_error']); ?></strong></div></td> 
    </tr> 
    <?php
	  }
	  elseif ($invalid_email != "") {
	  ?> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2" valign="bottom"><div align="center"><strong><?php echo($language['invalid_email_error']); ?></strong></div></td> 
    </tr> 
    <?php
	  }
	  ?> 
    <tr> 
      <td width="22">&nbsp;</td> 
      <td valign="bottom"><div align="right"><?php echo($language['your_email']); ?>:</div></td> 
      <td><input name="EMAIL" type="text" id="EMAIL" value="<?php echo($email); ?>" size="40" style="width:300px;filter:alpha(opacity=75);moz-opacity:0.75"></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="bottom"><div align="right"><?php echo($language['your_name']); ?>:</div></td> 
      <td> <input name="NAME" type="text" id="NAME" value="<?php echo($name); ?>" size="40" style="width:300px;filter:alpha(opacity=75);moz-opacity:0.75"></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['message']); ?>:</div></td> 
      <td align="right" valign="top"><div align="left"> 
          <textarea name="MESSAGE" cols="30" rows="4" id="MESSAGE" style="width:300px;filter:alpha(opacity=75);moz-opacity:0.75"><?php echo($message); ?></textarea> 
        </div></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2" align="right" valign="top"><div align="left"> 
          <input name="SEND_COPY_TO_SELF" type="checkbox" value="true"> 
          <?php echo($language['send_copy']); ?></div></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2" align="right" valign="top"> <div align="center"> 
          <input name="COMPLETE" type="hidden" id="COMPLETE" value="true"> 
          <input name="SERVER" type="hidden" id="SERVER" value="<?php echo($_GET['SERVER']); ?>"> 
          <table border="0" cellpadding="2" cellspacing="2"> 
            <tr> 
              <td><div align="center"> 
                  <input type="Submit" name="Submit" value="<?php echo($language['send_msg']); ?>"> 
                </div></td> 
              <td><input type="Button" name="Close" onClick="parent.close();" value="<?php echo($language['close_window']); ?>"> </td> 
            </tr> 
          </table> 
          <span class="small"><?php echo($language['stardevelop_copyright']); ?></span></div></td> 
    </tr> 
  </table> 
</form> 
<?php
  }
  else {
  ?> 
<p><?php echo($language['thank_you_enquiry']); ?><br> 
  <?php echo($language['contacted_soon']); ?></p> 
<table border="0" align="center" cellpadding="2" cellspacing="2"> 
  <tr> 
    <td><img src="icons/mail_send.gif" alt="<?php echo($language['leave_msg']); ?>" width="22" height="22"></td> 
    <td colspan="2"><?php echo($language['leave_msg']); ?> ::</td> 
  </tr> 
  <?php
	  if ($status != "") {
	  ?> 
  <?php
	  }
	  ?> 
  <tr> 
    <td width="22">&nbsp;</td> 
    <td valign="bottom"><div align="right"><?php echo($language['your_email']); ?>:</div></td> 
    <td width="260"><em><?php echo($email); ?></em></td> 
  </tr> 
  <tr> 
    <td>&nbsp;</td> 
    <td valign="bottom"><div align="right"><?php echo($language['your_name']); ?>:</div></td> 
    <td><em><?php echo($name); ?></em></td> 
  </tr> 
  <tr> 
    <td>&nbsp;</td> 
    <td valign="top"><div align="right"><?php echo($language['message']); ?>:</div></td> 
    <td align="right" valign="top">&nbsp;</td> 
  </tr> 
  <tr> 
    <td>&nbsp;</td> 
    <td colspan="2" align="right" valign="top"><div align="center"> 
        <textarea name="MESSAGE" cols="40" rows="4" id="MESSAGE" style="width:300px;filter:alpha(opacity=75);moz-opacity:0.75"><?php echo(stripslashes($_POST['MESSAGE'])); ?></textarea> 
      </div></td> 
  </tr> 
  <tr> 
    <td>&nbsp;</td> 
    <td colspan="2" align="right" valign="top"> <div align="center"> 
        <table border="0" cellpadding="2" cellspacing="2"> 
          <tr> 
            <td><input type="Button" name="Close" onClick="parent.close();" value="<?php echo($language['close_window']); ?>"> </td> 
          </tr> 
        </table> 
        <span class="small"><?php echo($language['stardevelop_copyright']); ?></span></div></td> 
  </tr> 
</table> 
<?php
  }
  ?> 
</body>
</html>
