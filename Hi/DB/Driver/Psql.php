<?php
/**
 *  [HiPHP]
 *  @copyright (C) 2013 weird
 *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
/**
 * @ignore
 */
!defined('IN_HI') && die('Access Denied!');

/**
 *  PostgreSQL数据库操作驱动
 */
abstract class Hi_DB_Driver_Psql  implements Hi_DB_Driver_Interface {
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
    private $_port=5432;

    public function Hi_DB_Abstract_Psql($conf) {
        isset($config['host']) && $this->_host = $config['host'];
        isset($config['user']) && $this->_user = $config['user'];
        isset($config['password']) && $this->_pw = $config['password'];
        isset($config['data']) && $this->_db = $config['data'];
        isset($config['long']) && $this->_long = $config['long'];
        isset($config['charset']) && $this->_charset = $config['charset'];
        isset($config['port']) && $this->_port = $config['port'];
    }

    /**
     * 建立连接
     */
    public function connect() {
        if (!isset($this->_conn)) {
            $conn_str = 'host='.$this->_host.' port='.$this->_port.' dbname='.$this->_db.' user='.$this->_user.' password='.$this->_pw.' options=\'--client_encoding='.str_replace('-', '', $this->_charset).'\'';
            $this->_conn = $this->_long ?  pg_pconnect($conn_str) : pg_connect($conn_str);
            if (false === $this->_conn) {
                return false;
            } else {
                $this->_closed = false;
            }
            return true;
        }
    }

    /**
     * 查询方法接口
     * @param string $sql
     */
    public function query($sql) {
        $this->_query = pg_query($this->_conn, $sql);
        if (false === $this->_query) {
            return false;
        }
        return $this->_query;
    }

    /**
     * 获取最后插入数据序号方法接口
     * @param string $d 表名
     * @param string $p 主键
     */
    function get_last_id($d, $p) {
        if (($id = pg_last_oid($this->_conn)) > 0) {
            return $id;
        } else {
            $sql = 'SELECT max(' . $p . ') FROM ' . $d;
            $rs = $this->fetch_row($this->query($sql), true);
            $this->free_result();
            return $rs[0];
        }
    }

    /**
     * 获取数据行方法
     */
    public function fetch_row($q, $n) {
        if (!$q) {
            $q = $this->_query;
        }
        return pg_fetch_array ($q, $n ? PGSQL_BOTH : PGSQL_ASSOC);
    }

    /**
     * 释放资源
     */
    function free_result($q) {
        if (!$q)
            $q = $this->_query;
        return pg_free_result($q);
    }

    /**
     * SQL转义
     */
    public function escape($m, $v) {
        switch ($m) {
            case '%d':
                return intval($v);
            case '%s':
                return '\'' . addslashes($v) . '\'';
            case '%f':
                return floatval($v);
            case '%b':
                return '\'' . addslashes($v) . '\'';
        }
    }

    /**
     * 关闭
     */
    function close() {
        if ($this->_closed == false) {
            $this->_closed = pg_close($this->_conn);
        }
    }

    /*
     * 事务开始
    */
    function begin() {
        pg_query($this->_conn,'START TRANSACTION');
    }

    /**
     * 事务回滚
     */
    function rollback() {
        pg_query($this->_conn,'ROLLBACK');
    }

    /**
     * 事务提交
     */
    function commit() {
        pg_query($this->_conn,'COMMIT');
    }
}
?>
