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
if (!isset($_SERVER['DOCUMENT_ROOT'])){ $_SERVER['DOCUMENT_ROOT'] = ""; }
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }
if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
if (!isset($_GET['TRACKER'])){ $_GET['TRACKER'] = ""; }
if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = ""; }

if (isset($_SERVER['PATH_TRANSLATED']) && $_SERVER['PATH_TRANSLATED'] != '') { $env_path = $_SERVER["PATH_TRANSLATED"]; } else { $env_path = $_SERVER["SCRIPT_FILENAME"]; }
$full_path = str_replace("\\\\", "/", $env_path);
$livehelp_path = $_SERVER['PHP_SELF'];
if (strpos($full_path, '/') === false) { $livehelp_path = str_replace("\\", "/", $livehelp_path); }
$pos = strpos($full_path, $livehelp_path);
if ($pos === false) {
	$install_path = $full_path;
}
else {
	$install_path = substr($full_path, 0, $pos);
}

include($install_path . '/livehelp/include/config_database.php');
include($install_path . '/livehelp/include/class.mysql.php');
include($install_path . '/livehelp/include/config.php');

$language_file = $install_path . '/livehelp/locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include($install_path . '/livehelp/locale/en/lang_en.php');
}
header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
</head>
<body> 
<div align="center"> 
  <p>&nbsp;</p> 
  <table width="300" border="0"> 
    <tr> 
      <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td> 
      <td><div align="center"> 
          <p><em><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['general_access_denied']); ?></font></em></p> 
          <p><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="4"><strong> </strong></font><?php echo($language['access_denied_line_one']); ?></font></em></p> 
          <p><em><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['access_denied_line_two']); ?></font></em></p> 
        </div></td> 
    </tr> 
  </table> 
</div> 
</body>
</html>
