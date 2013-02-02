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
 * 
 * 对象注册表
 * @author ww
 */
class Hi_Registry extends Hi_Abstract_Iterator {

    /**
     * Object
     * @var <type> 
     */
    protected static $_objs = array();
    private static $_instance;

    /**
     * 获取注册的值
     * @param string $n
     * @return mixed
     */
    public static function get($n) {
        if (!self::exists($n)) {
            trigger_error("访问的对象{$n}未被注册",E_USER_ERROR);
        }
        return self::$_objs[$n];
    }

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct(self::$_objs);
    }

    /**
     * 注册对象
     * @param string $n 注册名
     * @param mixed $v 注册值
     * @return NULL 无返回值
     */
    public static function set($n, $v) {
        self::$_objs[$n] = $v;
    }

    /**
     * 查询某一项目是否存在于注册表中
     * @param string $n
     * @return bool 存在返回TRUE, 不存在返回FALSE
     */
    public static function exists($n) {
        return isset(self::$_objs[$n]);
    }

    /**
     * 删除某一注册值
     * @param string $n 注册名
     * @return bool 返回TRUE
     */
    public static function del($n) {
        if (self::exists($n)) {
            unset(self::$_objs[$n]);
        }
        return true;
    }

    /**
     * 清空对象
     * @return NULL
     */
    public static function reset() {
        self::$_objs = array();
    }

    /**
     * 获取本身实例
     * @return Hi_Registry Hi_Registry实例对象
     */
    public static function instance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

}

?>
