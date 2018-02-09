<?php

if (!session_id()) {
    session_start();
}
require_once './inc/config.php';
require_once PATH_LIB.'/function.php';

require_once PATH_LIB."/facebook/src/Facebook/autoload.php";

$oauth_callback = 'http://'.SERVER_NAME.WWW_PATH.'/fb_callback.php';

$fb = new \Facebook\Facebook(
    array(
        'app_id'  => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v2.8',
    )
);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional
$loginUrl = $helper->getLoginUrl($oauth_callback, $permissions);
header('Location: '.$loginUrl); exit;
