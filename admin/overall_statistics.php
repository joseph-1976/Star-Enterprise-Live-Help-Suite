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
<?php
if ($current_win32_client == 'true') {
?>
<style type="text/css">
<!--
.background {
	background-image: url(../images/background_statistics.gif);
	background-repeat: no-repeat;
	background-position: right bottom;
}
-->
</style>
<?php
}
?>
</head>
<body<?php if($current_win32_client == 'true') { ?> class="background"<?php } ?>> 
<div align="center"> 
  <table width="400" border="0"> 
    <tr> 
      <td width="22"><strong><img src="../icons/stats_small.gif" alt="<?php echo($language['overall_stats']); ?>" width="22" height="22" border="0"></strong></td> 
      <td colspan="2"><em class="heading"><?php echo($language['overall_stats']); ?> :: </em></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td width="225">&nbsp;</td> 
      <td width="150">&nbsp;</td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td><div align="right"><?php echo($language['total_unique']); ?>:</div></td> 
      <td> <?php
	  $query_unique_visitors = "SELECT count(session_id) FROM " . $table_prefix . "requests";
	  $row_unique_visitors = $SQL->selectquery($query_unique_visitors);
	  if (is_array($row_unique_visitors)) {
	  echo($row_unique_visitors['count(session_id)']);
	  }
	  else {
	  echo('Unavailable');
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['online_supported']); ?>: </div></td> 
      <td> <?php
	  $query_online_guests = "SELECT count(DISTINCT r.session_id) FROM " . $table_prefix . "requests AS r LEFT JOIN " . $table_prefix . "sessions AS s ON s.session_id = r.session_id WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(r.last_request)) < '300' AND s.session_id IS NOT NULL";
	  $row_online_guests = $SQL->selectquery($query_online_guests);
	  if (is_array($row_online_guests)) {
	  echo($row_online_guests['count(DISTINCT r.session_id)']);
	  }
	  else {
	  echo('Unavailable');
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['online_unsupported']); ?>:</div></td> 
      <td> <?php
	  $query_online_guests = "SELECT count(DISTINCT r.session_id) FROM " . $table_prefix . "requests AS r LEFT JOIN " . $table_prefix . "sessions AS s ON s.session_id = r.session_id WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(r.last_request)) < '300' AND s.session_id IS NULL";
	  $row_online_guests = $SQL->selectquery($query_online_guests);
	  if (is_array($row_online_guests)) {
	  echo($row_online_guests['count(DISTINCT r.session_id)']);
	  }
	  else {
	  echo('Unavailable');
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['total_supported']); ?>:</div></td> 
      <td> <?php
	  $query_supported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE active > '0'";
	  $row_supported_users = $SQL->selectquery($query_supported_users);
	  if (is_array($row_supported_users)) {
	  echo($row_supported_users['count(session_id)']);
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['total_unsupported']); ?>:</div></td> 
      <td> <?php
	  $query_unsupported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) > '" . $connection_timeout . "' AND active = '0'";
	  $row_unsupported_users = $SQL->selectquery($query_unsupported_users);
	  if (is_array($row_unsupported_users)) {
	  echo($row_unsupported_users['count(session_id)']);
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['total_sent_msgs']); ?>:</div></td> 
      <td> <?php
	  $query_sent_msgs = "SELECT count(message_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "messages AS m  WHERE s.login_id = m.from_login_id AND s.active =  '-1'";
	  $row_sent_stats = $SQL->selectquery($query_sent_msgs);
	  if (is_array($row_sent_stats)) {
	  echo($row_sent_stats['count(message_id)']);
	  }
	  ?> </td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['total_received_msgs']); ?>:</div></td> 
      <td> <?php
	  $query_received_msgs = "SELECT count(message_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "messages AS m  WHERE s.login_id = m.to_login_id AND s.active =  '-1'";
	  $row_received_stats = $SQL->selectquery($query_received_msgs);
	  if (is_array($row_received_stats)) {
	  echo($row_received_stats['count(message_id)']);
	  }
	  ?> </td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['average_rating']); ?>:</div></td> 
      <td> <?php
	  $query_average_rating = "SELECT rating FROM " . $table_prefix . "sessions  WHERE rating > 0";
	  $rows_average_rating = $SQL->selectall($query_average_rating);
	  $total_rating = 0;
	  $count = 0;
	  
	  if (is_array($rows_average_rating)) {
	  	foreach($rows_average_rating as $row_average_rating) {
	  		if (is_array($row_average_rating)) {
				$total_rating = $total_rating + $row_average_rating['rating'];
				$count++;
	  		}
		}
		$average_rating = $total_rating / $count;
	  	echo(round($average_rating, 2));
	  }
	  else {
	  	echo($language['unavailable']);
	  }
	  ?> 
        / 5</td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td>&nbsp;</td> 
      <td>&nbsp;</td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td colspan="2" valign="top"><em><?php echo($language['current_session_stats']); ?>: <?php echo($current_username); ?></em></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['sent_msgs']); ?>:</div></td> 
      <td> <?php
	  $query_current_sent_msgs = "SELECT count(message_id) FROM " . $table_prefix . "messages WHERE from_login_id =  '$current_login_id'";
	  $row_current_sent_stats = $SQL->selectquery($query_current_sent_msgs);
	  if (is_array($row_current_sent_stats)) {
	  echo($row_current_sent_stats['count(message_id)']);
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['received_msgs']); ?>:</div></td> 
      <td> <?php
	  $query_current_received_msgs = "SELECT count(message_id) FROM " . $table_prefix . "messages WHERE to_login_id =  '$current_login_id'";
	  $row_current_received_stats = $SQL->selectquery($query_current_received_msgs);
	  if (is_array($row_current_received_stats)) {
	  echo($row_current_received_stats['count(message_id)']);
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['supported_users']); ?>:</div></td> 
      <td> <?php
	  $query_current_supported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions  WHERE active =  '$current_login_id'";
	  $row_current_supported_users = $SQL->selectquery($query_current_supported_users);
	  if (is_array($row_current_supported_users)) {
	  echo($row_current_supported_users['count(session_id)']);
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['average_rating']); ?>:</div></td> 
      <td> <?php
	  $query_average_rating = "SELECT rating FROM " . $table_prefix . "sessions  WHERE active =  '$current_login_id' AND rating > 0";
	  $rows_average_rating = $SQL->selectall($query_average_rating);
	  $total_rating = 0;
	  $count = 0;
	  
	  if (is_array($rows_average_rating)) {
	  	foreach($rows_average_rating as $row_average_rating) {
	  		if (is_array($row_average_rating)) {
				$total_rating = $total_rating + $row_average_rating['rating'];
				$count++;
	  		}
		}
		$average_rating = $total_rating / $count;
	  	echo(round($average_rating, 2) . ' / 5');
	  }
	  else {
	  	echo($language['unavailable']);
	  }
	  ?></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td valign="top"><div align="right"><?php echo($language['total_time']); ?>:</div></td> 
      <td> <?php
	  $query_user_stats = "SELECT ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(datetime))) AS time_online FROM " . $table_prefix . "sessions WHERE login_id = '$current_login_id'";
	  $row_user_stats = $SQL->selectquery($query_user_stats);
	  $time = $row_user_stats['time_online'];

	  $minutes = (int)($time / 60);
	  if ($minutes > 60) {
	  $hours = (int)(($time / 60) / 60);
	  $minutes = (int)(($time / 60) - ($hours * 60));
	  if ($minutes < 10) {
	  $minutes = '0' . (int)(($time / 60) - ($hours * 60));
	  }
	  $seconds = ($time % 60);
	  if ($seconds < 10) {
	  $seconds = '0' . ($time % 60);
	  }
	  echo($hours . ':' . $minutes . ':' . $seconds . ' ' . $language['hours']);
	  }
	  else {
	  if ($minutes < 10) {
	  $minutes = '0' . (int)($time / 60);
	  }
	  $seconds = ($time % 60);
	  if ($seconds < 10) {
	  $seconds = '0' . ($time % 60);
	  }
	  echo($minutes . ':' . $seconds . ' ' . $language['minutes']);
	  }
      ?> </td> 
    </tr> 
  </table> 
</div> 
</body>
</html>
