<?php
require '../inc/config.php';
require_once PATH_INC . '/class_init.php';

$Auth = new Auth($dbh, $Session);

if (!$Auth->IsAdminLoggedIn()) {
    header('location: '.WWW_PATH.'/backend/login.php');exit;

    switch( $mode )
    {
        case 'login':
            if( $Auth->AdminLogin($_POST) )
            { redirectTo( WWW_PATH.'/' ); }
            else
            {   # login failed, display error
                $_HTML['TITLE'] = '管理者登入';
                $_HTML['error_str'] = $Auth->getErrorMsg();
                $tpl_file = '/tpl/login.html.php';
            }
            break;

        case 'login_form':
        default:
            $_HTML['TITLE'] = '管理者登入';
            $_HTML['info_str'] = '您尚未登入，或連線已逾時。請輸入帳號與密碼：';
            $tpl_file = '/login.html.php';
    }
} else {
	# user is logged in, load her data
    $_HTML['ADMIN'] = $Auth->GetAdminData();
    $user_is_super = ($_HTML['ADMIN']['role_no'] == ROLE_ADMIN_SUPER) ? true : false;

    switch( $mode )
    {
        ############################################################
        # Authentication                                           #
        ############################################################
        case 'logout':
            $Auth->AdminLogout();
            redirectTo(WWW_PATH.'/');
            break;


        default:
            $tpl_file = '/home.html.php';
    }
}

# output page content
header( "Content-type: text/html; charset=" .CHARSET );

include ADMIN_PATH_TPL . "{$tpl_file}";
