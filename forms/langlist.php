<?php
include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('langlist');
$writeperm = get_write_permission('langlist');


//get data
$language = $db -> get_all_lang();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Language list'), 4);
echo tablerow_langlist_header();


if (count($language) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    foreach ($language as $row) {
        echo tablerow_langlist($row);
    }
}

if ($writeperm > 0) {
    echo tablerow_langlist_new();
}
echo end_div();


?>