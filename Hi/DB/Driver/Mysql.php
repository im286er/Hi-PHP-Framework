<?php

!defined('IN_HI') && die('Access Denied!');

/**
 *  MySQL数据库操作抽象类
 */
class Hi_DB_Driver_Mysql implements Hi_DB_Driver_Interface {

    /**
     * 数据库地址
     * @var string
     */
    private $_host = 'localhost';
    /**
     * 用户名
     * @var string
     */
    private $_user = 'root';
    /**
     * 密码
     * @var string
     */
    private $_pw = '';
    /**
     * 目标数据库
     * @var string
     */
    private $_db = '';
    /**
     * 目标数据库字符集
     * @var string
     */
    private $_charset = 'utf-8';
    /**
     * 是否长连接
     * @var bool
     */
    private $_long = false;
    /**
     * 连接池
     * @var resource
     */
    private $_conn;
    /**
     * 数据库关闭状态标识
     * @var bool
     */
    private $_closed = true;
    /**
     * 查询资源
     * @var resource
     */
    private $_query;
    /**
     * 端口
     */
    private $_port;

    /**
     * 构造方法
     * @param string $config 配置参数
     */
    public function Hi_DB_Driver_Mysql($config) {
        isset($config['host']) && $this->_host = $config['host'];
        isset($config['user']) && $this->_user = $config['user'];
        isset($config['password']) && $this->_pw = $config['password'];
        isset($config['data']) && $this->_db = $config['data'];
        isset($config['long']) && $this->_long = $config['long'];
        isset($config['charset']) && $this->_charset = $config['charset'];
        isset($config['port']) && $this->_port = $config['port'];
    }

    /**
     * 链接数据库
     */
    public function connect() {
        if (!isset($this->_conn)) {
            $server = $this->_host . (isset($this->_port) ? (':' . $this->_port) : '');
            $this->_conn = $this->_long ? mysql_pconnect($server, $this->_user, $this->_pw,true) : mysql_connect($server, $this->_user, $this->_pw,true);
            if (false === $this->_conn) {
                return false;
            } else {
                $this->_closed = false;
            }
            if (mysql_select_db($this->_db, $this->_conn)) {
                $this->query('SET character_set_connection=' . $this->_charset . ', character_set_results=' . $this->_charset . ',character_set_client=binary,sql_mode=\'\';');
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 查询
     * @param string $sql SQL查询语句
     */
    public function query($sql) {
        $this->_query = mysql_query($sql, $this->_conn);
        if (false === $this->_query) {
            return false;
        }
        return $this->_query;
    }

    public function get_last_id($d, $p) {
        if (($id = mysql_insert_id($this->_conn)) > 0) {
            return $id;
        } else {
            $sql = 'SELECT max(' . $p . ') FROM ' . $d;
            $rs = $this->fetch_row($this->query($sql), true);
            $this->free_result();
            return $rs[0];
        }
    }

    /**
     * 锁表
     * @param string $t 表名
     */
    public function lock($t) {
        mysql_query("lock tables $t read", $this->_conn);
    }

    /**
     * 解锁表
     */
    public function unlock() {
        mysql_query('unlock tables');
    }

    public function fetch_row($q, $n) {
        if (!$q) {
            $q = $this->_query;
        }
        return mysql_fetch_array($q, $n ? MYSQL_BOTH : MYSQL_ASSOC);
    }

    public function free_result($q) {
        if (!$q)
            $q = $this->_query;
        return mysql_free_result($q);
    }
    
    public function escape($m, $v) {
        switch ($m) {
            case '%d':
                return intval($v);
            case '%s':
                return '\'' . addslashes($v) . '\'';
            case '%f':
                return floatval($v);
            case '%b':
                return '\'' . addslashes($v). '\'';
        }
    }


    public function begin() {
        return mysql_query('START TRANSACTION');
        return true;
    }


    public function rollback() {
        return mysql_query('ROLLBACK');
        return true;
    }


    public function commit() {
        return mysql_query('COMMIT');
        return true;
    }

    public function close() {
        if ($this->_closed == false) {
            $this->_closed = mysql_close($this->_conn);
        }
    }

    public function __destruct() {
        $this->close();
    }

}

?>
