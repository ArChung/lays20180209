<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', 0);
define('ENV', '');

// 時間區域設定
date_default_timezone_set('Asia/Taipei');

// SERVER
define('SERVER_NAME', $_SERVER['HTTP_HOST']);

// DB
if(SERVER_NAME=='long.978.tw') {
    define('WWW_PATH', '/lays201703');
    define('DB_HOST', 'mysql');
    define('DB_NAME', 'lays201703');
    define('DB_USERNAME', 'lays201703');
    define('DB_PASSWD', 'lays201703');

    define('FACEBOOK_APP_ID', '277672759333204');
    define('FACEBOOK_APP_SECRET', '8a38a6f3dbd7af92e6a2afd0fa9aae86');

    define('AWARD_DAILY_LIMIT', 10);
    define('AWARD_PROBABILITY', 1000);
    define('AWARD_ITEMS', 1000);
}else{
    define('WWW_PATH', '');
    define('DB_HOST', '192.168.12.42');
    define('DB_NAME', 'yummylaystw_test');
    define('DB_USERNAME', 'yummylaystw');
    define('DB_PASSWD', 'A0jqI2yi');

    define('FACEBOOK_APP_ID', '272547966512350');
    define('FACEBOOK_APP_SECRET', '377c08feb8ff523eab30a85c446db21b');

    define('AWARD_DAILY_LIMIT', 10);
    define('AWARD_PROBABILITY', 1000000);
    define('AWARD_ITEMS', 1000);
}

define('PATH_ROOT', dirname(dirname(__FILE__)));
define('PATH_LIB', PATH_ROOT.'/lib');
define('PATH_TMP', PATH_ROOT.'/tmp');
define('PATH_INC', PATH_ROOT.'/inc');

define('ADMIN_PATH_TPL', PATH_ROOT.'/backend/tpl/');
define('ADMIN_USERNAME', 'lays201703');
define('ADMIN_PASSWD', 'lays201703');

require_once PATH_INC.'/class_base.php';
require_once PATH_INC.'/class_init.php';

$_CFG = [];
$_CFG['admins.nav'] = [];

define('RE_USERNAME', '!^[a-zA-Z0-9]{3,32}$!');
define('RE_PASSWORD', '!^[a-zA-Z0-9]{3,32}$!');
define('MAX_RECORDS_PER_PAGE', 10);

// recaptcha
define('RECAPTCHA_KEY', '6LdVBRcUAAAAAGimeF9TSR86QZXygS-gFTP2BMqC');
define('RECAPTCHA_SECRET', '6LdVBRcUAAAAAJb4An3VYvCSL6VdVHdSvK9ch8fq');
