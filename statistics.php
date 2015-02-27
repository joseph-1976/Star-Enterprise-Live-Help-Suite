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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

session_start();
$login_id = $_SESSION['GUEST_LOGIN_ID'];
$referer_url = $_SESSION['REFERER_URL'];
session_write_close();

header('Content-type: text/html; charset=' . CHARSET);
?>
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$resolution = $_GET['WIDTH'] . ' x ' . $_GET['HEIGHT'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

$query_insert_stats = "INSERT INTO " . $table_prefix . "statistics (statistics_id,login_id,user_agent,resolution,hostname,referer_url) VALUES('', '$login_id','$user_agent','$resolution','$hostname','$referer_url')";
$SQL->miscquery($query_insert_stats);

?>
</body>
</html>