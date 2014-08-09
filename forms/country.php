<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();


//Check access to page
$readperm = get_read_permission('country');
$writeperm = get_write_permission('country');

if (isset($_REQUEST['cid'])) {
    $cid = intval($_REQUEST['cid']);
} else {
    $cid =0;
}


if ($cid == 0) {
    //new country
    $country_id = 0;
    $countryname = '';
    $countryshort = '';
} else {
    //edit country
    $country = $db -> get_all_country($cid);
    $country_id = $country['country_id'];
    $countryname = $country['country'];
    $countryshort = $country['country_short'];
}

//refresh country



$hidden[]=array('cid',$cid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=country');
echo tableheader(_('Country definition'), 2);
echo tablerow_2col_textbox(_('country'), 'country', $countryname);
echo tablerow_2col_textbox(_('country short (2 char)'), 'countryshort', $countryshort);

echo tablefooter_user(2, $cid, $writeperm);
echo built_form_footer($hidden);
echo end_div();

?>