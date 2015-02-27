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

if (!isset($_POST['REMEMBER_LOGIN'])){ $_POST['REMEMBER_LOGIN'] = ""; }
if (!isset($_POST['WIN32_CLIENT'])){ $_POST['WIN32_CLIENT'] = ""; }
if (!isset($_POST['REDIRECT'])){ $_POST['REDIRECT'] = ""; }

$username = $_POST['USER_NAME'];
$password = $_POST['PASSWORD'];
$server = $_POST['SERVER'];
$remember_login = $_POST['REMEMBER_LOGIN'];


$ip_address = $_SERVER['REMOTE_ADDR'];


// Find if the User-Agent HTTP Header is the Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 Win32 Client
if ($_POST['WIN32_CLIENT'] == 'true') {
	$current_win32_client = 'true';
}
else {
	$current_win32_client = 'false';
}

$md5_password = md5($password);

// Get username, password and email from database and authorise the login details
$query_select = "SELECT username,email,account_status FROM " . $table_prefix . "users WHERE username = '$username' AND password = '$md5_password'";
$row = $SQL->selectquery($query_select);
if (!is_array($row)) {
	if ($current_win32_client == 'true') {
		header('HTTP/1.0 403 Forbidden');
		exit;
	}
	elseif ($current_win32_client == 'false')  {
		header("Location: ./index_popup.php?STATUS=error");
		exit;
	}
}

else {
	if($row['account_status'] == 1) {
		if ($current_win32_client == 'true') {
			header('HTTP/1.0 403 Forbidden');
			exit;

		}
		elseif ($current_win32_client == 'false')  {
			header("Location: ./index_popup.php?STATUS=disabled");
			exit;
		}
	}
}

$email = $row['email'];

session_start();
$current_session = session_id();
$_SESSION['WIN32_CLIENT'] = $current_win32_client;


// Set cookie if not already set to remember the username and password if user requested to remember login
if ($remember_login == "true") {
	$domain = '.' . $_SERVER['HTTP_HOST'];
	setcookie('LiveHelpLogin[username]', $username, time() + 7776000, '/', $domain, 0);
	setcookie('LiveHelpLogin[password]', $password, time() + 7776000, '/', $domain, 0);
}

// Add supporter session to database mark active field to set a support from displaying in pending
$query_sessions = "INSERT INTO " . $table_prefix . "sessions (login_id,session_id,username,datetime,email,ip_address,server,support_department,rating,active) VALUES ('','$current_session','$username',NOW(),'$email','$ip_address','$server','',-1,'-2')";
$result = $SQL->insertquery($query_sessions);

// Find login id for relative session and set as session variable
$query_select = "SELECT login_id FROM " . $table_prefix . "sessions WHERE session_id = '$current_session' AND username='$username' ORDER BY datetime DESC LIMIT 1";
$row = $SQL->selectquery($query_select);
if (is_array($row)) {
	$login_id = $row['login_id'];
	$_SESSION['OPERATOR_LOGIN_ID'] = $login_id;
	
	// Update admin user table with new last_login_id
	$query_update_user_login = "UPDATE " . $table_prefix . "users SET last_login_id = '$login_id' WHERE username = '$username' AND password = '$md5_password'";
	$SQL->miscquery($query_update_user_login);
}
session_write_close();

if ($_POST['REDIRECT'] == 'true') {
	header("Location: ./visitors_index.php?" . SID);
}

header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>Admin <?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset cols="225,*,90" frameborder="NO" border="0" framespacing="0">
<?php
if ($current_win32_client == 'true') {
?>
  <frameset rows="80,115,*" frameborder="NO" border="0" framespacing="0">
    <frame src="users_header.php?<?php echo(SID); ?>" name="usersHeaderFrame" scrolling="NO">
    <frame src="status_controls.php?<?php echo(SID); ?>" name="statusControlsFrame" scrolling="NO">
      <frameset cols="30,*" frameborder="NO" border="0" framespacing="0">
        <frame src="users_messenger.php?<?php echo(SID); ?>" name="usersMessengerFrame" scrolling="NO">
        <frame src="users.php?<?php echo(SID); ?>" name="usersFrame">
      </frameset>
  </frameset>
<?php
}
elseif ($current_win32_client == 'false') {
?>
  <frameset rows="80,115,40,2,*" frameborder="NO" border="0" framespacing="0">
    <frame src="users_header.php?<?php echo(SID); ?>" name="usersHeaderFrame" scrolling="NO">
    <frame src="status_controls.php?<?php echo(SID); ?>" name="statusControlsFrame" scrolling="NO">
    <frame src="sound_controls.php?<?php echo(SID); ?>" name="soundControlsFrame" scrolling="NO">
    <frame src="users_refresher.php?<?php echo(SID); ?>" name="usersRefresherFrame" scrolling="NO">
      <frameset cols="30,*" frameborder="NO" border="0" framespacing="0">
        <frame src="users_messenger.php?<?php echo(SID); ?>" name="usersMessengerFrame" scrolling="NO">
        <frame src="users.php?<?php echo(SID); ?>" name="usersFrame">
      </frameset>
  </frameset>
<?php
}
?>
<frameset rows="*,225,2" frameborder="NO" border="0" framespacing="0">
  <frame src="visitors_index.php?<?php echo(SID); ?>" name="displayFrame">
  <frame src="messenger.php?<?php echo(SID); ?>" name="messengerFrame" scrolling="NO">
  <frame src="blank.php?<?php echo(SID); ?>" name="sendMessageFrame" scrolling="NO">
</frameset>
  <frame src="control_panel.php?<?php echo(SID); ?>" name="menuFrame">
</frameset><noframes></noframes>
</frameset>
<body>
</body>
</html>