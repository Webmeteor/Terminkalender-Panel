<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: gastbuch.php
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
require_once "../../maincore.php";
require_once THEMES."templates/header.php";
require_once INCLUDES."bbcode_include.php";
require_once INFUSIONS."kalender_panel/infusion_db.php";
if (!db_exists(DB_WMKP_KALENDER)) { redirect(BASEDIR."error.php?code=404"); }
require_once INCLUDES."infusions_include.php";

if (file_exists(INFUSIONS."kalender_panel/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."kalender_panel/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."kalender_panel/locale/German.php";
}

add_to_title($locale['WMKP003']);
opentable($locale['WMKP003']);

$result=dbquery("SELECT * FROM ".DB_WMKP_KALENDER." WHERE kalender_enddatum >=NOW() ORDER BY kalender_startdatum LIMIT 0,10");
if(dbrows($result)){
    echo "<div class='container'>\n";
    while($data=dbarray($result)){
        $startdate =  strtotime($data['kalender_startdatum']);
        $startdate = date("d-m-Y", $startdate);
        $enddate =  strtotime($data['kalender_enddatum']);
        $enddate = date("d-m-Y", $enddate);
         echo "<div class='row'>\n";
         echo "<div class='col col-xs-2'>".$startdate;
         if($startdate!=$enddate){
             echo " - ".$enddate;
         }
         echo "</div>\n";
         echo "<div class='col col-xs-4'>".$data['kalender_title']."</div>\n";
         echo "<div class='col col-xs-6'>".parse_textarea($data['kalender_text'])."</div>\n";
         echo "</div>\n";
    }
    echo "</div>\n";
}else echo $locale['WMKP004'];

closetable();
//echo "<div class='small'>Diese Kalender Infusion wurde programmiert von <a href='http://webmeteor24.de' target='_blank'>Webmeteor24</a></div>\n";
require_once THEMES."templates/footer.php";
