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

$spider_browser = false;

$spiderAgent[0] = "Googlebot";
$spiderAgent[1] = "Slurp";
$spiderAgent[2] = "Scooter";
$spiderAgent[3] = "Openbot";
$spiderAgent[4] = "Mercator";
$spiderAgent[5] = "AltaVista";
$spiderAgent[6] = "AnzwersCrawl";
$spiderAgent[7] = "FAST-WebCrawler";
$spiderAgent[8] = "Gulliver";
$spiderAgent[9] = "WISEnut";
$spiderAgent[10] = "InfoSeek";
$spiderAgent[11] = "Lycos_Spider";
$spiderAgent[12] = "HenrytheMiragoRobot";
$spiderAgent[13] = "IncyWincy";
$spiderAgent[14] = "MantraAgent";
$spiderAgent[15] = "MegaSheep";
$spiderAgent[16] = "Robozilla";
$spiderAgent[17] = "Scrubby";
$spiderAgent[18] = "Speedy_Spider";
$spiderAgent[19] = "Sqworm";
$spiderAgent[20] = "teoma";
$spiderAgent[21] = "Ultraseek";
$spiderAgent[22] = "whatUseek";
$spiderAgent[23] = "Jeeves";
$spiderAgent[24] = "AllTheWeb";
$spiderAgent[25] = "Google";
$spiderAgent[26] = "ia_archiver";
$spiderAgent[27] = "grub-client";
$spiderAgent[28] = "ZyBorg";
$spiderAgent[29] = "Atomz";
$spiderAgent[30] = "ArchitextSpider";
$spiderAgent[31] = "Arachnoidea";
$spiderAgent[32] = "UltraSeek";
$spiderAgent[33] = "MSNBOT";
$spiderAgent[34] = "YahooSeeker";

foreach($spiderAgent as $spider) {
	if (strpos($_SERVER['HTTP_USER_AGENT'], $spider) !== false) {
		$spider_browser = true;
		break;
	}
}

if ($spider_browser == true) {
	header("HTTP/1.0 404 Not Found"); 
}
?>