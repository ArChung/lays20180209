<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: application/json; charset=utf-8');

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

try {
    $sql = 'SELECT * FROM vote LIMIT 3';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $dbErr = $stmt->errorInfo();
    if ($dbErr[0] != '00000') {
        die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
    }
    if($stmt->rowCount()>0){
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        die(json_encode(['status'=>'1', 'result'=>$data]));
    }
} catch (Exception $e) {
    die(json_encode(['status'=>'0']));
}
