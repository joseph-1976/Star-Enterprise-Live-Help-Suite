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

$query_select_msgs = "SELECT message_id,from_login_id,to_login_id,DATE_FORMAT(DATE_ADD(message_datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE),'%H:%i:%s') AS message_time,message,src_flag,dest_flag,display_time,display_username,display_align,hidden,username AS from_username FROM " . $table_prefix . "messages," . $table_prefix . "sessions WHERE from_login_id = login_id AND (from_login_id = '$guest_login_id' OR to_login_id = '$guest_login_id') ORDER BY message_datetime";
$rows_msgs = $SQL->selectall($query_select_msgs);

if (is_array($rows_msgs)) {
	foreach ($rows_msgs as $row) {
		if (is_array($row)) {

			$guest_username = str_replace('\'', '\\\'', $guest_username);
			$support_username = str_replace('\'', '\\\'', $row['from_username']);		
			$message = str_replace('\\\'', '\'', $row['message']);
			//$message = str_replace("\r\n", "\\r\\n", $message); 
		
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
			if($row['from_login_id'] == "$current_login_id"){
				if ($row['hidden'] == '0') {
?>
<table width="100%" border="0" align="center">
	<tr>
		<td><div align="<?php echo($align); ?>"><font size="<?php echo($guest_chat_font_size); ?>" color="<?php echo($sent_font_color); ?>" face="<?php echo($font_type); ?>"><?php if ($row['display_time'] == '1' || $row['display_username'] == '1') { ?><strong><?php if ($row['display_time'] == '1') { echo('[' . $row['message_time'] . ']'); } ?> <?php if ($row['display_username'] == '1') { echo($support_username); } ?></strong>: <?php } } echo($displayMessage); ?><?php if ($row['hidden'] == '0') { ?><br></font></div></td>
	</tr>
</table>
<?php
				}
			}
			// Outputs received message
			if($row['to_login_id'] == "$current_login_id"){
				if ($row['hidden'] == '0') {
?>
<table width="100%" border="0" align="center">
	<tr>
		<td><div align="<?php echo($align); ?>"><font size="<?php echo($guest_chat_font_size); ?>" color="<?php echo($received_font_color); ?>" face="<?php echo($font_type); ?>"><?php if ($row['display_time'] == '1' || $row['display_username'] == '1') { ?><strong><?php if ($row['display_time'] == '1') { echo('[' . $row['message_time'] . ']'); } ?> <?php if ($row['display_username'] == '1') { echo($support_username); } ?></strong>: <?php } } echo($displayMessage); ?><?php if ($row['hidden'] == '0') { ?><br></font></div></td>
	</tr>
</table>
<?php 
				}
			}
		}
	}
}
?> 