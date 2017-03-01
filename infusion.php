<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
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

$inf_folder = "kalender_panel";
if (file_exists(INFUSIONS.$inf_folder."/locale/".$settings['locale'].".php")) {
    include INFUSIONS.$inf_folder."/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS.$inf_folder."/locale/German.php";
}

include INFUSIONS.$inf_folder."/infusion_db.php";

$inf_title = $locale['WMKP001'];
$inf_description = $locale['WMKP002'];
$inf_version = "1.0.0";
$inf_developer = "Dennis Vorpahl";
$inf_email = "info@webmeteor24.de";
$inf_weburl = "http://www.webmeteor24.de";


// Delete any items not required here.
$inf_newtable[] = DB_WMKP_KALENDER." (
    			kalender_id SMALLINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    			kalender_title VARCHAR(250) NOT NULL DEFAULT '',
    			kalender_text TEXT,
    			kalender_startdatum DATETIME,
                kalender_enddatum DATETIME,
                PRIMARY KEY  (kalender_id)
                ) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci;";



$inf_droptable[] = DB_WMKP_KALENDER;

$inf_sitelink[] = array(
    "title" => $locale['WMKP003'],
    "url" => "kalender.php",
    "visibility" => "101"
);

$inf_adminpanel[] = array(
    "image" => "kalender.jpg",
    "page" => 1,
    "rights" => "WMKP",
    "title" => $locale['WMKP003'],
    "panel" => "admin.php"
);
