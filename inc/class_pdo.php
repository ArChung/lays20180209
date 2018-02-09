<?php

/*
LongChang
PDO class
*/
class DBException extends Exception
{
    public $last_query;
    public function __construct($msg, $code, $last_query = "") {
        $this->last_query = $last_query;
        parent::__construct($msg, $code);
    }
}
 // end DBException

// end DBException

class PDOc
{

    /***** Class Variables *****/
    protected $__server;
    protected $__username;
    protected $__password;
    protected $__database;
    public $_link;

    // connection resource
    public $_stmt;

    // PDOStatement
    public $_query_string;

    // query string
    protected $_error_string;

    // error string
    protected $_result;

    // result resource

    public $count_queries;

    // number of queries executed
    public $query_log;

    // array containing all executed queries

    /***** Constructor *****/

    /*
    public void
    */
    public function __construct($server, $username, $password, $database = "") {
        $this->__server = $server;
        $this->__username = $username;
        $this->__password = $password;
        $this->__database = $database;

        $this->_connect(2);

        // init executed query count
        $this->count_queries = 0;
        $this->query_log = array();
    }

    protected function _connect() {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $this->__server, $this->__database);

        try {
            $this->_link = new PDO($dsn, $this->__username, $this->__password, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_PERSISTENT => false));
        }
        catch(PDOException $e) {
            $this->_error_string = $e->getMessage();
            echo $this->_error_string;
            die;
        }

        if (!empty($this->_link)) {
            $this->_link->exec('SET NAMES ' . (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4'));
        } else {
        }

        return $this->_link;
    }

    /***** Core Operations *****/

    /*
    public resource
    */
    public function query($sql) {

        try {
            $this->_query_string = $sql;
            $this->_stmt = $this->_link->query($sql, PDO::FETCH_ASSOC);
            return $this->_stmt;
        }
        catch(Exception $e) {

            // $this->_throwDBException('Invalid Query');
            throw $e;

            return false;
        }
    }

    public function prepare($sql) {
        $this->_query_string = $sql;
        $this->_stmt = $this->_link->prepare($sql);
        return $this->_stmt;
    }

    public function execute($args = array()) {

        try {
            $i = 0;
            $v = array();
            if (is_array($args)) {
                $x = 0;
                foreach ($args as $key => $value) {
                    $k = str_replace(array('.','(',')'), '', $key);
                    $t = $k;// . $x++;
                    $v[$i] = $value;

                    if (is_int($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                    else if (is_bool($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_BOOL);
                    else if (is_null($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_NULL);
                    else $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);

                    $i++;
                }
            }

            return $this->_stmt->execute();
        }
        catch(Exception $e) {
            if(DEBUG) {
                pr(debug_backtrace());
                $this->_stmt->debugDumpParams();
                pr($this->_getError());
            }
            return false;
        }
    }

    /*
    public string
    */
    public function escape($str) {
        return $this->_link->quote($str);
    }

    /*
    public string
    returns the first single cell value from the result set
    */
    public function getVal($sql) {

        if($this->_link) {
            $this->_stmt = $this->_link->query($sql);
            if ($this->numRows() > 0) {
                $row = $this->_stmt->fetch(PDO::FETCH_ASSOC);
                return array_values($row)[0];
            }
        }
        return false;
    }

    /*
    public array
    returns the first row of the result set as associative array, false if no results
    */
    public function getRow() {
        if($this->numRows() > 0) {
            if ($this->_stmt) {
                return $this->_stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        return false;
        // return ($this->numRows() > 0) ? $this->_stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    /*
    public array
    returns an one dimensional array from the 1st column of the result set
    */
    public function getCol() {
        $result_arr = array();
        $row = $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($row as $key => $value) {
            // pr($row);die;
            $result_arr[] = reset($value);
        }
        return $result_arr;
    }

    /*
    public array
    returns an associative array from the 1st (key) & 2nd (value) columns of the result set
    */
    public function getTwoCol($sql) {
        $result_arr = array();
        $this->query($sql);
        while ($row = $this->fetchArray()) {

            $result_arr[$row[0]] = $row[1];
        }
        return $result_arr;
    }

    /*
    public array
    returns an two dimensional array
    */
    public function GetAll($sql, $indexed = false) {
        $result_arr = array();
        $stmt = $this->query($sql);
        while ($row = $this->fetchAssoc()) {
            if ($indexed) {

                // only works if first column is the index!
                reset($row);
                $key1 = key($row);
                $val1 = $row[$key1];
                $result_arr[$val1] = $row;
            } else {
                $result_arr[] = $row;
            }
        }
        return $result_arr;
    }

    /*
    public mixed-array
    */
    public function fetchArray() {
        $return = array();
        if ($this->_stmt) {
            $this->_stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $return;
    }

    /*
    public assoc-array
    */
    public function fetchAssoc() {
        $return = null;
        $i = 0;
        if ($this->_stmt) {
            $return = $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $return;
    }

    /*
    public assoc-array
    */
    public function fetchObj() {
        $return = null;
        $i = 0;
        if ($this->_stmt) {
            $return = $this->_stmt->fetchAll(\PDO::FETCH_OBJ);
        }
        return $return;
    }

    /*
    public array
    same as fetchArray()
    */
    public function fetchRow() {
        $return = array();
        if ($this->_stmt) {
            $return = $this->_stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $return;
    }

    /*
    */
    public function insertRow($query, $params) {
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateRow($query, $params) {
        return $this->insertRow($query, $params);
    }

    public function deleteRow($query, $params) {
        return $this->insertRow($query, $params);
    }

    /*
    public string (integer)
    */
    public function numRows() {
        $numRows = 0;
        if ($this->_stmt) {
            $numRows = $this->_stmt->rowCount();
        }
        return $numRows;
    }

    /*
    public string (integer)
    */
    public function affectedRows() {
        $numRows = 0;
        if ($this->_stmt) {
            $numRows = $this->_stmt->rowCount();
        }
        return $numRows;
    }

    /*
    public string
    */
    public function insertId() {
        $id = 0;
        if ($this->_stmt) {
            $id = $this->_link->lastInsertId();
        }
        return $id;
    }

    public function close() {
        $this->_stmt->closeCursor();
    }

    /***** Error Handling *****/

    /*
    private string
    deprecated
    */
    protected function _getError() {
        $this->_error_string = $this->_stmt->errorInfo();
        return $this->_error_string;
    }


    /*
    insert, select, update, delete
    */

    // {{{ function insert_data($table, $val_ary, $get_insert_id = false)
    function insertData($table, $val_ary, $get_insert_id = false) {
        $dbh = $this->_link;

        $col = '';
        $vcol = '';
        $val = '';
        $x = 0;
        foreach ($val_ary as $key => $value) {
            $col.= '`' . $key . '`,';

            $t = $key . $x++;
            $vcol.= ':' . $t . ',';
        }
        $col = rtrim($col, ',');
        $vcol = rtrim($vcol, ',');

        $sql = "INSERT INTO $table ($col) VALUES ($vcol)";

        $this->_stmt = $dbh->prepare($sql);

        // 若有 get_table_col_type 的 function 指定 type
        $fntype = '';
        $fn = 'get_' . $table . '_col_type';
        if (function_exists($fn)) {
            $fntype = $fn();

            $i = 0;
            $v = array();
            $x = 0;
            foreach ($val_ary as $key => $value) {
                $type = 'str';
                if (isset($fntype[$key])) $type = $fntype[$key];

                $t = $key . $x++;
                $v[$i] = $value;
                if ($type == 'int') {
                    $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                } else {
                    $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);
                }
                $i++;
            }
        } else {
            $i = 0;
            $v = array();
            $x = 0;
            foreach ($val_ary as $key => $value) {
                $t = $key . $x++;
                $v[$i] = $value;
                if (is_int($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                else if (is_bool($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_BOOL);
                else if (is_null($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_NULL);
                else $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);
                $i++;
            }
        }
        try {
            $r = $this->_stmt->execute();
            $this->_query_string = $sql;
        } catch (Exception $e) {
            if(DEBUG) {
                pr($e);
            }
        }


        //$error = $this->_stmt->errorInfo();
        //if ($error[0] != 0)
        //    print_r($this->_stmt->errorInfo());

        if ($get_insert_id) $r = $this->_link->lastInsertId();

        return $r;
    }

    // }}}
    // {{{ function select_data($table, $col_ary, $where_array = '', $oder_ary = '', $limit = 0, $begin = 0)
    // $order_array = array('col desc', 'col asc');
    function selectData($table, $col_ary, $where_array = '', $order_array = '', $limit = 0, $begin = 0) {
        $dbh = $this->_link;

        $col = '';
        $col = implode(',', $col_ary);

        $limit = intval($limit);
        $begin = intval($begin);
        $where = '';
        $order = '';
        $limit_str = '';

        if (is_array($where_array)) {
            $where = ' WHERE ';

            $x = 0;
            foreach ($where_array as $key => $val) {
                $t = $key . $x++;
                $where.= "$key = :$t AND ";
            }

            $where = rtrim($where, 'AND ');
        }

        if (is_array($order_array)) {
            $order = ' ORDER BY ';
            $order.= implode(',', $order_array);
        }

        if ($limit > 0) {
            $limit_str = ' LIMIT ';
            $limit_str.= ($begin > 0) ? "$begin,$limit" : $limit;
        }

        $sql = "SELECT $col FROM $table $where $order $limit_str";
        $this->_stmt = $dbh->prepare($sql);

        // 若有 get_table_col_type 的 function 指定 type
        // bindParam
        $fntype = '';
        $fn = 'get_' . $table . '_col_type';
        if (function_exists($fn)) {
            $fntype = $fn();

            $i = 0;
            $v = array();
            if (is_array($where_array)) {
                $x = 0;
                foreach ($where_array as $key => $value) {
                    $type = 'str';
                    if (isset($fntype[$key])) $type = $fntype[$key];

                    $t = $key . $x++;
                    $v[$i] = $value;
                    if ($type == 'int') {
                        $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                    } else {
                        $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);
                    }
                    $i++;
                }
            }
        } else {
            $i = 0;
            $v = array();
            if (is_array($where_array)) {
                $x = 0;
                foreach ($where_array as $key => $value) {
                    $t = $key . $x++;
                    $v[$i] = $value;

                    if (is_int($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                    else if (is_bool($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_BOOL);
                    else if (is_null($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_NULL);
                    else $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);

                    $i++;
                }
            }
        }

        try {
            $this->_stmt->execute();
            $this->_query_string = $sql;
        } catch (Exception $e) {
            if(DEBUG) {
                pr($e);
            }
        }

        $return = array();
        $i = 0;
        while ($row = $this->_stmt->fetch(PDO::FETCH_ASSOC)) {
            foreach ($col_ary as $key) $return[$i][$key] = $row[$key];
            $i++;
        }

        return $return;
    }

    // }}}

    // {{{ function update_data($table, $val_ary, $where_array = '')
    function updateData($table, $val_ary, $where_array = '') {
        if (empty($where_array)) return false;

        $dbh = $this->_link;

        $value_map = array();

        $i = 0;
        $x = 0;
        $set = '';
        foreach ($val_ary as $key => $val) {
            $t = $key . $x++;

            //$set  .= ',' . $key . ' = ?';
            $set.= ',`' . $key . '` = :' . $t;
            $value_map[$i++] = array($key => $val);
        }
        $set = ltrim($set, ',');

        $where = '';
        if (is_array($where_array)) {
            $where = ' WHERE ';
            foreach ($where_array as $key => $val) {

                //$where .= $key . ' = ? AND ';
                $t = $key . $x++;
                $where.= '`' . $key . '` = :' . $t . ' AND ';
                $value_map[$i++] = array($key => $val);
            }
            $where = rtrim($where, 'AND ');
        }

        $sql = "UPDATE $table SET $set $where";
        $this->_stmt = $dbh->prepare($sql);

        // 若有 get_table_col_type 的 function 指定 type
        $fntype = '';
        $fn = 'get_' . $table . '_col_type';
        if (function_exists($fn)) {
            $fntype = $fn();

            $i = 1;
            foreach ($value_map as $index => $data) {
                foreach ($data as $key => $value) {
                    $type = 'str';
                    if (isset($fntype[$key])) $type = $fntype[$key];

                    $v[$i] = $value;
                    if ($type == 'int') {
                        $this->_stmt->bindParam($i, $v[$i], PDO::PARAM_INT);
                    } else {
                        $this->_stmt->bindParam($i, $v[$i], PDO::PARAM_STR);
                    }
                    $i++;
                }
            }
        } else {
            $x = 0;
            $i = 1;
            $v = array();
            foreach ($value_map as $index => $data) {
                foreach ($data as $key => $value) {
                    $t = $key . $x++;
                    $v[$i] = $value;
                    if (is_int($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                    else if (is_bool($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_BOOL);
                    else if (is_null($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_NULL);
                    else $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);
                    $i++;
                }
            }
        }

        try {
            $r = $this->_stmt->execute();
            $this->_query_string = $sql;
        } catch (Exception $e) {
            if(DEBUG) {
                pr($e);
            }
        }

        //$error = $this->_stmt->errorInfo();
        //if ($error[0] != 0)
        //    print_r($this->_stmt->errorInfo());

        return $r;
    }

    // }}}

    // {{{ function delete_data($table, $where_array = '', $where_str = '')
    function deleteData($table, $where_array = '') {

        // could be delete all data
        if (empty($where_array)) return false;

        $dbh = $this->_link;

        $where = '';
        if (is_array($where_array)) {
            $where = ' WHERE ';

            $x = 0;
            foreach ($where_array as $key => $val) {
                $t = $key . $x++;
                $where.= $key . ' = :' . $t . ' AND ';
            }

            $where = rtrim($where, 'AND ');
        }

        $sql = "DELETE FROM $table $where";
        $this->_stmt = $dbh->prepare($sql);

        // 若有 get_table_col_type 的 function 指定 type
        $fntype = '';
        $fn = 'get_' . $table . '_col_type';
        if (function_exists($fn)) {
            $fntype = $fn();

            $i = 0;
            $v = array();
            if (is_array($where_array)) {
                $x = 0;
                foreach ($where_array as $key => $value) {
                    $type = 'str';
                    if (isset($fntype[$key])) $type = $fntype[$key];

                    $t = $key . $x++;
                    $v[$i] = $value;
                    if ($type == 'int') {
                        $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                    } else {
                        $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);
                    }
                    $i++;
                }
            }
        } else {
            $i = 0;
            $v = array();
            if (is_array($where_array)) {
                $x = 0;
                foreach ($where_array as $key => $value) {
                    $t = $key . $x++;
                    $v[$i] = $value;
                    if (is_int($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_INT);
                    else if (is_bool($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_BOOL);
                    else if (is_null($value)) $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_NULL);
                    else $this->_stmt->bindParam(':' . $t, $v[$i], PDO::PARAM_STR);
                    $i++;
                }
            }
        }

        return $this->_stmt->execute();
    }

    function last_query() {
        return $this->_query_string;
    }

    // }}}


}
