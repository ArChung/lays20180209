<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: application/json; charset=utf-8');

// Validation
if(empty($_POST)) {
    die(json_encode(['status'=>'0']));
}

$invoice = getParam('post', 'invoice');
$invoice = explode(',', $invoice);
if(!empty($invoice)) {
    foreach ($invoice as $key => $value) {
        if(!preg_match('/^[a-zA-Z]{2}[0-9]{8}/', $value)) {
            die(json_encode(['status'=>'0', 'msg'=>'發票格式XX12345678']));
        }
    }
}else{
    die(json_encode(['status'=>'0', 'msg'=>'未填發票']));
}
$username = getParam('post', 'username');
$phone = getParam('post', 'phone');
$email = getParam('post', 'email');

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

try {
    $invoice_used = [];
    foreach ($invoice as $key => $value) {
        // query
        $sql = 'SELECT * FROM invoice WHERE invoice=:invoice AND phone=:phone LIMIT 1';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'phone'=>$phone,
            'invoice'=>$value
        ]);
        if($stmt->rowCount()>0){
            $invoice_used[] = $value;
            continue;
        }

        // insert
        $sql = "INSERT INTO invoice SET ";
        $sql .= "invoice=:invoice,";
        $sql .= "username=:username,";
        $sql .= "phone=:phone,";
        $sql .= "email=:email,";
        $sql .= "ip=:ip,";
        $sql .= "created=:created";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'invoice'=>$value,
            'username'=>$username,
            'phone'=>$phone,
            'email'=>$email,
            'ip'=>$_ip,
            'created'=>$_current_datetime
        ]);
        $dbErr = $stmt->errorInfo();
        if ($dbErr[0] != '00000') {
            // pr($dbErr);
            die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
        }
    }
    die(json_encode(['status'=>'1', 'invoice_used'=>$invoice_used]));
} catch (Exception $e) {
    die(json_encode(['status'=>'0', 'msg'=>__LINE__, 'invoice_used'=>$invoice_used]));
}
