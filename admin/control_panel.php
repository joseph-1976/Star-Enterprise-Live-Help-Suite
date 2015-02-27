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

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body marginwidth="0" leftmargin="0" topmargin="5" bottommargin="0" rightmagin="0"> 
<table height="100%" border="0" align="right" cellpadding="0" cellspacing="0"> 
  <tr> 
    <td><div align="right"> 
        <?php
	if ($current_win32_client == 'false') {
	?> 
        <table border="0" align="center" cellpadding="2" cellspacing="2"> 
          <tr> 
            <td><div align="center"><a href="logout.php?<?php echo(SID); ?>" target="_parent"><img src="../icons/logout.gif" alt="<?php echo($language['logout']); ?>" width="22" height="22" border="0"></a></div></td> 
          </tr> 
          <tr> 
            <td><div align="center"><a href="logout.php?<?php echo(SID); ?>" target="_parent" class="normlink"><?php echo($language['logout']); ?></a></div></td> 
          </tr> 
        </table> 
        <?php
	}
	?> 
      </div></td> 
  </tr> 
  <tr> 
    <td valign="middle"><table width="90" border="0" align="right" cellpadding="4" cellspacing="4"> 
        <tr> 
          <td><div align="center"><a href="visitors_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/online_visitors.gif" width="32" height="32" border="0"></a><br> 
              <a href="visitors_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['visitors']); ?></a> </div></td> 
        </tr> 
        <tr> 
          <td><div align="center"><a href="overall_statistics.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/stats.gif" alt="<?php echo($language['statistics']); ?>" width="32" height="32" border="0"></a><br> 
              <a href="overall_statistics.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['statistics']); ?></a></div></td> 
        </tr> 
        <tr> 
          <td><div align="center"><a href="reports_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/reports.gif" alt="<?php echo($language['reports']); ?>" width="32" height="32" border="0"></a><br> 
              <a href="reports_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['reports']); ?></a></div></td> 
        </tr> 
        <tr> 
          <td><div align="center"><a href="control_panel/users_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/users.gif" alt="<?php echo($language['users']); ?>" width="32" height="32" border="0"></a><br> 
              <a href="control_panel/users_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['users']); ?></a></div></td> 
        </tr> 
        <tr> 
          <td><div align="center"><a href="control_panel/db_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/dbase.gif" alt="<?php echo($language['database']); ?>" width="32" height="32" border="0"></a><br> 
              <a href="control_panel/db_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['database']); ?></a></div></td> 
        </tr> 
        <tr> 
          <td><div align="center"><a href="control_panel/settings_index.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/configure.gif" alt="<?php echo($language['settings']); ?>" width="32" height="32" border="0"></a><br> 
              <a href="control_panel/settings_index.php?<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['settings']); ?></a></div></td> 
        </tr> 
      </table></td> 
  </tr> 
</table> 
</body>
</html>
