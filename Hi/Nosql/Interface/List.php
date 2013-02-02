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
 * 链表操作
 * 有序链表
 */
interface Hi_Nosql_Interface_List {

    /**
     * 向list头添加元素
     *
     * @param 键名 $key
     * @param 键值 $value
     * @param 过期时间 $time
     */
    public function list_push($key, $value, $time=0);

    /**
     * 向list尾添加元素
     *
     * @param 键名 $key
     * @param 键值 $value
     * @param 过期时间 $time
     */
    public function list_push($key, $value, $time=0);

    /**
     * 从头部弹出一个元素
     *
     * @param 键名 $key
     */
    public function list_left_pop($key);

    /**
     * 从尾部弹出一个元素
     *
     * @param 键名 $key
     */
    public function list_right_pop($key);

    /**
     * 返回名称为key的元素个数
     *
     * @param 键名 $key
     */
    public function list_size($key);

    /**
     * 返回一定区间内的所有元素 $end 为-1则返回所有 $start 从0开始
     * 从头开始，不删除元素
     *
     * @param 键名 $key
     * @param 开始位置 $start
     * @param 结束位置 $end
     */
    public function list_left_range($key, $start, $end);

    /**
     * 返回一定区间内的所有元素 $end 为-1则返回所有 $start 从0开始
     * 从尾开始，不删除元素
     *
     * @param 键名 $key
     * @param 开始位置 $start
     * @param 结束位置 $end
     */
    public function list_right_range($key, $start, $end);

    /**
     * 从左开始删除值为value的内容
     *
     * @param 键名 $key
     * @param 值 $value
     * @param 删除数量 $num
     */
    public function list_delete($key, $value, $num);
}

?>
