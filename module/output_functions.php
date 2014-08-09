<?php

function built_form_header($action){
    return '<form method="post" action="'.$action.'">' . "\n";
}

function built_form_footer($hidden){
    $tabstring = '';
    foreach ($hidden as $hid) {
        $tabstring .= '<input type="hidden" name="'.$hid[0].'" value="'.$hid[1] .'" />';
    }
    $tabstring .= '<form>' . "\n";
    return $tabstring;

}


function tablefooter_filter($cols, $label){
        $tabstring = '<tr>' . "\n";
        $tabstring .=    '<td class="DataTD" colspan="' . $cols . '"><input type="submit" name="filter" value="' . $label . '"</td>' . "\n";
        $tabstring .= '</tr>' . "\n";
        $tabstring .= '</table>' . "\n";
        return $tabstring;
}

/**
 * builddropdown()
 *
 * @param mixed $result     result of query
 * @param mixed $value      given value of the dropdown
 * @param mixed $valuecol   columnname of the query column that holds the value to be refferd to
 * @param mixed $displaycol columnname of the query column that holds the value to be displaed
 * @param integer $all      option to define if the default choice all should be given 0 - not dispaled, 1- displayed
 * @return
 */
function builddropdown($result, $value, $valuecol, $displaycol, $all=0){
    $tabstring = '<select name="' . $valuecol . '">' . "\n";
    if ($all >0) {
        $tabstring .= sprintf('<option value="%d"%s>%s</option>',0, 0 == $value ? " selected" : "" ,_("All")) . "\n";
    }
    if(count($result) >= 1){
        foreach($result as $row){
             $tabstring .= sprintf('<option value="%d"%s>%s</option>',$row[$valuecol], $row[$valuecol] == $value ? " selected" : "" , $row[$displaycol]) . "\n";
        }
    }
    $tabstring .= '</select>' . "\n";
    return $tabstring;
}


function error($output){
    $tabstring = _('Error: ') . $output;
    return $tabstring;
}

function empty_line(){
    return '<br/>' . "\n";
}

function tableheader($title,$cols){
    $tabstring = '<table align="center" valign="middle" border="0" cellspacing="0" cellpadding="0" class="wrapper">' . "\n";
    $tabstring .= '<tr>' . "\n";
    $tabstring .=   '<td colspan="'.$cols.'" class="title">'.$title.'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;

}

function tablerow_2col_textbox($label, $name, $value){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input name="'.$name.'" type="text" value="'.$value.'" /></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablecell($value, $cols=0){
    $colspan = '';
    if ($cols > 0) {
        $colspan = ' colspan="' . $cols . '" ';
    }
    $tabstring =   '<td class="DataTD" ' . $colspan . '>' . $value . '</td>' . "\n";
    return $tabstring;
}
function tablerow_start(){
    $tabstring = '<tr>' . "\n";
    return $tabstring;
}
function tablerow_end(){
    $tabstring = '</tr>' . "\n";
    return $tabstring;
}
function table_end(){
    $tabstring = '</table>' . "\n";
    return $tabstring;
}

function tablerow_label($label, $col){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="'.$col.'">'.$label.'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_3col_textbox_2col($label, $name, $value){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="2"><input name="'.$name.'" type="text" value="'.$value.'" /></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_2col_dropbox($label, $result, $value, $valuecol, $displaycol, $all=0){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$label.'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . builddropdown($result, $value, $valuecol, $displaycol, $all) . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_user_rights($roles, $read, $write){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"></td>' . "\n";
    $tabstring .=   '<td class="DataTD">Read</td>' . "\n";
    $tabstring .=   '<td class="DataTD">Write</td>' . "\n";
    $i = 0;
    foreach ($roles as $role) {
        if ((pow(2, $i) & $read) == pow(2, $i)) {
            $readpos = 'checked';
        }else{
            $readpos = '';
        }

        if ((pow(2, $i) & $write) == pow(2, $i)) {
            $writepos = 'checked';
        }else{
            $writepos = '';
        }

        $tabstring .= '</tr>' . "\n";
        $tabstring .=   '<td class="DataTD">'.$role.'</td>' . "\n";
        $tabstring .=   '<td class="DataTD"><input type="checkbox" name="read'.$i.'" '.$readpos.'/></td>' . "\n";
        $tabstring .=   '<td class="DataTD"><input type="checkbox" name="write'.$i.'" '.$writepos.'/></td>' . "\n";
        $tabstring .= '</tr>' . "\n";

        $i +=1;
    }

    return $tabstring;

}


function tablerow_2col_checkbox($label, $name, $value){
    if ($value == 1) {
        $checked = 'checked';
    }else{
        $checked = '';
    }
    $tabstring = '</tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . $label . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input type="checkbox" name="' .$name .'" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablefooter_user($cols, $uid, $write=0){
    if ($uid == 0 ) {
        $label = 'New entry';
        $name = 'new';
    }else{
        $label = 'Save entry';
        $name = 'edit';
    }
    if ($write !=0) {
        $tabstring = '<tr>' . "\n";
        $tabstring .=    '<td class="DataTD" colspan="'.$cols.'"><input type="submit" name="'.$name.'" value="'.$label.'"</td>' . "\n";
        $tabstring .= '</tr>' . "\n";
        $tabstring .= '</table>' . "\n";
        return $tabstring;
    } else {
        return '';
    }

}

function tablerow_userlist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('User') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Read permission') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Write permission') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_userlist($user){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../www/index.php?type=user&cid='.$user['user_id'].'">'.$user['user_name'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$user['read_permission'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$user['write_permission'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_userlist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="3"><a href="../www/index.php?type=user&cid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_no_entry($cols){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="'.$cols.'">No entry available</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}

function tablerow_topics_active($active){
    if ($active == 1) {
        $checked = 'checked';
    }else{
        $checked = '';
    }
    $tabstring = '</tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD"><input type="checkbox" name="active" '.$checked.'/></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;

}

function start_div($class=''){
    if ($class != '') {
        $class = 'class="'.$class.'"';
    }
    $tabstring = '<div '.$class.'>' . "\n";
    return $tabstring;
}

function end_div(){
    $tabstring = '</div>' . "\n";
    return $tabstring;
}


// view managemnt
function tablerow_viewlist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('View') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Read Permission') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Write Permission') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Active') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_viewlist($view){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../www/index.php?type=view&vid='.$view['view_id'].'">'.$view['view_name'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$view['read_permission'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$view['write_permission'].'</td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$view['active'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_viewlist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="4"><a href="../www/index.php?type=view&vid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


// language managemnt
function tablerow_langlist_header(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Language') . '</td>' . "\n";
    $tabstring .=   '<td class="DataTD">' . _('Language short') . '</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_langlist($lang){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD"><a href="../www/index.php?type=lang&lid='.$lang['language_id'].'">'.$lang['language'].'</a></td>' . "\n";
    $tabstring .=   '<td class="DataTD">'.$lang['language_short'].'</td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}


function tablerow_langlist_new(){
    $tabstring = '<tr>' . "\n";
    $tabstring .=   '<td class="DataTD" colspan="2"><a href="../www/index.php?type=lang&lid=0">New entry</a></td>' . "\n";
    $tabstring .= '</tr>' . "\n";
    return $tabstring;
}



?>