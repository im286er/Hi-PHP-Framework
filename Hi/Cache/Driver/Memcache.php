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
 * 基于Memcached缓存驱动类
 *
 * @author weird
 */
class Hi_Cache_Driver_Memcached implements Hi_Cache_Driver_Interface {

    /**
     * mc
     * @var Memcache
     */
    private $_mc;

    public function Hi_Cache_Driver_Memcached($config) {
        $mc = new Memcache();
        if (!isset($config['host'])) {
            trigger_error('Memcached缓存配置信息数组必须提供\'host\'参数',E_USER_ERROR);
        }
        if (is_array($config['host'])) {
            if (0 == count($config)) {
                trigger_error('Memcached缓存配置信息数组\'host\'参数不能为空',E_USER_ERROR);
            }
            foreach ($config['host'] as $h) {
                list($hostname, $port) = explode(':', $h);
                $mc->addserver($hostname, $port);
            }
        } else {
            list($hostname, $port) = explode(':', $config['host']);
            $mc->addserver($hostname, $port);
        }
        $this->_mc = $mc;
    }

    public function fetch($k) {
        $content = $this->_mc->get($k);
        return $content === false ? '' : unserialize($content);
    }

    public function store($k, $v, $e) {
        return $this->_mc->add($k, serialize($v), false, $e);
    }

    public function del($k) {
        $this->_mc->delete($k);
    }

    public function __destruct() {
        $this->_mc->close();
        unset($this->_mc);
    }

}

?>
