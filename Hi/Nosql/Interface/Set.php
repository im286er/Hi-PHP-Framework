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
 * 集合操作
 * 集合体，会有排重机制，一样的数据只有一份，并且集合内的数据是无序的,一键多值
 */
interface Hi_Nosql_Interface_Set {

    /**
     * 增加集合元素
     * @param $k 键名
     * @param $v 键值
     * @param $e 过期时间
     */
    public function set_add($k, $v, $e=0);

    public function set_delete($k, $v);

    public function set_move($fk, $tk, $v);

    public function set_size($k);

    /**
     * 元素是否属于某个key
     *
     * @param 键名 $k
     * @param 元素 $v
     */
    public function set_is_member($k, $v);

    /**
     * 求交集
     *
     * @param key集合 $keyArr
     */
    public function set_inter($keyArr = array());

    /**
     * 求并集
     *
     * @param key集合 $keyArr
     */
    public function set_union($keyArr = array());

    /**
     * 求差集
     *
     * @param key集合 $keyArr
     */
    public function set_diff($keyArr = array());

    /**
     * 获取当前key下的所有元素
     *
     * @param key集合 $k
     */
    public function set_members($k);
}

?>
