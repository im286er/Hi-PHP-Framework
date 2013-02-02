<?php

!defined('IN_HI') && die('Access Denied!');

/**
 * 缓存过期时间
 * 主要为增强代码可读性
 */
class Hi_Cache_Expire {
    /**
     * 一刻钟
     */
    const QUARTER_HOUR = 900;
    /**
     * 半个小时
     */
    const HALF_HOUR = 1800;
    /**
     * 一小时
     */
    const ONE_HOUR = 3600;
    /**
     * 两个小时
     */
    const TWO_HOUR = 7200;
    /**
     * 4个小时
     */
    const FOUR_HOUR = 14400;
    /**
     * 半天/12小时
     */
    const HALF_DAY = 43200;
    /**
     * 一天
     */
    const ONE_DAY = 86400;
    /**
     * 一周
     */
    const ONE_WEEK = 604800;
}

?>
