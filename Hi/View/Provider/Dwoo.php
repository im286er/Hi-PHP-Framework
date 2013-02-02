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
 * Description of FastTemplate
 *
 * @author ww
 */
class Hi_View_Provider_Dwoo extends Hi_View_Abstract {
    private $_tpl;
    private $_path;
    private $_file;
    private $_data = array();

    public function Hi_View_Provider_Dwoo($tpl) {
        parent::Hi_View_Abstract($tpl);
        $this->init();
    }

    private function init() {
        $this->_path = APP_VIEW . $this->_template->skin;
        $this->_file = $this->_template->file;

        if (!is_dir($this->_path)) {
            trigger_error('view path \'' . $path . '\' not exists',E_USER_ERROR);
        }
        if (!file_exists($this->_path . SYSTEM_DS . $this->_file)) {
            trigger_error('view file \'' . $this->_path . SYSTEM_DS . $this->_file . '\' not exists',E_USER_ERROR);
           
        }

        $compile_dir = APP_VAR . 'templates_c';
        $cache_dir = APP_VAR . 'templates_cache';

        spl_autoload_unregister(array('Hi', 'autoload'));
        !class_exists('Dwoo') && require(SYSTEM_ROOT . 'ThirdParty/dwoo/dwooAutoload.php');

        $this->_tpl = new Dwoo($compile_dir, $cache_dir);
        $this->_tpl->setSecurityPolicy();

        //re-register spl
        spl_autoload_register(array('Hi', 'autoload'));
    }

    public function fetch() {
        try {
            $tpl = new Dwoo_Template_File($this->_path . SYSTEM_DS . $this->_file);
            return $this->_tpl->get($tpl, $this->_data);
        } catch (Exception $e) {
            trigger_error('Fetch View Error In View File\'' . $this->file . '\'',E_USER_ERROR);
        }
    }

    public function assign($data) {
        $this->_data = $data;
    }

}

?>
