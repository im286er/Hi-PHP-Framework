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
 * 键值基本操作
 * 单键单值
 */
interface Hi_Nosql_Interface_Key {

    /**
     * key-value模式写入单健值
     *
     * @param 键名 $key
     * @param 键值 $value
     * @param 有效期 $time
     */
    public function set($key, $value, $time=0);

    /**
     * ey-value模式获取单健值
     *
     * @param 键名 $key
     */
    public function get($key);

    /**
     * 删除一个或多个元素
     *
     * @param string/array $key
     */
    public function delete($key);

    /**
     * 设置多值
     *
     * @param key-value数组 $keyArr
     * @param 有效期 $time
     */
    public function set_multi($keyArr, $time=0);

    /**
     * 获取多值
     *
     * @param array $keyArr
     */
    public function get_multi($keyArr);

    /**
     * 对值追加内容
     *
     * @param 键名 $key
     * @param 要追加的值 $value
     */
    public function append($key, $value);

    /**
     * 在获取一个值的同时用另一个新的值覆盖当前key的值
     *
     * @param 键名 $key
     * @param 追加的值 $value
     */
    public function get_set($key, $value, $time=0);
}

?>
