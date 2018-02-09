<?php
/**
*
*/
class Init extends Base
{
    public $DBWeb;

    public $Session;

    public function __construct()
    {
        $this->DBWeb = getMasterDB(); // 153
    }
}

require_once PATH_LIB.'/function.php';
require_once PATH_INC.'/class_input.php';
require_once PATH_INC.'/class_auth.php';
require_once PATH_INC.'/class_session.php';
require_once PATH_INC.'/class_pdo.php';
require_once PATH_INC.'/class_validator.php';

$Input = new Input();
$Session = new Session();

$Init = new Init();

function &getMasterDB() {
    $DB = new PDOc(DB_HOST, DB_USERNAME, DB_PASSWD, DB_NAME);
    return $DB;
}