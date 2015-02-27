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
include('../include/config_database.php');
include('../include/class.mysql.php');
include('../include/config.php');
include('../include/auth.php');

$language_file = '../locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('../locale/en/lang_en.php');
}

if (!isset($_POST['UPDATE'])){ $_POST['UPDATE'] = ""; }
if (!isset($_POST['DELETE'])){ $_POST['DELETE'] = ""; }
$status = '';
$contents = '';

if ($_POST['UPDATE'] == "true") {
	
	$contents = $_POST['CONTENTS'];
	
	if(($contents != "")) {
		$query_insert_response = "INSERT INTO " . $table_prefix . "responses(response_id,contents) VALUES('','$contents')";
		$SQL->insertquery($query_insert_response);
		$status = $language['response_added'];
	}
	else {
		$status = $language['complete_all_fields_responses'];
	}
}
elseif($_POST['DELETE'] == "true") {
	
	$response_id = $_POST['RESPONSES'];
	
	$query_delete_response = "DELETE FROM " . $table_prefix . "responses WHERE response_id = '$response_id'";
	$SQL->miscquery($query_delete_response);
	$status = $language['response_removed'];
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="MSThemeCompatible" content="Yes">
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<?php
if ($_POST['UPDATE'] == "true" || $_POST['DELETE'] == "true") {
?>
<script language="JavaScript" type="text/JavaScript">
<!--
parent.messengerFrame.location.reload();
//-->
</script>
<?php
}
?>
<body> 
<div align="center"> 
  <table width="401" border="0" align="center"> 
    <tr> 
      <td width="22"><img src="../icons/mail_edit.gif" alt="<?php echo($language['manage_responses']); ?>" width="22" height="22"></td> 
      <td><em class="heading"><?php echo($language['manage_responses']); ?> :: <?php echo($current_username); ?></em> </td> 
      <td width="48">&nbsp;</td> 
    </tr> 
    <form name="updateSettings" method="post" action="manage_responses.php?<?php echo(SID); ?>"> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="center"><strong> 
            <?php
echo($status);
?> 
            <input name="UPDATE" type="hidden" id="UPDATE" value="true"> 
            </strong></div></td> 
        <td>&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td width="317"><div align="left"><?php echo($language['add_responses']); ?>:</div></td> 
        <td width="48">&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"> 
            <input name="CONTENTS" type="text" id="CONTENTS" value="<?php echo(stripslashes($contents)); ?>" size="40"> 
          </div></td> 
        <td> <input type="submit" name="Submit" value="<?php echo($language['add']); ?>"></td> 
      </tr> 
    </form> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2"><div align="center" class="small"><em><?php echo($language['response_instructions']); ?></em></div></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2"><div align="center"><?php echo($language['or']); ?></div></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td><div align="left"><?php echo($language['delete_responses']); ?>:</div></td> 
      <td>&nbsp;</td> 
    </tr> 
    <form name="deleteResponse" method="post" action="manage_responses.php?<?php echo(SID); ?>"> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"> 
            <select name="RESPONSES" id="RESPONSES" width="300" style="width:300px;"> 
              <?php
		$query_select_responses = "SELECT * FROM " . $table_prefix . "responses";
		$rows = $SQL->selectall($query_select_responses);
		if (is_array($rows)) {
			foreach($rows as $row) {
				if (is_array($row)) {
					?> 
              <option value="<?php echo($row['response_id']); ?>"><?php echo($row['contents']); ?></option> 
              <?php
				}
			}
		}
		?> 
            </select> 
            <input name="DELETE" type="hidden" id="DELETE" value="true"> 
          </div></td> 
        <td> <input type="submit" name="Submit" value="<?php echo($language['delete']); ?>"></td> 
      </tr> 
    </form> 
  </table> 
</div> 
</body>
</html>
