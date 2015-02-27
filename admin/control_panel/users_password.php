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

if (!isset($_GET['UID'])){ $_GET['UID'] = ''; }
if (!isset($_GET['COMPLETE'])){ $_GET['COMPLETE'] = ''; }
if (!isset($_POST['UPDATE'])){ $_POST['UPDATE'] = ''; }
$error = '';
$error_password = '';
$error_incorrect = '';

$user_id = $_GET['UID'];
$complete = $_GET['COMPLETE'];

$query_user_details = "SELECT username FROM " . $table_prefix . "users WHERE user_id = '$user_id'";
$row_user_details = $SQL->selectquery($query_user_details);

if($_POST['UPDATE'] == 'true') {

	$current_password = $_POST['CURRENT_PASSWORD'];
	$new_password = $_POST['NEW_PASSWORD'];
	$confirm_password = $_POST['CONFIRM_PASSWORD'];

	if ($current_password == '' || $new_password == '' || $confirm_password == '') {
		$error = 'true';
	}
	elseif ($new_password != $confirm_password) {
		$error_password = 'true';
	}
	elseif (($error != 'true') && ($error_password != 'true')) {
	
		$md5_current_password = md5($current_password);
		$md5_new_password = md5($new_password);
	
		$query_user_password = "SELECT * FROM " . $table_prefix . "users WHERE password = '$md5_current_password' AND user_id = '$user_id'";
		$row_user_password = $SQL->selectquery($query_user_password);
			if (!is_array($row_user_password)) {
				$error_incorrect = 'true';
			}
			else {
				$query_edit_password = "UPDATE " . $table_prefix . "users SET password = '$md5_new_password' WHERE user_id = '$user_id'";
				$SQL->miscquery($query_edit_password);	
				header('Location: ./users_password.php?COMPLETE=true&UID=' . $user_id);
		}
	}
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="MSThemeCompatible" content="Yes">
<link href="../../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body> 
<div align="center"> 
  <form action="./users_password.php?UID=<?php echo($user_id); ?>&<?php echo(SID); ?>" method="post"> 
    <table width="400" border="0"> 
      <tr> 
        <td width="22"><img src="../../icons/staff.gif" alt="<?php echo($language['manage_accounts']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['change_user_password']); ?> :: <?php echo($row_user_details['username']); ?></em></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2">&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><table width="300" border="0" align="center"> 
            <tr> 
              <td width="32"><img src="../../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td> 
              <td><div align="center"> 
                  <p><em><?php echo($language['warning']); ?><strong><br> 
                    </strong><?php echo($language['change_user_warning']); ?></em></p> 
                </div></td> 
            </tr> 
          </table></td> 
      </tr> 
      <?php
		if ($error == "true"){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"><?php echo($language['complete_error']); ?> </div></td> 
      </tr> 
      <?php
		}
		if ($error_password == "true"){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><div align="center"><strong><?php echo($language['change_user_match_error']); ?></strong></div></td> 
      </tr> 
      <?php
		}
		if ($error_incorrect == "true"){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><div align="center"><strong><?php echo($language['change_user_password_error']); ?></strong></div></td> 
      </tr> 
      <?php
		}
		if ($complete == "true"){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"><div align="center"><strong><?php echo($language['change_user_changed']); ?></strong></div></td> 
      </tr> 
      <?php
		}
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['current_password']); ?>: </div></td> 
        <td><input name="CURRENT_PASSWORD" type="password" id="CURRENT_PASSWORD"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"> <?php echo($language['new_password']); ?>:</div></td> 
        <td><input name="NEW_PASSWORD" type="password" id="NEW_PASSWORD"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['retype_password']); ?>:</div></td> 
        <td><input name="CONFIRM_PASSWORD" type="password" id="CONFIRM_PASSWORD"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
      </tr> 
    </table> 
    <input name="UPDATE" type="hidden" id="UPDATE" value="true"> 
    <input type="submit" name="Submit" value="<?php echo($language['update_password']); ?>"> 
&nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>"> 
  </form> 
</div> 
</body>
</html>
