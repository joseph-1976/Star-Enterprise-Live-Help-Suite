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

if ($current_access_level > 2){
	header('Location: ../include/access_denied.php?' . SID);
	exit();
}

if (!isset($_GET['MONTH'])){ $_GET['MONTH'] = ""; }
if (!isset($_GET['DATE'])){ $_GET['DATE'] = ""; }

if ($_GET['DATE'] == "") {
	$num_date = date('Y-m-d', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,date('m'),date('d'),date('Y')));
 	  
	$day = date('d', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,date('m'),date('d'),date('Y')));
	$month = date('F', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,date('m'),date('d'),date('Y')));
	$year = date('Y', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,date('m'),date('d'),date('Y'))); 
}

if (LANGUAGE_TYPE != 'en') {	  
	switch ($month) { 
		case 'January':
			$month = $language['january']; 
			break;
		case 'February':
			$month = $language['february']; 
			break;
		case 'March':
			$month = $language['march']; 
			break;
		case 'April':
			$month = $language['april']; 
			break;
		case 'May':
			$month = $language['may']; 
			break;
		case 'June':
			$month = $language['june']; 
			break;
		case 'July':
			$month = $language['july']; 
			break;
		case 'August':
			$month = $language['august']; 
			break;
		case 'September':
			$month = $language['september']; 
			break;
		case 'October':
			$month = $language['october']; 
			break;
		case 'November':
			$month = $language['november']; 
			break;
		case 'December':
			$month = $language['december']; 
			break;
	}
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="MSThemeCompatible" content="Yes">
<script language="JavaScript" type="text/JavaScript">
<!--
function getReport(td, date){
  var day = td.innerHTML.replace(/<[^>]+>/g,"");
  
  day = day.replace(/ /g,"");
  
  if (day != 0) {
    if (day < 10) {
    day = "0" + day;
    }
    location.href='reports_index.php?<?php echo(SID); ?>&DATE=' + date + '-' + day + '&MONTH=<?php echo($_GET['MONTH']); ?>';
  }
}
//-->
</script>
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
<?php
if ($current_win32_client == 'true') {
?>
<style type="text/css">
<!--
.background {
	background-image: url(../images/background_reports.gif);
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
  <table border="0" cellspacing="2" cellpadding="2"> 
    <tr> 
      <td width="22"><strong><img src="../icons/reports_small.gif" alt="<?php echo($language['daily_reports']); ?>" width="22" height="22" border="0"></strong></td> 
      <td colspan="2"><em class="heading"><?php echo($language['daily_reports']); ?> :: </em></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td><?php
	  include('calendar_include.php');
      ?></td> 
      <td><table border="0" align="center" cellpadding="2" cellspacing="2"> 
          <tr> 
            <td colspan="2">&nbsp;</td> 
          </tr> 
          <tr> 
            <td colspan="2"><div align="center"> 
                <p align="right"><strong><em><?php echo($language['date']); ?>:
                  <?php
			if ($_GET['DATE'] == "") {
				  echo($day . ' ' . $month . ' ' . $year);
			}
			else {
				  $num_date = $_GET['DATE'];
				  list($year, $month, $day) = split('[-]', $num_date);
				  $date = date('d F Y', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,$month,$day,$year));
				  echo($date);
			}
			?> 
                  </em> </strong></p> 
              </div></td> 
          </tr> 
          <tr> 
            <td><div align="right"><?php echo($language['unique']); ?>:</div></td> 
            <td> <?php
	  $query_unique_visitors = "SELECT count(session_id) FROM " . $table_prefix . "requests WHERE DATE_FORMAT(DATE_ADD(last_request, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%Y-%m-%d') = '$num_date'";
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
            <td><div align="right"><?php echo($language['supported_users']); ?>:</div></td> 
            <td> <?php
	  $query_supported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE (active > '0' OR active = '-3') AND DATE_FORMAT(DATE_ADD(datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%Y-%m-%d') = '$num_date'";
	  $row_supported_users = $SQL->selectquery($query_supported_users);
	  if (is_array($row_supported_users)) {
	  echo($row_supported_users['count(session_id)']);
	  }
	  ?></td> 
          </tr> 
          <tr> 
            <td><div align="right"><?php echo($language['unsupported_users']); ?>:</div></td> 
            <td> <?php
	  $query_unsupported_users = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE active = '0' AND DATE_FORMAT(DATE_ADD(datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%Y-%m-%d') = '$num_date'";
	  $row_unsupported_users = $SQL->selectquery($query_unsupported_users);
	  if (is_array($row_unsupported_users)) {
	  echo($row_unsupported_users['count(session_id)']);
	  }
	  ?></td> 
          </tr> 
          <tr> 
            <td><div align="right"><?php echo($language['sent_msgs']); ?>:</div></td> 
            <td> <?php
	  $query_sent_msgs = "SELECT count(message_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "messages AS m  WHERE s.login_id = m.from_login_id AND s.active =  '-1' AND DATE_FORMAT(DATE_ADD(datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%Y-%m-%d') = '$num_date'";
	  $row_sent_stats = $SQL->selectquery($query_sent_msgs);
	  if (is_array($row_sent_stats)) {
	  echo($row_sent_stats['count(message_id)']);
	  }
	  ?> </td> 
          </tr> 
          <tr> 
            <td><div align="right"><?php echo($language['received_msgs']); ?>:</div></td> 
            <td> <?php
	  $query_received_msgs = "SELECT count(message_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "messages AS m  WHERE s.login_id = m.to_login_id AND s.active =  '-1' AND DATE_FORMAT(DATE_ADD(datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%Y-%m-%d') = '$num_date'";
	  $row_received_stats = $SQL->selectquery($query_received_msgs);
	  if (is_array($row_received_stats)) {
		  echo($row_received_stats['count(message_id)']);
	  }
	  ?> </td> 
          </tr> 
        </table></td> 
    </tr> 
  </table> 
  <?php
 	if ($row_supported_users['count(session_id)'] > 0) {
		if ($_GET['DATE'] == "") {
			$date = date('d-m-Y', mktime());
		}
		else {
			$num_date= $_GET['DATE'];
			list($year, $month, $day) = split('[-]', $num_date);
			$date = date('d-m-Y', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,$month,$day,$year));
		}
	?> 
  <p> 
    <input type="button" name="Button" value="View Daily Summary" onClick="location.href = './reports_daily_summary.php?DATE=<?php echo($date); ?>&<?php echo(SID); ?>';"> 
    <?php
	}
	?> 
  </p> 
  </p> 
</div> 
</body>
</html>
