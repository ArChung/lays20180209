<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: application/json; charset=utf-8');

$d = strtotime('2017-04-15');
if(time() > $d) {
    die(json_encode(['status'=>1]));
}else{
    die(json_encode(['status'=>0]));
}
