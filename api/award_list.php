<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: application/json; charset=utf-8');

$limit = getParam('post', 'limit');
if(empty($limit)) {
    $limit = 10;
}else{
    $limit = intval($limit);
    if($limit<0) {
        $limit = 10;
    }
    if($limit>1000) {
        $limit = 10;
    }
}

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

try {
    $sql = 'SELECT * FROM award
            LEFT JOIN vote_log ON vote_log.vote_log_id=award.vote_log_id
            WHERE status=1
            ORDER BY updated DESC LIMIT '.$limit;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $dbErr = $stmt->errorInfo();
    if ($dbErr[0] != '00000') {
        die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
    }
    if($stmt->rowCount()>0){
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        die(json_encode(['status'=>'1', 'result'=>$data]));
    }else{
        die(json_encode(['status'=>'0', 'result'=>[]]));
    }
} catch (Exception $e) {
    die(json_encode(['status'=>'0']));
}
