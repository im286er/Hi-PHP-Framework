<?php
/**
 * Hi框架入口文件
 */
!defined('IN_HI') && die('Access Denied!');

//检查PHP版本
if (PHP_VERSION < '5.0.0') {
    die('Please use PHP Version >= 5.0! Your PHP version is ' . PHP_VERSION);
}

//Test for PHP bug which breaks PHP 5.0.x on 64-bit...
//As of 1.8 this breaks lots of common operations instead
//of just some rare ones like export.
$borked = str_replace('a', 'b', array(-1 => -1));
if (!isset($borked[-1])) {
    die("PHP 5.0.x is buggy on your 64-bit system; you must upgrade to PHP 5.1.x\n or higher. ABORTING. (http://bugs.php.net/bug.php?id=34879 for details)\n");
}

//6.0.0-dev以上版本已经移除 magic_quotes 和 Register Globals
if (version_compare(PHP_VERSION, '6.0.0-dev', '<')) {
    //ini_set('set_magic_quotes_runtime', 0);
    $is_strip = (ini_get('get_magic_quotes_gpc') ? true : false);

    $input = array('_GET', '_POST', '_COOKIE', '_SERVER', '_SESSION', '_ENV', '_FILE');

    foreach ($input as $varname) {
        if (!isset($$varname) || !is_array($$varname))
            $$varname = array();
        //为安全起见任何时候都不得传递GLOBALS参数
        if (isset(${$varname}['GLOBALS'])) {
            unset(${$varname}['GLOBALS']);
            reset($$varname);
        }
        //反转义
        if ($is_strip) {
            while (list($k, $v) = each($$varname)) {
                if (is_array(${$varname}[$k])) {
                    while (list($k2, $v2) = each(${$varname}[$k])) {
                        ${$varname[$k]}[$k2] = stripslashes($v2);
                    }
                    reset(${$varname}[$k]);
                } else {
                    ${$varname}[$k] = stripslashes($v);
                }
            }
            reset($$varname);
        }
    }
    unset($input, $is_strip, $varname);
}

if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
    register_shutdown_function('session_write_close');
}
//timezone
if (version_compare(PHP_VERSION, '5.1', '>=')) {
    date_default_timezone_set('PRC');
}
define('SYSTEM', "HiPHP");
define('SYSTEM_VAR', "1.0");
define('SYSTEM_DS', DIRECTORY_SEPARATOR);
define('SYSTEM_ROOT', dirname(__FILE__) . SYSTEM_DS); //框架路径
define('APP_ROOT', getcwd() . SYSTEM_DS); //程序路径
/**
 * 入口类
 */
class Hi {
    /**
     * 配置信息
     * @var array
     */
    public static $config;
    /**
     * 对象自动加载
     * @param string $name
     * @return <type>
     */
    public static function autoload($name) {
        if (empty($name)) {
            trigger_error('class or interface name is null');
        }
        $name_arr = explode('_', $name);
        $path = '';
        switch ($name_arr[0]) {
            case 'Hi':
                $path = SYSTEM_ROOT . 'Hi' . SYSTEM_DS;
                break;
            case 'Utility':
                $path = SYSTEM_ROOT . 'Utility' . SYSTEM_DS;
                break;
            default:
                $path = APP_ROOT . $name_arr[0] . SYSTEM_DS;
        }

        array_shift($name_arr);
        $path = $path . implode(SYSTEM_DS, $name_arr) . '.php';
        include($path);
        if (!class_exists($name, false) && !interface_exists($name, false)) {
            trigger_error('Class or interface does not exist in loaded file');
            return false;
        }
        return true;
    }

    /**
     * 初始化
     */
    public static function init($conf) {
        //注册函数到SPL
        spl_autoload_register(array('Hi', 'autoload'));
        //注册错误处理函数
        set_error_handler(array('Hi_Error', 'handler'));
        //注册异常处理函数
        set_exception_handler(array('Hi_Exception', 'handler'));
        //注册退出处理函数
        register_shutdown_function(array('Hi', 'dispose'));
        //载入配置文件
        self::$config = new Hi_Config($conf);
        self::load_conf();
    }

    /**
     * 执行过程
     */
    public static function exec() {
        //初始化请求对象
        $request = new Hi_Request();
        //初始化服务器响应对象
        $response = new Hi_Response();
        $view = new Hi_View();
        //保存到注册表
        Hi_Registry::set('request', $request);
        Hi_Registry::set('response', $response);
        Hi_Registry::set('view', $view);
        $controller = $request->get_cotroller();
        $action = $request->get_action();
        $do = $request->get_do();
        
        $controller = 'Page_' . ucfirst($controller) . '_' . ucfirst($action);
        $action = 'do_' . $do;

        if (!class_exists($controller)) {
            trigger_error("The controller of $controller not exist in the files!",E_USER_ERROR);
        }
        $control = new $controller;
        if (!($control instanceof Hi_Controller)) {
            trigger_error($controller.'不是有效地控制器!',E_USER_ERROR);
        }
        if (!(method_exists($control, $action) || is_callable(array($control, $action)))) {
            trigger_error("The action of $action not exist in  $controller!",E_USER_ERROR);
        }
        //保存控制器到注册表
        Hi_Registry::set('controller', $control);
        try {
            $control->$action();
        } catch (Exception $e) {
            if ($e instanceof Hi_Exception) {
                throw $e;
            } else {
                trigger_error("执行控制器{$controller}::{$action}错误:" . $e->getMessage(),E_USER_ERROR);
            }
        }
    }

    /**
     * 释放资源
     */
    public static function dispose() {
        //关闭数据库联系
        Hi_DB_Helper::close();
        //释放注册表
        Hi_Registry::reset();
        //配置文件
        Hi::$config = null;
    }

    /**
     * 载入配置文件
     */
    public static function load_conf() {
        //调试模式
        defined('SYSTEM_DEBUG') || define('SYSTEM_DEBUG', 1);
        //临时路径
        defined('APP_VAR') || define('APP_VAR', APP_ROOT . 'Var' . SYSTEM_DS);
        //配置路径
        defined('APP_CFG') || define('APP_CFG', APP_ROOT . 'Config' . SYSTEM_DS);
        //视图路径
        defined('APP_VIEW') || define('APP_VIEW', APP_ROOT . 'View' . SYSTEM_DS);
        //视图供给者
        defined('APP_VIEW_PROVIDER') || define('APP_VIEW_PROVIDER','Smarty');
    }

}

?>