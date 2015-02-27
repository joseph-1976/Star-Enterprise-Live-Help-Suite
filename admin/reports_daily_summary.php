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
	header('Location: ../include/access_denied.php' . SID);
	exit();
}

if (!isset($_GET['DATE'])){ $_GET['DATE'] = ""; }
if (!isset($_POST['SKIP'])){ $_POST['SKIP'] = 0; }
if (!isset($_POST['LIMIT'])){ $_POST['LIMIT'] = 0; }

$date = $_GET['DATE'];
$skip = $_POST['SKIP'];
$limit = $_POST['LIMIT'];

list($day, $month, $year) = split('[-]', $date);
$txt_date = date('d F Y', mktime(date('H')+$difference_timezone_hours,date('i')+$difference_timezone_minutes,0,$month,$day,$year));

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

if ($limit == 0) $limit = 6;

// displays the number of pages according to the limit speciifed
function show_product_pages($limit, $total_products, $skip) {
	global $language;
	// if more products than the limit, then display the number of pages
	if ($total_products > $limit) { 
	  echo('Page: ');   
	  $paginas = ceil(($total_products/$limit));
	   
	  // for each page display the link to the page
	  if (!$_POST['SKIP']) { 
	   $comeco = 0; 
	  } else { 
	   $comeco = $_POST['SKIP']; 
	  } 
	
	  for ($i = 0; $i < $paginas; $i++) { 
		$page = $i+1;
		// if the page is selected then don't link to it
		if ($skip == ($i * $limit)){
		  echo(' ' . $page . ' |'); 
		  }
		else {
			echo('<a href="#" onClick="submitForm(' . $i * $limit . ');" title="' . $language['page'] . ' ' . $page . ' ' . $language['of_reports'] . '" class="normlink">' . $page . '</a> |'); 
		  }
		} 
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
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function submitForm(skipped) {
	document.reports.SKIP.value = skipped;
	void(document.reports.submit());
}
/-->
</script>
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
  <form name="reports" method="post" action="./reports_daily_summary.php?<?php echo(SID); ?>&DATE=<?php echo($date); ?>"> 
    <table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
        <td width="22"><strong><img src="../icons/reports_small.gif" alt="<?php echo($language['daily_reports']); ?>" width="22" height="22" border="0"></strong></td> 
        <td><em class="heading"><?php echo($language['daily_reports']); ?> :: <?php echo($txt_date); ?></em></td> 
      </tr> 
      <tr> 
        <td colspan="2"> <?php
	  // Count the total number of sessions for the passed date
	  $query_num_daily_sessions = "SELECT count(login_id) FROM " . $table_prefix . "sessions WHERE DATE_FORMAT(DATE_ADD(datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%d-%m-%Y') = '$date' AND (active > 0 OR active = -3)";
	  $row_num_daily_sessions = $SQL->selectquery($query_num_daily_sessions); 
	  if (is_array($row_num_daily_sessions)) {
			$total_sessions = $row_num_daily_sessions['count(login_id)'];
	  }
	  ?> 
          <table width="400" height="25" border="0" align="center" cellpadding="4" cellspacing="0"> 
            <tr height="5"> 
              <td></td> 
              <td></td> 
              <td></td> 
              <td></td> 
              <td></td> 
            </tr> 
            <tr> 
              <td></td> 
              <td><strong><?php echo($language['username']); ?></strong></td> 
              <td><strong><?php echo($language['department']); ?></strong></td> 
              <td><strong><?php echo($language['rating']); ?></strong></td> 
              <td><strong><?php echo($language['email']); ?></strong></td> 
            </tr> 
            <?php
	  $query_daily_sessions = "SELECT login_id, session_id, username, email, ip_address, support_department, rating, (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) AS ttl_refresh FROM " . $table_prefix . "sessions WHERE DATE_FORMAT(DATE_ADD(datetime, INTERVAL '$difference_timezone_hours:$difference_timezone_minutes' HOUR_MINUTE), '%d-%m-%Y') = '$date' AND (active > 0 OR active = -3) LIMIT $skip, $limit";
	  $rows_daily_sessions = $SQL->selectall($query_daily_sessions);
	  
	  $colour = 'false';
	  if (is_array($rows_daily_sessions)) {
	  		foreach($rows_daily_sessions as $row_daily_session) {
				if (is_array($row_daily_session)) {
				
					if ($colour == 'false') {
						$colour = 'true';
					}
					elseif ($colour == 'true') {
						$rgb = '#E4F2FB';
						$colour = 'false';
					}
					
					$login_id = $row_daily_session['login_id'];
					$session_id = $row_daily_session['session_id'];
					$username = $row_daily_session['username'];
					$ip_address = $row_daily_session['ip_address'];
					$support_department = $row_daily_session['support_department'];
					$rating = $row_daily_session['rating'];
					$email = $row_daily_session['email'];
					$ttl_refresh = $row_daily_session['ttl_refresh'];
					
					switch ($rating) { 
						case '-1':
							$rating = $language['unavailable']; 
							break;
						case '1':
							$rating = $language['poor']; 
							break;
						case '2':
							$rating = $language['fair']; 
							break;
						case '3':
							$rating = $language['good']; 
							break;
						case '4':
							$rating = $language['very_good']; 
							break;
						case '5':
							$rating = $language['excellent']; 
							break;
					}

	  ?> 
            <tr<?php if($colour == 'true') { echo(' bgcolor="E4F2FB"'); } ?>  onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='<?php if($colour == 'true') { echo('E4F2FB'); } else { echo('#FFFFFF'); } ?>';" onClick="location.href = './visitors_index.php?<?php echo(SID . '&SESSION_ID=' . $session_id); if($ttl_refresh < $connection_timeout) { echo('&PREVIOUS=false'); } else { echo('&PREVIOUS=true'); }  ?>';" style="filter:alpha(opacity=75);moz-opacity:0.75"> 
              <td><img src="../images/<?php if($ttl_refresh < $connection_timeout) { echo('mini_chatting.gif'); } else { echo('mini_chat_ended.gif'); } ?>" alt="<?php if($ttl_refresh < $connection_timeout) { echo($language['currently_chatting']); } else { echo($language['chat_ended']); } ?>"></td> 
              <td><?php echo($username); ?></td> 
              <td><?php echo($support_department); ?></td> 
              <td><?php echo($rating); ?></td> 
              <td><?php if ($email != '') { ?> 
                <a href="mailto:<?php echo($email); ?>" class="normlink"><?php echo($language['send_email']); ?></a> 
                <?php } else { echo($language['unavailable']); } ?></td> 
            </tr> 
            <?php
						}
			}
	  }
	  ?> 
            <tr> 
              <td colspan="5"><div align="right"> 
                  <input name="SKIP" type="hidden" id="SKIP" value="<?php echo($skip); ?>"> 
                  <input name="LIMIT" type="hidden" id="LIMIT" value="<?php echo($limit); ?>"> 
                  <?php show_product_pages($limit, $total_sessions, $skip); ?> 
                </div></td> 
            </tr> 
          </table></td> 
      </tr> 
    </table> 
  </form> 
</div> 
</body>
</html>
