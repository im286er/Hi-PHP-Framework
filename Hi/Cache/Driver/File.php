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
 * 基于IO文件缓存驱动类
 *
 * @author weird
 */
class Hi_Cache_Driver_File implements Hi_Cache_Driver_Interface {

    private $_path;

    public function Hi_Cache_Driver_File($conf) {
        if (isset($conf['path'])) {
            $this->_path = $conf['path'];
        } else {
            trigger_error('File文件缓存配置信息必须提供\'path\'参数',E_USER_ERROR);
        }
    }

    public function fetch($k) {
        $now_sec = Hi_Request_Abstract::instance()->dateline();
        $cache_file = $this->get_file_path($k);
        if (!is_file($cache_file) || (0 == $fl = @filesize($cache_file))) {
            return false;
        } else {
            $content = IO::readFile($cache_file);
            preg_match("/Expire:\s*(\d+)/i", $content, $ex);
            if ($now_sec > $ex[1]) {
                return false;
            } else {
                $content = preg_replace("/<\?php(.*)\?>\r\n/Uis", '', $content);
            }
        }
        return unserialize($content);
    }

    public function store($k, $v, $e) {
        $cache_file = $this->get_file_path($k);
        $this->del($k);
        $v = "<?php\r\n !defined('IN_APP') && die('Access Denied');\r\n //Cache: {$k}\r\n //Created: " . date('Y-m-d H:i', $now_sec) . " \r\n //Expire: " . ($now_sec + $e) . " \r\n?>\r\n" . serialize($v);
        return IO::write_file($cache_file, $v);
    }

    public function del($k) {
        $cache_file = $this->get_file_path($k);
        if (is_file($cache_file)) {
            @unlink($cache_file);
        }
        return true;
    }

    private function get_file_path($k) {
        $hash_key = md5($k);
        $path = $this->_path . SYSTEM_DS . substr($hash_key, 0, 2) . SYSTEM_DS . substr($hash_key, 2, 2) . SYSTEM_DS . substr($hash_key, 4, 2) . SYSTEM_DS;

        IO::createDir($path);
        return $path . 'cache_' . $hash_key . '.php';
    }

}

?>
