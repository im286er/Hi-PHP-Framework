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
 * 请求对象接口
 *
 * @author weird
 */
interface  Hi_Request_Interface {

    /**
     * 获取action名
     * @return string
     */
    public function get_action();
    
    /**
     * 获取cotroller名
     * @return string
     */
    public function get_controller();

    public function get_do();

    /**
     * 请求时间
     * @return  timestamp
     */
    public function dateline();
}

?>
