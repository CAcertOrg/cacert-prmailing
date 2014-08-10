<?php
session_start();
include_once('../module/basic_layout.php');
include_once('../module/basic_functions.php');
include_once('../module/login_functions.php');
include_once('../module/class.db_functions.php');

$db = new db_function();


// login routine
if (isset( $_REQUEST['login'])) {
    $login = $type = $_REQUEST['login'];
    if ( $login == 'login') {
        login();
    }
    if ( $login == 'logout') {
        logout();
    }}

//test_data();

if (isset( $_REQUEST['type'])) {
    $type = $_REQUEST['type'];
}else{
    $type='';
}

if (!isset($_SESSION['user']['read_permission'])) {
    $_SESSION['user']['read_permission'] =1;
}
if (!isset($_SESSION['user']['write_permission'])) {
    $_SESSION['user']['write_permission'] =1;
}

$title = '';

switch ($type) {
    case 'userlist':
        ;$title = ' - ' . _('List of user');
        break;
    case 'user':
        ;$title = ' - ' . _('User');
        break;
    case 'view':
        ;$title = ' - ' . _('View');
        break;
    case 'viewlist':
        ;$title = ' - ' . _('View List');
        break;
    case 'lang':
        ;$title = ' - ' . _('Language definition');
        break;
    case 'langlist':
        ;$title = ' - ' . _('Language List');
        break;
    case 'country':
        ;$title = ' - ' . _('Country definition');
        break;
    case 'countrylist':
        ;$title = ' - ' . _('Country List');
        break;
    case 'media':
        ;$title = ' - ' . _('Media definition');
        break;
    case 'medialist':
        ;$title = ' - ' . _('Media List');
        break;
    default:
        $title = '';
}

echo headerstart($title);

echo titlebar();

echo menu();

echo footer();

if (array_key_exists('error', $_SESSION)) {
    echo '<div class="error">' . $_SESSION['error'] . '</div>';
}
$_SESSION['error'] = '';

$userroles = count(define_roles())-1;

//user management
if ($type == 'userlist') {
    include('../forms/userlist.php');
}

if ($type == 'user') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
        $username = array_key_exists('username',$_REQUEST) ? tidystring($_REQUEST['username']) : '';
        $email = array_key_exists('email',$_REQUEST) ? tidystring($_REQUEST['email']) : '';
        for ($i = 0; $i <= $userroles; $i++){
            $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
            $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';
            if ($readtest == 'on') {
                $read +=  pow(2, $i);
            }
            if ($writetest == 'on') {
                $write +=  pow(2, $i);
            }
        }

        //check valid data
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email == '';
        }

        if ($username == '' || $email == '') {
//missing data
        }

        if (isset( $_REQUEST['new'])){
            $db -> insert_user($username, $email, $read, $write, intval($_SESSION['user']['id']));
        } else {
            $db -> update_user($username, $email, $read, $write, intval($_SESSION['user']['id']), $cid);
        }
        include('../forms/userlist.php');
  //      http_redirect("index.php", array("type" => "userlist"), true, HTTP_REDIRECT_PERM);

        $continue=false;
    }
    if (isset( $_REQUEST['cid'])) {
         $_SESSION['user']['cid'] = $_REQUEST['cid'];
    }else{
         $_SESSION['user']['cid']=0;
    }
    if ($continue==true) {
        include('../forms/user.php');
    }
}





//view management
if ($type == 'viewlist') {
    include('../forms/viewlist.php');
}

if ($type == 'view') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $vid = array_key_exists('vid',$_REQUEST) ? intval($_REQUEST['vid']) : '';
        $view_name = array_key_exists('view_name',$_REQUEST) ? tidystring($_REQUEST['view_name']) : '';
        for ($i = 0; $i <= $userroles; $i++){
            $readtest = array_key_exists('read'.$i,$_REQUEST) ? tidystring($_REQUEST['read'.$i]) : '';
            $writetest = array_key_exists('write'.$i,$_REQUEST) ? tidystring($_REQUEST['write'.$i]) : '';
            if ($readtest == 'on') {
                $read +=  pow(2, $i);
            }
            if ($writetest == 'on') {
                $write +=  pow(2, $i);
            }
        }

        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == 'on') {
            $active = 1;
        } else {
            $active = 0;
        }

        if (isset( $_REQUEST['new'])){
            $db -> insert_view($view_name, $read, $write);
        } else {
            $db -> update_view($view_name, $read, $write, $active, $vid);
        }

        include('../forms/viewlist.php');
        $continue=false;
    }
    if ($continue==true) {
        include('../forms/view.php');
    }
}

//language  management
if ($type == 'langlist') {
    include('../forms/langlist.php');
}

if ($type == 'lang') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $lid = array_key_exists('lid',$_REQUEST) ? intval($_REQUEST['lid']) : '';
        $lang = array_key_exists('lang',$_REQUEST) ? tidystring($_REQUEST['lang']) : '';
        $langshort = array_key_exists('langshort',$_REQUEST) ? tidystring($_REQUEST['langshort']) : '';
        if (isset( $_REQUEST['new'])){
            $db -> insert_lang($lang, $langshort);
        } else {
            $db -> update_lang($lang, $langshort, $lid);
        }

        include('../forms/langlist.php');
        $continue=false;
    }
    if ($continue==true) {
        include('../forms/lang.php');
    }
}


//country  management
if ($type == 'countrylist') {
    include('../forms/countrylist.php');
}

if ($type == 'country') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
        $country = array_key_exists('country',$_REQUEST) ? tidystring($_REQUEST['country']) : '';
        $countryshort = array_key_exists('countryshort',$_REQUEST) ? tidystring($_REQUEST['countryshort']) : '';
        if (isset( $_REQUEST['new'])){
            $db -> insert_country($country, $countryshort);
        } else {
            $db -> update_country($country, $countryshort, $cid);
        }

        include('../forms/countrylist.php');
        $continue=false;
    }
    if ($continue==true) {
        include('../forms/country.php');
    }
}


//media  management
if ($type == 'medialist') {
    include('../forms/medialist.php');
}

if ($type == 'media') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $mid = array_key_exists('mid',$_REQUEST) ? intval($_REQUEST['mid']) : '';
        $media = array_key_exists('media',$_REQUEST) ? tidystring($_REQUEST['media']) : '';
        if (isset( $_REQUEST['new'])){
            $db -> insert_media($media);
        } else {
            $db -> update_media($media, $mid);
        }

        include('../forms/medialist.php');
        $continue=false;
    }
    if ($continue==true) {
        include('../forms/media.php');
    }
}
//contact  management
if ($type == 'contactlist') {
    include('../forms/contactlist.php');
}

if ($type == 'contact') {
    $continue=true;
    if (isset( $_REQUEST['new']) || isset( $_REQUEST['edit'])) {
        $read = 0;
        $write = 0;
        $cid = array_key_exists('cid',$_REQUEST) ? intval($_REQUEST['cid']) : '';
        $contactinfo = array_key_exists('contactinfo',$_REQUEST) ? tidystring($_REQUEST['contactinfo']) : '';
        $contactname = array_key_exists('contactname',$_REQUEST) ? tidystring($_REQUEST['contactname']) : '';
        $email = array_key_exists('email',$_REQUEST) ? tidystring($_REQUEST['email']) : '';
        $country_id = array_key_exists('country_id',$_REQUEST) ? intval($_REQUEST['country_id']) : 0;
        $language_id = array_key_exists('language_id',$_REQUEST) ? intval($_REQUEST['language_id']) : 0;
        $media_id = array_key_exists('media_id',$_REQUEST) ? intval($_REQUEST['media_id']) : 0;
        $comment = array_key_exists('comment',$_REQUEST) ? tidystring($_REQUEST['comment']) : '';
        $activetest = array_key_exists('active',$_REQUEST) ? tidystring($_REQUEST['active']) : '';
        if ($activetest == 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        if (checkEmailAdress($email) && !$contactinfo){
            if (isset( $_REQUEST['new'])){
                $db -> insert_contact($contactinfo, $contactname, $email, $country_id, $language_id, $media_id, $comment, $active);
            } else {
                $db -> update_contact($contactinfo, $contactname, $email, $country_id, $language_id, $media_id, $comment, $active, $cid);
            }

            include('../forms/contactlist.php');
            $continue=false;
        } else {
            echo '<div class="error">' . _('The data is not valid.') . '</div>';
        }

    }
    if ($continue==true) {
        include('../forms/contact.php');
    }
}

echo footerend();

?>