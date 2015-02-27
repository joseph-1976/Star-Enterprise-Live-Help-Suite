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
if (!isset($_SERVER['HTTP_REFERER'])){ $_SERVER['HTTP_REFERER'] = ""; }
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }
if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
if (!isset($_GET['COOKIE'])){ $_GET['COOKIE'] = ""; }
if (!isset($_GET['TRACKER'])){ $_GET['TRACKER'] = ""; }
if (!isset($_GET['STATUS'])){ $_GET['STATUS'] = ""; }

$install_status = 'false';

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
if ($install_status == 'true') {
include($install_path . '/livehelp/include/class.mysql.php');
include($install_path . '/livehelp/include/config.php');

$language_file = $install_path . '/livehelp/locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include($install_path . '/livehelp/locale/en/lang_en.php');
}

$department = $_GET['DEPARTMENT'];
$server = $_GET['SERVER'];
$cookie_domain = $_GET['COOKIE'];
$tracker_enabled = $_GET['TRACKER'];

if ($server != '') { $site_address = $server; }
if ($tracker_enabled == '') { $tracker_enabled = 'true'; }

if ($cookie_domain != '') {
	ini_set('session.cookie_domain', '.' . $cookie_domain);
}
else {
	// Set session cookie timeout and domain
	if (preg_match("/^(http:\/\/)?([^\/]+)/i", $_SERVER['HTTP_REFERER'], $matches)) {
		if (is_array($matches)) {
			$hostname = $matches[2];
			preg_match('/[^\.\/]+\.[^\.\/]+(\.[^\.\/]{2})?$/', $hostname, $matches);
			
			if (is_array($matches)) {
				$domain = $matches[0];
				ini_set('session.cookie_domain', '.' . $domain);
			}
		}
	}
}

session_start();
$current_session = session_id();
session_write_close();

?>
<!--
// Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 International Copyright 2003
// JavaScript Check Status Functions

var server = '<?php echo($site_address); ?>';
var session = '<?php echo($current_session); ?>';

function currentTime() {
	var date = new Date();
	return date.getTime();
}

var ns6 = (!document.all && document.getElementById); 
var ie4 = (document.all);
var ns4 = (document.layers);

var initiateOpen = 0;
var infoOpen = 0;
var infoImageLoaded = 0;
var countTracker = 0;
var idleTimeout = 90 * 60 * 1000;
var timeStart = currentTime();
var timeElapsed; var timerTracker; var timerInitiateLayer; var timerLiveHelpInfo; var timerClosingLiveHelpInfo;

var topMargin = 10;
var leftMargin = 10;
var itopMargin = topMargin;
var ileftMargin = leftMargin;
var hAlign = "<?php echo($initiate_chat_halign); ?>";
var vAlign = "<?php echo($initiate_chat_valign); ?>";
var layerHeight = 238;
var layerWidth = 377;
var slideTime = 1200;

var windowWidth = 525;
var windowHeight = 380;
var windowLeft = (screen.width - windowWidth) / 2;
var windowTop = (screen.height - windowHeight) / 2;
var size = 'height=' + windowHeight + ',width=' + windowWidth + ',top=' + windowTop + ',left=' + windowLeft;

var trackerStatus = new Image;
var time = currentTime();
var title = parent.document.title;
var referrer = escape(parent.document.referrer);

if (referrer == '') { referrer = 'false'; }

<?php if ($tracker_enabled == 'true' ) { ?>

// Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 International Copyright 2003
// JavaScript Initiate Chat Layer Functions

window.onload = onloadEvent;
window.onresize = resetLayerLocation;

function onloadEvent() {
	resetLayerLocation();
	main();
}

function toggle(object) {
  if (document.getElementById) {
    if (document.getElementById(object).style.visibility == 'visible')
      document.getElementById(object).style.visibility = 'hidden';
    else
      document.getElementById(object).style.visibility = 'visible';
  }
  else if (document.layers && document.layers[object] != null) {
    if (document.layers[object].visibility == 'visible' || document.layers[object].visibility == 'show' )
      document.layers[object].visibility = 'hidden';
    else
      document.layers[object].visibility = 'visible';
  }
  else if (document.all) {
    if (document.all[object].style.visibility == 'visible')
      document.all[object].style.visibility = 'hidden';
    else
      document.all[object].style.visibility = 'visible';
  }
  return false;
}

function initiateFloatLayer() {
	var openingTrackerStatus = new Image;
	var time = currentTime();
	
	openingTrackerStatus.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=opened&SESSION=<?php echo($current_session); ?>';

	if ( ie4 )initiateChatResponse.location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=opened&SESSION=<?php echo($current_session); ?>';
	if ( ns4 )document.initiateChatResponse.location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=opened&SESSION=<?php echo($current_session); ?>';
	if ( ns6 )document.getElementById('initiateChatResponse').location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=opened&SESSION=<?php echo($current_session); ?>';

	floatRefresh();
}
	
function floatRefresh() {
	window.clearTimeout(timerInitiateLayer);
	window.clearTimeout(timerTracker);
	if (countTracker == 10000) {
		var time = currentTime();
		trackerStatus.onload = resetTimer;
		trackerStatus.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&REFERRER=' + referrer;
		countTracker = 0;
	}
	else {
		countTracker = countTracker + 10;
		timerInitiateLayer = window.setTimeout('main();floatRefresh();', 10);
	}
}

function resetTimer() {
	main(); floatRefresh();
}
	
function floatObject() { 
	if (ns4 || ns6) {
		findHeight = window.innerHeight;
		findWidth = window.innerWidth;
	} else if(ie4) {
		findHeight = document.body.clientHeight;
		findWidth = document.body.clientWidth;
	}
} 

function main() { 
	if (ns4) { 
		this.currentY = document.floatLayer.top;
		this.currentX = document.floatLayer.left; 
		this.scrollTop = window.pageYOffset;
		this.scrollLeft = window.pageXOffset;
		mainTrigger();
	} else if(ns6) {
		this.currentY = parseInt(document.getElementById('floatLayer').style.top);
		this.currentX = parseInt(document.getElementById('floatLayer').style.left); 
		this.scrollTop = scrollY;
		this.scrollLeft = scrollX;
		mainTrigger();
	} else if(ie4) { 
		this.currentY = floatLayer.style.pixelTop;
		this.currentX = floatLayer.style.pixelLeft;
		this.scrollTop = document.body.scrollTop;
		this.scrollLeft = document.body.scrollLeft; 
		mainTrigger();
	}
} 

function mainTrigger() { 
	var newTargetY = this.scrollTop + this.topMargin;
	var newTargetX = this.scrollLeft + this.leftMargin;
	if ( this.currentY != newTargetY || this.currentX != newTargetX) { 
		if ( newTargetY != this.targetY || newTargetX != this.targetX ) { 
			this.targetY = newTargetY; this.targetX = newTargetX;
			floatStart();
		} 
		animate(); 
	} 
} 

function floatStart() { 
	var now = new Date();
	this.Y = this.targetY - this.currentY; this.X = this.targetX - this.currentX;
	
	this.C = Math.PI / ( 2 * this.slideTime ) 
	this.D = now.getTime() 
	if (Math.abs(this.Y) > this.findHeight) { 
		this.E = this.Y > 0 ? this.targetY - this.findHeight : this.targetY + this.findHeight;
		this.Y = this.Y > 0 ? this.findHeight : -this.findHeight;
	} else { 
		this.E = this.currentY;
	} 
	if (Math.abs(this.X) > this.findWidth) { 
		this.F = this.X > 0 ? this.targetX - this.findWidth : this.targetX + this.findWidth;
		this.X = this.X > 0 ? this.findWidth : -this.findWidth;
	} else { 
		this.F = this.currentX;
	} 
} 

function animate() { 
	var now = new Date() ;
	var newY = this.Y * Math.sin( this.C * ( now.getTime() - this.D ) ) + this.E;
	var newX = this.X * Math.sin( this.C * ( now.getTime() - this.D ) ) + this.F;
	newY = Math.round(newY);
	newX = Math.round(newX);
	if (( this.Y > 0 && newY > this.currentY ) || ( this.Y < 0 && newY < this.currentY )) { 
		if ( ie4 ) { floatLayer.style.pixelTop = newY; }
		else if ( ns4 ) { document.floatLayer.top = newY; }
		else if ( ns6 ) { document.getElementById('floatLayer').style.top = newY + "px"; }
	}
	if (( this.X > 0 && newX > this.currentX ) || ( this.X < 0 && newX < this.currentX )) { 
		if ( ie4 ) { floatLayer.style.pixelLeft = newX; }
		else if ( ns4 ) { document.floatLayer.left = newX; }
		else if ( ns6 ) { document.getElementById('floatLayer').style.left = newX + "px"; }
	}
} 

function resetLayerLocation() {
	if ((ns4) || (ns6)) { 
		if (hAlign == "left") { leftMargin = ileftMargin; }
		if (hAlign == "right") { leftMargin = window.innerWidth-ileftMargin-layerWidth-20; }
		if (hAlign == "center") { leftMargin = Math.round((window.innerWidth-20)/2)-Math.round(layerWidth/2); }
		if (vAlign == "top") { topMargin = itopMargin; } 
		if (vAlign == "bottom") { topMargin = window.innerHeight-itopMargin-layerHeight; }
		if (vAlign == "center") { topMargin = Math.round((window.innerHeight-20)/2)-Math.round(layerHeight/2); }
	}
	if (ie4) {
		if (hAlign == "left") { leftMargin = ileftMargin; }
		if (hAlign == "right") { leftMargin = document.body.offsetWidth-ileftMargin-layerWidth-20; }
		if (hAlign == "center") { leftMargin = Math.round((document.body.offsetWidth-20)/2)-Math.round(layerWidth/2); }
		if (vAlign == "top") { topMargin = itopMargin; }
		if (vAlign == "bottom") { topMargin = document.body.offsetHeight-itopMargin-layerHeight; }
		if (vAlign == "center") { topMargin = Math.round((document.body.offsetHeight-20)/2)-Math.round(layerHeight/2); }
	}
}

function stopLayer() {
	window.clearTimeout(timerInitiateLayer);	
	if ( ns4 ) {
		document.floatLayer.top = "10"; document.floatLayer.left = "10";
	} else if ( ns6 ) {
		document.getElementById('floatLayer').style.top = "10"; document.getElementById('floatLayer').style.left = "10";
	} else if ( ie4 ) {
		floatLayer.style.pixelTop = "10"; floatLayer.style.pixelLeft = "10";
	}
}

function acceptInitiateChat() {
	var acceptTrackerStatus = new Image;
	var time = currentTime();
	
	acceptTrackerStatus.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=accepted&SESSION=<?php echo($current_session); ?>';

	if ( ie4 )initiateChatResponse.location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=accepted&SESSION=<?php echo($current_session); ?>';
	if ( ns4 )document.initiateChatResponse.location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=accepted&SESSION=<?php echo($current_session); ?>';
	if ( ns6 )document.getElementById('initiateChatResponse').location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=accepted&SESSION=<?php echo($current_session); ?>';

	if (initiateOpen == 1) {
		toggle('floatLayer');
	}
	stopLayer();
	onlineTracker();
}

function declineInitiateChat() {
	var declineTrackerStatus = new Image;
	var time = currentTime();
	
	declineTrackerStatus.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=declined&SESSION=<?php echo($current_session); ?>';

	if ( ie4 )initiateChatResponse.location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=declined&SESSION=<?php echo($current_session); ?>';
	if ( ns4 )document.initiateChatResponse.location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=declined&SESSION=<?php echo($current_session); ?>';
	if ( ns6 )document.getElementById('initiateChatResponse').location = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&INITIATE=declined&SESSION=<?php echo($current_session); ?>';

	if (initiateOpen == 1) {
		toggle('floatLayer');
		initiateOpen = 0;
	}
	stopLayer();
	onlineTracker();
}

function checkInitiate() {
	// Check if site visitor has an Initiate Chat Request Pending for display...
	var imageWidth = this.width; var imageHeight = this.height;
	
	if (imageWidth == 2 && imageHeight == 2 && initiateOpen == 0) {
		initiateFloatLayer();
		toggle('floatLayer');
		initiateOpen = 1;
	} 
}

<?php } ?>

//-->
<?php

if (isset($current_session)) {

	$ip_address = $_SERVER['REMOTE_ADDR'];
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$referer = $_SERVER['HTTP_REFERER'];

	// Get the current page from the referer (the page the JavaScript was called from)
	$current_page = $_SERVER['HTTP_REFERER'];
	for ($i = 0; $i < 3; $i++) {
		$substr_pos = strpos($current_page, '/');
		if ($substr_pos === false) {
			$current_page = '';
			break;
		}
		if ($i < 2) {
			$current_page = substr($current_page, $substr_pos + 1);
		}
		elseif ($i >= 2) {
			$current_page = substr($current_page, $substr_pos);
		}
	}

	// Get the current host from the referer (the page the JavaScript was called from)
	$current_host = $_SERVER['HTTP_REFERER'];
	$str_start = 0; 
	for ($i = 0; $i < 3; $i++) {
		$substr_pos = strpos($current_host, '/');
		if ($substr_pos === false) {
			break;
		}
		if ($i < 2) {
			$current_host = substr($current_host, $substr_pos + 1);
			$str_start += $substr_pos + 1;
		}
		elseif ($i >= 2) {
			$current_host = substr($_SERVER['HTTP_REFERER'], 0, $substr_pos + $str_start);
		}
	}


	// Update the current URL statistics within the requests tables
	if ($current_page == '') { $current_page = '/'; }
 
 	$select_session_id = "SELECT page_path FROM " . $table_prefix . "requests WHERE session_id = '$current_session'";
	$row = $SQL->selectquery($select_session_id);
 
	if (is_array($row)) {
		$current_page = urldecode($current_page);
		$prev_path = explode(';  ', $row['page_path']);
		$current_path = $row['page_path'];
	
		end($prev_path);
		$index = key($prev_path);
			
		if ($current_page != $prev_path[$index]) {
			if (isset($_COOKIE['PHPSESSID'])) {
				$update_current_url_stat = "UPDATE " . $table_prefix . "requests SET last_request = NOW(), current_page = '$referer', page_path = '$current_path;  $current_page', status = '0' WHERE session_id = '$current_session'";
				$SQL->insertquery($update_current_url_stat);
			}
			else {
				$update_current_url_stat = "UPDATE " . $table_prefix . "requests SET last_request = NOW(), current_page = '$referer', page_path = '$current_path;  $current_page', status = '1' WHERE session_id = '$current_session'";
				$SQL->insertquery($update_current_url_stat);
			}
		}
		else {
			if (isset($_COOKIE['PHPSESSID'])) {
				$update_current_url_stat = "UPDATE " . $table_prefix . "requests SET last_request = NOW(), current_page = '$referer', status = '0' WHERE session_id = '$current_session'";
				$SQL->insertquery($update_current_url_stat);
			}
		}
	}
	else {
		if (isset($_COOKIE['PHPSESSID'])) {
			$insert_current_url_stat = "INSERT INTO " . $table_prefix . "requests(request_id,session_id,ip_address,user_agent,first_request,last_request,last_refresh,current_page,current_page_title,page_path,request_flag,status) VALUES('', '$current_session', '$ip_address', '$user_agent', NOW(), NOW(), NOW(), '$referer', '', '$current_page', '0', '0')";
			$SQL->insertquery($insert_current_url_stat);
		}
		else {
			$insert_current_url_stat = "INSERT INTO " . $table_prefix . "requests(request_id,session_id,ip_address,user_agent,first_request,last_request,last_refresh,current_page,current_page_title,page_path,request_flag,status) VALUES('', '$current_session', '$ip_address', '$user_agent', NOW(), NOW(), NOW(), '$referer', '', '$current_page', '0', '1')";
			$SQL->insertquery($insert_current_url_stat);
		}
	}
}

// Counts the total number of support users Hidden Offline status -1 over ALL Departments
$query_select_count_hidden_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-1'";
$row_count_hidden_users = $SQL->selectquery($query_select_count_hidden_users);
$total_num_support_hidden_users = $row_count_hidden_users['count(login_id)'];	

// Counts the total number of support users Online status -2 (doesn't include hidden users) over ALL Departments
$query_select_count_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
$row_count_users = $SQL->selectquery($query_select_count_users);
$total_num_support_users = $row_count_users['count(login_id)'];

// Counts the total number of support users in Be Right Back status -4 over ALL Departments
$query_select_count_brb_users = "SELECT count(login_id) FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-4'";
$row_count_brb_users = $SQL->selectquery($query_select_count_brb_users);
$total_num_support_brb_users = $row_count_brb_users['count(login_id)'];

$initiate_request_flag = 0;

$select_session_id = "SELECT request_id, request_flag, page_path FROM " . $table_prefix . "requests WHERE session_id = '$current_session'";
$row = $SQL->selectquery($select_session_id);

// Set the initate chat flag in case admin has initated a chat request
$initiate_request_flag = $row['request_flag'];

// If Initiate Chat Request has occured the open the Live Help chat window and auto-login
if ($initiate_request_flag > 0 || $initiate_request_flag == -1) {

	// Update request flag to show that the guest uesr OPENED the Online Chat Request
	$query_update_refresh = "UPDATE " . $table_prefix . "requests SET request_flag = '-1' WHERE session_id = '$current_session'";
	$SQL->miscquery($query_update_refresh);
?>
<!--

	window.onload = onloadEventInit;

function onloadEventInit() {
	resetLayerLocation();
	main();
	
	initiateFloatLayer();
	toggle('floatLayer');
	initiateOpen = 1;
}

//-->
<?php
}
?>

<!--

<?php

//  If popup help is not disabled then
if ($disable_popup_help != 'true') {

?>
function openInfo(statusImage, e) {
	window.clearTimeout(timerLiveHelpInfo);
	cancelClosingInfo();
	
	var iLayerX = document['LiveHelpInfoContent'].width;
	var iLayerY = document['LiveHelpInfoContent'].height;
	var iPosX = statusImage.offsetLeft;
	var iPosY = statusImage.offsetTop;

	var iWidth = statusImage.clientWidth;
	var iHeight = statusImage.clientHeight;
	if (!iWidth) {
		iWidth = statusImage.offsetWidth;
	}
	obj = statusImage.offsetParent;
	while(obj != null){
		iPosX += obj.offsetLeft;
		iPosY += obj.offsetTop;
		obj = obj.offsetParent;
	}

	var iCurrentY; var iCurrentX; var iScrollTop; var iScrollLeft; var iFindHeight; var iFindWidth;

	if (ns4) { 
		iScrollTop = window.pageYOffset;
		iScrollLeft = window.pageXOffset;
	} else if(ns6) {
		iScrollTop = scrollY;
		iScrollLeft = scrollX;
	} else if(ie4) { 
		iScrollTop = document.body.scrollTop;
		iScrollLeft = document.body.scrollLeft; 
		iFindHeight = document.body.clientHeight;
		iFindWidth = document.body.clientWidth;
	}
	
	if (ns4 || ns6) {
		iFindHeight = window.innerHeight; iFindWidth = window.innerWidth;
	}

	infoImage = new Image();
	infoImage.onload = infoImageLoad;
	
	var iMarginHeight = iFindHeight - (iHeight + iPosY - iScrollTop);
	var iMarginWidth = iFindWidth - (iWidth + iPosX - iScrollLeft);
	
	if (iMarginHeight < iLayerY && iPosY > iLayerY) {
		infoImage.src = "<?php echo($site_address); ?>/livehelp/images/livehelp_info_bg_top.gif";
		if (ie4) { 
			LiveHelpInfo.style.background = 'url(' + infoImage.src + ')';
		} else if (ns4) { 
			document.LiveHelpInfo.background = 'url(' + infoImage.src + ')';
		} else if(ns6) {
			document.getElementById('LiveHelpInfo').style.background = 'url(' + infoImage.src + ')';
		}
		iNewX = iPosX - 15;
		iNewY = iPosY + 20 - iLayerY - 20;
	}
	else {
		infoImage.src = "<?php echo($site_address); ?>/livehelp/images/livehelp_info_bg_bottom.gif";
		if (ie4) { 
			LiveHelpInfo.style.background = 'url(' + infoImage.src + ')';
		} else if (ns4) { 
			document.LiveHelpInfo.background = 'url(' + infoImage.src + ')';
		} else if(ns6) {
			document.getElementById('LiveHelpInfo').style.background = 'url(' + infoImage.src + ')';
		}
		iNewX = iPosX + 15;
		iNewY = iPosY + 20 + statusImage.height - 20;
	}
	
	if (iMarginWidth < iLayerX && iPosX > iLayerX) {
		iNewX = iPosX - iLayerX + 175;
	}
	else if (iMarginWidth > iLayerX && iPosX < iLayerX) {
		iNewX = iPosX + 25;
	}
	
	if (ie4) { 
		LiveHelpInfo.style.pixelTop = iNewY;
		LiveHelpInfo.style.pixelLeft = iNewX;
	} else if (ns4) { 
		document.LiveHelpInfo.top = iNewY;
		document.LiveHelpInfo.left = iNewX; 
	} else if(ns6) {
		document.getElementById('LiveHelpInfo').style.top = iNewY + "px";
		document.getElementById('LiveHelpInfo').style.left = iNewX + "px"; 
	}
	if (infoImageLoaded == 1) {
		displayInfo();
	}
}

function infoImageLoad() {
	infoImageLoaded = 1;
}

function displayInfo() {
	if (infoOpen == 0) {
		timerLiveHelpInfo = window.setTimeout("toggle('LiveHelpInfo'); infoOpen = 1;", 2000);
	}
}

function closeInfo() {
	window.clearTimeout(timerLiveHelpInfo);
	if (infoOpen == 1) {
		timerClosingLiveHelpInfo = window.setTimeout("toggle('LiveHelpInfo'); infoOpen = 0;", 500);
	}
}

function cancelClosingInfo() {
	window.clearTimeout(timerClosingLiveHelpInfo);
}
<?php
}
else {
?>
function openInfo(statusImage, e) {
	return false;
}

function infoImageLoad() {
	return false;
}

function displayInfo() {
	return false;
}

function closeInfo() {
	return false;
}

function cancelClosingInfo() {
	return false;
}
<?php
}
?>

function openLiveHelp() {
<?php
// If Admin Users are Offline/Hidden and Offline Email is disabled
if (($total_num_support_users == $total_num_support_hidden_users || $total_num_support_users == 0) && $disable_offline_email == 'true') {
?>
	return false;
<?php
}
?>
	var winLiveHelp = window.open('<?php echo($site_address); ?>/livehelp/index.php?REFERER=' + escape(document.referrer)<?php if ($department != '') { echo(" + '&DEPARTMENT=" . $department . "' "); } ?> + '&TITLE=' + document.title + '&URL=' + document.location + '&SERVER=<?php echo($server); ?>&LANGUAGE=<?php echo(LANGUAGE_TYPE); ?>', 'SUPPORTER', size);
	if (winLiveHelp) { winLiveHelp.opener = self; }
}

function onlineTracker() {
	var time = currentTime();
<?php
// If the Online Tracker is Enabled and there is Admin Users Online/Hidden/BRB then... start JavaScript timer
if ($tracker_enabled == 'true' && ($total_num_support_users > 0 || $total_num_support_hidden_users > 0 || $total_num_support_brb_users > 0) ) {
?>
	trackerStatus.onload = checkInitiate;
	trackerStatus.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&REFERRER=' + referrer;

	// If the Site Visitor has been Idle for under the given idleTimout then run the tracker
	timeElapsed = time - timeStart;
	if (timeElapsed < idleTimeout) {
		window.clearTimeout(timerTracker);
		timerTracker = window.setTimeout('onlineTracker();', 10000);
	}
<?php
}
else {
?>
	trackerStatus.src = '<?php echo($site_address); ?>/livehelp/include/image_tracker.php?TIME=' + time + '&TITLE=' + title + '&REFERRER=' + referrer;
<?php
}
?>
}

onlineTracker();

//-->
<?php
}
?>