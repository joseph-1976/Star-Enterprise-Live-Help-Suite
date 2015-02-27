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
include('../include/functions.php');

ignore_user_abort(true);

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
<!--

<?php
$timeout = $user_panel_refresh_rate * 2;
if ( pendingUsersPopup($timeout) == 'true' || transferredUsersPopup($timeout) == 'true' ) {
?>
parent.usersFrame.location.reload(true);
window.setTimeout('parent.usersRefresherFrame.location.reload(true);', '<?php echo($user_panel_refresh_rate * 1000); ?>');
<?php
}
else {
?>
window.setTimeout('parent.usersRefresherFrame.location.reload(true);', '<?php echo($user_panel_refresh_rate * 1000); ?>');
<?php
}
?>

//-->
</script>
</head>
<body></body>
</html>
