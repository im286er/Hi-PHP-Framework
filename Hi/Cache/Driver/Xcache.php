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
 * 基于XCache缓存驱动类
 *
 * @author weird
 */
class Hi_Cache_Driver_Xcache implements Hi_Cache_Driver_Interface {

    public function Hi_Cache_Driver_Xcache() {
        
    }

    public function fetch($k) {
        if (!xcache_isset($k)) {
            return xcache_get($k);
        } else {
            return false;
        }
    }

    public function store($k, $v, $e) {
        xcache_set($k, $v, $e);
    }

    public function del($k) {
        xcache_unset($k);
    }

}

?>
