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
include('../../include/config_database.php');
include('../../include/class.mysql.php');
include('../../include/config.php');

$language_file = '../locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('../../locale/en/lang_en.php');
}

if (!isset($_POST['USER_NAME'])){ $_POST['USER_NAME'] = ""; }
if (!isset($_POST['PASSWORD'])){ $_POST['PASSWORD'] = ""; }
if (!isset($_POST['ACTION'])){ $_POST['ACTION'] = ""; }
if (!isset($_POST['LOGIN_ID'])){ $_POST['LOGIN_ID'] = ""; }

$current_username = $_POST['USER_NAME'];
$current_md5_password = $_POST['PASSWORD'];
$action = $_POST['ACTION'];
$current_login_id = '';

// Get username, password from database and authorise the login details
$query_select = "SELECT last_login_id,department FROM " . $table_prefix . "users WHERE username = '$current_username' AND password = '$current_md5_password'";
$row = $SQL->selectquery($query_select);
if (!is_array($row)) {
	if (strpos(php_sapi_name(), 'cgi') === false ) { header('HTTP/1.0 403 Forbidden'); } else { header('Status: 403 Forbidden'); }
	exit;
}
else {
	$current_login_id = $row['last_login_id'];
	$current_department = $row['department'];
	
	// Update the username, password and make no mode changes
	$query_update_refresh = "UPDATE " . $table_prefix . "sessions SET last_refresh=NOW() WHERE login_id = '$current_login_id'";
	$SQL->miscquery($query_update_refresh);
}
header('Content-type: text/xml; charset=utf-8');
echo('<?xml version="1.0" encoding="utf-8"?>' . "\n");
?>
<SiteVisitors xmlns="urn:LiveHelp">
<?php

$query_requests = "SELECT *, ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(first_request))) AS time_on_site, ((UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_request))) AS time_on_page FROM " . $table_prefix . "requests WHERE (UNIX_TIMESTAMP(NOW())  - UNIX_TIMESTAMP(last_refresh)) < 45 AND status = '0' ORDER BY last_request";
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
			
			// IP2 Country calculations
				if ($ip2country_installed == 'true') {
				$ip_number = sprintf("%u", ip2long($current_request_ip_address));
				$query_country = "SELECT c.code, c.country FROM " . $table_prefix . "countries AS c, " . $table_prefix . "ip2country AS i WHERE c.code = i.code AND i.ip_from <= " . $ip_number . " AND i.ip_to >= " . $ip_number;
				$row_country = $SQL->selectquery($query_country);
				if (is_array($row_country)){
					$code = $row_country['code'];
					$current_request_country = ucwords(strtolower($row_country['country']));
				}
				else {
					$current_request_country = $language['unavailable'];
				}
			}
			else {
				$current_request_country = $language['unavailable'];
			}
			
			if ($current_request_current_page == '') {
				$current_request_current_page = $language['unavailable'];
			}
			
			// Set the referrer as approriate
			if ($current_request_referrer != '' && $current_request_referrer != 'false') {
				$current_request_referrer_title = $current_request_referrer;
			}
			elseif ($current_request_referrer == 'false') {
				$current_request_referrer_title = 'Direct Visit / Bookmark';
			}
			else {
				$current_request_referrer_title = $language['unavailable'];
			}
?>
<Visitor ID="<?php echo($current_request_id); ?>" Flag="<?php echo($current_request_request_flag); ?>">
<SessionID><?php echo($current_request_session_id); ?></SessionID>
<Hostname><?php echo(gethostbyaddr($current_request_ip_address)); ?></Hostname>
<Country><?php echo($current_request_country); ?></Country>
<UserAgent><?php echo($current_request_user_agent); ?></UserAgent>
<CurrentPage><?php echo($current_request_current_page); ?></CurrentPage>
<CurrentPageTitle><?php echo($current_request_current_page_title); ?></CurrentPageTitle>
<Referrer><?php echo($current_request_referrer_title); ?></Referrer>
<TimeOnPage><?php echo($current_request_time_on_page); ?></TimeOnPage>
<PagePath><?php echo($current_request_page_path); ?></PagePath>
<TimeOnSite><?php echo($current_request_time_on_site); ?></TimeOnSite>
</Visitor>
<?php
			
			
		}
	}
}
?>
</SiteVisitors>