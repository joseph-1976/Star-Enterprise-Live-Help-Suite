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
if (!isset($_GET['lang'])){ $_GET['lang'] = ''; }
if (!isset($_GET['empty'])){ $_GET['empty'] = ''; }

if ($_GET['lang'] == '' || strlen($_GET['lang'] > 2)) {
	$lang = 'en';
}
else {
	$lang = $_GET['lang'];
}

$language_path = '../locale/' . $lang . '/lang_' . $lang . '.php';
include($language_path);

// Increase memory limit
$memory = ini_set("memory_limit", "32M");
$zlib = extension_loaded("zlib");

if (isset($_SERVER['PATH_TRANSLATED'])) { $full_path = str_replace("\\\\", "/", $_SERVER["PATH_TRANSLATED"]); } else { $full_path = str_replace("\\\\", "/", $_SERVER["SCRIPT_FILENAME"]); }
$livehelp_path = '/livehelp/install/installer.php';
if (strpos($full_path, '/') === false) { $livehelp_path = str_replace("/", "\\", $livehelp_path); }
$install_path = substr($full_path, 0, strpos($full_path, $livehelp_path));

if ($_SERVER['SERVER_PORT'] == '443') {
	$server_protocol = 'https://';
}
else {
	$server_protocol = 'http://';
}
$install_domain = $server_protocol . $_SERVER['SERVER_NAME'];

?>
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
  <p><img src="../images/help_logo_admin.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="252" height="81"> </p>
  <table width="500" border="0" cellspacing="2" cellpadding="2"> 
    <tr> 
      <td> <div align="center"> 
          <table width="190" border="0" cellspacing="2" cellpadding="2"> 
            <tr> 
              <td width="32"><img src="../icons/setup.gif" width="32" height="32"></td> 
              <td><div align="center" class="heading"><strong><?php echo($language['installation']); ?></strong></div></td> 
            </tr> 
          </table> 
          <p><?php echo($language['install_welcome_detailed']); ?></p> 
          <?php
		  $config_db_file = '../include/config_database.php';
		  if (!is_writable($config_db_file)) {
		  ?> 
          <table width="400" border="0"> 
            <tr> 
              <td width="32"><img src="../icons/error.gif" alt="<?php echo($language['installation_warning']); ?>" width="32" height="32"></td> 
              <td><div align="center"> 
                  <p><span class="heading"><em><?php echo($language['installation_warning']); ?><strong></strong></em></span><em><strong><br> 
                    </strong><?php echo($language['install_db_file_warning']); ?></em></p> 
                </div></td> 
            </tr> 
          </table> 
          <?php
		  }
		  ?> 
          <p><?php echo($language['install_welcome_enjoy']); ?></p> 
        </div></td> 
    </tr> 
  </table> 
</div> 
<form name="install" method="POST" action="process.php?lang=<?php echo($lang); ?>&empty=<?php echo($_GET['empty']); ?>"> 
  <div align="center"> 
    <table width="500" border="0"> 
      <tr> 
        <td colspan="2"><div align="left"> 
            <table width="200" border="0" align="center"> 
              <tr> 
                <td width="32"><img src="../icons/dbase.gif" alt="<?php echo($language['database_setup']); ?>" width="32" height="32"></td> 
                <td><div align="center" class="heading"><?php echo($language['database_setup']); ?></div></td> 
              </tr> 
            </table> 
          </div></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_hostname']); ?>:</strong></div></td> 
        <td> <input name="DB_HOSTNAME" type="text" id="DB_HOSTNAME" value="localhost" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_type']); ?>:</strong></div></td> 
        <td> <select name="DB_TYPE" id="DB_TYPE"> 
            <option value="mySQL" selected>mySQL</option> 
          </select></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_name']); ?>:</strong></div></td> 
        <td> <input name="DB_NAME" type="text" id="DB_NAME2" value="livehelp" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_username']); ?>:</strong> </div></td> 
        <td> <input name="DB_USERNAME" type="text" id="DB_USERNAME" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_password']); ?>:</strong> </div></td> 
        <td> <input name="DB_PASSWORD" type="password" id="DB_PASSWORD2" style="width: 150px"></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['database_table_prefix']); ?>:</strong> </div></td> 
        <td><p> 
            <input name="DB_TABLE_PREFIX" type="text" id="DB_TABLE_PREFIX2" value="livehelp_" style="width: 150px"> 
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
            <input name="INSTALL_DOMAIN" type="text" id="INSTAL_DOMAIN" value="<?php echo($install_domain); ?>" style="width: 200px"> 
            <br> 
            <span class="small">eg. http://www.mydomain.com </span></p></td> 
      </tr> 
      <tr> 
        <td><div align="right"><strong><?php echo($language['install_geolocation_database']); ?>:</strong></div></td> 
        <td><p> 
            <input name="IP2COUNTRY_INSTALLED" type="checkbox" id="IP2COUNTRY_INSTALLED" value="true"<?php if ($memory == false && $zlib == false) { echo(' disabled="true"'); } ?>>
            <span class="small"><?php if ($memory == false && $zlib == false) { ?>Unavailable<?php } else { ?>Minimum 2MB MySQL Database<?php } ?></span><br> 
          </p></td> 
      </tr> 
      <tr> 
        <td colspan="2"><div align="center" class="small">Warning: Installing the IP2Country geolocation database may slow the installation. Requires Zlib Compression support and 32MB PHP memory. </div></td> 
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
        <td> <input name="OFFLINE_EMAIL" type="text" id="OFFLINE_EMAIL" style="width: 150px"></td> 
      </tr> 
    </table> 
    <input type="submit" name="Submit" value="<?php echo($language['install']); ?>"> 
&nbsp; 
    <input name="Reset" type="reset" id="Reset" value="<?php echo($language['reset']); ?>"> 
  </div> 
</form> 
</body>
</html>
