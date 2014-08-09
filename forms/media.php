<?php

include_once('../module/output_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();


//Check access to page
$readperm = get_read_permission('media');
$writeperm = get_write_permission('media');

if (isset($_REQUEST['mid'])) {
    $mid = intval($_REQUEST['mid']);
} else {
    $mid =0;
}


if ($mid == 0) {
    //new media
    $media_id = 0;
    $media = '';
} else {
    //edit media
    $med = $db -> get_all_media($mid);
    $media_id = $med['media_id'];
    $media = $med['media'];
}

//refresh media



$hidden[]=array('mid',$mid);

//buildform
echo start_div('content');

if ($readperm == 0) {
    echo error(_('You do not have the right to read this page.'));
    exit;
}

echo built_form_header('../www/index.php?type=media');
echo tableheader(_('Media definition'), 2);
echo tablerow_2col_textbox(_('Media'), 'media',  $media);
echo tablefooter_user(2, $mid, $writeperm);
echo built_form_footer($hidden);
echo end_div();

?>