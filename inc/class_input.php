<?php
/***********************************************************************
 @ filename            : class_input.php
 @ author              : Ken Wang
 @ description         : input object (wrapper)
                         functions can be called statically
 @ created             : 2007-11-12
 @ modified            : 2007-11-12
 ***********************************************************************/

class Input 
{
    function get( $key, $default=null)
    {
        if(empty($_GET[$key])) {
            return $default;
        }
        return isset($_GET[$key]) ? myUnescape($_GET[$key]) : false;
    }
    function post( $key, $default=null)
    {
        if(empty($_POST[$key])) {
            return $default;
        }
        return isset($_POST[$key]) ? myUnescape($_POST[$key]) : false;
    }
    function cookie( $key )
    {
        return isset($_COOKIE[$key]) ? myUnescape($_COOKIE[$key]) : false;
    }
    function request( $key )
    {
        return isset($_REQUEST[$key]) ? myUnescape($_REQUEST[$key]) : false;
    }
}   // end class Input

?>