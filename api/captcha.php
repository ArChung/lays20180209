<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';
require_once PATH_LIB.'/recaptcha/src/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($_POST['g-recaptcha-response'])) {
    $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET);
    // 確認驗證碼與 IP
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

    $var = var_export($_POST, true);

    // 確認正確
    if ($resp->isSuccess()) {
        echo json_encode(array('status'=>'1', 'success' => true));
    }else{
        die(json_encode(['status'=>'0', 'msg'=>'recaptcha error']));
    }
}else{
    die(json_encode(['status'=>'0', 'msg'=>'recaptcha error']));
}
