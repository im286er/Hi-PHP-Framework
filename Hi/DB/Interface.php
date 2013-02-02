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
 * 数据库操作类接口
 * @author weird
 */
interface HI_DB_Interface {

    /**
     * 建立连接方法接口
     */
    function connect();

    /**
     * 查询方法接口
     * @param string $sql
     */
    function query($sql);

    /**
     * 获取最后插入数据序号方法接口
     * @param string $d 表名
     * @param string $p 主键
     */
    function get_last_id($d, $p);

    /**
     * 获取数据行方法接口
     */
    function fetch_row($q, $n);

    /**
     * 释放资源方法接口
     */
    function free_result($q);

    /**
     * SQL转义方法接口
     */
    function escape($m, $v);

    /**
     * 关闭方法接口
     */
    function close();

    /*
     * 事务开始
    */
    function begin();

    /**
     * 事务回滚
     */
    function rollback();

    /**
     * 事务提交
     */
    function commit();
}

?>
