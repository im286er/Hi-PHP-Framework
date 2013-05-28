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
 * 缓存访问助手
 * @author ww
 */
class Hi_Cache_Helper {

    /**
     * 缓存实体对象
     * @var array
     */
    private static $_cache_entity = array();

    /**
	* 缓存实例池
	* @var array
	*/
	private static $_instance = array();

    /**
     *
     * @param string $instance
     * @return Lib_Cache_Abstract
     */
    private static function get_instance($instance) {
        if (!isset(self::$_cache_entity[$instance])) {
            $class = 'Cache_' . $instance;
            self::$_cache_entity[$instance] = new $class;
        }
        return self::$_cache_entity[$instance];
    }
    
    /**
     * 获取缓存
     * @param string $instance 缓存项实例对象
     * @param string $item
     */
    public static function get($instance, $k) {
        $c = self::get_instance($instance);
        return $c->get($k);
    }

    /**
     * 设置缓存
     * @param <type> $instance
     * @param <type> $k
     * @param <type> $v
     */
    public static function set($instance, $k, $v) {
        $c = self::get_instance($instance);
        $c->set($k, $v);
    }

    /**
     * 删除缓存
     * @param <type> $instance
     * @param <type> $k
     */
    public static function del($instance, $k) {
        $c = self::get_instance($instance);
        $c->del($k);
    }
	
	/**
     * 获取缓存实例对象
     * @param string $provider 实例名
     * @return Hi_Cache_Driver_Interface
     */
	public static function instance($provider=''){
		if(!isset(self::$_instance[$provider])) {
			//获取配置
			if (!isset(Hi::$config->Cache[$provider])) {
				 trigger_error("'{$provider}'缓存配置信息不存在",E_USER_ERROR);
			}
			$conf =  Hi::$config->Cache[$provider];
			$driver = ucfirst(isset($conf['driver']) ? $conf['driver'] : 'file');
            $classname = 'Hi_Cache_Driver_'.$driver;
			if (!class_exists($this->_driver)) {
				trigger_error("数据访问配置信息错误:'{$provider}'缓存访问所需驱动'{$this->_driver}'不存在",E_USER_ERROR);
			}
			$instance = new $classname($conf);
			self::$_instance[$provider] = $instance;
		}
		return self::$_instance[$provider];
	}
}
?>
