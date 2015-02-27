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

$guest_username = stripslashes($_GET['USER']);
$guest_login_id = $_GET['LOGIN_ID'];

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
function writeMessage(messageText) {
    top.displayFrame.displayContentsFrame.document.write(messageText);  
}

function rescroll() {
  top.displayFrame.displayContentsFrame.window.scrollTo( 0, 99999999 );
}

writeMessage('<link href="/livehelp/styles/styles.php" rel="stylesheet" type="text/css">');

//-->
</script>
</head>
<body>
<?php

// Query database to see if guest_login_id is an operator
$query_select_admin = "SELECT user_id FROM " . $table_prefix . "users WHERE last_login_id = '$guest_login_id'";
$row = $SQL->selectquery($query_select_admin);
if (!(is_array($row))) {
	// Update the active flag of the guest user to the ID of their supporter and update the support_user to the username of their supporter
	$query_update_status = "UPDATE " . $table_prefix . "sessions SET active = '$current_login_id', support_username = '$current_username' WHERE login_id = '$guest_login_id'";
	$SQL->miscquery($query_update_status);

	// Update the flags of the messages so that user can now read all previous messages from the previous sessions.
	$query_update_sent_messages = "UPDATE " . $table_prefix . "messages SET to_login_id = '$current_login_id' WHERE from_login_id = '$guest_login_id'";
	$SQL->miscquery($query_update_sent_messages);
	$query_update_recv_messages = "UPDATE " . $table_prefix . "messages SET from_login_id = '$current_login_id' WHERE to_login_id = '$guest_login_id' AND from_login_id > 0";
	$SQL->miscquery($query_update_recv_messages);
}

$query_select_msgs = "SELECT message_id,from_login_id,to_login_id,DATE_FORMAT(DATE_ADD(message_datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE),'%H:%i:%s') AS message_time,message,src_flag,dest_flag,display_time,display_username,display_align,hidden,username AS from_username FROM " . $table_prefix . "messages," . $table_prefix . "sessions WHERE from_login_id = login_id AND (from_login_id = '$guest_login_id' OR to_login_id = '$guest_login_id') ORDER BY message_datetime";
$rows_msgs = $SQL->selectall($query_select_msgs);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {

			$guest_username = str_replace('\'', '\\\'', $guest_username);
			$support_username = str_replace('\'', '\\\'', $row['from_username']);		
			$message = str_replace('\'', '\\\'', $row['message']);
			$message = str_replace("\r\n", "\\r\\n", $message); 
			
			// Search and replace smilies with images if smilies are on
			if ($admin_smilies == "true") {
			$displayMessage = htmlSmilies($message, '../icons/');
			}
			else {
			$displayMessage = $message;
			}

			$align = 'left';
			if ($row['display_align'] == '1') { $align = 'left'; } elseif ($row['display_align'] == '2') { $align = 'center'; } elseif ($row['display_align'] == '3') { $align = 'right'; }
			
			// Outputs sent message
			if(($row['from_login_id'] == "$current_login_id")){
?>
<script language="JavaScript">
<!--
writeMessage('<?php if ($row['hidden'] == '0') { ?><table width="100%" border="0" align="center"><tr><td><div align="<?php echo($align); ?>"><font size="<?php echo($guest_chat_font_size); ?>" color="<?php echo($sent_font_color); ?>" face="<?php echo($font_type); ?>"><?php if ($row['display_time'] == '1' || $row['display_username'] == '1') { ?><strong><?php if ($row['display_time'] == '1') { echo('[' . $row['message_time'] . ']'); } ?> <?php if ($row['display_username'] == '1') { echo($support_username); } ?></strong>: <?php } } echo($displayMessage); ?><?php if ($row['hidden'] == '0') { ?><br></font></div></td></tr></table><?php } ?>');
rescroll();
//-->
</script>
<?php
				// Updates displayed flag
				$query_update_displayed = "UPDATE " . $table_prefix . "messages SET src_flag=1 WHERE message_id = '" . $row['message_id'] . "'";
				$SQL->miscquery($query_update_displayed);
			}
			// Outputs received message
			if(($row['to_login_id'] == "$current_login_id")){	
?>
<script language="JavaScript">
<!--
writeMessage('<?php if ($row['hidden'] == '0') { ?><table width="100%" border="0" align="center"><tr><td><div align="<?php echo($align); ?>"><font size="<?php echo($guest_chat_font_size); ?>" color="<?php echo($received_font_color); ?>" face="<?php echo($font_type); ?>"><?php if ($row['display_time'] == '1' || $row['display_username'] == '1') { ?><strong><?php if ($row['display_time'] == '1') { echo('[' . $row['message_time'] . ']'); } ?> <?php if ($row['display_username'] == '1') { echo($support_username); } ?></strong>: <?php } } echo($displayMessage); ?><?php if ($row['hidden'] == '0') { ?><br></font></div></td></tr></table><?php } ?>');
rescroll();
//-->
</script>
<?php
				// Updates displayed flag
				$query_update_displayed = "UPDATE " . $table_prefix . "messages SET dest_flag=1 WHERE message_id = '" . $row['message_id'] . "'";
				$SQL->miscquery($query_update_displayed);
			}
		}
	}
?>
</script>
<?php
}
?>
<script language="JavaScript" type="text/JavaScript">
<!--
parent.displayRefreshFrame.location.href = 'displayer_refresher.php?<?php echo(SID); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>';
//-->
</script>
</body>
</html>