<?php
/***********************************************************************
 * @filename            : class_session.php
 * @author              : Ken Wang
 * @description         : session class based on Harry Fuecks' code
 * @created             : 2005-11-15
 * @modified            : 2005-11-15
 * @req-constants       : 
 * @req-functions       :
 * @req-classes         : 
 ***********************************************************************/

class Session
{
    /***** Constructor *****/
    /*
    public void
    */
    function Session()
    {
        //session_start();
        appInitSession();
    }
    
    
    /***** Operations *****/
    /*
    public void
    */
    function set( $name, $value )
    {
        if( is_string( $name ) )
        {
            $_SESSION[$name] = $value;
        }
        elseif( is_array( $name ) )
        {   # takes up to 3 levels
            switch( count( $name ) )
            {
                case 2:
                    if( !isset($_SESSION[$name[0]]) ) $_SESSION[$name[0]] = array();
                    $_SESSION[$name[0]][$name[1]] = $value;
                    break;
                case 3:
                    if( !isset($_SESSION[$name[0]]) ) $_SESSION[$name[0]] = array();
                    if( !isset($_SESSION[$name[0]][$name[1]]) ) $_SESSION[$name[0]][$name[1]] = array();
                    $_SESSION[$name[0]][$name[1]][$name[2]] = $value;
                    break;
                case 4:
                    if( !isset($_SESSION[$name[0]]) ) $_SESSION[$name[0]] = array();
                    if( !isset($_SESSION[$name[0]][$name[1]]) ) $_SESSION[$name[0]][$name[1]] = array();
                    if( !isset($_SESSION[$name[0]][$name[1]][$name[2]]) ) $_SESSION[$name[0]][$name[1]][$name[2]] = array();
                    $_SESSION[$name[0]][$name[1]][$name[2]][$name[3]] = $value;
                    break;
                default: die( 'Unable to set multi-dimensional session value (exceeded level)' );
            }
        }
        
    }
    
    
    /*
    public mixed
    */
    function get( $name )
    {
        if( is_string( $name ) )
        {
            return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
        }
        elseif( is_array( $name ) )
        {
            switch( count( $name ) )
            {
                case 2:
                    return isset( $_SESSION[$name[0]][$name[1]] ) ? $_SESSION[$name[0]][$name[1]] : false;
                    break;
                case 3:
                    return isset( $_SESSION[$name[0]][$name[1]][$name[2]] ) ? $_SESSION[$name[0]][$name[1]][$name[2]] : false;
                    break;
                case 4:
                    return isset( $_SESSION[$name[0]][$name[1]][$name[2]][$name[3]] ) ? $_SESSION[$name[0]][$name[1]][$name[2]][$name[3]] : false;
                    break;
                default: die( 'Unable to get multi-dimensional session value (exceeded level)' );
            }
        }
        return false;
    }
    
    
    /*
    public void
    */
    function del( $name )
    {
        if( is_string( $name ) )
        {
            unset( $_SESSION[$name] );
        }
        elseif( is_array( $name ) )
        {
            switch( count( $name ) )
            {
                case 2:
                    unset( $_SESSION[$name[0]][$name[1]] );
                    break;
                case 3:
                    unset( $_SESSION[$name[0]][$name[1]][$name[2]] );
                    break;
                case 4:
                    unset( $_SESSION[$name[0]][$name[1]][$name[2]][$name[3]] );
                    break;
                default: die( 'Unable to delete multi-dimensional session value (exceeded level)' );
            }
        }
    }


    /*
    public void
    */
    function destroy()
    {
        $_SESSION = array();
        session_destroy();
    }
    
    
    /*
    regenerates session id, and deletes old session id
    */
    function regen()
    {
        if( function_exists('session_regenerate_id') )
        return session_regenerate_id( true );
    }

}