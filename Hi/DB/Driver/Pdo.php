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
 *  Pdo数据库操作抽象类
 */
abstract class Hi_DB_Driver_Pdo implements Hi_DB_Driver_Interface {
    /**
     * dsn
     * @var string
     */
    private $_dsn = '';
    /**
     * 用户名
     * @var string
     */
    private $_user = '';
    /**
     * 密码
     * @var string
     */
    private $_pw = '';

    /**
     * 目标数据库字符集
     * @var string
     */
    private $_charset = 'utf-8';

    /**
     * 连接池
     * @var PDO
     */
    private $_conn;
    /**
     * 数据库关闭状态标识
     * @var bool
     */
    private $_closed = true;
    /**
     * 查询资源
     * @var PDOStatement
     */
    private $_query;

    /**
     * 是否长连接
     * @var bool
     */
    private $_long = false;

    /**
     * 驱动
     * @var string
     */
    private $_driver = 'mysql';

    public function Hi_DB_Driver_Pdo($conf) {
        isset($config['dns']) && $this->_dsn = $config['dns'];
        isset($config['user']) && $this->_user = $config['user'];
        isset($config['password']) && $this->_pw = $config['password'];
        isset($config['charset']) && $this->_charset = $config['charset'];
        isset($config['long']) && $this->_long = $config['long'];
        $dns_arr = explode(':', $this->_dsn);
        $this->_driver = strtolower($dns_arr[0]);
    }
    /**
     * 建立连接方法接口
     */
    public function connect() {
        try {
            if($this->_long) {
                $this->_conn = new PDO($this->_dsn,$this->_user,$this->_pw,array(PDO::ATTR_PERSISTENT => true));
            }else {
                $this->_conn = new PDO($this->_dsn,$this->_user,$this->_pw);
            }
        }catch (PDOException $e) {
            return false;
        }
        //设置字符集
        if('mysql' == $this->_driver) {
            $this->_conn->query('SET character_set_connection=' . $this->_charset . ', character_set_results=' . $this->_charset . ',character_set_client=binary,sql_mode=\'\';');
        }elseif ('pgsql' == $this->_driver) {
            $this->_conn->exec('SET CLIENT_ENCODING TO '.$this->_charset);
        }
        $this->_closed = true;
        return true;
    }

    /**
     * 查询方法接口
     * @param string $sql
     */
    public function query($sql) {
        $args = func_num_args() == 2 ?  func_get_arg(1) : array();
        $this->_query = $this->_instance->prepare($sql);
        return $this->_sth->execute($args) ? $this->_query : false;
    }

    /**
     * 获取最后插入数据序号方法接口
     * @param string $d 表名
     * @param string $p 主键
     */
    function get_last_id($d, $p) {
        return  $this->_conn->lastInsertId();
    }

    /**
     * 获取数据行方法接口
     */
    function fetch_row($q, $n) {
        if (!$q) {
            $q = $this->_query;
        }
        return $q->fetch($q, $n ? PDO::FETCH_BOTH : PDO::FETCH_ASSOC);
    }

    /**
     * 释放资源方法接口
     */
    function free_result($q) {
        if (!$q) {
            $q = $this->_query;
        }
        $q->closeCursor();
    }

    /**
     * SQL转义方法接口
     */
    function escape($m, $v) {
        return null;
    }

    /**
     * 关闭方法接口
     */
    function close() {
        if ($this->_closed == false) {
            $this->_conn = null;
        }
    }

    /*
     * 事务开始
    */
    function begin() {
        return $this->_conn->beginTransaction();
    }

    /**
     * 事务回滚
     */
    function rollback() {
        return $this->_conn->rollBack();
    }

    /**
     * 事务提交
     */
    function commit() {
        return $this->_conn->commit();
    }
}
?>