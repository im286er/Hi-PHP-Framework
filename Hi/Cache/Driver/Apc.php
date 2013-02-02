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
 * 基于APC缓存驱动
 */
class Hi_Cache_Driver_Apc implements Hi_Cache_Driver_Interface {

    public function Hi_Cache_Driver_Apc() {
        
    }

    public function fetch($k) {
        $now_sec = Hi_Request_Abstract::instance()->dateline();
        if (false !== ($content = @apc_fetch($k))) {
            if ($now_sec > substr($content, 0, 10)) {
                return false;
            } else {
                return substr($content, 10);
            }
        } else {
            return false;
        }
    }

    public function store($k, $v, $e) {
        $now_sec = Hi_Request_Abstract::instance()->dateline();
        $v = ($now_sec + $e) . $v;
        return @apc_store($k, $v);
    }

    public function del($k) {
        return @apc_delete($k);
    }

}

?>
