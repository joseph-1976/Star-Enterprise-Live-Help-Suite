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
$pretyped_option = '';
$contents = '';
$description = '';

if ($_POST['UPDATE'] == "true") {
	
	$pretyped_option = $_POST['PRETYPED_OPTION'];
	$contents = $_POST['CONTENTS'];
	$description = $_POST['DESCRIPTION'];
	
	if(($pretyped_option == "LINK") && ($description != "" || $contents != "")) {
		$command_contents = "<a href=\'$contents\' target=\'_blank\' class=\'normlink\'>$description</a>";
		$query_insert_command = "INSERT INTO " . $table_prefix . "commands(command_id,type,description,contents) VALUES('','$pretyped_option','$description','$command_contents')";
		$SQL->insertquery($query_insert_command);
		$status = $language['command_added'];
	}
	else if(($pretyped_option == "IMAGE") && ($description != "" || $contents != "")) {
		$command_contents = "<img src=\'$contents\' alt=\'$description\'>";
		$query_insert_command = "INSERT INTO " . $table_prefix . "commands(command_id,type,description,contents) VALUES('','$pretyped_option','$description','$command_contents')";
		$SQL->insertquery($query_insert_command);
		$status = $language['command_added'];
	}
	else if(($pretyped_option == "JAVASCRIPT") && ($description != "" || $contents != "")) {
		$command_contents = "<script language=\'JavaScript\' type=\'text/JavaScript\'>$contents</script>";
		$query_insert_command = "INSERT INTO " . $table_prefix . "commands(command_id,type,description,contents) VALUES('','$pretyped_option','$description','$command_contents')";
		$SQL->insertquery($query_insert_command);
		$status = $language['command_added'];
	}
	else if(($pretyped_option == "PUSH") && ($description != "" || $contents != "")) {
		$command_contents = "<script language=\'JavaScript\' type=\'text/JavaScript\'>if (top.window.opener) { top.window.opener.location.href = \'$contents\'; }</script>";
		$query_insert_command = "INSERT INTO " . $table_prefix . "commands(command_id,type,description,contents) VALUES('','$pretyped_option','$description','$command_contents')";
		$SQL->insertquery($query_insert_command);
		$status = $language['command_added'];
	}
	else {
		$status = $language['complete_all_fields_commands'];
	}
}
elseif($_POST['DELETE'] == "true") {
	
	$command_id = $_POST['COMMANDS'];
	
	$query_delete_command = "DELETE FROM " . $table_prefix . "commands WHERE command_id = '$command_id'";
	$SQL->miscquery($query_delete_command);
	$status = $language['command_removed'];
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
      <td width="22"><img src="../icons/mail_edit.gif" alt="<?php echo($language['manage_commands']); ?>" width="22" height="22"></td> 
      <td colspan="2"><em class="heading"><?php echo($language['manage_commands']); ?> :: <?php echo($current_username); ?></em> </td> 
    </tr> 
    <form name="updateSettings" method="post" action="manage_commands.php?<?php echo(SID); ?>"> 
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
        <td width="317"><div align="left"><?php echo($language['add_commands']); ?>:</div></td> 
        <td width="48">&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="center"> 
            <input name="PRETYPED_OPTION" type="radio" value="LINK" <?php if ($pretyped_option == "LINK") { echo("checked"); }?>> 
            <?php echo($language['link']); ?> 
            <input name="PRETYPED_OPTION" type="radio" value="IMAGE" <?php if ($pretyped_option == "IMAGE") { echo("checked"); }?>> 
            <?php echo($language['image']); ?> 
            <input name="PRETYPED_OPTION" type="radio" value="JAVASCRIPT" <?php if ($pretyped_option == "JAVASCRIPT") { echo("checked"); }?>> 
            <?php echo($language['javascript']); ?> 
            <input name="PRETYPED_OPTION" type="radio" value="PUSH" <?php if ($pretyped_option == "PUSH") { echo("checked"); }?>> 
            <?php echo($language['push']); ?> </div></td> 
        <td>&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['description']); ?>:
            <input name="DESCRIPTION" type="text" id="DESCRIPTION" value="<?php echo(stripslashes($description)); ?>" size="30"> 
          </div></td> 
        <td>&nbsp;</td> 
      </tr> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"><?php echo($language['contents']); ?>: 
            <input name="CONTENTS" type="text" id="CONTENTS" value="<?php echo(stripslashes($contents)); ?>" size="30"> 
          </div></td> 
        <td><input type="submit" name="Submit" value="<?php echo($language['add']); ?>"></td> 
      </tr> 
    </form> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2"><div align="center" class="small"><em><?php echo($language['command_instructions']); ?></em></div></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2"><div align="center"><?php echo($language['or']); ?></div></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td><div align="left"><?php echo($language['delete_commands']); ?>:</div></td> 
      <td>&nbsp;</td> 
    </tr> 
    <form name="deleteCommand" method="post" action="manage_commands.php?<?php echo(SID); ?>"> 
      <tr> 
        <td>&nbsp;</td> 
        <td><div align="right"> 
            <select name="COMMANDS" id="COMMANDS" width="300" style="width:300px;"> 
              <?php
		$query_select_commands = "SELECT * FROM " . $table_prefix . "commands";
		$rows = $SQL->selectall($query_select_commands);
		if (is_array($rows)) {
			foreach($rows as $row) {
				if (is_array($row)) {
					?> 
              <option value="<?php echo($row['command_id']); ?>"><?php echo($row['type'] . " " . $row['description']); ?></option> 
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
