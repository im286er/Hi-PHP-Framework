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
 * 
 *
 * @author ww
 */
class Hi_Response {
    const HTTP_VERSION = 'HTTP/1.0';
    const HTTP_OK = '200 OK';
    const HTTP_MOVED_PERMANENTLY = '301 Moved Permanently';
    const HTTP_MOVED_TEMPORARILY = '302 Moved Temporarily';
    const HTTP_BAD_REQUEST = '400 Bad Request';
    const HTTP_UNAUTHORIZED = '401 Unauthorized';
    const HTTP_PAYMENT_REQUIRED = '402 Payment Required';
    const HTTP_FORBIDDEN = '403 Forbidden';
    const HTTP_NOT_FOUND = '404 Not Found';
    const HTTP_REQUEST_TIME_OUT = '408 Request Time Out';
    const HTTP_NOT_IMPLEMENTED = '501 Not Implemented';
    const HTTP_BAD_GATEWAY = '502 Bad Gateway';
    const HTTP_SERVICE_UNAVAILABLE = '503 Service Unavailable';
    const HTTP_GATEWAY_TIME_OUT = '504 Gateway Time Out';

    private $_header;
    private $_status;

    /**
     * 构造函数
     */
    public function Hi_Response() {
        $this->_header = array();
        $this->_status = self::HTTP_VERSION . ' ' . self::HTTP_OK;
    }

    public function set_status($code) {
        $this->_status = self::HTTP_VERSION . ' ' . $code;
        header($this->_status);
    }

    public function set_header($header, $value) {
        if (isset($this->_header[$header])) {
            $this->_header[$header] .= ';' . $value;
        } else {
            $this->_header[$header] = $value;
        }
    }

    public function flush($html='') {
        if (function_exists('ob_gzhandler')) {
            // ob_start('ob_gzhandler');
        }
        if (!headers_sent()) {
            foreach ($this->_header as $k => $v) {
                header($k . ': ' . $v);
            }
        }
        if ($html) {
            echo $html;
        }
        //ob_flush();
        return;
    }

}

?>
