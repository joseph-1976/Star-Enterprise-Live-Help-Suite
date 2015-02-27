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
include('include/config_database.php');
include('include/class.mysql.php');
include('include/config.php');

session_start();
session_write_close();

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
</head>
<frameset rows="2,*" frameborder="NO" border="0" framespacing="0">
  <frame src="displayer_js_init.php?<?php echo(SID); ?>" name="displayRefreshFrame" scrolling="NO">
  <frame src="blank.php?<?php echo(SID); ?>" name="displayContentsFrame">
</frameset><noframes></noframes>
</html>