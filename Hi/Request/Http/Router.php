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
 * 路由器
 *
 * @author ww
 */
class Hi_Request_Http_Router {
    const CONTROLLER_NAME = 'controller';
    const ACTION_NAME = 'action';
    const DO_NAME = 'do';

    private $_uri; //请求Uri
    private $_config;
    private $_route;
    private $_params;
    public $controller;
    public $action;
    public $do;
    public $is_found = false; //是否存在

    public function Hi_Request_Http_Router($uri) {
        $pos = strpos($uri, '?');
        if ($pos !== false)
            $uri = substr($uri, 0, $pos);
        $this->_uri = $uri; //获取请求文件
        $this->_config = $this->default_routes(); //路由规则
    }

    /**
     * 	设置路由
     */
    public function set_route($config=array()) {
        $this->_config = count($config) ? array_merge($config, $this->default_routes()) : $this->default_routes();
    }

    /**
     * 默认参数
     */
    private function default_routes() {
        $config = array();
        $config['/'] = '/:controller/:action';
        $config['/:controller'] = '/:controller';
        $config['/:controller/:action'] = '/:controller/:action';
        $config['/:controller/:action/([\d]+)'] = '/:controller/:action/:id';
        $config['/:controller/:action/([\w]+)'] = '/:controller/:action/:do';
        return $config;
    }

    /**
     * 匹配路由
     */
    public function match() {
        if (count($this->_config)) {
            foreach ($this->_config as $k => $v) {
                $route = new Hi_Request_Http_Route($k, $v);
                if (preg_match('@^' . $route->origin . '$@', $this->_uri, $result)) {
                    $this->_route = $route;
                    $this->parse_url($result);
                    break;
                }
            }
        }
        if (isset($this->_route)) {
            $this->is_found = true;
            $this->controller = $this->_params['controller'];
            $this->action = $this->_params['action'];
            $this->do = $this->_params['do'];
        } else {
            $this->is_found = false;
        }
    }

    public function get_param() {
        return $this->_params;
    }

    /**
     * 解析路由规则
     */
    private function parse_url($match) {
        preg_match_all('@:([\w]+)@', $this->_route->target, $result, PREG_PATTERN_ORDER);
        if (isset($result) && count($result[1])) {
            for ($i = 0; $i < count($result[1]); $i++) {
                if (isset($match[$i + 1]))
                    $this->_params[$result[1][$i]] = $match[$i + 1];
            }
        }
        //采用默认规则 /:controller/:action/:do
        preg_match_all('@/([a-zA-Z0-9_\+\-%]+)@', $this->_route->target, $result, PREG_PATTERN_ORDER);
        if (!isset($this->_params['controller'])) {
            isset($result[1][0]) && ($this->_params['controller'] = $result[1][0]);
            isset($result[1][1]) && !isset($this->_params['action']) && ($this->_params['action'] = $result[1][1]);
            isset($result[1][2]) && !isset($this->_params['do']) && ($this->_params['do'] = $result[1][2]);
        } elseif (!isset($this->_params['action'])) {
            isset($result[1][0]) && ($this->_params['action']= $result[1][0]);
            isset($result[1][1]) && ($this->_params['do'] = $result[1][1]);
        } elseif (!isset($this->_params['do'])) {
            isset($result[1][0]) && ($this->_params['do'] = $result[1][0]);
        }
        //缺省
        if (!isset($this->_params['controller']))
            $this->_params['controller'] = Hi_Request::DEFAULT_CONTROLLER;
        if (!isset($this->_params['action']))
            $this->_params['action'] = Hi_Request::DEFAULT_ACTION;
        if (!isset($this->_params['do']))
            $this->_params['do'] = Hi_Request::DEFAULT_DO;
    }

}

?>
