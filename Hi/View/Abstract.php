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
 * Hi_View_Abstrac
 *
 * @author ww
 */
abstract class Hi_View_Abstract {
    /**
     * 模板
     * @var Hi_View_Template
     */
    protected $_template;

    public function Hi_View_Abstract($tpl) {
        $this->_template = $tpl;
    }

    /**
     * Fetch
     */
    public function fetch() {

    }

    /**
     *
     */
    public function assign($data) {

    }
}

?>