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
 * Description of Config
 *
 * @author ww
 */
class Hi_Config extends Hi_Abstract_Iterator {

    /**
     * 配置是否只读
     * @var bool
     */
    private $_read_only = false;
    /**
     * 配置文件路径
     * @var string
     */
    private $_path;
    /**
     * 配置信息池
     * @var mixed
     */
    private $_pool;

    /**
     * 构造函数
     * @param string $path 配置文件夹
     * @param array $data 配置信息
     * @param bool $read_only 只读
     */
    public function __construct($path=APP_CFG, &$data=array(), $read_only=false) {
        $this->_path = $path . SYSTEM_DS;
        $this->_pool = array();
        $this->_read_only = $read_only;
        parent::__construct($data);
    }

    public function __get($k) {
        if (isset($this->_pool[$k])) {
            return $this->_pool[$k];
        }
        $path = $this->_path . $k;
        $config = null;
        if (is_file($path . '.php')) {
            $config = require($path . '.php');
        } elseif (is_file($path . '.ini')) {
            $config = parse_ini_file($path . '.ini', true);
        } elseif (is_dir($path)) {
            $config = new Hi_Config($path, $this->_read_only);
        }
        $this->_pool[$k] = $config;
        return $config;
    }

    public function __set($k, $v) {
        if ($this->_read_only) {
            throw new Hi_Exception('不能更改配置信息', "不能给只读配置变量'{$k}'赋值!");
        } else {
            parent::__set($k, $v);
        }
    }

}

?>
