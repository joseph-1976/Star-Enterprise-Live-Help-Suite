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

function htmlSmilies($message, $path) {

	$smilie[0] = ':-)';
	$smilieImage[0] = 'smilie_01.gif';
	$smilie[1] = ':-(';
	$smilieImage[1] = 'smilie_02.gif';
	$smilie[2] = '$-D';
	$smilieImage[2] = 'smilie_03.gif';
	$smilie[3] = ';-P';
	$smilieImage[3] = 'smilie_04.gif';
	$smilie[4] = ':-/';
	$smilieImage[4] = 'smilie_05.gif';
	$smilie[5] = ':(';
	$smilieImage[5] = 'smilie_06.gif';
	$smilie[6] = "8-)";
	$smilieImage[6] = 'smilie_07.gif';
	$smilie[7] = ":)";
	$smilieImage[7] = 'smilie_08.gif';
	$smilie[8] = ':-|';
	$smilieImage[8] = 'smilie_09.gif';
	$smilie[9] = ':--';
	$smilieImage[9] = 'smilie_10.gif';
	$smilie[10] = '/-|';
	$smilieImage[10] = 'smilie_11.gif';
	$smilie[11] = ':-O';
	$smilieImage[11] = 'smilie_12.gif';


	for($i=0; $i < count($smilie); $i++) {
		$message = str_replace($smilie[$i], "<image src=\"$path" . $smilieImage[$i] . "\">", $message);
	}
	return $message;
}

function time_layout($unixtime) {

	global $language;

	$minutes = (int)($unixtime / 60);
	if ($minutes > 60) {
		$hours = (int)(($unixtime / 60) / 60);
	  	$minutes = (int)(($unixtime / 60) - ($hours * 60));
		
		if ($minutes < 10) {
	  		$minutes = '0' . (int)(($unixtime / 60) - ($hours * 60));
		}
	  
		$seconds = ($unixtime % 60);
		
		if ($seconds < 10) {
	  		$seconds = '0' . ($unixtime % 60);
		}
		return $hours . ':' . $minutes . ':' . $seconds . ' ' . $language['hours'];
	}
	else {
		if ($minutes < 10) {
			$minutes = '0' . (int)($unixtime / 60);
		}
		
		$seconds = ($unixtime % 60);
		
		if ($seconds < 10) {
			$seconds = '0' . ($unixtime % 60);
		}
		return $minutes . ':' . $seconds . ' ' . $language['minutes'];
	}
}

function pendingUsersPopup($timeoutValue){

	global $table_prefix;
	global $departments;
	global $current_department;
	global $SQL;
	
	// PENDING USERS QUERY displays pending users not logged in on users users table depending on department settings
	if ($departments == 'true') {
		$departments_sql = departmentsSQL($current_department);
		$query_select = "SELECT DISTINCT (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $timeoutValue AND s.active = '0' AND $departments_sql";
	}
	elseif ($departments == 'false') {
		$query_select = "SELECT DISTINCT (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $timeoutValue AND s.active = '0'";
	}
	$rows = $SQL->selectall($query_select);
	
	// Initalise user status to false
	$user_status = 'false';
	
	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (is_array($row)) {
				$display_flag = $row['display_flag'];
				if ($display_flag < $timeoutValue) {
					$user_status = 'true';
				}
			}
		}
	}

	return $user_status;
}

function browsingUsersPopup($timeoutValue){

	global $table_prefix;
	global $SQL;

	// BROWSING USERS QUERY displays browsing users
	$query_select = "SELECT (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_request)) AS display_flag FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_request)) < $timeoutValue AND status = '0'";
	$rows = $SQL->selectall($query_select);

	// Initalise user status to false
	$user_status = 'false';
	
	$count = 0;
	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (is_array($row)) {
				$display_flag = $row['display_flag'];
			
				if ($display_flag < $timeoutValue) {
					$user_status = 'true';
				}
			}
		}
	}

	return $user_status;
}


function transferredUsersPopup($timeoutValue){

	global $table_prefix;
	global $current_login_id;
	global $SQL;

	// TRANSFERRED USERS QUERY displays transferred users not loged in on users users table
	$query_select = "SELECT DISTINCT (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.datetime)) AS display_flag FROM " . $table_prefix . "sessions AS s WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $timeoutValue AND s.active = '-2' AND s.transfer_id = '$current_login_id'";
	$rows = $SQL->selectall($query_select);

	// Initalise user status to false
	$user_status = 'false';
	
	if (is_array($rows)) {
		foreach ($rows as $row) {
			if (is_array($row)) {
				$display_flag = $row['display_flag'];
			
				if ($display_flag < $timeoutValue) {
					$user_status = 'true';
				}
			}
		}
	}

	return $user_status;
}

function totalPendingUsers(){

	global $table_prefix;
	global $connection_timeout;
	global $SQL;

	// PENDING USERS QUERY displays pending users not loged in on users users table
	$query_select = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s LEFT JOIN " . $table_prefix . "users AS u ON s.login_id = u.last_login_id WHERE u.last_login_id IS NULL AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND s.active = '0'";
	$row = $SQL->selectquery($query_select);

	// Initalise user status to false
	$total_users = '0';
	if (is_array($row)) {
		$total_users = $row['count(login_id)'];
	}

	return $total_users;
}

function totalBrowsingUsers(){

	global $table_prefix;
	global $connection_timeout;
	global $SQL;

	// BROWSING USERS QUERY displays browsing users
	$query_select = "SELECT count(request_id) FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) < $connection_timeout";
	$row = $SQL->selectquery($query_select);

	// Initalise user status to false
	$total_users = '0';
	if (is_array($row)) {
		$total_users = $row['count(request_id)'];
	}

	return $total_users;
}

function departmentsSQL($department){
	$multi_departments = split ('[;]', $department);
	$departments_sql = '';
	if (is_array($multi_departments)) {
		$i = 0;
		$length = count($multi_departments);
		if ($length > 1) {
			while ($i < $length):
				$department = trim($multi_departments[$i]);
				if ($i == 0) {
					$departments_sql = "( s.support_department = '$department'";
				}
				elseif ($i > 0 && $i < $length - 1) {
					$departments_sql .= " OR s.support_department = '$department'";
				}
				elseif ($i == $length - 1) {
					$departments_sql .= " OR s.support_department = '$department' OR s.support_department = '' )";
				}
				$i++;
			endwhile;
		}
		else {
			$departments_sql = "( s.support_department = '$department' OR s.support_department = '' )";
		}
	}
	else {
		$departments_sql = "( s.support_department = '$department' OR s.support_department = '' )";
	}
	return $departments_sql;
}
?>