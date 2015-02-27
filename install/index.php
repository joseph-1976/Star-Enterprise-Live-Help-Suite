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

if ($_GET['lang'] == '' || strlen($_GET['lang'] > 2)) {
	$lang = 'en';
}
else {
	$lang = $_GET['lang'];
}

$language_path = '../locale/' . $lang . '/lang_' . $lang . '.php';
include($language_path);
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
<p align="center"><img src="../images/help_logo_admin.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="252" height="81"> <br>
  <strong class="heading"><?php echo($language['installation']); ?></strong></p> 
<p align="center"><?php echo($language['install_welcome']); ?><br> 
  <?php echo($language['install_welcome_cont']); ?>:</p> 
<div align="center"> 
  <table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#CBE7F7"> 
    <tr> 
      <td><img src="../images/bottom_left.gif"></td> 
      <td>&nbsp;</td> 
      <td><img src="../images/bottom_right.gif" width="20" height="20"></td> 
    </tr> 
    <tr> 
      <td>&nbsp;</td> 
      <td><table width="100%" border="0" cellspacing="2" cellpadding="2"> 
          <tr> 
            <td><div align="right"><span class="heading">- <?php echo($language['install_docs_title']); ?></span><br> 
                <?php echo($language['install_docs_details']); ?><br> 
              </div></td> 
            <td width="80"><div align="center"><br> 
                <?php echo($language['click_here']); ?></div></td> 
          </tr> 
        </table></td> 
      <td>&nbsp;</td> 
    </tr> 
    <tr> 
      <td><img src="../images/header_left.gif" width="20" height="20"></td> 
      <td>&nbsp;</td> 
      <td><img src="../images/header_right.gif"></td> 
    </tr> 
  </table> 
  <br> 
  <table width="200" border="0" align="center" cellpadding="0" cellspacing="0"> 
    <tr> 
      <td><div align="center"><em><?php echo($language['select_language']); ?>:</em></div></td> 
    </tr> 
    <tr> 
      <td><div align="center"><a href="index.php?lang=en"><img src="../images/us.gif" alt="English (US)" width="18" height="12" border="0"></a>&nbsp;<img src="../images/de.gif" alt="German (Unavailable)" width="18" height="12" border="0">&nbsp;<img src="../images/fr.gif" alt="French - Unavailable" width="18" height="12">&nbsp;<img src="../images/it.gif" alt="Italian - Unavailable" width="18" height="12"></div></td> 
    </tr> 
  </table> 
  <p><br> 
    <?php echo($language['install_problems']); ?> </p> 
</div> 
<?php
$licensefilename = "../LICENSE.TXT";
$handle = fopen ($licensefilename, "r");
$licensecontents = fread ($handle, filesize ($licensefilename));
fclose ($handle);

?> 
<form name="install" method="POST" action="installer.php?lang=<?php echo($lang); ?>"> 
  <div align="center"> 
    <textarea name="textarea" cols="60" rows="10"><?php echo($licensecontents); ?></textarea> 
    <br> 
    <br> 
    <input type="submit" name="Submit" value="<?php echo($language['accept']); ?>"> 
&nbsp; 
    <input type="button" name="Submit" value="<?php echo($language['decline']); ?>"> 
  </div> 
</form> 
</body>
</html>
