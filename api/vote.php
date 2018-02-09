<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: application/json; charset=utf-8');

// Validation
if(empty($_POST)) {
    die(json_encode(['status'=>'0', 'msg'=>__LINE__]));
}

$fb_id = getParam('post', 'fb_id');
$vote_id = getParam('post', 'vote_id');

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

$sql = 'SELECT * FROM fb_auth_log where fb_id=:fb_id LIMIT 1';
$stmt = $dbh->prepare($sql);
$stmt->execute(['fb_id'=>$fb_id]);

if($stmt->rowCount()>0){
    // 查詢今天參加次數
    $limit = AWARD_DAILY_LIMIT;
    $sql = 'SELECT * FROM vote_log where fb_id=:fb_id AND date(created)=:current_date';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'fb_id'=>$fb_id,
        'current_date'=>date("Y-m-d")
    ]);
    if($stmt->rowCount()>$limit){
        die(json_encode(['status'=>'2', 'msg'=>'今天已經參加'.$limit.'次']));
    }

    $sql = 'INSERT INTO vote_log SET fb_id=:fb_id, vote_id=:vote_id, ip=:ip, created=:created';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'fb_id'=>$fb_id,
        'vote_id'=>$vote_id,
        'ip'=>$_ip,
        'created'=>$_current_datetime
    ]);
    $dbErr = $stmt->errorInfo();
    if ($dbErr[0] != '00000') {
        die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
    }
    $vote_log_id = $dbh->lastInsertId();

    if($vote_log_id>0) {
        $sql = 'UPDATE vote SET vote_count=vote_count+1 WHERE id=:vote_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'vote_id'=>$vote_id
        ]);
    }

    // 隨機中獎
    $rand_num = mt_rand(1, AWARD_PROBABILITY);

    $sql = 'SELECT * FROM award WHERE status=0 AND opentime<=:opentime ORDER BY rand() LIMIT 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        'opentime'=>$_current_datetime
    ]);
    if($stmt->rowCount()==0){
        die(json_encode(['status'=>'3', 'msg'=>'未中獎'.__LINE__, 'rand_num'=>$rand_num]));
    }else{
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if(!empty($data) && $rand_num<AWARD_ITEMS) {
        $keychain = uniqid(true);
        // 中獎
        $sql = 'UPDATE vote_log SET keychain=:keychain WHERE vote_log_id=:vote_log_id';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'keychain'=>$keychain,
            'vote_log_id'=>$vote_log_id
        ]);
        $sql = 'UPDATE award SET keychain=:keychain,status=1, vote_log_id=:vote_log_id, updated=:updated WHERE award_id=:award_id AND status=0';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'keychain'=>$keychain,
            'vote_log_id'=>$vote_log_id,
            'updated'=>$_current_datetime,
            'award_id'=>$data['award_id']
        ]);
        $dbErr = $stmt->errorInfo();
        if ($dbErr[0] != '00000') {
            die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
        }
        if($stmt->rowCount()>0){
            die(json_encode(['status'=>'1', 'msg'=>'中獎', 'keychain'=>$keychain]));
        }else{
            die(json_encode(['status'=>'3', 'msg'=>'未中獎'.__LINE__, 'rand_num'=>$rand_num]));
        }
    }else{
        die(json_encode(['status'=>'3', 'msg'=>'未中獎'.__LINE__, 'rand_num'=>$rand_num]));
    }

}else{
    die(json_encode(['status'=>'0', 'msg'=>__LINE__]));
}
