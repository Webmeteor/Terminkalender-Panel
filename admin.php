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
//pageAccess('WMKP');
require_once THEMES."templates/admin_header.php";
require_once INCLUDES."bbcode_include.php";
require_once INFUSIONS."kalender_panel/infusion_db.php";
if (!db_exists(DB_WMKP_KALENDER)) { redirect(BASEDIR."error.php?code=404"); }
require_once INCLUDES."infusions_include.php";

if (!defined("KALENDER")) {
    define("KALENDER", INFUSIONS."kalender_panel/");
}
if (file_exists(KALENDER."locale/".$settings['locale'].".php")) {
    require_once KALENDER."locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."locale/German.php";
}

add_to_title($locale['WMKP003']);
opentable($locale['WMKP003']);

if(isset($_GET['action']) && $_GET['action']=='delete'){
    if(isnum($_GET['id'])){
        $result=dbquery("DELETE FROM ".DB_WMKP_KALENDER." WHERE kalender_id=".$_GET['id']);
        if($result) addNotice('success', $locale['WMKP005']);
        else addNotice('warning', $locale['WMKP006']);
    }
}

$title='';
$text='';
$startdatum='';
$enddatum='';

// submit Button gedrückt?
if(isset($_POST['kalender'])){
    // prüfen ob die wichtigen Daten ausgefüllt wurden
    $title=form_sanitizer($_POST['title'], '', 'title');
    $text=form_sanitizer($_POST['text'], '', 'text');
    $startdatum=form_sanitizer($_POST['startdatum'], '', 'startdatum');
    $enddatum=form_sanitizer($_POST['enddatum'], '', 'enddatum');

    if(defender::safe()){
        if(isset($_GET['action'])&&$_GET['action']=='insert'){
            $result=dbquery("INSERT INTO ".DB_WMKP_KALENDER." (kalender_title, kalender_text, kalender_startdatum, kalender_enddatum) VALUES (:title, :text, :startdatum, :enddatum)", array(':title'=>$title, ':text'=>$text, ':startdatum'=>$startdatum, ':enddatum'=>$enddatum));
            if($result) addNotice("success", "<p>".$locale['WMKP007']."</p>\n");
            else addNotice("warning", "<p>".$locale['WMKP008']."</p>\n");
        }
        if(isset($_GET['action'])&&$_GET['action']=='update'){
            if(isset($_GET['id'])&&is_numeric($_GET['id'])){
                $result=dbquery("UPDATE ".DB_WMKP_KALENDER." SET kalender_title=:title, kalender_text=:text, kalender_startdatum=:startdatum, kalender_enddatum=:enddatum WHERE kalender_id=".$_GET['id'], array(':title'=>$title, ':text'=>$text, ':startdatum'=>$startdatum, ':enddatum'=>$enddatum));
                if($result) addNotice("success", "<p>".$locale['WMKP009']."</p>\n");
                else addNotice("warning", "<p>".$locale['WMKP010']."</p>\n");
            }else addNotice("warning", "<p>".$locale['WMKP011']."</p>\n");
        }
    }
}
if(isset($_GET['action'])&&$_GET['action']=='bearbeiten'&&isset($_GET['id'])&&is_numeric($_GET['id'])){
    opentable($locale['WMKP018']);
    $data=dbarray(dbquery("SELECT * FROM ".DB_WMKP_KALENDER." WHERE kalender_id='".$_GET['id']."'"));
    $startdate =  strtotime($data['kalender_startdatum']);
    $startdate = date("d-m-Y", $startdate);
    $enddate =  strtotime($data['kalender_enddatum']);
    $enddate = date("d-m-Y", $enddate);
    echo openform('kalender_form', 'post', KALENDER.'admin.php'.fusion_get_aidlink().'&action=update&id='.$_GET['id']);
    echo form_text('title', $locale['WMKP012'], $data['kalender_title'], array('required' => 1, 'error_text' => $locale['WMKP013'], 'max_length' => 128));
    echo form_textarea('text', $locale['WMKP014'], $data['kalender_text'], array("type" => "bbcode"));
    echo form_datepicker('startdatum', $locale['WMKP015'], $startdate, array("date_format_js" => "DD-M-YYYY", "date_format_php" => "Y-m-d", 'error_text' => $locale['WMKP016'], "type"=>"date"));
echo form_datepicker('enddatum', $locale['WMKP015a'], $enddate, array("date_format_js" => "DD-M-YYYY", "date_format_php" => "Y-m-d", 'error_text' => $locale['WMKP016a'], "type"=>"date"));
    echo form_button('kalender', $locale['WMKP017'], "save", array('class' => 'btn-primary m-t-10'));
    echo closeform();
    closetable();
}else{
    opentable($locale['WMKP019']);
    echo openform('kalender_form', 'post', KALENDER.'admin.php'.fusion_get_aidlink().'&action=insert');
    echo form_text('title', $locale['WMKP012'], $title, array('required' => 1, 'error_text' => $locale['WMKP013'], 'max_length' => 128));
    echo form_textarea('text', $locale['WMKP014'], $text, array("type" => "bbcode"));
    echo form_datepicker('startdatum', $locale['WMKP015'], $startdatum, array("date_format_js" => "DD-M-YYYY", "date_format_php" => "Y-m-d", 'error_text' => $locale['WMKP016'], "type"=>"date"));
    echo form_datepicker('enddatum', $locale['WMKP015a'], $enddatum, array("date_format_js" => "DD-M-YYYY", "date_format_php" => "Y-m-d", 'error_text' => $locale['WMKP016a'], "type"=>"date"));
    echo form_button('kalender', $locale['WMKP017'], "save", array('class' => 'btn-primary m-t-10'));
    echo closeform();
    closetable();


    opentable($locale['WMKP020']);
    $rows=dbrows(dbquery("SELECT kalender_id FROM ".DB_WMKP_KALENDER));
    $_GET['rowstart'] = (isset($_GET['rowstart']) && isnum($_GET['rowstart'])) ? $_GET['rowstart'] : 0;

    $result=dbquery("SELECT * FROM ".DB_WMKP_KALENDER." ORDER BY kalender_startdatum DESC LIMIT ".$_GET['rowstart'].", 20");
    if(dbrows($result)){
        echo "<div class='container'>\n";
        while($data = dbarray($result)) {
            $text = parse_textarea($data['kalender_text']);
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
            echo "<div class='col col-xs-4'>".parse_textarea($data['kalender_text'])."</div>\n";
            echo "<div class='col col-xs-2'><a href='".KALENDER.'admin.php'.fusion_get_aidlink()."&action=bearbeiten&id=".$data['kalender_id']."'><i class='fa fa-pencil'></i></a> <a href='".KALENDER.'admin.php'.fusion_get_aidlink()."&action=delete&id=".$data['kalender_id']."'><i class='fa fa-trash-o'></i></a></div>\n";
            echo "</div>\n";
        }
        echo "</div>\n";

    }else{
        echo $locale['WMKP004'];
    }
    if($rows>20){
        makepagenav($_GET['rowstart'], 20, $rows, 3);
    }

closetable();

}
echo "<div class='small'>Dieser Kalender wurde programmiert von <a href='http://webmeteor24.de'>Webmeteor24</a></div>\n";
require_once THEMES."templates/footer.php";