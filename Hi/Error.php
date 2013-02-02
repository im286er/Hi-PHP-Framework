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
 * PHP出错处理
 *
 * @author ww
 */
class Hi_Error {
    /*     * *
     * 出错处理
    */
    public static function handler($errno, $errstr, $errfile, $errline, $errcontext) {
        if($errno == E_USER_ERROR || $errno == E_WARNING) {
            if (!SYSTEM_DEBUG) {
                //生产环境记录日志
                $err = date('[Y-m-d H:i:s]', time()) . "{$errno} {$errline} {$errfile} {$errstr}\r\n";
                Utility_IO::append_file(APP_VAR . 'error.log', $err);
            }
            throw new Hi_Exception('系统异常', $errstr, $errno, $errfile, $errline);
            return true;
        }elseif($errno == E_NOTICE) {
            return false;
        }else {

            return SYSTEM_DEBUG ? false : true;
        }
    }
}
?>
