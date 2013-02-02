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
 * 缓存接口类
 * @author ww
 */
interface Hi_Cache_Driver_Interface {

    /**
     * 获取缓存
     * @param string $k 缓存名
     */
    public function fetch($k);

    /**
     * 建立缓存
     * @param string $k 缓存名
     * @param mixed $v 缓存内容
     * @param int $e 缓存有效期(单位s)
     */
    public function store($k, $v, $e);

    /**
     * 根据缓存名删除缓存
     * @param string $k 缓存名
     */
    public function del($k);
}

?>
