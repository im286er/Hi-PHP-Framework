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
 *  Cli模式请求类
 *
 * @author ww
 */
final class Hi_Request_Cli implements Hi_Request_Interface {

    private $_controller;
    private $_action;
    private $_do;
    private $_time;
    private $_vars;

    /**
     * 构造函数
     */
    public function Hi_Request_Cli() {
        global $argv;
        $res = array();
        foreach ($argv as $k => $v) {
            $v = trim($v, '-');
            $v_arr = explode('=', $v);
            if (count($v_arr) == 1) {
                $res[$k] = $v;
            } else {
                $res[$v_arr[0]] = $v_arr[1];
            }
        }
        $this->_vars = $res;
        $this->_time = time();
        $this->_controller = isset($this->_vars['c']) ? $this->_vars['c'] : Hi_Request::DEFAULT_CONTROLLER;
        $this->_action = isset($this->_vars['a']) ? $this->_vars['a'] : Hi_Request::DEFAULT_ACTION;
        $this->_do = isset($this->_vars['d']) ? $this->_vars['d'] : Hi_Request::DEFAULT_DO;
    }

    /**
     * 获取action名
     * @return string
     */
    public function get_action() {
        return $this->_action;
    }

    public function get_do() {
        return $this->_do;
    }

    /**
     * 获取cotroller名
     * @return string
     */
    public function get_controller() {
        return $this->_controller;
    }

    public function dateline() {
        return $this->_time;
    }

}

?>
