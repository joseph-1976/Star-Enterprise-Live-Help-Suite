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

ignore_user_abort(true);

if (!isset($_GET['LOGIN_ID'])){ $_GET['LOGIN_ID'] = ''; }
if (!isset($_GET['USER'])){ $_GET['USER'] = ''; }

if ($_GET['LOGIN_ID'] != '' && $_GET['USER'] != ''){
	$guest_login_id = $_GET['LOGIN_ID'];
	$guest_username = str_replace('\\\\\'', '\'', $_GET['USER']);
}
else {
	$guest_login_id = '';
	$guest_username = '';
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

function currentTime() {
	var date = new Date();
	return date.getTime();
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
		updateIsTypingStatus.src = '/livehelp/admin/typing_status.php?LOGIN_ID=<?php echo($guest_login_id); ?>&STATUS=true&TIME=' + time;
	}
}

function notTyping() {
	var updateNotTypingStatus = new Image;
	var time = currentTime();
	
	updateNotTypingStatus.src = '/livehelp/admin/typing_status.php?LOGIN_ID=<?php echo($guest_login_id); ?>&STATUS=false&TIME=' + time;
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
<link href="../styles/styles.php" rel="stylesheet" type="text/css">
</head>
<body onFocus="parent.document.title = 'Admin <?php echo($livehelp_name); ?>'"; onLoad="preloadImages('../locale/<?php echo(LANGUAGE_TYPE); ?>/images/send_hover.gif')"> 
<div id="Layer1" style="position:absolute; left:288px; top:8px; width:196px; height:60px; z-index:1; visibility: hidden;"><img src="../icons/bubble.gif" width="191" height="77"></div> 
<div id="Layer2" style="position:absolute; left:307px; top:17px; width:146px; height:49px; z-index:2; visibility: hidden;"> 
  <div align="center"> <a href="#" onClick="javascript:appendText(':-)');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_01.gif" name="22" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(';-P');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_04.gif" width="21" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':)');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_08.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);-moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('$-D');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_03.gif" width="20" height="21" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('8-)');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_07.gif" width="21" height="20" border="0" style="filter:alpha(opacity=75);-moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-/');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_05.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-O');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_12.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':(');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_06.gif" width="20" height="21" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-(');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_02.gif" width="20" height="20" border="0"  style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-|');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_09.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);-moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':--');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_10.gif" width="20" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('/-|');toggle('Layer1');toggle('Layer2');"><img src="../icons/smilie_11.gif" width="21" height="20" border="0" style="filter:alpha(opacity=75);moz-opacity:0.75" onMouseover="high(this)" onMouseout="low(this)"></a> </div> 
</div> 
<div align="center"> 
  <form action="../send_message_js.php" method="POST" name="message_form" target="sendMessageFrame"> 
    <table width="470" border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
        <td colspan="2"><span class="small"><?php echo($language['chatting_with']); ?>&nbsp; 
          <?php
		  if ($guest_login_id == '') {
		  	echo($language['click_chat_user']);
		  }
		  else {
		  	$query_select_server = "SELECT server FROM " . $table_prefix . "sessions WHERE login_id = '$guest_login_id'";
		  	$row = $SQL->selectquery($query_select_server);
		  	if (is_array($row)) {
		  	  $server = $row['server'];
			  if ($server != '') {
			  	if (substr($server, 0, 7) == 'http://') {
			  		$server = substr($server, 7);
			  	}
			  	elseif (substr($server, 0, 8) == 'https://') {
			  		$server = substr($server, 8);
			  	}
				echo(' ' . stripslashes($guest_username) . '@' . $server);
			  }
			  else {
			    echo(' ' . stripslashes($guest_username));
			  }
		  	}
		  }
		  ?> 
          </span> <img src="../locale/<?php echo(LANGUAGE_TYPE); ?>/images/waiting.gif" alt="Typing Status" name="messengerStatus" width="125" height="20" id="messengerStatus"> </td> 
      </tr> 
      <tr> 
        <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CBE7F7"> 
            <tr> 
              <td width="20" height="20"><img src="../images/bottom_left.gif" width="20" height="20"></td> 
              <td rowspan="3"> <div align="center"> 
                  <textarea name="MESSAGE" cols="38" rows="2" onKeyDown="return checkEnter(event); limitInput(this, '255')" onKeyUp="limitInput(this, '255')" onBlur="notTyping()"></textarea> 
                  <input type="hidden" name="FROM_LOGIN_ID" value="<?php echo($current_login_id); ?>"> 
                  <input type="hidden" name="TO_LOGIN_ID" value="<?php echo($guest_login_id); ?>"> 
                  <a href="#" onMouseOut="swapImgRestore()" onMouseOver="swapImage('Send','','../locale/<?php echo(LANGUAGE_TYPE); ?>/images/send_hover.gif',1)" onClick="processForm();"><img src="../locale/<?php echo(LANGUAGE_TYPE); ?>/images/send.gif" alt="<?php echo($language['send_msg']); ?>" name="Send" width="42" height="42" border="0"></a>&nbsp;<a href="#" onClick="toggle('Layer1');toggle('Layer2')" ><img src="../icons/smilie_01.gif" alt="<?php echo($language['add_smilie']); ?>" width="20" height="20" border="0"></a></div></td> 
              <td width="20" height="20"><img src="../images/bottom_right.gif" width="20" height="20"></td> 
            </tr> 
            <tr> 
              <td>&nbsp;</td> 
              <td>&nbsp;</td> 
            </tr> 
            <tr> 
              <td width="20" height="20"><img src="../images/header_left.gif" width="20" height="20"></td> 
              <td width="20" height="20"><img src="../images/header_right.gif" width="20" height="20"></td> 
            </tr> 
          </table></td> 
      </tr> 
      <tr> 
        <td><div align="right"><?php echo($language['responses']); ?>:</div></td> 
        <td><select name="RESPONSE" id="RESPONSE" width="300" style="width:300px;"> 
            <?php
		$query_select_responses = "SELECT contents FROM " . $table_prefix . "responses";
		$rows = $SQL->selectall($query_select_responses);
		if (is_array($rows)) {
		?> 
            <option value=""><?php echo($language['select_response']); ?></option> 
            <?php
			foreach($rows as $row) {
				if (is_array($row)) {
		?> 
            <option value="<?php echo($row['contents']); ?>"><?php echo($row['contents']); ?></option> 
            <?php
				}
			}
		?> 
          </select> 
          <a href="#" onClick="appendResponse()"><img src="../icons/mail_send.gif" alt="<?php echo($language['append_response']); ?>" width="22" height="22" border="0"></a>&nbsp;<a href="manage_responses.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_responses']); ?>" width="22" height="22" border="0"></a> 
          <?php
		}
		else {
		?> 
          <option value=""><?php echo($language['click_add_response']); ?></option> 
          </select> 
          <a href="manage_responses.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_responses']); ?>" width="22" height="22" border="0"></a> 
          <?php
		}
		?> </td> 
      </tr> 
      <tr> 
        <td><div align="right"><?php echo($language['commands']); ?>:</div></td> 
        <td> <select name="COMMAND" id="COMMAND" width="300" style="width:300px;"> 
            <?php
		$query_select_commands = "SELECT command_id,contents,description,type FROM " . $table_prefix . "commands";
		$rows = $SQL->selectall($query_select_commands);
		if (is_array($rows)) {
		?> 
            <option value=""><?php echo($language['select_command']); ?></option> 
            <?php
			foreach($rows as $row) {
				if (is_array($row)) {
					?> 
            <option value="<?php echo($row['command_id']); ?>"><?php echo($row['type'] . " " . $row['description']); ?></option> 
            <?php
				}
			}
		?> 
          </select> 
          <a href="manage_commands.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_commands']); ?>" width="22" height="22" border="0"></a> 
          <?php
		}
		else {
		?> 
          <option value=""><?php echo($language['click_add_command']); ?></option> 
          </select> 
          <a href="manage_commands.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_commands']); ?>" width="22" height="22" border="0"></a> 
          <?php
		}
		?> </td> 
      </tr> 
      <tr> 
        <td colspan="2"><div align="center"> 
            <?php
		// query database to see if login is admin
		$query_select_admin = "SELECT user_id FROM " . $table_prefix . "users WHERE last_login_id = '$guest_login_id'";
		$row = $SQL->selectquery($query_select_admin);
		if (!(is_array($row))) {
			if ($guest_login_id != "") {
		?> 
            <table border="0" cellspacing="0" cellpadding="0"> 
              <tr> 
                <td width="30"><div align="center"><a href="transfer_user.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>" target="displayFrame"><img src="../icons/reload.gif" alt="<?php echo($language['transfer_user']); ?>" border="0"></a></div></td> 
                <td class="small"><a href="transfer_user.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>" target="displayFrame" class="normlink"><?php echo($language['transfer_user']); ?></a></td> 
                <td width="20"><div align="center" class="small">::</div></td> 
                <td width="30"><div align="center"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>" target="displayFrame"><img src="../icons/stats_small.gif" alt="<?php echo($language['visitor_stats']); ?>" border="0"></a></div></td> 
                <td class="small"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>" target="displayFrame" class="normlink"><?php echo($language['visitor_stats']); ?></a></td> 
                <td width="20"><div align="center" class="small">::</div></td> 
                <td width="30"><div align="center"><a href="print_display.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>" target="displayFrame"><img src="../icons/fileprint.gif" alt="<?php echo($language['print_chat']); ?>" width="22" height="22" border="0"></a></div></td> 
                <td class="small"><a href="print_display.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>" target="displayFrame" class="normlink"><?php echo($language['print_chat']); ?></a></td> 
                <td width="20"><div align="center" class="small">::</div></td> 
                <td width="30"><div align="center"><a href="displayer_frame.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>" target="displayFrame"><img src="../icons/chat.gif" alt="<?php echo($language['display_chat']); ?>" width="22" height="22" border="0"></a></div></td> 
                <td class="small"><a href="displayer_frame.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo(stripslashes($_GET['USER'])); ?>" target="displayFrame" class="normlink"><?php echo($language['display_chat']); ?></a></td> 
              </tr> 
            </table> 
            <?php
			}
		}
		?> 
          </div></td> 
      </tr> 
    </table> 
    <span class="small"><?php echo($language['stardevelop_copyright']); ?><br> 
    <?php echo($language['stardevelop_livehelp_version']); ?></span> 
  </form> 
  <script language="JavaScript">
<!--
document.message_form.MESSAGE.focus();

function processForm() {
  notTyping();
  void(document.message_form.submit());
  document.message_form.MESSAGE.value="";
  document.message_form.RESPONSE.value = '';
  document.message_form.COMMAND.value = '';
  document.message_form.MESSAGE.focus();
}

function appendResponse() {
  var current = document.message_form.MESSAGE.value;
  var text = document.message_form.RESPONSE.value;
  document.message_form.RESPONSE.value = '';
  document.message_form.MESSAGE.value = current + text;
  document.message_form.MESSAGE.focus();
}

function appendText(text) {
  var current = document.message_form.MESSAGE.value;
  document.message_form.MESSAGE.value = current + text;
  document.message_form.MESSAGE.focus();
}

function checkEnter(e) {
  isTyping();
  var characterCode

  if(e && e.which){
    e = e
	characterCode = e.which
  }
  else{							
    e = event						
	characterCode = e.keyCode
  } 
  
  if(characterCode == 13){ 
    processForm()
    return false 
  }
  else{
    return true 
  }
}
//-->
</script> 
</div> 
</body>
</html>
