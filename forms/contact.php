<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();


//Check access to page
$readperm = get_read_permission('contact');
$writeperm = get_write_permission('contact');

if (isset($_REQUEST['cid'])) {
    $cid = intval($_REQUEST['cid']);
} else {
    $cid =0;
}


if ($cid == 0) {
    //new contact
    $contactinfo = '';
    $contactname = '';
    $email = '';
    $countryid = 0;
    $languageid = 0;
    $mediaid = 0;
    $comment = '';
    $active = 1;
} else {
    //edit contact
    $contact = $db -> get_all_contact(" AND `c`.`contact_id` = $cid");
    $contactinfo = $contact[0]['contactinfo'];
    $contactname = $contact[0]['contactname'];
    $email = $contact[0]['email'];
    $countryid = $contact[0]['countryid'];
    $languageid = $contact[0]['languageid'];
    $mediaid = $contact[0]['mediaid'];
    $comment = $contact[0]['comment'];
    $active = $contact[0]['active'];
}


//refresh user

$country = $db -> get_all_country();
$language = $db -> get_all_lang();
$media = $db -> get_all_media();


$hidden[]=array('cid',$cid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=contact');
echo tableheader(_('Contact definition'), 2);
echo tablerow_2col_textbox(_('Contact info'), 'contactinfo', $contactinfo);
echo tablerow_2col_textbox(_('Contact name'), 'contactname', $contactname);
echo tablerow_2col_textbox(_('Email'), 'email', $email);
echo tablerow_2col_dropbox(_('County'), $country, $countryid, 'country_id', 'country');
echo tablerow_2col_dropbox(_('Language'), $language, $languageid, 'language_id', 'language');
echo tablerow_2col_dropbox(_('Media'), $media, $mediaid, 'media_id', 'media');
echo tablerow_2col_textbox(_('Comment'), 'comment', $comment);
echo tablerow_2col_checkbox(_('Active'), 'active', $active);
echo tablefooter_user(2, $cid, $writeperm);
echo built_form_footer($hidden);
echo end_div();

?>