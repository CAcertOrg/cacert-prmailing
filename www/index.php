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


echo footerend();

?>