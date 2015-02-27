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

if (!isset($_POST['ADD'])){ $_POST['ADD'] = ""; }
$error = '';
$error_username = '';
$error_access_denied = '';
$username = '';
$first_name = '';
$last_name = '';
$password = '';
$password_retype = '';
$email = '';
$department = '';
$access_level = '';

if($_POST['ADD'] == 'true') {

	$username = $_POST['USERNAME'];
	$first_name = $_POST['FIRST_NAME'];
	$last_name = $_POST['LAST_NAME'];
	$password = $_POST['PASSWORD'];
	$password_retype = $_POST['PASSWORD_RETYPE'];
	$email = $_POST['EMAIL'];
	$department = $_POST['DEPARTMENT'];
	$access_level = $_POST['ACCESS_LEVEL'];
	
	if ($current_access_level > 1 && $current_username != $username) {
		$error_access_denied = 'true';
	}
	elseif ($username == '' || $first_name == '' || $password == '' || $password_retype == '' || $email == '' || $department == '' || $password != $password_retype) {
		$error = 'true';
	}
	else {
		// Check username doesn't already exist within the users table, duplicate users not allowed
		$query_check_username = "SELECT user_id FROM " . $table_prefix . "users WHERE username = '$username'";
		$row = $SQL->selectquery($query_check_username);
		if (is_array($row)) {
			$error_username = 'true';
		}
		elseif ($error == '' && $error_access_denied == '' && $error_username == '') {
			$md5_password = md5($password);
			$query_add_user = "INSERT INTO " . $table_prefix . "users(user_id,username,first_name,last_name,password,email,department,last_login_id,account_status,access_level) VALUES('','$username','$first_name','$last_name','$md5_password','$email','$department','',0,'$access_level')";
			$SQL->insertquery($query_add_user);
			header("Location: ./users_index.php?" . SID);
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
  <form action="./users_add.php?<?php echo(SID); ?>" method="post"> 
    <table width="400" border="0"> 
      <tr> 
        <td width="22"><img src="../../icons/user_add.gif" alt="<?php echo($language['add_user_details']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['add_user_details']); ?></em></td> 
      </tr> 
      <?php
		if ($error_access_denied == 'true'){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> <strong><?php echo($language['add_access_denied']); ?></strong> </div></td> 
      </tr> 
      <tr> 
        <?php
		}
		elseif ($error == 'true'){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> <strong><?php echo($language['add_user_error']); ?></strong> </div></td> 
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
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['username']); ?>:</div></td> 
        <td><input name="USERNAME" type="text" id="USERNAME" value="<?php echo($username); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['first_name']); ?>:</div></td> 
        <td><input name="FIRST_NAME" type="text" id="FIRST_NAME" value="<?php echo($first_name); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['last_name']); ?>:</div></td> 
        <td><input name="LAST_NAME" type="text" id="LAST_NAME" value="<?php echo($last_name); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['password']); ?>:</div></td> 
        <td><input name="PASSWORD" type="password" id="PASSWORD"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['retype_password']); ?>:</div></td> 
        <td><input name="PASSWORD_RETYPE" type="password" id="PASSWORD_RETYPE"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['email']); ?>:</div></td> 
        <td><input name="EMAIL" type="text" id="EMAIL" value="<?php echo($email); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['department']); ?>:</div></td> 
        <td><?php if(($current_access_level == 1)) { ?> 
          <em><?php echo($current_department); ?>
          <input name="DEPARTMENT" type="hidden" value="<?php echo($current_department); ?>">
          </em> 
          <?php } else { ?> 
          <input name="DEPARTMENT" type="text" id="DEPARTMENT" value="<?php echo($department); ?>"> 
        <?php } ?></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['access_level']); ?>:</div></td> 
        <td><select name="ACCESS_LEVEL" id="ACCESS_LEVEL"<?php if($current_access_level > 1) { echo(' disabled="true"'); } ?>> 
            <?php
			if($current_access_level != 1) {
			?> 
            <option value="0"<?php if ($access_level == 0) { echo(' selected'); } ?>><?php echo($language['full_administrator']); ?></option> 
            <option value="1"<?php if ($access_level == 1) { echo(' selected'); } ?>><?php echo($language['department_administrator']); ?></option> 
            <?php
			}
			?> 
            <option value="2"<?php if ($access_level == 2) { echo(' selected'); } ?>><?php echo($language['limited_administrator']); ?></option> 
            <option value="3"<?php if ($access_level == 3) { echo(' selected'); } ?>><?php echo($language['support_sales_staff']); ?></option> 
            <option value="4"<?php if ($access_level == 4) { echo(' selected'); } ?>><?php echo($language['guest']); ?></option> 
          </select></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
      </tr> 
    </table> 
    <input name="ADD" type="hidden" id="ADD" value="true"> 
    <input type="submit" name="Submit" value="<?php echo($language['add_user']); ?>"> 
&nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>"> 
  </form> 
</div> 
</body>
</html>
