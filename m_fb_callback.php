<?php

if (!session_id()) {
    session_start();
}
require_once './inc/config.php';
require_once PATH_LIB.'/function.php';

require_once PATH_LIB."/facebook/src/Facebook/autoload.php";

$oauth_url = 'http://'.SERVER_NAME.WWW_PATH.'/m_fb_auth.php';

$fb = new \Facebook\Facebook(
    array(
        'app_id'  => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v2.8',
    )
);

$helper = $fb->getRedirectLoginHelper();
$_SESSION['FBRLH_state']=$_GET['state'];

try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    // echo 'Graph returned an error: ' . $e->getMessage();
    header('Location: '.$oauth_url);
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    // echo 'Facebook SDK returned an error: ' . $e->getMessage();
    header('Location: '.$oauth_url);
    exit;
}
try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // echo 'Graph returned an error: ' . $e->getMessage();
    header('Location: '.$oauth_url);
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // echo 'Facebook SDK returned an error: ' . $e->getMessage();
    header('Location: '.$oauth_url);
    exit;
}

$user = $response->getGraphUser();

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

try {

    $sql = "INSERT INTO fb_auth_log SET ";
    $sql .= "fb_id=:fb_id,";
    $sql .= "ip=:ip,";
    $sql .= "created=:created";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'fb_id'=>$user['id'],
        'ip'=>$_ip,
        'created'=>$_current_datetime
    ]);
    $dbErr = $stmt->errorInfo();
    if ($dbErr[0] != '00000') {
        pr($dbErr);die;
    }

} catch (Exception $e) {
    pr($e);die;
}

header('location: index.html?id='.htmlspecialchars($user['id']));
