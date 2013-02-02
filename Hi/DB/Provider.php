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
 * 数据访问提供者类
 *
 * @author weird
 */
class Hi_DB_Provider {
    /**
     * 驱动类名
     * @var string
     */
    private $_driver;
    /**
     * 数据访问实体对象
     * @var  HI_DB_Interface
     */
    private $_instance;
    private $_query;
    private $_args = null;
    /**
     * 实例名称
     * @var string
     */
    private $_provider;
    /**
     * 重新连接次数
     * @var Int
     */
    private $_reconnect = 0;
    /**
     * 数据库连接状态标识
     * @var bool
     */
    private $_closed = true;

    /**
     * 构造函数
     * @param string $provide 数据供给者
     * @return void
     */
    public function Hi_DB_Provider($provider) {
        if (!isset(Hi::$config->DB[$provider])) {
            trigger_error("'{$provider}'数据访问配置信息不存在",E_USER_ERROR);
        }
        $drive = isset(Hi::$config->DB[$provider]['driver']) ? Hi::$config->DB[$provider]['driver'] : 'mysql';
        $this->_driver = 'Hi_DB_Driver_' . ucfirst($drive);
        $this->_provider = $provider;
        isset(Hi::$config->DB[$provider]['reconnect']) && $this->_reconnect = Hi::$config->DB[$provider]['reconnect'];

        if (!class_exists($this->_driver)) {
            trigger_error("数据访问配置信息错误:'{$provider}'数据访问所需驱动'{$this->_driver}'不存在",E_USER_ERROR);
        }
        $instance = new $this->_driver(Hi::$config->DB[$provider]);
        if (!$instance instanceof HI_DB_Driver_Interface) {
            trigger_error("数据访问配置信息错误'{$provider}'数据访问所需驱动'{$this->_driver}'无效",E_USER_ERROR);
        }
        $this->_instance = $instance;
    }

    /**
     * 建立数据访问连接
     * @return bool
     */
    public function connect() {
        if ($this->_closed) {
            $res = false;
            $reconnect = $this->_reconnect + 1;
            while ($reconnect--) {
                $res = $this->_instance->connect();
            }
            if (false == $res) {
                trigger_error("连接数据库服务器失败:创建'{$this->_provider}'数据访问连接失败",E_USER_ERROR);
            } else {
                $this->_closed = false;
            }
        }
        return true;
    }

    /**
     * SQL查询
     * @param string $sql SQL查询语句
     * @return Hi_DB_Provider
     */
    public function query($sql) {
        if (empty($sql)) {
            trigger_error('查询数据失败:SQL执行语句为空',E_USER_ERROR);
        } else {
            $this->connect();
            $args = func_get_args();
            array_shift($args);
            //兼容数组模式
            $this->_args = (func_num_args()>1 && is_array(func_get_arg(1))) ? func_get_arg(1) : $args;
            $q = false;
            if($this->_instance instanceof Hi_DB_Driver_Pdo) {//PDO特殊处理
                $q = $this->_instance->query($sql, $args);
            }else {
                if (count($args)) {
                    $sql = preg_replace_callback('/(%d|%f|%s|%b|%%)/', array($this, 'escape_callback'), $sql);
                    $this->_args = null;
                }
                $q = $this->_instance->query($sql);
            }
            if ($q) {
                $this->_query = $q;
            } else {
                trigger_error("查询数据失败:SQL执行语句'{$sql}'错误",E_USER_ERROR);
            }
        }
        return $this;
    }

    /**
     * 获取数据行
     * @param bool $n 是否数字做键名
     * @param int $r 获取行数
     * @return array
     */
    public function fetch($n=false, $r=0) {
        $result = array();
        $i = 0;
        while (($r == 0 ? true : ($i++ < $r)) && ($row = $this->_instance->fetch_row($this->_query, $n))) {
            $result[] = $row;
        }
        $this->_instance->free_result($this->_query);
        return $result;
    }

    /**
     * 获取数据首行
     * @param bool $n 是否数字做键名
     * @return array
     */
    public function first($n=false) {
        $r = $this->fetch($n);
        return count($r) ? $r[0] : Null;
    }

    /**
     * 获取首行第N列数据
     * @param int $n 第N列数据
     * @return mixed
     */
    public function mixed($n=0) {
        $r = $this->first(true);
        return $r[$n];
    }

    /**
     * 获取最后插入数据序列号
     * 如对应的驱动提供该方法，参数为空即可
     * @param string $d 数据表名
     * @param string $p 主键
     * @return mixed
     */
    public function last_id($d=null, $p=null) {
        $this->connect();
        return $this->_instance->get_last_id($d, $p);
    }

    /**
     * 提供给SQL转义的正则替换回调函数
     * @param <type> $match
     * @return <type>
     */
    public function escape_callback($match) {
        if ($match[1] == '%%') {
            return '%';
        } else {
            return $this->_instance->escape($match[1], array_shift($this->_args));
        }
    }

    /**
     * 关闭数据库连接
     */
    public function close() {
        $this->_instance->close();
        $this->_closed = true;
    }
    /**
     * 转义
     * %d=>int, %f=>fload,%s=>string,%b=>Binary,%%=>%
     * @param string $t 类型
     * @param mixed  $v 值
     * @return <type>
     */
    public function escape($t,$v) {
        if(is_array($t) && is_array($v)) {
            foreach($v as $k=>$vv) {
                $v[$k] = $this->_instance->escape($t[$k],$vv);
            }
            return $v;
        }else {
            if(is_array($v)) {
                foreach($v as $k=>$vv) {
                    $v[$k] = $this->_instance->escape($t,$vv);
                }
                return $v;
            }else {
                return $this->_instance->escape($t,$v);
            }
        }
    }

    /**
     * 对驱动特殊方法的调用
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, $args) {
        if (method_exists($this->_instance, $name) || is_callable(array($this->_instance, $name))) {
            $this->connect();
            return call_user_func_array(array($this->_instance, $name), $args);
        } else {
            trigger_error("调用数据访问方法错误:{$this->_driver}->{$name}()无法访问)",E_USER_ERROR);
        }
    }

}
?>
