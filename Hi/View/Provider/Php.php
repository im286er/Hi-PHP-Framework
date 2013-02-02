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
 * Php视图解析
 *
 * @author ww
 */
class Hi_View_Provider_Php extends Hi_View_Abstract {
    private $_data;
    public function Hi_View_Provider_Php($tpl) {
        parent::Hi_View_Abstract($tpl);
    }

    public function fetch() {
        $file = APP_VIEW . $this->_template->skin.SYSTEM_DS.$this->_template->file;
        if(!is_file($file)) {
            trigger_error('view path \'' . $file. '\' not exists',E_USER_ERROR);
        }
        empty($this->_data) || extract($this->_data);
        ob_start();
        include($file);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function assign($data) {
        $this->_data = $data;
    }
}

