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
if (!isset($_SERVER['DOCUMENT_ROOT'])){ $_SERVER['DOCUMENT_ROOT'] = ""; }
if (!isset($_GET['TITLE'])){ $_GET['TITLE'] = ""; }
if (!isset($_GET['INITIATE'])){ $_GET['INITIATE'] = ""; }
if (!isset($_GET['REFERRER'])){ $_GET['REFERRER'] = ""; }

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

include($install_path . '/livehelp/include/config_database.php');
include($install_path . '/livehelp/include/class.mysql.php');
include($install_path . '/livehelp/include/config.php');

$current_title = $_GET['TITLE'];
$initiate_status = $_GET['INITIATE'];
$referrer = $_GET['REFERRER'];
$request_initiated = 'false';
$referrer_empty = 'false';

ignore_user_abort(true);

if (isset($_COOKIE['PHPSESSID'])) {

	session_start();
	$current_session = session_id();
	session_write_close();

	// Select the Initiate flag to check if an Administrator has initiated the user with a Support request
	$query_select_initiate = "SELECT request_flag,referrer FROM " . $table_prefix . "requests WHERE session_id = '$current_session'";
	$row_select_initiate = $SQL->selectquery($query_select_initiate);
	if (is_array($row_select_initiate)){
		$request_initiate_flag = $row_select_initiate['request_flag'];
		$request_referrer = $row_select_initiate['referrer'];
		
		if ($request_initiate_flag > 0){ $request_initiated = 'true'; }
		if ($request_referrer == '' && $referrer != ''){ $referrer_empty = 'true'; }

		// Update Initiate status fields to display the status of the floating popup.
		if ($initiate_status == "opened") {
			// Update request flag to show that the guest uesr OPENED the Online Chat Request
			$query_update_refresh = "UPDATE " . $table_prefix . "requests SET last_refresh=NOW(), request_flag = '-1' WHERE session_id = '$current_session'";
			$SQL->miscquery($query_update_refresh);
		}
		elseif ($initiate_status == "accepted") {
			// Update request flag to show that the guest uesr ACCEPTED the Online Chat Request
			$query_update_refresh = "UPDATE " . $table_prefix . "requests SET last_refresh=NOW(), request_flag = '-2' WHERE session_id = '$current_session'";
			$SQL->miscquery($query_update_refresh);
		}
		elseif ($initiate_status == "declined") {
			// Update request flag to show that the guest uesr DENIED the Online Chat Request
			$query_update_refresh = "UPDATE " . $table_prefix . "requests SET last_refresh=NOW(), request_flag = '-3' WHERE session_id = '$current_session'";
			$SQL->miscquery($query_update_refresh);
		}
		else {
			// Update referrer if appropriate depending if already stored.
			if ($referrer_empty != 'true') {
				// Update last refresh so user is in Online status mode
				$query_update_refresh = "UPDATE " . $table_prefix . "requests SET last_refresh=NOW(), current_page_title = '$current_title' WHERE session_id = '$current_session'";
				$SQL->miscquery($query_update_refresh);
			}
			else {
				// Update last refresh so user is in Online status mode
				$query_update_refresh = "UPDATE " . $table_prefix . "requests SET last_refresh=NOW(), current_page_title = '$current_title', referrer = '$referrer' WHERE session_id = '$current_session'";
				$SQL->miscquery($query_update_refresh);
			}
		}	
	}
	
}

header('Content-type: image/gif');
if ($request_initiated == 'true') {
	readfile($install_path . '/livehelp/include/image_initiate.gif');
}
elseif ($request_initiated == 'false') {
	readfile($install_path . '/livehelp/include/image_tracker.gif');
}
?>