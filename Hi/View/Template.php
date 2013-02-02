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
 * 皮肤模板实体类
 *
 * @author ww
 */
class Hi_View_Template {

    /**
     * 视图文件夹路径
     * @var string
     */
    public $skin = 'default';
    /**
     * 视图文件名
     * @var string
     */
    public $file = '';
    /**
     * 视图文件编码
     * @var <type>
     */
    public $charset = 'utf-8';
    /**
     * 视图文件类型
     * @var string
     */
    public $type = 'html';
}

?>
