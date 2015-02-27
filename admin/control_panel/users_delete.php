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
if (!isset($_POST['DELETE'])){ $_POST['DELETE'] = ""; }
$error_access_denied = '';

$user_id = $_GET['UID'];

$query_user_details = "SELECT * FROM " . $table_prefix . "users WHERE user_id = '$user_id'";
$row_user_details = $SQL->selectquery($query_user_details);

if($_POST['DELETE'] == "true") {

	if (($current_user_id == $user_id) || ($current_access_level > 1 && $current_user_id != $user_id) || ($current_access_level == 1 && $current_department != $row_user_details['department'])) {
		$error_access_denied = 'true';
	}
	else {
		$query_delete_user = "DELETE FROM " . $table_prefix . "users WHERE user_id = '$user_id'";
		$SQL->miscquery($query_delete_user);
		header('Location: ./users_index.php?' . SID);
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
  <form action="./users_delete.php?UID=<?php echo($row_user_details['user_id']); ?>&<?php echo(SID); ?>" method="post"> 
    <table width="400" border="0"> 
      <tr> 
        <td width="22"><img src="../../icons/staff.gif" alt="<?php echo($language['manage_accounts']); ?>" width="22" height="22"></td> 
        <td colspan="2"><em class="heading"><?php echo($language['delete_user_details']); ?> :: <?php echo($row_user_details['username']); ?></em></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> 
            <table width="300" border="0"> 
              <tr> 
                <td width="32"><img src="../../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td> 
                <td><div align="center"> 
                    <p><em><?php echo($language['warning']); ?><strong><br> 
                      </strong><?php echo($language['delete_user_warning']); ?></em></p> 
                  </div></td> 
              </tr> 
            </table> 
          </div></td> 
      </tr> 
      <?php
		if ($error_access_denied == 'true'){
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td colspan="2"> <div align="center"> <strong> <?php echo($language['delete_access_denied']); ?></strong></div></td> 
      </tr> 
      <tr> 
        <?php
		}
		?> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['username']); ?>:</div></td> 
        <td><input name="USERNAME" type="text" id="USERNAME" value="<?php echo($row_user_details['username']); ?>"></td> 
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
        <td><input name="DEPARTMENT" type="text" id="DEPARTMENT" value="<?php echo($row_user_details['department']); ?>"></td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
        <td>&nbsp;</td> 
      </tr> 
    </table> 
    <input name="DELETE" type="hidden" id="DELETE" value="true"> 
    <input type="submit" name="Submit" value="<?php echo($language['delete_user']); ?>"> 
  </form> 
</div> 
</body>
</html>
