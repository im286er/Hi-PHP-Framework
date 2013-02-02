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
 * 路由规则
 * 把规则解析成正则表达式
 * @author ww
 */
class Hi_Request_Http_Route {
    public $origin;
    public $target;

    public function  Hi_Request_Http_Route($origin,$target) {
        $this->target = $target;
        $this->origin = $origin;
        $this->set_route($origin, $target);
    }

    /**
     * @param $origin url来源
     * @param $target 路由目标
     */
    public function set_route($origin,$target) {
        $url_regex = preg_replace_callback('@:[\w]+@', array($this, 'regex_callback'), $origin);
        $this->origin = $url_regex.'/?';
        return $this;
    }
    /**
     * 正则回调函数
     */
    private function regex_callback($match) {
        $key = str_replace(':', '', $match[0]);
        if($key == Hi_Request_Http_Router::CONTROLLER_NAME || $key = Hi_Request_Http_Router::ACTION_NAME || $key = Hi_Request_Http_Router::DO_NAME) {
            return '([a-zA-Z]+[a-zA-Z0-9_\+\-%]*)';
        }else {
            return $match[0];
        }
    }
}
?>
