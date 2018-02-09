<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: application/json; charset=utf-8');

// Validation
if(empty($_POST)) {
    die(json_encode(['status'=>'0', 'msg'=>__LINE__]));
}

$fb_id = getParam('post', 'fb_id');
$username = getParam('post', 'username');
$phone = getParam('post', 'phone');
$address = getParam('post', 'address');
$email = getParam('post', 'email');
$vote_id = getParam('post', 'vote_id');
$keychain = getParam('post', 'keychain');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   die(json_encode(['status'=>'0', 'msg'=>__LINE__]));
}

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

$sql = 'SELECT * FROM award where keychain=:keychain LIMIT 1';
$stmt = $dbh->prepare($sql);
$stmt->execute(['keychain'=>$keychain]);

if($stmt->rowCount()>0) {
    $sql = 'UPDATE vote_log SET
    fb_id=:fb_id, username=:username, phone=:phone, address=:address, email=:email
    WHERE keychain=:keychain AND username IS NULL';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'fb_id'=>$fb_id,
        'username'=>$username,
        'phone'=>$phone,
        'address'=>$address,
        'email'=>$email,
        'keychain'=>$keychain
    ]);
    $dbErr = $stmt->errorInfo();
    if ($dbErr[0] != '00000') {
        die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
    }
    if($stmt->rowCount()>0){
        die(json_encode(['status'=>'1', 'msg'=>'']));
    }else{
        die(json_encode(['status'=>'0', 'msg'=>__LINE__]));
    }
}else{
    die(json_encode(['status'=>'0', 'msg'=>'keychain not exists']));
}
