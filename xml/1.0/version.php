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

if (!isset($_POST['WEB'])){ $_POST['WEB'] = ""; }
if (!isset($_POST['WINDOWS'])){ $_POST['WINDOW'] = ""; }

$current_web_version = $_REQUEST['WEB'];
$current_windows_version = $_REQUEST['WINDOWS'];
$available_web_version = '1.61';
$available_windows_version = '2.26';

if ($current_web_version == $available_web_version) { $web_version_result = 'true'; } else { $web_version_result = 'false'; }
if ($current_windows_version == $available_windows_version) { $windows_version_result = 'true'; } else { $windows_version_result = 'false'; }

header('Content-type: text/xml; charset=utf-8');
echo('<?xml version="1.0" encoding="utf-8"?>' . "\n");
?>
<Version xmlns="urn:LiveHelp" Web="<?php echo($web_version_result); ?>" Windows="<?php echo($windows_version_result); ?>"/>
