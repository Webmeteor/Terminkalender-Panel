<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: kalender_panel.php
| CVS Version: 1.0.0
| Author: Dennis Vorpahl
| Email: info@webmeteor24.de
| Support: http://webmeteor24.de
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."kalender_panel/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."kalender_panel/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."kalender_panel/locale/German.php";
}

opentable($locale['003']);
$result=dbquery("SELECT * FROM ".DB_WMKP_KALENDER." WHERE kalender_enddatum>=NOW() ORDER_BY kalender_startdatum DESC LIMIT 0,10");
if(dbrows($result)){
    while($data=dbarray($result)){
        $startdate =  strtotime($data['kalender_startdatum']);
        $startdate = date("d-m-Y", $startdate);
        $enddate =  strtotime($data['kalender_enddatum']);
        $enddate = date("d-m-Y", $enddate);
        echo "<p>".$startdate
        if($startdate!=$enddate){
             echo " - ".$enddate;
         }
         echo " ".$data['kalender_title']."</p>";
    }
} else {
    echo "<p>".$locale['004']."</p>";
}
closetable();