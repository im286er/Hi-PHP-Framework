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
 * 队列操作
 * 有序双向链表，一键多值
 */
interface Hi_Nosql_Interface_Queue {

    /**
     * 向队列尾添加元素
     *
     * @param 键名 $k
     * @param 键值 $v
     */
    public function queue_push($k, $v);

    /**
     * 从队列头部弹出一个元素
     *
     * @param 键名 $k
     */
    public function queue_pop($k);
}

?>
