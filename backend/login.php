<?php
require '../inc/config.php';
require_once PATH_INC . '/class_init.php';

$Auth = new Auth($dbh, $Session);
$mode = getParam('get', 'mode');

if (!$Auth->IsAdminLoggedIn()) {
    // otherwise, display login form
    switch ($mode) {
        case 'login':
            // var_dump($Auth->AdminLogin($_POST));die;
            if ($Auth->AdminLogin($_POST)) {
                redirectTo(WWW_PATH.'/backend');
            } 
            else { // login failed, display error
                $_HTML['TITLE'] = '管理者登入';
                $_HTML['error_str'] = $Auth->getErrorMsg();
                $tpl_file = '/admin/login.html.php';
            }
            break;

        case 'login_form':
        default:
            $_HTML['TITLE'] = '管理者登入';
            $_HTML['info_str'] = '您尚未登入，或連線已逾時。請輸入帳號與密碼：';
            $tpl_file = 'login.html.php';
    }
} 
else {
    // user is logged in, load her data
    $_HTML['ADMIN'] = $Auth->GetAdminData();
    
    switch ($mode) {
            //###########################################################
            // Authentication                                           #
            //###########################################################
            
        case 'logout':
            $Auth->AdminLogout();
            redirectTo(WWW_PATH.'/backend');
            break;

        default:
            $tpl_file = '/home.html.php';
    }
}
// output page content
header("Content-type: text/html; charset=" . CHARSET);

include ADMIN_PATH_TPL . "{$tpl_file}";
