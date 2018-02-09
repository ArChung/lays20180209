<?php

/***********************************************************************
 @ filename            : class_base.php
 @ author              : Ken Wang
 @ description         : base object (abstract) class
                         functions to be shared by all
 @ created             : 2005-04-07
 @ modified            : 2005-04-07
 ***********************************************************************/

class Base
{
    public $msg = '';
    public $errors = array();
    
    function __construct() {
    }
    
    function getErrorMsg() {
        $msg = (strlen($this->msg) > 0) ? '<p class="errors">' . $this->msg . '</p>' : '';
        $errors = (count($this->errors) > 0) ? '<ul class="errors"><li>' . implode('</li><li>', $this->errors) . '</li></ul>' : '';
        $html = $msg . $errors;
        return $html;
    }
    
    /***** msg *****/
    function getMsg() {
        return $this->msg;
    }
    
    function getMsgHTML() {
        return '<div class="errors"><p>' . $this->msg . '</p></div>';
    }
    
    /***** errors *****/
    function getErrorsArr() {
        return $this->errors;
    }
    
    function getErrorsHTML() {
        $html = '
        <div class="msg-error">
            ' . ($this->msg != "" ? '<p>' . $this->msg . '</p>' : '') . '
            ' . (count($this->errors) > 0 ? '<ul><li>' . implode('</li><li>', $this->errors) . '</li></ul>' : '') . '
        </div>
        ';
        return $html;
    }
    
    function getErrorsJS() {
        if (strlen($this->msg) > 0) {
            $str.= addslashes(strip_tags($this->msg)) . '\n\n';
        }
        if (count($this->errors) > 0) {
            $str.= '- ' . implode('\n- ', array_map('addslashes', $this->errors));
        }
        return $str;
    }
    
    /*
    Emails developer of the error and dies
    */
    function __reportError($msg, $file, $line) {
        $body = <<<ERROR_BODY
$msg
File: $file
Line: $line
ERROR_BODY;
        $body.= "\n\n[GET]\n" . var_export($_GET, true);
        $body.= "\n\n[POST]\n" . var_export($_POST, true);
        $body.= "\n\n[SESSION]\n" . var_export($_SESSION, true);
        $body.= "\n\n[COOKIE]\n" . var_export($_COOKIE, true);
        $body.= "\n\n[SERVER]\n" . var_export($_SERVER, true);
        
        $subject = '[' . SITE_TITLE . '] Error Report : ' . date("Y-m-d H:i:s");
        if (DEBUG === true) {
            die(nl2br($body));
        } else {
            mail(EMAIL_DEVELOPER, $subject, $body);
            die(ERROR_DIE_MSG);
        }
    }
    
    /*
    Emails the developer with environment variables
    */
    function __blackMail($msg, $file, $line) {
        $body = <<<ERROR_BODY
$msg
File: $file
Line: $line
ERROR_BODY;
        $body.= "\n\n[GET]\n" . var_export($_GET, true);
        $body.= "\n\n[POST]\n" . var_export($_POST, true);
        $body.= "\n\n[SESSION]\n" . var_export($_SESSION, true);
        $body.= "\n\n[COOKIE]\n" . var_export($_COOKIE, true);
        $body.= "\n\n[SERVER]\n" . var_export($_SERVER, true);
        
        $subject = '[' . SITE_TITLE . '] Error Report (BM) : ' . date("Y-m-d H:i:s");
        mail(EMAIL_DEVELOPER, $subject, $body);
    }
}
