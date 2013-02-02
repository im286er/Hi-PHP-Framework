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
 * 请求
 *
 * @author ww
 */
class Hi_Request extends Hi_Abstract_Iterator {
    const DEFAULT_CONTROLLER = 'home';
    const DEFAULT_ACTION = 'index';
    const DEFAULT_DO = 'default';
    private $_controller;
    private $_action;
    private $_do;
    /**
     *
     * @var Hi_Request_Interface
     */
    private $_provider;
    private $_params;

    /**
     * 获取控制器
     * @return string
     */
    public function get_cotroller() {
        if (empty($this->_controller)) {
            trigger_error('请求资源不存在或已经被删除',E_USER_ERROR);
        }
        return $this->_controller;
    }

    /**
     * 构造函数
     */
    public function Hi_Request() {
        //实例化对象
        $this->_provider = PHP_SAPI == 'cli' ? new Hi_Request_Cli() : new Hi_Request_Http();
        $this->_controller = $this->_provider->get_controller();
        $this->_action = $this->_provider->get_action();
        $this->_do = $this->_provider->get_do();

        if (empty($this->_action)) {
            $this->_action = Hi_Request::DEFAULT_ACTION;
        }
        if (empty($this->_do)) {
            $this->_do = Hi_Request::DEFAULT_DO;
        }
        parent::__construct($this->_params);
    }

    /**
     * 获取action方法
     * @return string
     */
    public function get_action() {
        return $this->_action;
    }

    /**
     * 获取action方法
     * @return string
     */
    public function get_do() {
        return $this->_do;
    }

    /**
     * 设置控制器
     * @param <string> $v
     */
    public function set_cotroller($v) {
        $this->_controller = $v;
    }

    /**
     * 设置action方法
     * @param string $v 设置
     */
    public function set_action($v) {
        $this->_action = $v;
    }

    /**
     * 获取请求Unix时间戳
     * @return int
     */
    public function dateline() {
        return $this->_provider->dateline();
    }

    public function __call($name, $args) {
        if (method_exists($this->_provider, $name) && is_callable(array($this->_provider, $name))) {
            return call_user_func_array(array($this->_provider, $name), $args);
        } else {
            trigger_error(get_class($this->_provider) . '::' . $name . '()方法不存在或无法调用',E_USER_ERROR);
        }
    }

}

?>
