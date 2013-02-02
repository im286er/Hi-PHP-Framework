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
 * 基于eaccelerator缓存驱动
 */
class Hi_Cache_Driver_Eaccelerator implements Hi_Cache_Driver_Interface {

    public function Hi_Cache_Driver_Eaccelerator() {
        
    }

    public function fetch($k) {
        $r = eaccelerator_get($k);
        return $r == NULL ? false : $r;
    }

    public function store($k, $v, $e) {
        return eaccelerator_put($k, $v, $e);
    }

    public function del($k) {
        return eaccelerator_rm($k);
    }

}

?>
