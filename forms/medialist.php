<?php
include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();

//Check access to page
$readperm = get_read_permission('medialist');
$writeperm = get_write_permission('medialist');


//get data
$media = $db -> get_all_media();


echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo tableheader(_('Media list'), 4);
echo tablerow_medialist_header();


if (count($media) <= 0 ) {
    echo tablerow_no_entry(2);
} else {
    foreach ($media as $row) {
        echo tablerow_medialist($row);
    }
}

if ($writeperm > 0) {
    echo tablerow_medialist_new();
}
echo end_div();


?>