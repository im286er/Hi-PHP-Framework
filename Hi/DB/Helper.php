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
 * 数据访问助手类
 *
 * @author ww
 */
class Hi_DB_Helper {
    /**
     * 实体对象表
     * @var array
     */
    private static $_instance = array();

    /**
     * 获取实体对象
     * @param string $provider 数据访问实体名
     * @return Hi_DB_Provider
     */
    public static function instance($provider='') {
        $provider = strtolower($provider);
        if (!isset(self::$_instance[$provider])) {
            self::$_instance[$provider] = new Hi_DB_Provider($provider);
        }
        return self::$_instance[$provider];
    }

    /**
     * 关闭所有数据访问连接
     */
    public static function close() {
        if (count(self::$_instance)) {
            foreach (self::$_instance as $i) {
                $i->close();
            }
        }
    }
}

?>
