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

include('../include/functions.php');

if (!isset($_GET['RECORD'])){ $_GET['RECORD'] = 0; }
if (!isset($_GET['SESSION_ID'])){ $_GET['SESSION_ID'] = ''; }
if (!isset($_GET['REQUEST_ID'])){ $_GET['REQUEST_ID'] = ''; }
if (!isset($_GET['PREVIOUS'])){ $_GET['PREVIOUS'] = ''; }

$current_record = $_GET['RECORD'];
$current_record_session_id = $_GET['SESSION_ID'];
$current_record_request_id = $_GET['REQUEST_ID'];
$current_record_previous = $_GET['PREVIOUS'];
$current_request_request_flag = '';
$current_session_result = 'false';

$query_guests_online = "SELECT count(request_id) FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(last_refresh)) < 45 AND status = '0'";
$row_guests_online = $SQL->selectquery($query_guests_online);
$total_records = $row_guests_online['count(request_id)'];

if ($current_record_session_id == '' && $current_record_request_id == '') {
	$query_requests = "SELECT *, ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(first_request))) AS time_on_site, ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_request))) AS time_on_page FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(last_refresh)) < 45 AND status = '0' ORDER BY last_request DESC LIMIT $current_record, 1";
	$rows_requests = $SQL->selectall($query_requests);
	if (is_array($rows_requests)) {
	  	foreach ($rows_requests as $row_request) {
			if (is_array($row_request)) {
				$current_request_id = $row_request['request_id'];
				$current_request_session_id = $row_request['session_id'];
				$current_request_ip_address = $row_request['ip_address'];
				$current_request_user_agent = $row_request['user_agent'];
				$current_request_current_page = $row_request['current_page'];
				$current_request_current_page_title = $row_request['current_page_title'];
				$current_request_referrer = $row_request['referrer'];
				$current_request_time_on_page = $row_request['time_on_page'];
				$current_request_page_path = $row_request['page_path'];
				$current_request_time_on_site = $row_request['time_on_site'];
				$current_request_request_flag = $row_request['request_flag'];
			}
			else {
				header('Location: visitors_index.php?' . SID);
				exit();
			}
		}
	}
}
elseif ($current_record_session_id != '') {
	$query_requests = "SELECT *, ((UNIX_TIMESTAMP(last_refresh) - UNIX_TIMESTAMP(first_request))) AS time_on_site, ((UNIX_TIMESTAMP(last_refresh) - UNIX_TIMESTAMP(last_request))) AS time_on_page FROM " . $table_prefix . "requests WHERE session_id = '$current_record_session_id' ORDER BY last_request DESC";
	$row_request = $SQL->selectquery($query_requests);
		if (is_array($row_request)) {
			$current_request_id = $row_request['request_id'];
			$current_request_session_id = $row_request['session_id'];
			$current_request_ip_address = $row_request['ip_address'];
			$current_request_user_agent = $row_request['user_agent'];
			$current_request_current_page = $row_request['current_page'];
			$current_request_current_page_title = $row_request['current_page_title'];
			$current_request_referrer = $row_request['referrer'];
			$current_request_time_on_page = $row_request['time_on_page'];
			$current_request_page_path = $row_request['page_path'];
			$current_request_time_on_site = $row_request['time_on_site'];
			$current_request_request_flag = $row_request['request_flag'];
		}
		else {
			$current_session_result = 'true';
		}
}
elseif ($current_record_request_id != '') {
	$query_requests = "SELECT *, ((UNIX_TIMESTAMP(last_refresh) - UNIX_TIMESTAMP(first_request))) AS time_on_site, ((UNIX_TIMESTAMP(last_refresh) - UNIX_TIMESTAMP(last_request))) AS time_on_page FROM " . $table_prefix . "requests WHERE request_id = '$current_record_request_id' ORDER BY last_request DESC";
	$row_request = $SQL->selectquery($query_requests);
		if (is_array($row_request)) {
			$current_request_id = $row_request['request_id'];
			$current_request_session_id = $row_request['session_id'];
			$current_request_ip_address = $row_request['ip_address'];
			$current_request_user_agent = $row_request['user_agent'];
			$current_request_current_page = $row_request['current_page'];
			$current_request_current_page_title = $row_request['current_page_title'];
			$current_request_referrer = $row_request['referrer'];
			$current_request_time_on_page = $row_request['time_on_page'];
			$current_request_page_path = $row_request['page_path'];
			$current_request_time_on_site = $row_request['time_on_site'];
			$current_request_request_flag = $row_request['request_flag'];
		}
		else {
			$current_session_result = 'true';
		}
}

// The Site Visitor has not been sent an Initiate Chat request..
if ($current_request_request_flag == '0'){
	$current_request_initiate_status = $language['initiated_default'];
}
// displayed the request..
elseif ($current_request_request_flag == '-1') {
	$current_request_initiate_status = $language['initiated_waiting'];
}
// accepted the request..
elseif ($current_request_request_flag == '-2') {
	$current_request_initiate_status = $language['initiated_accepted'];
}
// declined the request..
elseif ($current_request_request_flag == '-3') {
	$current_request_initiate_status = $language['initiated_declined'];
}
// currently chatting..
elseif ($current_request_request_flag == '-4') {
	$current_request_initiate_status = $language['initiated_chatting'];
}
// sent a request and waiting to open on screen..
else {
	$current_request_initiate_status = $language['initiated_sending'];
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
<style type="text/css">
<!--
.visitorstable {
	border: thin dashed #6DB4D3;
}
.visitorscelltop {
	border-top-width: 3px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	border-top-color: #F3F3F3;
}

<?php
if ($current_win32_client == 'true') {
?>
.background {
	background-image: url(../images/background_visitor.gif);
	background-repeat: no-repeat;
	background-position: right bottom;
}

<?php
}
?>
-->
</style>
</head>
<body<?php if($current_win32_client == 'true') { ?> class="background"<?php } ?>>
<div align="center"> 
  <table width="425" border="0"> 
    <tr> 
      <td width="25"><div align="center"><strong><img src="../icons/identity.gif" alt="<?php if($current_record_previous == 'true') { echo($language['previous_visitors']); } else {  echo($language['online_visitors']); } ?>" width="22" height="22" border="0"></strong></div></td> 
      <td width="400" colspan="2"><em class="heading">
      <?php if($current_record_previous == 'true') { echo($language['previous_visitors']); } else {  echo($language['online_visitors']); } ?>
:: </em></td> 
    </tr> 
    <?php
	  if ($total_records > 0 && $current_record_session_id == ''){
	  ?> 
    <tr> 
      <td width="25">&nbsp;</td> 
      <td width="400"> <div align="left" class="small"> <em><?php echo($language['current_record']); ?>:
            <?php if ($total_records == 0) { echo('0'); } else { echo($current_record + 1); } ?>
/ <?php echo($total_records); ?></em></div></td> 
      <td width="400"><div align="right"> 
          <table border="0" cellspacing="0" cellpadding="2"> 
            <tr> 
              <td class="small"><a href="#" onClick="javascript:document.location.reload(true);" class="normlink"><?php echo($language['refresh']); ?></a> / <a href="hide_visitor.php?REQUEST_ID=<?php echo($current_request_id); ?>&<?php echo(SID); ?>" class="normlink"><?php echo($language['hide']); ?> <?php echo($language['record']); ?></a></td> 
            </tr> 
          </table> 
        </div></td> 
    </tr> 
    <?php
	  }
	  ?> 
    <tr> 
      <td width="25">&nbsp;</td> 
      <td width="400" colspan="2"> <?php
	  if ($total_records > 0 || $current_record_session_id != '' && $current_session_result != 'true') {
	  ?> 
        <table width="400" border="0" align="center" cellpadding="2" cellspacing="2" class="visitorstable"> 
          <tr>
            <td width="5" rowspan="2" bgcolor="#F3F3F3">&nbsp;</td> 
            <td colspan="3"><table border="0" cellspacing="2" cellpadding="2"> 
                <tr> 
                  <td width="175" valign="top" class="small"><?php echo($language['hostname']); ?>:</td> 
                  <td width="250" class="small"><em><?php echo(gethostbyaddr($current_request_ip_address)); ?></em></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top" class="small"><?php echo($language['user_agent']); ?>:</td> 
                  <td width="250" class="small"><em><?php echo($current_request_user_agent); ?> </em></td> 
                </tr>
				<?php
				if ($ip2country_installed == 'true') {
				?>
                <tr>
                  <td valign="top" class="small"><?php echo($language['country']); ?>:</td>
                  <td class="small"><em>
                  <?php
					$ip_number = sprintf("%u", ip2long($current_request_ip_address));
					
					$query_country = "SELECT c.code, c.country FROM " . $table_prefix . "countries AS c, " . $table_prefix . "ip2country AS i WHERE c.code = i.code AND i.ip_from <= " . $ip_number . " AND i.ip_to >= " . $ip_number;
					$row_country = $SQL->selectquery($query_country);
					if (is_array($row_country)){
						$code = $row_country['code'];
						$country = ucwords(strtolower($row_country['country']));
					}
					else {
						$country = $language['unavailable'];
					}
					
					echo($country);
					?>
                  </em></td>
                </tr>
				<?php
				}
				?>
                <tr> 
                  <td valign="top" class="small"><?php echo($language['referrer']); ?>:</td> 
                  <td class="small"><em>
                  <?php
				  // Set the referrer as approriate
					if ($current_request_referrer != '' && $current_request_referrer != 'false') {
					
						$current_request_referrer_title = $current_request_referrer;
						if (strlen($current_request_referrer) > 40) {
							$current_request_referrer_title = substr($current_request_referrer, 0, 40) . "...";
						}
				  ?>
                  <a href="<?php echo($current_request_referrer); ?>" target="_blank" class="normlink"><?php echo($current_request_referrer_title); ?></a>
                  <?php
					}
					elseif ($current_request_referrer == 'false') {
						echo('Direct Visit / Bookmark');
					}
					else {
						echo($language['unavailable']);
					}					

				  ?>
                  </em></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top" class="small"><?php echo($language['current_url']); ?>:</td> 
                  <td width="250" class="small"><em>
				  <?php
				  if ($current_request_current_page != '' && $current_request_current_page_title != '') {
				  ?>
				  <a href="<?php echo($current_request_current_page); ?>" target="_blank" class="normlink"><?php echo($current_request_current_page_title); ?></a>
				  <?php
				  }
				  else {
				  	echo($language['unavailable']);
				  }
				  ?>
				  </em></td> 
                </tr> 
                <tr> 
                  <td valign="top" class="small"><?php echo($language['time_on_page']); ?>:</td> 
                  <td class="small"><em><?php echo(time_layout($current_request_time_on_page)); ?></em></td> 
                </tr> 
                <tr> 
                  <td valign="top" class="small"><?php echo($language['initiate_chat_status']); ?>:</td> 
                  <td valign="top" class="small"><em><?php echo($current_request_initiate_status); ?></em></td> 
                </tr> 
                <tr> 
                  <td width="175" valign="top" class="small"><?php echo($language['page_path']); ?>:</td> 
                  <td width="250" valign="top" class="small"><p><em>
                  <textarea name="textarea" cols="40" rows="2" readonly="readonly" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-style: italic;filter:alpha(opacity=75);moz-opacity:0.75" ><?php echo($current_request_page_path); ?></textarea>
                  </em></p></td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td width="175" bordercolor="#F3F3F3" class="visitorscelltop"><div align="center" class="small"><?php echo($language['time_on_site']); ?>:<br>
                <em><?php echo(time_layout($current_request_time_on_site)); ?></em></div></td> 
            <td width="90">&nbsp;</td> 
            <td width="100" bordercolor="6DB4D3">
                <table border="0" align="center" cellpadding="0" cellspacing="0"> 
                  <tr> 
                    <td><div align="center"><a href="initate_chat.php?SESSION=<?php echo($current_request_session_id); ?>&RECORD=<?php echo($current_record); ?>"><img src="../icons/chat.gif" width="22" height="22" border="0"></a></div></td> 
                    <td><div align="center" class="small"><a href="initate_chat.php?SESSION=<?php echo($current_request_session_id); ?>&RECORD=<?php echo($current_record); ?>" class="normlink"><?php echo($language['initiate_chat']); ?></a></div></td> 
                  </tr> 
              </table> 
              </td> 
          </tr> 
        </table> 
        <?php
		}
		?> </td> 
    </tr> 
    <tr> 
      <td width="25">&nbsp;</td> 
      <td width="400" colspan="2"><div align="center"> 
          <p>
            <?php
	  if ($total_records == 0 && $current_record_session_id == '') {
	  ?>
                    <table width="290" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['notice']); ?>" width="32" height="32"></td>
              <td><div align="center">
                  <p><em><?php echo($language['visitors_notice']); ?>:</em></p>
              </div></td>
            </tr>
          </table> 
          <p><?php echo($language['no_visitors_msg']); ?></p>
		  <p><?php echo($language['no_visitors_tracker_msg']); ?>
            <?php
	  }
	  elseif ($total_records == 1 && $current_record_session_id == '') {
	  ?>
            <?php echo($language['back_record']); ?> :: <?php echo($language['next_record']); ?>
            <?php
	  }
	  elseif ($current_record == 0 && $current_record_session_id == '') {
	  ?>
            <?php echo($language['back_record']); ?> :: <a href="./visitors_index.php?RECORD=<?php echo($current_record + 1)?>&<?php echo(SID); ?>" target="_self" class="normlink"><?php echo($language['next_record']); ?></a>
            <?php
	  }
	  elseif ($current_record == ($total_records - 1) && $current_record_session_id == '') {
	  ?>
            <a href="./visitors_index.php?RECORD=<?php echo($current_record - 1)?>&<?php echo(SID); ?>" target="_self" class="normlink"><?php echo($language['back_record']); ?></a> :: <?php echo($language['next_record']); ?>
            <?php
	  }
	  elseif ($current_record_session_id == '') {
	  ?>
            <a href="./visitors_index.php?RECORD=<?php echo($current_record - 1)?>&<?php echo(SID); ?>" target="_self" class="normlink"><?php echo($language['back_record']); ?></a> :: <a href="./visitors_index.php?RECORD=<?php echo($current_record + 1)?>&<?php echo(SID); ?>" target="displayFrame" class="normlink"><?php echo($language['next_record']); ?></a>
            <?php
	  }
	  ?>
</p>
            <?php
	  if ($current_session_result == 'true') {
	  ?>
          <table width="290" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['notice']); ?>" width="32" height="32"></td>
              <td><div align="center">
                  <p><em><?php echo($language['visitors_notice']); ?>:</em></p>
              </div></td>
            </tr>
          </table>
          <p><?php echo($language['no_visitor_record']); ?></p>
            </div></td> 
					  <?php
		  }
		  ?>
    </tr> 
  </table> 
</div> 
</body>
</html>
