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

if (!isset($_SERVER['HTTP_REFERER'])){ $_SERVER['HTTP_REFERER'] = ""; }
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }
if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
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

include($install_path . '/livehelp/include/block_spiders.php');
include($install_path . '/livehelp/include/config_database.php');
include($install_path . '/livehelp/include/class.mysql.php');
include($install_path . '/livehelp/include/config.php');

$department = $_GET['DEPARTMENT'];
$server = $_GET['SERVER'];
$status_enabled = $_GET['STATUS'];

if ($server == '') { $server = $site_address; }
if ($status_enabled == '') { $status_enabled = 'true'; }

	// Counts the total number of support users Hidden Offline status -1 over ALL Departments
	$query_select_count_hidden_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-1'";
	$row_count_hidden_users = $SQL->selectquery($query_select_count_hidden_users);
	$total_num_support_hidden_users = $row_count_hidden_users['count(login_id)'];	

	// Counts the total number of support users Online status -2 (doesn't include hidden users) over ALL Departments
	$query_select_count_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
	$row_count_users = $SQL->selectquery($query_select_count_users);
	$total_num_support_users = $row_count_users['count(login_id)'];

	// Counts the total number of support users in Away status -3 over ALL Departments
	$query_select_count_away_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-3'";
	$row_count_away_users = $SQL->selectquery($query_select_count_away_users);
	$total_num_support_away_users = $row_count_away_users['count(login_id)'];

	// Counts the total number of support users in Be Right Back status -4 over ALL Departments
	$query_select_count_brb_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-4'";
	$row_count_brb_users = $SQL->selectquery($query_select_count_brb_users);
	$total_num_support_brb_users = $row_count_brb_users['count(login_id)'];

// If the Live Help Status Image needs to be shown then process it...
if ($status_enabled == 'true') {

	// Counts the total number of support users within Department if Applic.
	$query_select_count_users_total = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout";
	if($department != '' && $departments == 'true') { $query_select_count_users_total .= " AND u.department LIKE '%$department%'"; }
	$row_count_users_total = $SQL->selectquery($query_select_count_users_total);
	$num_support_users_total = $row_count_users_total['count(login_id)'];

	// Counts the total number of support users Hidden Offline status -1 within Department if Applic.
	$query_select_count_hidden_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-1'";
	if($department != '' && $departments == 'true') { $query_select_count_users .= " AND u.department LIKE '%$department%'"; }
	$row_count_hidden_users = $SQL->selectquery($query_select_count_hidden_users);
	$num_support_hidden_users = $row_count_hidden_users['count(login_id)'];

	// Counts the total number of support users Online status -2 (doesn't include hidden users) within Department if Applic.
	$query_select_count_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
	if($department != '' && $departments == 'true') { $query_select_count_users .= " AND u.department LIKE '%$department%'"; }
	$row_count_users = $SQL->selectquery($query_select_count_users);
	$num_support_users = $row_count_users['count(login_id)'];

	// Counts the total number of support users in Away status -3 within Department if Applic.
	$query_select_count_away_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-3'";
	if($department != '' && $departments == 'true') { $query_select_count_away_users  .= " AND u.department LIKE '%$department%'"; }
	$row_count_away_users = $SQL->selectquery($query_select_count_away_users);
	$num_support_away_users = $row_count_away_users['count(login_id)'];

	// Counts the total number of support users in Be Right Back status -4 within Department if Applic.
	$query_select_count_brb_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-4'";
	if($department != '' && $departments == 'true') { $query_select_count_brb_users  .= " AND u.department LIKE '%$department%'"; }
	$row_count_brb_users = $SQL->selectquery($query_select_count_brb_users);
	$num_support_brb_users = $row_count_brb_users['count(login_id)'];
	
	// Set Be Right Back active status if all users are in BRB mode inc. Departments
	if ($num_support_users_total == $num_support_brb_users && $num_support_brb_users > 0 ) {
		$brb_mode_active = 'true';
	}
	else {
		$brb_mode_active = 'false';
	}
	// Set Away active status if all users are in Away mode inc. Departments
	if ($num_support_users_total == $num_support_away_users && $num_support_away_users > 0 ) {
		$away_mode_active = 'true';
	}
	else {
		$away_mode_active = 'false';
	}
	
	$allow_url_fopen = ini_get('allow_url_fopen');
	if($num_support_users > 0 || $brb_mode_active == 'true' || $away_mode_active == 'true') {
		// If Be Right Back Mode is to be displayed then print out...
		if ($brb_mode_active == 'true') {
			header('Content-type: image/gif');
			if ($allow_url_fopen == 1) {
				readfile($site_address . $online_brb_logo);
			}
			else {
				readfile('../../' . $online_brb_logo);
			}
			exit();
		}
		elseif ($away_mode_active == 'true') {
			header('Content-type: image/gif');
			if ($allow_url_fopen == 1) {
				readfile($site_address . $online_away_logo);
			}
			else {
				readfile('../../' . $online_away_logo);
			}
			exit();
		}
		else {
			header('Content-type: image/gif');
			if ($allow_url_fopen == 1) {
				readfile($site_address . $online_logo);
			}
			else {
				readfile('../../' . $online_logo);
			}
			exit();
		}
	}
	else {
		if ($disable_offline_email == "true") {
			header('Content-type: image/gif');
			if ($allow_url_fopen == 1) {
				readfile($site_address . $offline_logo_without_email);
			}
			else {
				readfile('../../' . $offline_logo_without_email);
			}
			exit();
		}
		else {
			header('Content-type: image/gif');
			if ($allow_url_fopen == 1) {
				readfile($site_address . $offline_logo);
			}
			else {
				readfile('../../' . $offline_logo);
			}
			exit();
		}
	}

}
?>