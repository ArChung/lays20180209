<?php
/***********************************************************************
 @ filename            : class_auth.php
 @ author              : Ken Wang
 @ description         : authentication class
 @ created             : 2006-03-08
 @ modified            : 2013-04-25
 ***********************************************************************/

class Auth extends Init
{
    /***** Class Variables *****/
    public $db;            #obj
    public $session;       #obj
    
    /***** Constructor *****/
    function __construct($DB, $session)
    {
        // parent::__construct();
        
        $this->db = $DB;
        $this->session = $session;
    }
    
    ######################################################################
    # adminLogin                                                         #
    ######################################################################
    /*
    public boolean
    */
    public function AdminLogin( $input )
    {
        # populate LoginForm
        // $this->html['FORM'] = $input;
        //var_export( $this->html['FORM'] ); exit;
        # validation
        // if( !Validator::checkString( $this->post['username'], 10, 100, 'email' ) )
        // { $this->errors[] = 'Email'; }
        if( !preg_match( RE_PASSWORD, $input['password'] ) )
        { $this->errors[] = '密碼'; }
        if( count($this->errors)>0 )
        { $this->msg = '請填寫／修正下列欄位:'; return false; }
        
        # check db
        // $sql = "SELECT  *
        //         FROM    `".TBL_ADMIN."`
        //         WHERE   email = '".myEscape($this->post['username'])."'
        //         LIMIT   1";
        // $this->db->query( $sql );
        
        $admin_data = [
            'username' => ADMIN_USERNAME,
            'passwd' => ADMIN_PASSWD
        ];
        // pr($admin_data);die;
        if( empty($admin_data) )
        {
            $this->msg = '帳號不存在。';
            return false;
        }
        # does password match?
        if( $admin_data['passwd'] != $input['password'] )
        {
            $this->msg = '密碼錯誤';
            return false;
        }
        
        $this->session->set( array(SES_ROOT,'admin'), $admin_data );
        
        return true;
    }   
    
    
    #public boolean
    public function AdminLogout()
    {
        # clear session data
        $this->session->set( array(SES_ROOT,'admin'), false );
        # regenerate session id upon privilege demotion
        $this->session->regen();
        return true;
    }   // end function AdminLogout
    
    
    # public array (assoc)
    public function GetAdminData()
    {
        return $this->session->get( array(SES_ROOT,'admin') );
    } # GetAdminData

    # public string
    public function GetAdminId()
    {
        return $this->session->get( array(SES_ROOT,'admin','id') );
    } # GetAdminId

    # public boolean
    function IsAdminLoggedIn()
    {
        $admin_data = $this->GetAdminData();
        return (!$admin_data) ? false : true;
    } # IsAdminLoggedIn
    
} # Auth
