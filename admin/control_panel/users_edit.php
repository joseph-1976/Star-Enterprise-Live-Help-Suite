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

if (!isset($_GET['UID'])){ $_GET['UID'] = ""; }
if (!isset($_POST['UPDATE'])){ $_POST['UPDATE'] = ""; }
$error = '';
$error_username = '';
$error_access_denied = '';

$user_id = $_GET['UID'];

// Get the existing details, dteails of user that's details are being changed from the users table
$query_user_details = "SELECT * FROM " . $table_prefix . "users WHERE user_id = '$user_id'";
$row_user_details = $SQL->selectquery($query_user_details);

if($_POST['UPDATE'] == 'true') {

	$username = $_POST['USERNAME'];
	$first_name = $_POST['FIRST_NAME'];
	$last_name = $_POST['LAST_NAME'];
	$email = $_POST['EMAIL'];
	$department = $_POST['DEPARTMENT'];
	$account_status = $_POST['ACCOUNT_STATUS'];
	$access_level = $_POST['ACCESS_LEVEL'];

	if (($current_access_level > 1 && $current_user_id != $user_id) || ($current_access_level >= 1 && $current_department != $row_user_details['department'])) {
		$error_access_denied = 'true';
	}
	elseif ($username == '' || $first_name == '' || $email == '' || ($department == '' && $current_access_level < 1)) {
		$error = "true";
	}
	elseif ($username != $row_user_details['username']) {
		// Check username doesn't already exist within the users table, duplicate users not allowed
		$query_check_username = "SELECT user_id FROM " . $table_prefix . "users WHERE username = '$username'";
		$row = $SQL->selectquery($query_check_username);
		if (is_array($row)) {
			$error_username = 'true';
		}
	}

	
	if ($error == '' && $error_username == '' && $error_access_denied == '') {
		// Don't update username, account status and access levels if...
		// User is the default root user setup with the Install and root user is the entity changing their own details
		if ($current_access_level == -1  && $current_user_id == $row_user_details['user_id']) {
			$query_edit_user = "UPDATE " . $table_prefix . "users SET first_name = '$first_name', last_name = '$last_name', email = '$email', department = '$department' WHERE user_id = '$user_id'";
			$SQL->miscquery($query_edit_user);
			header('Location: ./users_index.php?' . SID);
		}
		// Don't update account status and access levels if...
		// Loged in user is a Full Admin user or Root Superuser and...
		// they are changing their own details
		elseif($current_access_level < 1 && $current_user_id == $row_user_details['user_id']) {
			$query_edit_user = "UPDATE " . $table_prefix . "users SET username = '$username', first_name = '$first_name', last_name = '$last_name', email = '$email', department = '$department' WHERE user_id = '$user_id'";
			$SQL->miscquery($query_edit_user);
			
			// Update the username of the current session for the changed username
			$query_edit_user = "UPDATE " . $table_prefix . "sessions SET username = '$username' WHERE login_id = '$current_login_id'";
			$SQL->miscquery($query_edit_user);
			
			header('Location: ./users_index.php?' . SID);
		}
		// Update account status and access levels if...
		// Loged in user is a Full Admin user or Root Superuser and...
		// they are changing other users details
		elseif($current_access_level < 1 && $current_user_id != $row_user_details['user_id']) {
			$query_edit_user = "UPDATE " . $table_prefix . "users SET username = '$username', first_name = '$first_name', last_name = '$last_name', email = '$email', department = '$department', account_status = '$account_status', access_level = '$access_level' WHERE user_id = '$user_id'";
			$SQL->miscquery($query_edit_user);
			header('Location: ./users_index.php?' . SID);
		}
		// Update account status if...
		// Loged in user is a Department Admin user and...
		// they are changing other users details within their department
		elseif($current_access_level == 1 && $current_user_id != $row_user_details['user_id'] && $current_department == $row_user_details['department']) {
			$query_edit_user = "UPDATE " . $table_prefix . "users SET username = '$username', first_name = '$first_name', last_name = '$last_name', email = '$email', account_status = '$account_status', access_level = '$access_level' WHERE user_id = '$user_id'";
			$SQL->miscquery($query_edit_user);	
			header('Location: ./users_index.php?' . SID);
		}
		// Update all user information details
		elseif($current_access_level >= 1) {
			$query_edit_user = "UPDATE " . $table_prefix . "users SET username = '$username', first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE user_id = '$user_id'";
			$SQL->miscquery($query_edit_user);
			
			// Update the username of the current session for the changed username
			$query_edit_user = "UPDATE " . $table_prefix . "sessions SET username = '$username' WHERE login_id = '$current_login_id'";
			$SQL->miscquery($query_edit_user);
			
			header('Location: ./users_index.php?' . SID);
		}
		else {
			header('Location: ./users_index.php?' . SID);
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
  <form action="./users_edit.php?UID=<?php echo($row_user_details['user_id']); ?>&<?php echo(SID); ?>" method="post"> 
    <table width="400" border="0"> 
      <tr> 
        <td width="22"><img src="../../icons/staff.gif" alt="<?php echo($language['manage_accounts']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['edit_user_details']); ?> :: <?php echo($row_user_details['username']); ?></em></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2">&nbsp;</td> 
      </tr> 
      <?php
		if ($error_access_denied == 'true'){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> <strong> <?php echo($language['edit_access_denied']); ?></strong></div></td> 
      </tr> 
      <tr> 
        <?php
		}
		elseif ($error == 'true'){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> <strong><?php echo($language['complete_error']); ?></strong> </div></td> 
      </tr> 
      <tr> 
        <?php
		}
		elseif ($error_username == 'true'){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> <strong><?php echo($language['add_user_exists']); ?></strong> </div></td> 
      </tr> 
      <tr> 
        <?php
		}
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['username']); ?>:</div></td> 
        <td><?php if(($row_user_details['username'] == 'root' && $row_user_details['access_level'] == -1)) { ?> 
          <em><?php echo($row_user_details['username']); ?> (Superuser Account)
          <input name="USERNAME" type="hidden" value="<?php echo($row_user_details['username']); ?>"> 
          </em> 
          <?php } else { ?> 
          <input name="USERNAME" type="text" id="USERNAME" value="<?php echo($row_user_details['username']); ?>"> 
          <?php } ?></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['first_name']); ?>:</div></td> 
        <td><input name="FIRST_NAME" type="text" id="FIRST_NAME" value="<?php echo($row_user_details['first_name']); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['last_name']); ?>:</div></td> 
        <td><input name="LAST_NAME" type="text" id="LAST_NAME" value="<?php echo($row_user_details['last_name']); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['email']); ?>:</div></td> 
        <td><input name="EMAIL" type="text" id="EMAIL" value="<?php echo($row_user_details['email']); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['department']); ?>:</div></td> 
        <td><input name="DEPARTMENT" type="text" id="DEPARTMENT" value="<?php echo($row_user_details['department']); ?>"<?php if($current_access_level > 1) { echo(' disabled="true"'); } ?>></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['access_level']); ?>:</div></td> 
        <td><select name="ACCESS_LEVEL" id="ACCESS_LEVEL"<?php if ($current_access_level > 1 || ($current_access_level == 1 && current_department != $row_user_details['department'])) { echo(' disabled="true"'); } ?>> 
            <option value="0"<?php if ($row_user_details['access_level'] <= 0) { echo(' selected'); } ?>><?php echo($language['full_administrator']); ?></option> 
            <option value="1"<?php if ($row_user_details['access_level'] == 1) { echo(' selected'); } ?>><?php echo($language['department_administrator']); ?></option> 
            <option value="2"<?php if ($row_user_details['access_level'] == 2) { echo(' selected'); } ?>><?php echo($language['limited_administrator']); ?></option> 
            <option value="3"<?php if ($row_user_details['access_level'] == 3) { echo(' selected'); } ?>><?php echo($language['support_sales_staff']); ?></option> 
            <option value="4"<?php if ($row_user_details['access_level'] == 4) { echo(' selected'); } ?>><?php echo($language['guest']); ?></option> 
          </select></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['account_status']); ?>:</div></td> 
        <td> <input name="ACCOUNT_STATUS" type="radio" value="0"<?php if ($row_user_details['account_status'] == 0) { echo(' checked'); } ?><?php if ($current_access_level > 1 || ($current_access_level == 1 && current_department != $row_user_details['department'])) { echo(' disabled="true"'); } ?>> 
          <?php echo($language['enabled']); ?> 
          <input name="ACCOUNT_STATUS" type="radio" value="1"<?php if ($row_user_details['account_status'] == 1) { echo(' checked'); } ?><?php if ($current_access_level > 1 || ($current_access_level == 1 && current_department != $row_user_details['department'])) { echo(' disabled="true"'); } ?>> 
          <?php echo($language['disabled']); ?> </td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
      </tr> 
    </table> 
    <input name="UPDATE" type="hidden" id="UPDATE" value="true"> 
    <input type="submit" name="Submit" value="<?php echo($language['save']); ?>"> 
&nbsp; 
    <input name="Password" type="button" onClick="document.location = './users_password.php?UID=<?php echo($user_id); ?>&<?php echo(SID); ?>'" value="<?php echo($language['password']); ?>"> 
&nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>"> 
  </form> 
</div> 
</body>
</html>
