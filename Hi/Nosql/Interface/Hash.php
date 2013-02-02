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
 * Hash操作
 *
 */
interface Hi_Nosql_Interface_Hash {

    /**
     * hash存入
     *
     * @param 键名 $key
     * @param 子键名 $subKey
     * @param 值 $value
     */
    public function hash_set($key, $subKey, $value, $time=0);

    /**
     * 获取hash值
     *
     * @param 键名 $key
     * @param 子键名 $subKey
     */
    public function hash_get($key, $subKey);

    /**
     * 返回名称为h的hash中元素个数
     *
     * @param 键名 $key
     * @return number
     */
    public function hash_size($key);

    /**
     * 删除某个二级键值
     *
     * @param 主键 $key
     * @param 二级键 $subKey
     * @return unknown
     */
    public function hash_delete($key, $subKey);

    /**
     * 返回名称为key的hash中所有键
     *
     * @param 值 $value
     * @return array
     */
    public function hhash_keys($value);

    /**
     * 返回名称为key的hash中所有键对应的value
     *
     * @param 键名 $key
     */
    public function hash_vals($key);

    /**
     * 获取某个主键下的所有hash值
     *
     * @param 主键 $key
     * @return unknown
     */
    public function hash_get_all($key);

    /**
     * 检测一个值是否存在
     *
     * @param 键名 $key
     * @return bool
     */
    public function hash_exists($key, $item);

    /**
     * 给某个键下的某个子键+n
     *
     * @param 主key $key
     * @param 子key $item
     * @param 增的步长 $step
     * @return bool
     */
    public function hash_incr_by($key, $item, $step);

    /**
     * hash多值写入
     *
     * @param 键名 $key
     * @param 子键=>值（数组） $valueArr
     * @param 过期时间 $time
     * @return bool
     */
    public function hash_multi_set($key, $valueArr=array(), $time=0);

    /**
     * hash多值写入
     *
     * @param 键名 $key
     * @param 子键数组 $valueArr
     * @return bool
     */
    public function hash_multi_get($key, $keyArr=array());
}

?>