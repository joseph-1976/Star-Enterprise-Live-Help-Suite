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
?>
<!-- Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 International Copyright - All Rights Reserved //-->
<!--  BEGIN Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 Messenger Code - Copyright - NOT PERMITTED TO MODIFY IMAGE MAP/CODE/LINKS //-->
  <area shape="rect" coords="150,146,229,169" href="#" onClick="declineInitiateChat();" alt="Decline">
  <area shape="rect" coords="231,37,269,55" href="#" onClick="declineInitiateChat();" alt="Close">
</map>
<div id="floatLayer" style="position:absolute; left:10; top:10; visibility:hidden; z-index:5000;">
  <div align="center"><img src="<?php echo($site_address); ?>/livehelp/locale/en/images/initiate_dialog.gif" alt="Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007" width="377" height="238" border="0" usemap="#LiveHelpInitiateChatMap"></div>
</div>
<iframe name="initiateChatResponse" id="initiateChatResponse" src="<?php echo($site_address); ?>/livehelp/admin/blank.php" frameborder="0" border="0" width="1" height="1" style="visibility: hidden"></iframe>
<div id="LiveHelpInfo" style="position:absolute; left:10; top:10; z-index:4995; background-image:url(<?php echo($site_address); ?>/livehelp/images/livehelp_info_bg_bottom.gif); visibility:hidden;"><img src="<?php echo($site_address); ?>/livehelp/locale/en/images/livehelp_info.gif" id="LiveHelpInfoContent" name="LiveHelpInfoContent" border="0" usemap="#LiveHelpInfoMap" onMouseOver="cancelClosingInfo();" onMouseOut="closeInfo();"></div>
<a href="<?php echo($site_address); ?>/livehelp/index_offline.php" target="_blank" onClick="openLiveHelp(); closeInfo(); return false"><img src="<?php echo($site_address); ?>/livehelp/include/status_image.php" id="LiveHelpStatus" name="LiveHelpStatus" border="0" onMouseOver="openInfo(this, event);" onMouseOut="closeInfo();"></a>
<script language="JavaScript" type="text/JavaScript" src="<?php echo($site_address); ?>/livehelp/include/online_tracker.php"></script>
<!--  END Star Enterprise Live Help Created by: Joseph Stenhouse (Involution Media) 2007 Messenger Code - Copyright - NOT PERMITTED TO MODIFY IMAGE MAP/CODE/LINKS //-->