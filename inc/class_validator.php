<?php
/***********************************************************************
 * @filename            : class_validator.php
 * @author              : Ken Wang
 * @description         : validator class (static-safe)
 * @created             : 2005-11-15
 * @modified            : 2013-03-17
 * @req-constants       : CHARSET
 * @req-functions       : utf8_is_valid
 * @req-classes         : none
 ***********************************************************************/

class Validator
{
    /*
    public boolean
    */
    public static function checkString( $string, $length_min=0, $length_max=256, $type='normal' )
    {
        if( !is_string( $string ) )
        {
            return false;
        }
        if( $length_min == 0 && strlen($string)==0 )
        {   # special case
            return true;
        }
        $strlen = strlen($string); # byte-length
        
        # UTF-8 validation & strlen
        if( defined('CHARSET') && strtoupper( CHARSET ) == 'UTF-8' )
        {   # invalid UTF-8 string
            if( !utf8_is_valid($string) ) return false;
            # count character length instead of byte length
            if( function_exists('mb_strlen') )
            { $strlen = mb_strlen( $string, 'UTF-8' ); }
            else
            { $strlen = strlen( utf8_decode($string) ); }
        }
        
        if( $strlen >= $length_min && $strlen <= $length_max )
        {
            switch( strtolower($type) )
            {
                case 'normal':
                    return true;
                    break;
                
                case 'alphanumeric':
                    if( !preg_match( "!^[a-zA-Z0-9]+$!", $string ) )
                    {
                        return false;
                    }
                    break;
                
                case 'email':
                    if( !preg_match( "/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/i", $string ) ) 
                    {
                        return false;
                    }
                    break;
                
                case 'date':
                case 'mysql_date':
                    if( !preg_match( RE_DATE, $string ) )
                    { return false; }
                    break;
                
                case "domain":
                    if( !preg_match(RE_DOMAIN, $string) )
                    {
                        return false;
                    }
                    break;
                
                case 'uri':
                    if( !preg_match('/^[a-z][0-9a-z\._-]+[a-z0-9]+$/i', $string) )
                    {
                        return false;
                    }
                    break;
                
                case 'url':
                    if( !preg_match("!^(http://|https://).{5,}!", $string) )
                    {
                        return false;
                    }
                    break;
                
                case 'tw_pid': # Taiwan Personal ID Checker 
                    if( strlen($string) != 10 || !preg_match('/^[a-zA-Z][0-9]{9}$/', $string ) )
                    { return false; }
                    # split characters
                    $chars = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
                    # check initial = A-Z
                    $initial = strtoupper( $chars[0] );
                    if( !ereg("^[A-Z]$", $initial) )
                    { return false; }
                    # funky validation follows...
                    $allowed_initials = 'ABCDEFGHJKLMNPQRSTUVXYWZIO';
                    $c = strpos( $allowed_initials, $initial );
                    $n = floor( $c / 10 ) + ( $c % 10*9 ) + 1;
                    for( $i=1; $i<9; $i++ )
                    { $n += floor( $chars[$i] ) * (9-$i); }
                    $n = ( 10 - ($n % 10) ) % 10;
                    if( $n != floor( $chars[9] ) )
                    { return false; }
                    // you pass the test!
                    return true;
                    break;
                
                case 'session':
                case 'session_id':
                    if( !preg_match( '!^[a-zA-Z0-9]{32}$!', $string ) )
                    { 
                        return false; 
                    }
                    break;
                
                default:
                    die( 'Invalid parameter for Validator::checkString() - ' .$type );
            }
            return true;
        }
        return false;
    }
    
    /*
    public boolean
    */
    public static function checkNumber( $number, $lower=0, $upper=99999999, $type='int' )
    {
        if( !is_numeric( $number ) )
        {
            return false;
        }
        if( $number >= $lower && $number <= $upper )
        {
            switch( $type )
            {
                case 'int':
                case 'integer':
                    if( !preg_match( "!^[0-9]+$!", $number ) )
                    {
                        return false;
                    }
                    break;
                
                case 'float':
                    # no further checking for float
                    break;
                
                default:
                    die( 'Invalid parameter for Validator::checkNumber() - ' .$type );
            }
            return true;
        }
        else 
        {
            return false;
        }
    }

}


/***** UTF-8 functions *****/

/** utf8_is_valid
* Tests a string as to whether it's valid UTF-8 and supported by the
* Unicode standard
* Note: this function has been modified to simply return true or false
* @author <hsivonen@iki.fi>
* @param string UTF-8 encoded string
* @return boolean true if valid
* @see http://hsivonen.iki.fi/php-utf8/
* @see utf8_compliant
* @package utf8
* @subpackage validation
*/
function utf8_is_valid($str) {
    
    $mState = 0;     // cached expected number of octets after the current octet
                     // until the beginning of the next UTF8 character sequence
    $mUcs4  = 0;     // cached Unicode character
    $mBytes = 1;     // cached expected number of octets in the current sequence
    
    $len = strlen($str);
    
    for($i = 0; $i < $len; $i++) {
        
        $in = ord($str{$i});
        
        if ( $mState == 0) {
            
            // When mState is zero we expect either a US-ASCII character or a
            // multi-octet sequence.
            if (0 == (0x80 & ($in))) {
                // US-ASCII, pass straight through.
                $mBytes = 1;
                
            } else if (0xC0 == (0xE0 & ($in))) {
                // First octet of 2 octet sequence
                $mUcs4 = ($in);
                $mUcs4 = ($mUcs4 & 0x1F) << 6;
                $mState = 1;
                $mBytes = 2;
                
            } else if (0xE0 == (0xF0 & ($in))) {
                // First octet of 3 octet sequence
                $mUcs4 = ($in);
                $mUcs4 = ($mUcs4 & 0x0F) << 12;
                $mState = 2;
                $mBytes = 3;
                
            } else if (0xF0 == (0xF8 & ($in))) {
                // First octet of 4 octet sequence
                $mUcs4 = ($in);
                $mUcs4 = ($mUcs4 & 0x07) << 18;
                $mState = 3;
                $mBytes = 4;
                
            } else if (0xF8 == (0xFC & ($in))) {
                /* First octet of 5 octet sequence.
                *
                * This is illegal because the encoded codepoint must be either
                * (a) not the shortest form or
                * (b) outside the Unicode range of 0-0x10FFFF.
                * Rather than trying to resynchronize, we will carry on until the end
                * of the sequence and let the later error handling code catch it.
                */
                $mUcs4 = ($in);
                $mUcs4 = ($mUcs4 & 0x03) << 24;
                $mState = 4;
                $mBytes = 5;
                
            } else if (0xFC == (0xFE & ($in))) {
                // First octet of 6 octet sequence, see comments for 5 octet sequence.
                $mUcs4 = ($in);
                $mUcs4 = ($mUcs4 & 1) << 30;
                $mState = 5;
                $mBytes = 6;
                
            } else {
                /* Current octet is neither in the US-ASCII range nor a legal first
                 * octet of a multi-octet sequence.
                 */
                return FALSE;
                
            }
        
        } else {
            
            // When mState is non-zero, we expect a continuation of the multi-octet
            // sequence
            if (0x80 == (0xC0 & ($in))) {
                
                // Legal continuation.
                $shift = ($mState - 1) * 6;
                $tmp = $in;
                $tmp = ($tmp & 0x0000003F) << $shift;
                $mUcs4 |= $tmp;
            
                /**
                * End of the multi-octet sequence. mUcs4 now contains the final
                * Unicode codepoint to be output
                */
                if (0 == --$mState) {
                    
                    /*
                    * Check for illegal sequences and codepoints.
                    */
                    // From Unicode 3.1, non-shortest form is illegal
                    if (((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
                        ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
                        ((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
                        (4 < $mBytes) ||
                        // From Unicode 3.2, surrogate characters are illegal
                        (($mUcs4 & 0xFFFFF800) == 0xD800) ||
                        // Codepoints outside the Unicode range are illegal
                        ($mUcs4 > 0x10FFFF)) {
                        
                        return FALSE;
                        
                    }
                    
                    //initialize UTF8 cache
                    $mState = 0;
                    $mUcs4  = 0;
                    $mBytes = 1;
                }
            
            } else {
                /**
                *((0xC0 & (*in) != 0x80) && (mState != 0))
                * Incomplete multi-octet sequence.
                */
                
                return FALSE;
            }
        }
    }
    return TRUE;
}   // end utf8_is_valid
