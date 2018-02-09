<?php

require_once '../inc/config.php';
require_once PATH_INC.'/class_init.php';

$start_time = getMicrotime();

// load core classes
$Auth = new Auth($Init->DBWeb, $Session);

// determine what to do
$mode = getParam('get','mode');
$ts = time();

if (!$Auth->IsAdminLoggedIn()) {
    if ($Input->request('ajax') == '1') {

        // ajax request - do 400
        header('HTTP/1.0 400 Bad Request');
        exit;
    }

    // otherwise, display login form
    redirectTo('/');
} else {

    switch ($mode) {
        case 'export':
            $sql = 'SELECT * FROM `vote_log`';
            $Init->DBWeb->query($sql);
            $_HTML['rows'] = array();
            $result = $Init->DBWeb->fetchAssoc();
            if (!empty($result)) {
                header("Expires: 0");
                header("Cache-control: private");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Description: File Transfer");
                header("Content-type: application/vnd.ms-excel");
                header("Content-disposition: attachment; filename=lays_" . myHSC(time()) . ".csv");
                echo "\xEF\xBB\xBF";
                $col_name = [
                    "流水號",
                    "FB_ID",
                    "姓名",
                    "電話",
                    "地址",
                    "身分證字號",
                    "日期",
                ];
                $row = '';
                foreach ($col_name as $value) {
                    $row .= '"'.$value.'",';
                }
                echo rtrim($row, ',');
                echo "\n";
                foreach ($result as $key => $row) {
                    echo '"'.$row['vote_log_id'].'",';
                    echo '"'.$row['fb_id'].'",';
                    echo '"'.$row['username'].'",';
                    echo '"'.$row['phone'].'",';
                    echo '"'.$row['address'].'",';
                    echo '"'.$row['personal_id'].'",';
                    echo '"'.$row['created'].'"';
                    echo "\n";
                }
            }

            exit;
            break;
        case 'list':
        default:
            $getFilters = $Input->get('filters');
            $getOrderby = $Input->get('orderby');
            $getSortdir = $Input->get('sortdir');
            $getPage = $Input->get('page');

            $_HTML['PAGE_TITLE'] = 'vote_log';
            $orderbys = array(
                'vote_log_id',
                'created',
            );
            $orderby = in_array($getOrderby, $orderbys) ? $getOrderby : 'vote_log_id';
            $sortdir = ($getSortdir == 'ASC') ? 'ASC' : 'DESC';
            foreach ($orderbys as $ob) {
                $_HTML["col:$ob"] = 'sortable'.($orderby == $ob ? " sortable-$sortdir" : '');
            }
            $sql_orderby = "ORDER BY $orderby $sortdir";

            $wheres = array();

            $sql = 'SELECT COUNT(*) FROM `vote_log` ';
            if (!empty($wheres)) {
                $sql .= ' WHERE '.implode(' AND ', $wheres);
            }

            // pr($sql);
            $_HTML['count'] = $count_records = $Init->DBWeb->GetVal($sql);
            if ($count_records > 0) {
                $records_per_page = 100;
                $total_pages = ceil($count_records / $records_per_page);
                $page = Validator::checkNumber($getPage, 1, $total_pages, 'int') ? intval($getPage) : 1;
                $offset = ($page - 1) * $records_per_page;
                $sql_limit = " LIMIT $records_per_page OFFSET $offset";
                $sql = 'SELECT * FROM `vote_log`';
                if (!empty($wheres)) {
                    $sql .= ' WHERE '.implode(' AND ', $wheres);
                }
                $sql .= " $sql_orderby $sql_limit";
                $Init->DBWeb->query($sql);
                $_HTML['rows'] = array();
                $result = $Init->DBWeb->fetchAssoc();
                foreach ($result as $key => $row) {
                    // $row['type_no_label'] = $_CFG['magazine.type_no_options'][$row['type_no']];
                    $_HTML['rows'][] = $row;
                }
            }
            $tpl_file = ADMIN_PATH_TPL.'/vote_log.html.php';
            break;
    }
}

// output page content
header('Content-type: text/html; charset='.CHARSET);

@include $tpl_file;
