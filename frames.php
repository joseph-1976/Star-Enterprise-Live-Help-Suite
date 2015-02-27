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
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

$language_file = './locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('./locale/en/lang_en.php');
}

if (!isset($_POST['USER_NAME'])){ $_POST['USER_NAME'] = ""; }
if (!isset($_POST['EMAIL'])){ $_POST['EMAIL'] = ""; }
if (!isset($_POST['DEPARTMENT'])){ $_POST['DEPARTMENT'] = ""; }
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }
if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
if (!isset($_GET['URL'])){ $_GET['URL'] = ""; }
if (!isset($_GET['SESSION'])){ $_GET['SESSION'] = ""; }


$username = str_replace('\\\\\'', '\'', addslashes($_POST['USER_NAME']));
$email = addslashes($_POST['EMAIL']);
$department = $_POST['DEPARTMENT'];
$referer = $_GET['URL'];
$ip_address = $_SERVER['REMOTE_ADDR'];

if ($email != '') {
	if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
		'@'.
		'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
		'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
		if ($department == '') {
			header('Location: index.php?ERROR=email');
		}
		else {
			header('Location: index.php?ERROR=email&DEPARTMENT=' . $department);
		}
		exit();
	}
}

if ($department == '') { $department = $_GET['DEPARTMENT']; }

if ($_GET['SESSION'] != '') {
	$current_session = $_GET['SESSION'];
	session_id($current_session);
	session_start();
}
else {
	session_start();
	$current_session = session_id();
}

// Query to see if Admin/Operators are Online
$query_select_admin_online = "SELECT UNIX_TIMESTAMP(s.datetime) AS datetime FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
if ($departments == 'true' && $department != '') { $query_select_admin_online .= " AND u.department LIKE '%$department%'"; }
$row_admin_online = $SQL->selectquery($query_select_admin_online);
if(is_array($row_admin_online)) {
	$datetime = $row_admin_online['datetime'];
}
else {
	header('Location: index_offline.php?SERVER=' . $_GET['SERVER']);
}

// Remove the Initate chat flag and flag as Chatting from the requests as the chat has started.
$query_update_status = "UPDATE " . $table_prefix . "requests SET request_flag = '-4' WHERE session_id = '$current_session'";
$SQL->miscquery($query_update_status);

// Get the applicable hostname to show where the site visitor is located
$current_host = $_GET['URL'];
for ($i = 0; $i < 3; $i++) {
	$substr_pos = strpos($current_host, '/');
	if ($substr_pos === false) {
		break;
	}
	if ($i < 2) {
		$current_host = substr($current_host, $substr_pos + 1);
	}
	else {
		$current_host = substr($current_host, 0, $substr_pos);
	}

}

if (substr($current_host, 0, 4) == 'www.') { $current_host = substr($current_host, 4); }

if ($username == '') { $username = 'guest'; }

$query_select_count = "SELECT count(session_id) FROM " . $table_prefix . "sessions WHERE username LIKE '$username%' AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(last_refresh)) < $datetime";
$row_count = $SQL->selectquery($query_select_count);
if(is_array($row_count)) {
	if ($row_count['count(session_id)'] > 0) {
		$query_select = "SELECT username FROM " . $table_prefix . "sessions WHERE username LIKE '$username%' AND UNIX_TIMESTAMP(datetime) > $datetime ORDER BY login_id DESC LIMIT 1";
		$row = $SQL->selectquery($query_select);
		if (is_array($row)) {
			$length = strlen($username);
			$num_users = substr($row['username'], $length);
			$username_id = (int)$num_users + 1;
			$username = "$username" . "$username_id";
		}
	}
}

$_SESSION['USERNAME'] = $username;
$_SESSION['REFERER_URL'] = $referer;

// Add session details to the database
$query_insert_session = "INSERT INTO " . $table_prefix . "sessions (login_id,session_id,username,datetime,email,ip_address,server,support_department,rating,active,last_refresh) VALUES ('','$current_session','$username',NOW(),'$email','$ip_address','$current_host','$department',-1,'',NOW())";
$result = $SQL->insertquery($query_insert_session);

// Find login ID for current session and assign as a session variable
$query_select = "SELECT login_id FROM " . $table_prefix . "sessions WHERE session_id = '$current_session' AND username='$username' ORDER BY datetime DESC LIMIT 1";
$rows = $SQL->selectall($query_select);
if (is_array($rows)) {
	foreach ($rows as $row) {
		if (is_array($row)) {
		$login_id = $row['login_id'];
		$_SESSION['GUEST_LOGIN_ID'] = $login_id;
		}
	}
}

$query_select = "SELECT server FROM " . $table_prefix . "sessions WHERE login_id = $login_id";
$row = $SQL->selectquery($query_select);
if (is_array($row)) {
	$server = $row['server'];
}
header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<script language="JavaScript" type="text/JavaScript">
<!--
// Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 International Copyright 2003
// JavaScript Check Status Functions

function currentTime() {
	var date = new Date();
	return date.getTime();
}

function windowLogout() {
	var tracker = new Image;
	tracker.src = './logout.php?<?php echo(SID); ?>';
	window.location = './logout.php?<?php echo(SID); ?>';
}

var stats = new Image;
var time = currentTime();
stats.src = 'statistics.php?WIDTH=' + screen.width + '&HEIGHT=' + screen.height + '&REFERER_URL=<?php echo($referer); ?>&<?php echo(SID); ?>' + '&TIME=' + time;

function supportTimer() {
	var timer = setTimeout('supportTimer()', 1000);
	
	var currentTime = document.message_form.TIMER.value.split(":");
	currentMinutes = currentTime[0];
	currentSeconds = currentTime[1];

	if (currentMinutes.charAt(0) == '0') {
		currentMinutes = currentMinutes.charAt(1);
	}	

	if (currentSeconds.charAt(0) == '0') {
		currentSeconds = currentSeconds.charAt(1);
	}
	
	if (currentTime[1] < 59) {
		var minutes = parseInt(currentMinutes);
		var seconds = parseInt(currentSeconds) + 1;
	}
	else {
		var minutes = parseInt(currentMinutes) + 1;
		var seconds = 0;
	}
	
	if (minutes < 10) {
		minutes = '0' + minutes;
	}
	
	if (seconds < 10) {
		seconds = '0' + seconds;
	}
	
	newTime = minutes + ":" + seconds;
	document.message_form.TIMER.value = newTime;
}

function toggle(object) {
  if (document.getElementById) {
    if (document.getElementById(object).style.visibility == 'visible')
      document.getElementById(object).style.visibility = 'hidden';
    else
      document.getElementById(object).style.visibility = 'visible';
  }

  else if (document.layers && document.layers[object] != null) {
    if (document.layers[object].visibility == 'visible' ||
        document.layers[object].visibility == 'show' )
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

function high(theobject) {
  if (theobject.style.MozOpacity)
    theobject.style.MozOpacity=1
  else if (theobject.filters)
   theobject.filters.alpha.opacity=100
}

function low(which2) {
  if (which2.style.MozOpacity)
    which2.style.MozOpacity=0.75
  else if (which2.filters)
    which2.filters.alpha.opacity=75
}

function isTyping() {
	var updateIsTypingStatus = new Image;
	var time = currentTime();
	
	var message = document.message_form.MESSAGE.value;
	var intLength = message.length;
	if (intLength == 0) {
		notTyping();
	}
	else {	
		updateIsTypingStatus.src = '/livehelp/typing_status.php?LOGIN_ID=<?php echo($login_id); ?>&STATUS=true&TIME=' + time;
	}
}

function notTyping() {
	var updateNotTypingStatus = new Image;
	var time = currentTime();
	
	updateNotTypingStatus.src = '/livehelp/typing_status.php?LOGIN_ID=<?php echo($login_id); ?>&STATUS=false&TIME=' + time;
}

function limitInput(field, limit) {
	if (field.value.length > limit) {
		field.value = field.value.substring(0, limit);
	}
}

function swapImgRestore() { //v3.0
  var i,x,a=document.sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.p) d.p=new Array();
    var i,j=d.p.length,a=preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.p[j]=new Image; d.p[j++].src=a[i];}}
}

function findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function swapImage() { //v3.0
  var i,j=0,x,a=swapImage.arguments; document.sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=findObj(a[i]))!=null){document.sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

//-->
</script>
<style type="text/css">
<!--
.background {
	background-image: url(./images/livehelp_people_alpha.gif);
	background-repeat: no-repeat;
	background-position: left bottom;
}
.header-background {
	background-image: url(./images/livehelp_chat_header_bg.gif);
	background-repeat: no-repeat;
	background-position: left top;
}
.chat-background {
	background-image: url(./images/border.gif);
	background-repeat: no-repeat;
	background-position: center center;
}
-->
</style>
<link href="styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="preloadImages('/livehelp/locale/<?php echo(LANGUAGE_TYPE); ?>/images/send_hover.gif')" onUnload="windowLogout();" onBeforeUnload="windowLogout();"> 
<table width="525" border="0" cellpadding="7" cellspacing="0" class="header-background"> 
  <tr> 
    <td><div align="left"><img src="<?php echo($_GET['SERVER'] . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0"></div></td> 
  </tr> 
</table> 
<table width="525" border="0" cellpadding="0" cellspacing="0" class="background"> 
  <tr> 
    <td><table width="95%" border="0" align="center" cellpadding="2" cellspacing="2"> 
        <tr> 
          <td width="22"><img src="icons/chat.gif" alt="<?php echo($language['chat_transcript']); ?>" width="22" height="22"></td> 
          <td><em class="heading"><?php echo($language['chat_transcript']); ?> :: <?php echo(stripslashes($username)); ?></em> </td> 
          <td><div align="right"><font size="1" face="<?php echo($font_type); ?>"><a href="./logout.php" target="_top" class="normlink"><?php echo($language['logout']); ?></a></div></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td> <table border="0" align="center" cellpadding="0" cellspacing="0"> 
        <tr> 
          <td><table width="385" border="0" align="center" cellpadding="0" cellspacing="0" class="chat-background"> 
              <tr> 
                <td></td> 
                <td></td> 
                <td></td> 
              </tr> 
              <tr> 
                <td width="10" height="170" rowspan="3">&nbsp;</td> 
                <td width="365" height="10"></td> 
                <td width="10" height="170" rowspan="3">&nbsp;</td> 
              </tr> 
              <tr> 
                <td width="365" height="150" bgcolor="#FFFFFF"><iframe name="displayFrame" id="displayFrame" src="displayer_js_frame.php?<?php echo(SID); ?>" frameborder="0" border="0" width="365" height="150"> 
                  <script language="JavaScript" type="text/JavaScript">top.location.href = 'index_offline.php?<?php echo(SID); ?>';</script> 
                  </iframe></td> 
              </tr> 
              <tr> 
                <td width="365" height="10"></td> 
              </tr> 
            </table></td> 
          <td><div align="center"><a href="<?php echo($campaign_link); ?>" target="_blank"><img src="<?php echo($campaign_image); ?>" border="0"></a></div></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td> <div id="Layer1" style="position:absolute; left:322px; top:239px; width:191px; height:77px; z-index:1; visibility: hidden;"><img src="icons/bubble.gif" width="191" height="77"></div> 
      <div id="Layer2" style="position:absolute; left:340px; top:249px; width:150px; height:45px; z-index:2; visibility: hidden;"> 
        <div align="center"> <a href="#" onClick="javascript:appendText(':-)');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_01.gif" name="22" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(';-P');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_04.gif" width="21" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':)');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_08.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);-moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('$-D');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_03.gif" width="20" height="21" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('8-)');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_07.gif" width="21" height="20" border="0" style="filter:alpha(opacity=75);-moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-/');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_05.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-O');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_12.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':(');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_06.gif" width="20" height="21" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-(');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_02.gif" width="20" height="20" border="0"  style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-|');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_09.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);-moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':--');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_10.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('/-|');toggle('Layer1');toggle('Layer2');"><img src="icons/smilie_11.gif" width="21" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a> </div> 
      </div> 
      <iframe name="sendMessageFrame" id="sendMessageFrame" src="./admin/blank.php" frameborder="0" border="0" width="0" height="0" style="visibility: hidden"></iframe> 
      <form action="send_message_js.php?<?php echo(SID); ?>" method="POST" name="message_form" target="sendMessageFrame"> 
        <div align="center"> 
          <table width="350" border="0" cellpadding="0" cellspacing="0"> 
            <tr> 
              <td valign="middle"><div align="center">Live Support Timer:
                  <input name="TIMER" type="text" id="TIMER" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;" value="00:00" size="5" readonly="true" > 
                </div></td> 
              <td><div align="center"><img src="locale/<?php echo(LANGUAGE_TYPE); ?>/images/waiting.gif" alt="Typing Status" name="messengerStatus" width="125" height="20" id="messengerStatus"></div></td> 
            </tr> 
          </table> 
<textarea name="MESSAGE" cols="45" rows="3" onKeyDown="limitInput(this, '255');" onKeyUp="return checkEnter(event); limitInput(this, '255')" onBlur="notTyping()" style="filter:alpha(opacity=75);moz-opacity:0.75" disabled="true"></textarea> 
          <input type="hidden" name="FROM_LOGIN_ID" value="<?php echo($login_id); ?>"> 
          <a href="#" onMouseOut="swapImgRestore()" onMouseOver="swapImage('Send','','./locale/<?php echo(LANGUAGE_TYPE); ?>/images/send_hover.gif',1)" onClick="return processForm();"><img src="./locale/<?php echo(LANGUAGE_TYPE); ?>/images/send.gif" alt="<?php echo($language['send_msg']); ?>" name="Send" width="42" height="42" border="0"></a>&nbsp;<a href="#" onClick="toggle('Layer1');toggle('Layer2')" ><img src="icons/smilie_01.gif" alt="<?php echo($language['add_smilie']); ?>" width="20" height="20" border="0"></a><br> 
          <span class="small"><?php echo($language['stardevelop_copyright']); ?></span> 
          <script language="JavaScript">
<!--
if (top.document.message_form.MESSAGE) {
	if (top.document.message_form.MESSAGE.disabled == false) {
		top.document.message_form.MESSAGE.focus();
	}
}

supportTimer();

function supportTimer() {
	var timer = setTimeout('supportTimer()', 1000);
	
	var currentTime = document.message_form.TIMER.value.split(":");
	currentMinutes = currentTime[0];
	currentSeconds = currentTime[1];

	if (currentMinutes.charAt(0) == '0') {
		currentMinutes = currentMinutes.charAt(1);
	}	

	if (currentSeconds.charAt(0) == '0') {
		currentSeconds = currentSeconds.charAt(1);
	}
	
	if (currentTime[1] < 59) {
		var minutes = parseInt(currentMinutes);
		var seconds = parseInt(currentSeconds) + 1;
	}
	else {
		var minutes = parseInt(currentMinutes) + 1;
		var seconds = 0;
	}
	
	if (minutes < 10) {
		minutes = '0' + minutes;
	}
	
	if (seconds < 10) {
		seconds = '0' + seconds;
	}
	
	newTime = minutes + ":" + seconds;
	document.message_form.TIMER.value = newTime;
}

function processForm() {
  notTyping();
  void(document.message_form.submit());
  document.message_form.MESSAGE.value = "";
  if (top.document.message_form.MESSAGE) {
    if (top.document.message_form.MESSAGE.disabled == false) {
      top.document.message_form.MESSAGE.focus();
	}
  }
  return false;
}

function appendText(text) {
  var current = document.message_form.MESSAGE.value;
  document.message_form.MESSAGE.value = current + text;
  if (top.document.message_form.MESSAGE) {
    if (top.document.message_form.MESSAGE.disabled == false) {
      top.document.message_form.MESSAGE.focus();
	}
  }
}

function checkEnter(e) {
  var characterCode;
  isTyping();

  if(e && e.which){
    e = e;
	characterCode = e.which;
  }
  else{							
    e = event;						
	characterCode = e.keyCode;
  } 
  
  if(characterCode == 13){ 
    processForm();
    return false;
  }
  else{
    return true;
  }
}
//-->
</script> 
        </div> 
      </form></td> 
  </tr> 
</table> 
</body>
</html>
