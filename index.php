<?php
/*
Star Enterprise Live Help
Created by: Joseph Stenhouse
Conception: 2003


*/

if (!isset($_GET['REFERER'])){ $_GET['REFERER'] = ""; }
if (!isset($_GET['URL'])){ $_GET['URL'] = ""; }
if (!isset($_GET['SERVER'])){ $_GET['SERVER'] = ""; }
if (!isset($_GET['SESSION'])){ $_GET['SESSION'] = ""; }
if (!isset($_GET['TITLE'])){ $_GET['TITLE'] = ""; }
if (!isset($_GET['DEPARTMENT'])){ $_GET['DEPARTMENT'] = ""; }
if (!isset($_GET['ERROR'])){ $_GET['ERROR'] = ""; }

$database_config_include = include('./include/config_database.php');
if ($database_config_include == 'true') {
	include('./include/class.mysql.php');
	include('./include/config.php');
	$installed = 'true';
}
else {
	$installed = 'false';
	include('./include/settings_default.php');
}

$language_file = './locale/' . LANGUAGE_TYPE . '/lang_' . LANGUAGE_TYPE . '.php';
if (file_exists($language_file)) {
	include($language_file);
}
else {
	include('./locale/en/lang_en.php');
}

if ($installed == 'true') {

	$department = $_GET['DEPARTMENT'];
	$referer = $_GET['URL'];
	$title = $_GET['TITLE'];
	$error = $_GET['ERROR'];
	
	session_start();
	$current_session = session_id();
	session_write_close();

	// Update the Current URL, URL Title and Referer in the requests table.
	$current_page = $_GET['URL'];
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

	// Get the current host from the request data
	$current_host = $_GET['URL'];
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
			$current_host = substr($_GET['URL'], 0, $substr_pos + $str_start);
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
			$update_current_url_stat = "UPDATE " . $table_prefix . "requests SET current_page = '$referer', current_page_title = '$title', page_path = '$current_path;  $current_page' WHERE session_id = '$current_session'";
			$SQL->insertquery($update_current_url_stat);
		}
	}

	
	if ($disable_login_details == 'true') {
		header('Location: frames.php?URL=' . $_GET['URL'] . '&SERVER=' . $_GET['SERVER'] . '&SESSION=' . $_GET['SESSION']);
		exit();
	}
	
	// Checks if any users in user table are online
	$query_select_users_online = "SELECT login_id FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND s.username = u.username AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
	if ($department != '' && $departments == 'true') { $query_select_users_online .= " AND u.department LIKE '%$department%'"; }
	$rows_users_online = $SQL->selectall($query_select_users_online);
	if(!is_array($rows_users_online)) {
		header('Location: index_offline.php?SERVER=' . $_GET['SERVER']);
	}

}
header('Content-type: text/html; charset=' . CHARSET);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<link href="styles/styles.php" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.background {
	background-image: url(./images/livehelp_headset.gif);
	background-repeat: no-repeat;
	background-position: right bottom;
}
.header-background {
	background-image: url(./images/livehelp_chat_header_bg.gif);
	background-repeat: no-repeat;
	background-position: left top;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function disableForm() {
	document.login.Submit.disabled = true;
	return true;
}
//-->
</script>
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="background"> 
<div align="center"> 
  <table width="525" border="0" align="center" cellpadding="7" cellspacing="0" class="header-background"> 
    <tr> 
      <td><div align="left"><img src="<?php echo($_GET['SERVER'] . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0" /></div></td> 
    </tr> 
  </table> 
  <p><?php echo($language['welcome_to']); ?> <?php echo($site_name); ?>, <?php echo($language['our_live_help']); ?><br>
    <?php echo($language['enter_guest_details']); ?></p> 
  <p><?php echo($language['else_send_message']); ?> <a href="index_offline.php?SERVER=<?php echo($_GET['SERVER']); ?>" class="normlink"><?php echo($language['offline_message']); ?></a></p>
  <?php if ($error == 'email') { ?>
  <strong><?php echo($language['invalid_email_error']); ?></strong>
  </p>
  <?php } ?>
<form name="login" method="POST" onSubmit="return disableForm();" action="frames.php?SERVER=<?php echo($_GET['SERVER']); ?>&URL=<?php echo($_GET['URL']); ?>&SESSION=<?php echo($_GET['SESSION']); ?>"> 
    <table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
        <td><div align="right"><?php echo($language['name']); ?>: </div></td> 
        <td>
          <input type="text" name="USER_NAME" style="width:175px;filter:alpha(opacity=75);moz-opacity:0.75"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><?php echo($language['email']); ?>: </div></td> 
        <td>
          <input type="text" name="EMAIL" style="width:175px;filter:alpha(opacity=75);moz-opacity:0.75"></td> 
      </tr> 
      <?php
if (($departments == 'true') && ($department == '') && ($installed == 'true'))  {
?> 
      <tr> 
        <td><div align="right"><?php echo($language['department']); ?>: </div></td> 
        <td>
          <select name="DEPARTMENT" style="width:175px;filter:alpha(opacity=75);moz-opacity:0.75">
            <?php
		  	$query_select_departments = "SELECT DISTINCT u.department FROM " . $table_prefix . "sessions AS s, " . $table_prefix . "users AS u WHERE s.login_id = u.last_login_id AND (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(s.last_refresh)) < $connection_timeout AND active = '-2'";
			$rows_departments = $SQL->selectall($query_select_departments);
			
			if (is_array($rows_departments)) {
				$distinct_departments = array();
				foreach ($rows_departments as $row_department) {
					if (is_array($row_department)) {
						$department = $row_department['department'];
						$departments = split ('[;]',  $row_department['department']);
						if (is_array($departments)) {
							foreach($departments as $department) {
							$department = trim($department);
							if (!in_array($department, $distinct_departments)) {
								$distinct_departments[] = $department;
							?>
            <option value="<?php echo($department); ?>"><?php echo($department); ?></option>
            <?
							}
							}
						}
						else {
							$department = trim($department);
							if (!in_array($department, $distinct_departments)) {
								$distinct_departments[] = $department;

					?>
            <option value="<?php echo($department); ?>"><?php echo($department); ?></option>
            <?php
							}
						}
					}
				}
			}
		  ?>
          </select></td> 
      </tr> 
      <?php
}
elseif (($departments == 'true') || ($department != '')) {
?> 
      <input name="DEPARTMENT" type="hidden" value="<?php echo($department); ?>"> 
      <?php
}

?> 
    </table> 
    <p>
      <input name="Submit" type="submit" id="Submit" value="<?php echo($language['continue']); ?>">
</p> 
  </form> 
  <p class="small"><?php echo($language['stardevelop_copyright']); ?></p> 
</div> 
</body>
</html>
