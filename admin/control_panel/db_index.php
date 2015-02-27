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

$total_length = '';

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="MSThemeCompatible" content="Yes">
<?php
if ($current_win32_client == 'true') {
?>
<style type="text/css">
<!--
.background {
	background-image: url(../../images/background_database.gif);
	background-repeat: no-repeat;
	background-position: right bottom;
}
-->
</style>
<?php
}
?>
<link href="../../styles/styles.php" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--

function resetDatabase() {
	var result = confirm('Are you sure you wish to reset the Live Help database? Live Help chat, statistics and visitor data will be deleted.');
	if (result = true) {
		document.location.href="./db_reset.php";
	}
}

//-->
</script>
</head>
<body class="background"> 
<div align="center"> 
  <table width="400" border="0"> 
    <tr> 
      <td width="22"><img src="../../icons/dbase_small.gif" alt="<?php echo($language['manage_db']); ?>" width="22" height="22"></td> 
      <td colspan="2"><em class="heading"><?php echo($language['manage_db']); ?> :: <?php echo(DB_NAME); ?> </em></td> 
    </tr> 
    <tr> 
      <td></td> 
      <td></td> 
      <td></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td><strong><?php echo($language['table_name']); ?></strong></td> 
      <td><div align="right"><strong><?php echo($language['size']); ?></strong></div></td> 
    </tr> 
    <?php
$query_table_status = "SHOW TABLE STATUS";
$rows_table_status = $SQL->selectall($query_table_status);
if (is_array($rows_table_status)) {
	foreach ($rows_table_status as $row) {
		if (is_array($row)) {
?> 
    <tr> 
      <td>&nbsp;</td> 
      <td><?php echo($row["Name"]); ?></td> 
      <td><div align="right"><?php echo($row['Data_length']+$row['Index_length']); ?> bytes </div></td> 
    </tr> 
    <?php
		}
		$total_length += ($row['Data_length']+$row['Index_length']);
	}
}

?> 
    <tr> 
      <td>&nbsp;</td> 
      <td><div align="right"><strong><?php echo($language['total']); ?>:</strong></div></td> 
      <td><div align="right"><?php echo(round($total_length/1024,2)); ?> KB</div></td> 
    </tr> 
  </table> 
  <form action="./db_sql_dump.php?<?php echo(SID); ?>" method="get" name="sql_dump" id="sql_dump"> 
    <input name="SQL_DUMP" type="hidden" id="SQL_DUMP" value="true">
    <input type="submit" name="Submit" value="<?php echo($language['backup']); ?>">&nbsp;<input type="button" name="Reset" value="<?php echo($language['reset_database']); ?>" onclick="resetDatabase();">  
  </form> 
</div> 
</body>
</html>
