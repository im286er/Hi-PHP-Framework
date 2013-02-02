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
 * Http请求类
 *
 * @author ww
 */
final class Hi_Request_Http implements Hi_Request_Interface {

    private $_controller;
    private $_action;
    private $_do;

    public function Hi_Request_Http() {
        if (!isset($this->_controller) && !isset($this->_action)) {
            if (isset($_GET['controller'])) {
                $this->_controller = $_GET['controller'];
                if (isset($_GET['action'])) {
                    $this->_action = $_GET['action'];
                } else {
                    $this->action = Hi_Request::DEFAULT_ACTION;
                }
                if (isset($_GET['do'])) {
                    $this->_do = $_GET['do'];
                } else {
                    $this->_do = Hi_Request::DEFAULT_DO;
                }
            } else {
                $r = $this->rouler();
                if ($r->is_found) {
                    $this->_controller = $r->controller;
                    $this->_action = $r->action;
                    $this->_do = $r->do;
                    $_GET = array_merge($_GET, $r->get_param());
                }
            }
        }
    }

    /**
     * 获取action名
     * @return string
     */
    public function get_action() {
        return $this->_action;
    }

    /**
     * 获取cotroller名
     * @return string
     */
    public function get_controller() {
        return $this->_controller;
    }

    public function get_do() {
        return $this->_do;
    }

    private function rouler() {
        $r = new Hi_Request_Http_Router($this->get_uri());
        $r->set_route(Hi::$config->Route);
        $r->match();
        return $r;
    }

    /**
     * 请求方法
     * @return string
     */
    public function get_method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /*
     * 请求端口
     *  @return int
    */

    public function get_port() {
        return $_SERVER['REMOTE_PORT'];
    }

    /**
     * Http协议版本
     *  @return string
     */
    public function get_protocol() {
        return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
    }

    /**
     * 查询字符串
     * */
    public function get_querys() {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * 请求来源
     */
    public function get_referer() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

    /**
     * 获取请求主机头
     */
    public function get_host() {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    }

    /**
     * 用户代理信息
     */
    public function get_user_agen() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * 请求语言
     */
    public function get_language() {
        return isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
    }

    public function is_amf() {
        return (isset($_SERVER['CONTENT_TYPE']) ? ($_SERVER['CONTENT_TYPE'] == 'application/x-amf') : false);
    }

    /**
     * 压缩方法
     * */
    public function get_encoding() {
        return isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';
    }

    /**
     * 字符集
     */
    public function get_charset() {
        return isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : '';
    }

    public function is_ssl() {
        return!empty($_SERVER['HTTPS']);
    }

    /**
     * 是否POST提交
     * */
    public function is_post() {
        return ('POST' == $this->get_method());
    }

    /**
     * 是否Ajax请求
     * */
    public function is_ajax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public function get_uri() {
        return $_SERVER['REQUEST_URI'];
    }

    private function _get_ip() {
        $client_ip = '';
        if (getenv('HTTP_X_FORWARDED_FOR') != '') {
            $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR);

            $entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
            reset($entries);
            while (list(, $entry) = each($entries)) {
                $entry = trim($entry);
                if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
                    $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                    if ($client_ip != $found_ip) {
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
        } else {
            $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR);
        }

        return $client_ip;
    }

    /*
     * 获取客户端ip
    */

    public function get_ip() {
        return $this->_get_ip();
    }

    /**
     * 请求时间
     */
    public function dateline() {
        if (version_compare(PHP_VERSION, '5.1.0', '<')) {
            return time();
        } else {
            return $_SERVER['REQUEST_TIME'];
        }
    }

}

?>
