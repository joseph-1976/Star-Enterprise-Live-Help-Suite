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

// Open MySQL Connection
$SQL = new mySQL; 
$SQL->connect();

// Set default language/charset
if (!isset($_GET['LANGUAGE'])){ $_GET['LANGUAGE'] = ''; }
if (!isset($_GET['CHARSET'])){ $_GET['CHARSET'] = ''; }

$query_select_settings = "SELECT setting_name,setting_value FROM " . $table_prefix . "settings";
$rows_settings = $SQL->selectall($query_select_settings);
if (is_array($rows_settings)) {
	foreach ($rows_settings as $row) {
		if (is_array($row)) {
			$variable = $row['setting_name'];
			$$variable = $row['setting_value'];
		}
	}
}

// Make language type constant and overide default if set manually
if (isset($_GET['LANGUAGE']) && strlen($_GET['LANGUAGE']) == 2) {
	$language_type = $_GET['LANGUAGE'];
	$charset = $_GET['CHARSET'];
	
	session_start();
	$_SESSION['LANGUAGE'] = $language_type;
	$_SESSION['CHARSET'] = $charset;
	session_write_close();
	
}
else {
	if (isset($_SESSION['LANGUAGE']) && strlen($_SESSION['LANGUAGE']) == 2) {
		session_start();
		$language_type = $_SESSION['LANGUAGE'];
		$charset = $_SESSION['CHARSET'];
		session_write_close();
	}
	else {
		$language = split(',', $language_type);
		$language_type = trim($language[0]);
		$charset = trim($language[1]);
	
	}
}
define('LANGUAGE_TYPE', $language_type);
define('CHARSET', $charset);

//  Modify the language pack image locations unless images have been relocated and file exists
$lang_images_directory = '/livehelp/locale/' . LANGUAGE_TYPE . '/images';
if (!(strpos($livehelp_logo, '/livehelp/locale/') === false) && file_exists($include_path . $lang_images_directory . strrchr($livehelp_logo, "/"))) { $livehelp_logo = $lang_images_directory . strrchr($livehelp_logo, "/"); }
if (!(strpos($offline_logo, '/livehelp/locale/') === false) && file_exists($include_path . $lang_images_directory . strrchr($offline_logo, "/"))) { $offline_logo = $lang_images_directory . strrchr($offline_logo, "/"); }
if (!(strpos($online_logo, '/livehelp/locale/') === false) && file_exists($include_path . $lang_images_directory . strrchr($online_logo, "/"))) { $online_logo = $lang_images_directory . strrchr($online_logo, "/"); }
if (!(strpos($offline_logo_without_email, '/livehelp/locale/') === false) && file_exists($include_path . $lang_images_directory . strrchr($offline_logo_without_email, "/"))) { $offline_logo_without_email = $lang_images_directory . strrchr($offline_logo_without_email, "/"); }
if (!(strpos($online_brb_logo, '/livehelp/locale/') === false) && file_exists($include_path . $lang_images_directory . strrchr($online_brb_logo, "/"))) { $online_brb_logo = $lang_images_directory . strrchr($online_brb_logo, "/"); }
if (!(strpos($online_away_logo, '/livehelp/locale/') === false) && file_exists($include_path . $lang_images_directory . strrchr($online_away_logo, "/"))) { $online_away_logo = $lang_images_directory . strrchr($online_away_logo, "/"); }

if (!isset($_POST['SITE_ADDRESS'])){
	// Modify the protocol dependant on the current server port
	$protocols = array("http://", "https://"); 
	$site_address_original = $site_address; 
	if ($_SERVER['SERVER_PORT'] == '443') {
		$protocol = 'https://';
		$site_address = str_replace($protocols, $protocol, $site_address); 
	}
	else {
		$protocol = 'http://';
		$site_address = str_replace($protocols, $protocol, $site_address); 
	}
}

// Calculate timezone difference respective of LOCAL and REMOTE timezones
$local_timezone_sign = substr($timezone, 0, 1);
$local_timezone_hours = substr($timezone, 1, 2);
$local_timezone_minutes = substr($timezone, 3, 4);

// Convert LOCAL time to decimal format
if ($local_timezone_minutes != '00') { $local_timezone_minutes = ($local_timezone_minutes / 60); }
$local_timezone = $local_timezone_sign . $local_timezone_hours + $local_timezone_minutes;

$remote_timezone_sign = substr(date("O"), 0, 1);
$remote_timezone_hours = substr(date("O"), 1, 2);
$remote_timezone_minutes = substr(date("O"), 3, 4);

// Convert REMOTE time to decimal format
if ($remote_timezone_minutes != '00') { $remote_timezone_minutes = ($remote_timezone_minutes / 60); }
$remote_timezone = $remote_timezone_sign . $remote_timezone_hours + $remote_timezone_minutes;

// Calculate difference between decimal LOCAL time and REMOTE time and CONVERT to eg. +/-0430 format
$difference_timezone_hours = round(($local_timezone - $remote_timezone) - 0.1);
$difference_timezone_minutes = (($local_timezone - $remote_timezone) - $difference_timezone_hours) * 60;

?>