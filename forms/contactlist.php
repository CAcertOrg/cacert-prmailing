<?php
include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('contactlist');
$writeperm = get_write_permission('contactlist');

$filter = '';
if (isset($_REQUEST['country_id'])) {
    $countryid = intval($_REQUEST['country_id']);
    if ($countryid != 0) {
        $filter = " AND  `c`.`country_id` = $countryid";
    }
} else {
    $countryid = 0 ;
}
if (isset($_REQUEST['language_id'])) {
    $languageid = intval($_REQUEST['language_id']);
    if ($languageid != 0) {
        $filter .= " AND `c`.`language_id` = $languageid";
    }
} else {
    $languageid = 0 ;
}
if (isset($_REQUEST['media_id'])) {
    $mediaid = intval($_REQUEST['media_id']);
    if ($mediaid != 0) {
        $filter .= " AND `c`.`media_id`  = $mediaid";
    }
} else {
    $mediaid = 0 ;
}


$cid =0 ;
$hidden[]=array('cid',$cid);

//get data
$contacts = $db -> get_all_contact($filter);
$country = $db -> get_all_country();
$language = $db -> get_all_lang();
$media = $db -> get_all_media();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

// build filter form
echo built_form_header('../www/index.php?type=contactlist');
echo tableheader(_('Filter'), 2);
echo tablerow_2col_dropbox(_('Country'), $country, $countryid, 'country_id', 'country', 1);
echo tablerow_2col_dropbox(_('Language'), $language, $languageid, 'language_id', 'language', 1);
echo tablerow_2col_dropbox(_('Media'), $media, $mediaid, 'media_id', 'media', 1);

echo tablefooter_filter(2, _('Apply'));
echo built_form_footer($hidden);
echo empty_line();

// data table
echo tableheader(_('Contact list'), 8);
echo tablerow_contactlist_header();


if (count($contacts) <= 0 ) {
    echo tablerow_no_entry(8);
} else {
    foreach ($contacts as $row) {
        echo tablerow_contactlist($row);
    }
}

if ($writeperm > 0) {
    echo tablerow_contactlist_new();
}
echo end_div();


?>