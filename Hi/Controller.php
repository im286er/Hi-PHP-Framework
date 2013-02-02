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
 * 控制器抽象类
 */
abstract class Hi_Controller {
    /**
     * Request 请求对象
     * @var Hi_Request_Http
     */
    public $request;
    /**
     * Response 服务器回发对象
     * @var Hi_Response
     */
    public $response;
    /**
     * 视图
     * @var Hi_View
     */
    public $view;

    public function Hi_Controller() {
        $this->request = Hi_Registry::get('request');
        $this->response = Hi_Registry::get('response');
        $this->view = Hi_Registry::get('view');
        $this->view->this = $this;
    }
}

?>
