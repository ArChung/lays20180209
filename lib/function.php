<?php

function dbConnect()
{ //return DB resource
    //(PHP PDO Mysql)
    $database_host = DB_HOST;
    $database_name = DB_NAME;
    $database_username = DB_USERNAME;
    $database_password = DB_PASSWD;

    $dbConnString = 'mysql:charset=utf8mb4;host='.$database_host.'; dbname='.$database_name;
    try {
        $dbh = new PDO($dbConnString, $database_username, $database_password);
    } catch (Exception $e) {
        if ($_GET['debug']) {
            error_log(h($e->getMessage()));
            die(json_encode(['status' => '30004', 'msg'=>'Can not connect to database']));
        }
    }
    if (!$dbh) {
        die(json_encode(['status' => '30004', 'msg'=>'Can not connect to database']));
        exit;
    }
    $dbh->query("SET NAMES 'utf8'");

    return $dbh;
}

//取得url 參數的值
function getParam($method, $name, $filter = FILTER_SANITIZE_SPECIAL_CHARS)
{
    $res = '';

    switch ($method) {
        case 'get':
            $res = filter_input(INPUT_GET, $name, $filter);
            if ($res == null) {
                $res = '';
            }
        break;
        case 'post':
            $res = filter_input(INPUT_POST, $name, $filter);
            if ($res == null) {
                $res = '';
            }
        break;
    }
    $res = trim($res);

    return $res;
}

function getIp()
{
    return isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
}

function pr($value = '')
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

function appInitSession() {
    if (ini_get('session.use_cookies') && isset($_COOKIE['PHPSESSID'])) {
        $sessid = $_COOKIE['PHPSESSID'];
        if ( !preg_match('/^[a-z0-9]{1,128}$/', $sessid) ) {
            session_start();
            session_regenerate_id(true);
        }else{
            session_start();
        }
    }else{
        session_start();
    }

    $cutoff = time() - 60;
    if (!isset($_SESSION['updated']) || $_SESSION['updated'] < $cutoff) {
        $_SESSION['updated'] = time();
    }
    
    // pr($_SESSION);die;
}

function redirectTo($rel_url) {
    session_write_close();
    header('Location: ' . (preg_match('!^(\/|http:)!', $rel_url) == 1 ? $rel_url : WEB_ROOT . '/' . $rel_url));
    exit;
}

function getMicrotime() {
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

/*
converts timestamp values to a variety of date formats
*/
function convertTimestamp($timestamp, $datetype = '') {
    if (empty($timestamp)) {
        return $timestamp;
    }
    // e.g. $timestamp = 20040416094302
    // get rid of non numeric chars, so "datetime" values work also!
    $timestamp = preg_replace("![^0-9]!", '', $timestamp);
    $year = substr($timestamp, 0, 4);
    $year2digit = substr($timestamp, 2, 2);
    $month = substr($timestamp, 4, 2);
    $day = substr($timestamp, 6, 2);
    $hour = substr($timestamp, 8, 2);
    $min = substr($timestamp, 10, 2);
    $sec = substr($timestamp, 12, 2);
    $unixtime = mktime($hour, $min, $sec, $month, $day, $year);
    switch ($datetype) {
        case 'short':
            // 2004-05-27
            $datetime = date("Y-m-d", $unixtime);
            break;

        case 'reverse-short':
            // 27/05/2004
            $datetime = date("d/m/Y", $unixtime);
            break;

        case 'reverse-shorter':
            // 27/05/04
            $datetime = date("d/m/y", $unixtime);
            break;

        case 'reverse-shortest':
            // 27/05 (i.e. day/month only)
            $datetime = date("d/m", $unixtime);
            break;

        case 'long':
            // Fri 27 May 2004 10:15:51 +1000
            $datetime = date("l j F Y H:i:s", $unixtime);
            break;

        case 'article':
            // Jan 12th, 2012
            $datetime = date("l jS, Y", $unixtime);
            break;

        default:
            $datetime = date("Y-m-d H:i", $unixtime);
    }
    return $datetime;
}

function convertDateStr($date_str, $format = '') {
    if (empty($date_str)) {
        return $date_str;
    }
    $t = (preg_match('!^[1-9]([0-9]{0,9})$!', $date_str) == 1) ? $date_str : strtotime($date_str);
    switch ($format) {
        case 'short':
            $d = date("Y-m-d", $t);
            break;

        case 'Y/n/j':
            $d = date("Y/n/j", $t);
            break;

        case 'reverse-short':
            $d = date("d/m/Y", $t);
            break;

        case 'human':
            $d = date("n月j日 H:i", $t);
            break;

        case 'human-auto':
            $format_str = (date("Y", $t) != date("Y")) ? "Y年n月j日 H:i" : "n月j日 H:i";
            $d = date($format_str, $t);
            break;

        default:
            $d = date("Y-m-d H:i", $t);
            break;
    }
    return $d;
}

function getPaging($page_id, $total_records, $record_limit_per_page = 20) {
    $html = '';
    $html.= '<nav>';
    $html.= '<ul class="pagination">';
    $total_pages = ceil($total_records / $record_limit_per_page);
    if ($page_id < 1 || $page_id > $total_pages) $page_id = 1;
    $html.= ($page_id > 1) ? '<li class="longBtn"><a href="#">上一頁</a></li>' : '<li class="longBtn"><span class="inactive">上一頁</span></li>';
    $bits = array();
    for ($p = 1; $p <= $total_pages; $p++) {
        // always display first & last 5 pages
        if ($p <= 3 || $p > ($total_pages - 3) || ($p > ($page_id - 3) && $p < ($page_id + 3))) {
            $bits[] = ($page_id == $p) ? '<li class="active"><span>' . $p . '</span></li>' : '<li><a href="#">' . $p . '</a></li>';
        }
        elseif ($bits[(count($bits) - 1) ] != '<li><span>⋯</span></li>') {
            $bits[] = '<li><span>⋯</span></li>';
        }
    }
    $html.= implode(' ', $bits);
    $html.= ($page_id < $total_pages) ? '<li class="longBtn"><a href="#">下一頁</a></li>' : ' <li class="longBtn"><span class="inactive">下一頁</span></li>';
    $html.= '</ul>';
    $html.= '</nav>';
    return $html;
}


function h($str)
{
    return htmlspecialchars($str);
}

/*
public string
applies htmlentities
*/
function myEscape($str) {
    return (is_array($str) ? array_map('myEscape', $str) : htmlentities($str, ENT_QUOTES, 'UTF-8'));
}
/*
public string
strip slashes if magic_quotes_gpc is on
*/
function myUnescape($str) {
    return (get_magic_quotes_gpc() ? (is_array($str) ? array_map('myUnescape', $str) : stripslashes(trim($str))) : (is_array($str) ? array_map('myUnescape', $str) : trim($str)));
}
/*
returns htmlspecialchars-ed string
a wrapper function for htmlspecialchars as CHARSET is configurable
*/
function myHSC($str) {
    if (!is_string($str) || empty($str)) return $str;
    // return htmlspecialchars( $str, ENT_COMPAT, CHARSET );
    return preg_replace("!&amp;(copy;|reg;|trade;|quot;|gt;|lt;|[a-z]{2,6};|#1[0-9]{2};|#[0-9]{4,5};)!", "&\\1", htmlspecialchars($str, ENT_COMPAT, CHARSET));
}