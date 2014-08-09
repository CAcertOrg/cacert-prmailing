<?php
include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('countrylist');
$writeperm = get_write_permission('countrylist');


//get data
$country = $db -> get_all_country();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Country list'), 4);
echo tablerow_countrylist_header();


if (count($country) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    foreach ($country as $row) {
        echo tablerow_countrylist($row);
    }
}

if ($writeperm > 0) {
    echo tablerow_countrylist_new();
}
echo end_div();


?>