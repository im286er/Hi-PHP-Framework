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
 * Description of Smarty
 *
 * @author ww
 */
class Hi_View_Provider_Smarty extends Hi_View_Abstract {

    /**
     *
     * @var Smarty
     */
    private $_tpl;

    public function Hi_View_Provider_Smarty($tpl) {
        parent::Hi_View_Abstract($tpl);
        $this->init();
    }

    private function init() {
        $path = APP_VIEW . $this->_template->skin;
        $file = $this->_template->file;

        if (!is_dir($path)) {
            trigger_error('view path \'' . $path . '\' not exists',E_USER_ERROR);
        }
        if (!is_file($path . SYSTEM_DS . $file)) {
            trigger_error('view file \'' . $path . SYSTEM_DS . $file . '\' not exists',E_USER_ERROR);
        }
        //unregister autoload
        spl_autoload_unregister(array('Hi', 'autoload'));
        !class_exists('Smarty') && require(SYSTEM_ROOT . 'ThirdParty/smarty/Smarty.class.php');

        //Init
        $this->_tpl = new Smarty();
        $this->_tpl->default_modifiers = array('escape:"html"');
        $this->_tpl->template_dir = $path;
        $this->_tpl->compile_dir = APP_VAR . "templates_c";
        $this->_tpl->cache_dir = APP_VAR . "templates_cache";
        $this->_tpl->debugging = SYSTEM_DEBUG;
        //$this->_tpl->compile_dir = APP_VAR;
        //$this->_tpl->cache_dir = APP_VAR;
        $this->_tpl->allow_php_tag = false;
        $this->_tpl->left_delimiter = '<{';
        $this->_tpl->right_delimiter = '}>';
        $this->_tpl->compile_locking = false;
        //unregister Ssmarty autoload
        //spl_autoload_unregister('smartyAutoload');
        //re-register spl
        spl_autoload_register(array('Hi', 'autoload'));
    }

    public function fetch() {
        try {
            return $this->_tpl->fetch($this->_template->file);
        } catch (Exception $e) {
            trigger_error('Fetch View Error In View File\'' . $this->_template->file . '\':'.$e->getMessage(), E_USER_ERROR);
        }
    }

    public function assign($data) {
        $this->_tpl->assign($data);
    }

}

?>
