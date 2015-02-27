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

error_reporting(E_ERROR | E_PARSE);
set_time_limit(0);

if (!isset($_GET['lang'])){ $_GET['lang'] = ''; }
if (!isset($_GET['empty'])){ $_GET['empty'] = ''; }

$sql_error_msgs = '';
$config_error_msgs = ''; 

if ($_GET['lang'] == '' || strlen($_GET['lang'] > 2)) {
	$lang = 'en';
}
else {
	$lang = $_GET['lang'];
}

$language_path = '../locale/' . $lang . '/lang_' . $lang . '.php';
include($language_path);

$db_hostname = $_POST['DB_HOSTNAME'];
$db_type = $_POST['DB_TYPE'];
$db_name = $_POST['DB_NAME'];
$db_username = $_POST['DB_USERNAME'];
$db_password = $_POST['DB_PASSWORD'];
$db_table_prefix = $_POST['DB_TABLE_PREFIX'];
$user_password = $_POST['USER_PASSWORD'];
$user_password_retype = $_POST['USER_PASSWORD_RETYPE'];
$offline_email = $_POST['OFFLINE_EMAIL'];
$install_domain = $_POST['INSTALL_DOMAIN'];
$install_path = $_POST['INSTALL_PATH'];
$ip2country_installed = $_POST['IP2COUNTRY_INSTALLED'];

$config_db_file = '';

// Increase memory limit
$memory = ini_set("memory_limit", "32M");
$zlib = extension_loaded("zlib"); 

// Empty the database if required
if ($_GET['empty'] == 'true') {
	$database_config_include = include('../include/config_database.php');
	if ($database_config_include == 'true') {
		include('../include/class.mysql.php');
		include('../include/config.php');
		$query_drop_table = "DROP TABLE " . $table_prefix . "commands, " . $table_prefix . "messages, " . $table_prefix . "requests, " . $table_prefix . "responses, " . $table_prefix . "sessions, " . $table_prefix . "settings, " . $table_prefix . "statistics, " . $table_prefix . "users";
		$result = $SQL->miscquery($query_drop_table);
		if ($result == true) {
			$sql_error_msgs.= $language['db_emptied_sucess'] . '<br>';
		}
		else {
			$sql_error_msgs.= $language['db_emptied_failed'] . '<br>';
		}
	}
}

// Connect to mySQL database with provided form information
$link = mysql_connect("$db_hostname", "$db_username", "$db_password") or $sql_error_msgs .= $language['db_connection_error'] . ' ' . mysql_error() . ', ' . $language['db_confirm_setup'] . '<br>';
$install_status = $language['db_connected'] . "<br>";
$selected = mysql_select_db("$db_name", $link) or $sql_error_msgs .= $language['db_connection_error'] . ' ' . mysql_error() . ', ' . $language['db_confirm_setup'] . '<br>';

if ($user_password == '') {
	$sql_error_msgs .= $language['password_error_blank'] . ', ' . $language['password_confirm_setup'] . '<br>';
}

if ($user_password != $user_password_retype) {
$sql_error_msgs .= $language['password_error_match'] . ', ' . $language['password_confirm_setup'] . "<br>";
}

if ($install_domain == '') {
	$sql_error_msgs .= $language['site_domain_blank'] . '<br>';
}

if ($install_path == '') {
	$sql_error_msgs .= $language['install_path_blank'] . '<br>';
}

if (($link && !$sql_error_msgs ) || ($selected && !$sql_error_msgs )){

$sqlfile = file('mysql.schema.txt');
$dump = "";
foreach ($sqlfile as $line) {
	if (trim($line)!='' && substr(trim($line),0,1)!='#') {
		$line = str_replace("prefix_", $db_table_prefix, $line);
		$dump .= trim($line);
	}
}

$dump = trim($dump,';');
$tables = explode(';',$dump);

foreach ($tables as $sql) {
	mysql_query($sql, $link);
}
if (mysql_error()) {
	$sql_error_msgs .= $language['db_error_schema'] . "<br>";
	$sql_error_status = 'true';
}

// Insert the settings data into the database, and alter the offline email address.
$dump = "";
$sqlfile = file('mysql.data.settings.txt');
foreach ($sqlfile as $line) {
	if (trim($line)!='' && substr(trim($line),0,1)!='#') {
		$line = str_replace("prefix_", $db_table_prefix, $line);
		$line = str_replace('/home/domains/mydomain.com/livehelp', $install_path, $line);
		$line = str_replace("'ip2country_installed', 'false'", "'ip2country_installed', '$ip2country_installed'", $line);
		$dump .= trim($line);
	}
}
unset($sqlfile);

$dump = trim($dump,';');
$tables = explode(';',$dump);

foreach ($tables as $sql) {
	mysql_query($sql, $link);
}
unset($tables);

if (mysql_error()) {
	$sql_error_msgs .= $language['db_error_settings'] . "<br>";
	$sql_error_status = 'true';
}

if ($ip2country_installed == 'true') {
	
	// Create the country table and insert the country data into the database.
	$dump = "";
	$sqlfile = file('mysql.data.countries.txt');
	foreach ($sqlfile as $line) {
		if (trim($line)!='' && substr(trim($line),0,1)!='#') {
			$line = str_replace("prefix_", $db_table_prefix, $line);
			$dump .= trim($line);
		}
	}
	unset($sqlfile);
	
	$dump = trim($dump,';');
	$tables = explode(';',$dump);
	
	foreach ($tables as $sql) {
		mysql_query($sql, $link);
	}
	unset($tables);
	
	if (mysql_error()) {
		$sql_error_msgs .= $language['db_error_settings'] . "<br>";
		$sql_error_status = 'true';
	}
	
	// Create the ip2country table and insert the geolocation data into the database.
	$dump = "";
	$sqlfile = gzfile('mysql.data.ip2country.sql.gz');
	foreach ($sqlfile as $line) {
		if (trim($line)!='' && substr(trim($line),0,1)!='#') {
			$line = str_replace("prefix_", $db_table_prefix, $line);
			$dump .= trim($line);
		}
	}
	unset($sqlfile);
	
	$dump = trim($dump,';');
	$tables = explode(';',$dump);
	
	foreach ($tables as $sql) {
		mysql_query($sql, $link);
	}
	unset($tables);
	
	if (mysql_error()) {
		$sql_error_msgs .= $language['db_error_settings'] . "<br>";
		$sql_error_status = 'true';
	}

}

$md5_user_password = md5($user_password);

$insert_initial_user = "INSERT INTO " . $db_table_prefix . "users (user_id, username, password, first_name, last_name, email, department, last_login_id, account_status, access_level) VALUES ('1', 'root', '$md5_user_password', 'Default', 'Account', '$offline_email', 'General', '0', '0', '-1')";
mysql_query($insert_initial_user, $link);
if (mysql_error()) {
	$install_status .= $language['db_error_user'] . "<br>";
}
else {
	$install_status .= $language['username_sucessfully_created'] . "<br>";
}

mysql_close($link);

$config_db_file = '../include/config_database.php';

$config_db_content = "<?php\n";
$config_db_content .= 'define("DB_HOST", \'' . $db_hostname . '\');' . "\n";
$config_db_content .= 'define("DB_NAME", \'' . $db_name . '\');' . "\n";
$config_db_content .= 'define("DB_USER", \'' . $db_username . '\');' . "\n";
$config_db_content .= 'define("DB_PASS", \'' . $db_password . '\');' . "\n";
$config_db_content .= "\n";
$config_db_content .= '$table_prefix =  \'' . $db_table_prefix . '\';' . "\n";
$config_db_content .= "\n";
$config_db_content .= '$install_status = \'true\';' . "\n";
$config_db_content .= 'return($install_status);' . "\n";
$config_db_content .= "\n";
$config_db_content .= "?>
"; } ?>
<html>
<head>
<title>Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007</title>
<style type="text/css">
<!--
div, p, td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #000000;
}
.heading {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
	color: #000000;
}
-->
</style>
</head>
<body> 
<div align="center"> 
  <p><img src="../images/help_logo_admin.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="252" height="81"> <br>
  </p> 
</div> 
<table width="75%" border="0" align="center" cellpadding="0" cellspacing="0"> 
  <tr> 
    <td> <table width="190" border="0" align="center" cellpadding="2" cellspacing="2"> 
        <tr> 
          <td width="32"><img src="../icons/setup.gif" width="32" height="32"></td> 
          <td><div align="center" class="heading"><strong><?php echo($language['installation']); ?></strong></div></td> 
        </tr> 
      </table></td> 
  </tr> 
  <tr> 
    <td> <div align="center"> 
        <p> 
          <?php

if (is_writable($config_db_file)) {
    if (!$handle = fopen($config_db_file, 'w')) {
		$config_error_msgs = $language['file_error_open'] . "<br>";
    }
    else {
    	if (!fwrite($handle, $config_db_content)) {
			$config_error_msgs =  $language['file_error_write'] . "<br>";
    	}
		else {
			$install_status .=  $language['config_created'] . "<br>";
    		fclose($handle);
		}
	}	
}
else {
	$config_error_msgs = $language['file_not_found'] . "<br>";
}
?> 
          <br> 
          <?php echo($language['install_thank_you']); ?><br> 
          <?php echo($install_status); ?><br> 
          <?php
if ($sql_error_msgs || $config_error_msgs) {
?> 
        </p> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
          <tr> 
            <td><div align="center"> 
                <table width="250" border="0"> 
                  <tr> 
                    <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['warning']); ?>" width="32" height="32"></td> 
                    <td><div align="center"> 
                        <p><?php echo($language['installation_warning']); ?></p> 
                      </div></td> 
                  </tr> 
                </table> 
              </div></td> 
          </tr> 
          <tr> 
            <td><div align="center"> 
                <?php
	echo($sql_error_msgs);
	echo($config_error_msgs);
	echo($language['consult_support']);
?> 
              </div></td> 
          </tr> 
        </table> 
      </div></td> 
  </tr> 
</table> 
<form name="install" method="POST" action="process.php?lang=<?php echo($lang); ?>&empty=<?php echo($_GET['empty']); ?>"> 
  <div align="center"> 
    <table width="400" border="0"> 
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="200" border="0" align="center"> 
              <tr> 
                <td width="32"><img src="../icons/dbase.gif" width="32" height="32"></td> 
                <td><div align="center" class="heading"><?php echo($language['database_setup']); ?></div></td> 
              </tr> 
            </table> 
          </div></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_hostname']); ?>:</strong></div></td> 
        <td> <input name="DB_HOSTNAME" type="text" id="DB_HOSTNAME" value="<?php echo($db_hostname); ?>" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_type']); ?>:</strong></div></td> 
        <td> <select name="DB_TYPE" id="select"> 
            <option value="mySQL" selected>mySQL</option> 
          </select></td> 
      </tr> 
      <tr> 
        <td width="175"><div align="right"><strong><?php echo($language['database_name']); ?>:</strong></div></td> 
        <td> <input name="DB_NAME" type="text" id="DB_NAME" value="<?php echo($db_name); ?>" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td width="175"><div align="right"><strong><?php echo($language['database_username']); ?>:</strong> </div></td> 
        <td> <input name="DB_USERNAME" type="text" id="DB_USERNAME" value="<?php echo($db_username); ?>" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_password']); ?>:</strong> </div></td> 
        <td> <input name="DB_PASSWORD" type="password" id="DB_PASSWORD" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td width="175"><div align="right"><strong><?php echo($language['database_table_prefix']); ?>:</strong> </div></td> 
        <td><p> 
            <input name="DB_TABLE_PREFIX" type="text" id="DB_TABLE_PREFIX2" value="<?php echo($db_table_prefix); ?>" style="width: 150px"> 
          </p></td> 
      </tr> 
    </table> 
    <br> 
    <table width="500" border="0"> 
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="200" border="0" align="center"> 
              <tr> 
                <td width="32"><img src="../icons/configure.gif" alt="<?php echo($language['general_setup']); ?>" width="32" height="32"></td> 
                <td><div align="center" class="heading"><?php echo($language['general_setup']); ?></div></td> 
              </tr> 
            </table> 
          </div></td> 
      </tr> 
      <tr> 
        <td colspan="2" valign="top"><div align="center"><?php echo($language['general_details']); ?>:</div></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['install_path']); ?>:</strong></div></td> 
        <td> <input name="INSTALL_PATH" type="text" id="INSTALL_PATH" value="<?php echo($install_path); ?>" style="width: 200px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['install_site_domain']); ?>:</strong></div></td> 
        <td><p> 
            <input name="INSTALL_DOMAIN" type="text" id="INSTALL_DOMAIN" value="<?php echo($install_domain); ?>" style="width: 200px"> 
            <br> 
            <span class="small">eg. http://www.mydomain.com</span> </p></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['install_geolocation_database']); ?>:</strong></div></td> 
        <td><p> 
            <input name="IP2COUNTRY_INSTALLED" type="checkbox" id="IP2COUNTRY_INSTALLED" value="true"<?php if ($memory == false && $zlib == false) { echo(' disabled="true"'); } ?>> 
            <span class="small"><?php if ($memory == false && $zlib == false) { ?>Unavailable<?php } else { ?>Minimum 2MB MySQL Database<?php } ?></span><br> 
          </p></td> 
      </tr> 
      <tr> 
        <td colspan="2"><div align="center" class="small">Warning: Installing the IP2Country geolocation database may slow the installation.  Requires Zlib Compression support and 32MB PHP memory.</div></td> 
      </tr> 
    </table> 
    <br> 
    <table width="500" border="0"> 
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="160" border="0" align="center"> 
              <tr> 
                <td width="32"><img src="../icons/users.gif" alt="<?php echo($language['user_setup']); ?>" width="32" height="32"></td> 
                <td><div align="center" class="heading"><?php echo($language['user_setup']); ?></div></td> 
              </tr> 
            </table> 
          </div></td> 
      </tr> 
      <tr> 
        <td colspan="2"><div align="center"><?php echo($language['user_details']); ?>:</div></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['root_password']); ?>:</strong></div></td> 
        <td> <input name="USER_PASSWORD" type="password" id="USER_PASSWORD" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['retype_root_password']); ?>:</strong> </div></td> 
        <td><p> 
            <input name="USER_PASSWORD_RETYPE" type="password" id="USER_PASSWORD_RETYPE" style="width: 150px"> 
          </p></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['offline_email']); ?>:</strong></div></td> 
        <td> <input name="OFFLINE_EMAIL" type="text" id="OFFLINE_EMAIL" value="<?php echo($offline_email); ?>" style="width: 150px"></td> 
      </tr> 
    </table> 
    <input type="submit" name="Submit" value="<?php echo($language['retry_install']); ?>"> 
&nbsp; 
    <input type="reset" name="Reset" value="<?php echo($language['reset']); ?>"> 
  </div> 
</form> 
<div align="center"> 
  <?php
}
else {
?> 
  <a href="/livehelp/admin/"><img src="../images/finished_install.gif" alt="<?php echo($language['installation']); ?>" width="300" height="327" border="0"></a> 
  <?php
}
?> 
</div> 
</body>
</html>
