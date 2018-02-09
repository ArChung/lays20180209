<?php

require_once '../inc/config.php';
require_once PATH_LIB.'/function.php';

header('Content-Type: text/html; charset=utf-8');

$dbh = dbConnect();

$_current_datetime = date("Y-m-d H:i:s");
$_ip = getIp();

if(time() < strtotime('2017-04-10')) {
    $init_sql = file_get_contents('../inc/lay201703.sql');
    $dbh->query($init_sql);

    $sql = "INSERT INTO `vote` (`id`, `prod_name`, `vote_count`) VALUES
    (1, '青檸享清新', 0),
    (2, '椒香辛辣', 0),
    (3, '起司超濃郁', 0);";
    $dbh->query($sql);

    /*
    4/10-4/14 1box/hr, 5 days
    4/27-6/15 1box/hr, 50 days 15+31+4
    4/15-4/26 00:00~23:00 2box/hr, 23:01~24:00 3box/hr, 12 days
    */
    $start_date = '2017-04-10';
    for ($i = 1; $i <=5 ; $i++) {
        $x = $i-1;
        for ($h=0; $h < 24; $h=$h+2) {
            echo $datetime = date("Y-m-d {$h}:0:0", strtotime($start_date." +{$x} day"));
            echo PHP_EOL;
            $sql = 'INSERT INTO award SET status=0, opentime=:opentime, updated=:updated';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'opentime'=>$datetime,
                'updated'=>$_current_datetime
            ]);
        }

        $dbErr = $stmt->errorInfo();
        if ($dbErr[0] != '00000') {
            die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
        }
    }

    $start_date = '2017-04-27';
    for ($i = 1; $i <=50 ; $i++) {
        $x = $i-1;
        for ($h=0; $h < 24; $h=$h+2) {
            echo $datetime = date("Y-m-d {$h}:0:0", strtotime($start_date." +{$x} day"));
            echo PHP_EOL;
            $sql = 'INSERT INTO award SET status=0, opentime=:opentime, updated=:updated';
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                'opentime'=>$datetime,
                'updated'=>$_current_datetime
            ]);
        }
    }

    $start_date = '2017-04-15';
    for ($i = 1; $i <=12 ; $i++) {
        $x = $i-1;
        for ($h=0; $h < 23; $h++) {
            for ($s=0; $s < 1; $s++) {
                echo $datetime = date("Y-m-d {$h}:0:0", strtotime($start_date." +{$x} day"));
                echo PHP_EOL;
                $sql = 'INSERT INTO award SET status=0, opentime=:opentime, updated=:updated';
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                    'opentime'=>$datetime,
                    'updated'=>$_current_datetime
                ]);
            }
        }
        for ($h=23; $h < 24; $h++) {
            for ($s=0; $s < 2; $s++) {
                echo $datetime = date("Y-m-d {$h}:0:0", strtotime($start_date." +{$x} day"));
                echo PHP_EOL;
                $sql = 'INSERT INTO award SET status=0, opentime=:opentime, updated=:updated';
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                    'opentime'=>$datetime,
                    'updated'=>$_current_datetime
                ]);
            }
        }
    }

    for ($i = 1; $i <=40 ; $i++) {
        $x = $i-1;
        if($i<=10)
        $datetime = date('Y-m-d H:0:0');
        $sql = 'INSERT INTO award SET status=0, opentime=:opentime, updated=:updated';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            'opentime'=>$datetime,
            'updated'=>$_current_datetime
        ]);
        $dbErr = $stmt->errorInfo();
        if ($dbErr[0] != '00000') {
            die(json_encode(['status' => '30004', 'msg' => $dbErr[2].'#'.__LINE__]));
        }

    }
}
