<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

$roles = define_roles();

//Check access to page
$readperm = get_read_permission('lang');
$writeperm = get_write_permission('lang');

if (isset($_REQUEST['lid'])) {
    $lid = intval($_REQUEST['lid']);
} else {
    $lid =0;
}


if ($lid == 0) {
    //new language
    $lang_id = 0;
    $lang = '';
    $langshort = '';
} else {
    //edit language
    $language = $db -> get_all_lang($lid);
    $lang_id = $language['language_id'];
    $lang = $language['language'];
    $langshort = $language['language_short'];
}

//refresh user



$hidden[]=array('lid',$lid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=lang');
echo tableheader(_('Language definition'), 2);
echo tablerow_2col_textbox(_('Language'), 'lang',  $lang);
echo tablerow_2col_textbox(_('Language short (2 char)'), 'langshort', $langshort);

echo tablefooter_user(2, $lid, $writeperm);
echo built_form_footer($hidden);
echo end_div();

?>