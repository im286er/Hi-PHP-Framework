<?php

!defined('IN_HI') && die('Access Denied!');

/**
 *  Mssql数据库操作抽象类
 */
class Hi_DB_Driver_Mssql implements Hi_DB_Driver_Interface {

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
    public function Hi_DB_Driver_Mssql($config) {
        isset($config['host']) && $this->_host = $config['host'];
        isset($config['user']) && $this->_user = $config['user'];
        isset($config['password']) && $this->_pw = $config['password'];
        isset($config['data']) && $this->_db = $config['data'];
        isset($config['long']) && $this->_long = $config['long'];
        isset($config['charset']) && $this->_charset = $config['charset'];
        isset($config['reconnect']) && $this->_reconnect = $config['reconnect'];
        isset($config['port']) && $this->_port = $config['port'];
    }

    public function connect() {
        if (!isset($this->_conn)) {
            $server = $this->_host . (isset($this->_port) ? (':'.$this->_port) : '');
            $this->_conn = $this->_long ? mssql_pconnect($server, $this->_user, $this->_pw) : mssql_connect($server, $this->_user, $this->_pw);
            if (false === $this->_conn) {
                return false;
            } else {
                $this->_closed = false;
            }
            if (false === mssql_select_db($this->_db, $this->_conn)) {
                return false;
            }
        }

        return true;
    }

    public function query($sql) {
        return $this->_query = mssql_query($sql, $this->_conn);
    }

    public function get_last_id($d, $p) {
        if (empty($d) || empty($p)) {
            $sql = 'SELECT @@IDENTITY AS ID';
        } else {
            $sql = 'SELECT max(' . $p . ') FROM ' . $d;
        }
        $rs = $this->fetch_row($this->query($sql), true);
        $this->free_result($this->_query);
        return $rs[0];
    }

    public function fetch_row($q, $n) {
        if (!$q)
            $q = $this->_query;
        return mssql_fetch_array($q, $n ? MSSQL_BOTH : MSSQL_ASSOC);
    }

    public function free_result($q) {
        if (!$q)
            $q = $this->_query_id;
        mssql_free_result($q);
    }

    public function escape($m, $v) {
        switch ($m) {
            case '%d':
                return intval($v);
            case '%s':
                return str_replace("'", "''", $v);
            case '%f':
                return floatval($v);
            case '%b':
                return str_replace("'", "''", $v);
        }
    }

    public function begin() {
        return $this->query('BEGIN TRAN');
    }

    public function rollback() {
        return $this->mysql_query('ROLLBACK TRAN');
    }


    public function commit() {
        return $this->mysql_query('COMMIT TRAN');
    }

    public function close() {
        return $this->_conn_id ? mssql_close($this->_conn_id) : false;
    }

    public function __destruct() {
        $this->close();
    }

}

?>
