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
 *
 * 内核异常处理基类
 *
 */
class Hi_Exception extends Exception {

    public $detail = '';

    public function Hi_Exception($errorMsg = '', $detail='', $level = 0, $file = '', $line = 0) {
        $this->detail = $detail;
        parent::__construct($errorMsg);
        if ($file != '') {
            $this->file = $file;
        } else {
            $this->file = parent::getFile();
        }
        if (!empty($line)) {
            $this->line = $line;
        }
    }

    public function __toString() {
        $str ='<html xmlns="http://www.w3.org/1999/xhtml">';
        $str .='<head><title>Debug Info</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
        $str .= '<body>';
        $str .= '<div style="background: none repeat scroll 0 0 #FFFFDD; border: 1px solid #E0E0E0; padding: 10px;margin-bottom:20px;">';
        $str .= "<h2>---------- Exception Message ----------</h2>\n";
        $str .= "<b>Message:</b>{$this->message}<br /><br />\n";
        $str .= "<b>Detail:</b> {$this->detail} <br /><br />\n";
        $str .= "<b>File:</b> {$this->file} <br />\n";
        $str .= '</div>';
        $str .= '<div style="background: none repeat scroll 0 0 #FFFFDD; border: 1px solid #E0E0E0; padding: 10px;margin-bottom:20px;">';
        $str .= "<h2>---------- Debug Trace Info ----------</h2>\n";
        $str .= str_replace("\n", '<br />', $this->getTraceAsString());
        $str .= '</div>';
        $str .= $this->debug_code();
        $str .= '</body>';
        $str .= '</html>';
        return $str;
    }

    /**
     * 代码调试
     */
    protected function debug_code() {
        $lines = file($this->file);
        $total = count($lines);
        $start = $this->line > 5 ? ( $this->line - 5 ) : 0;
        $end = $this->line + 7;

        $str = '<div style="background: none repeat scroll 0 0 #FFFFDD; border: 1px solid #E0E0E0; padding: 10px;">';
        $str .= "<h2>---------- Debug Code Info ----------</h2>\n";

        for ($i = $start; $i < $end; $i++) {
            if ($i == $total)
                break;
            $line = str_replace(' ', '&nbsp;', htmlspecialchars($lines[$i]));
            $str .= ( $i == $this->line - 1) ? ('<font color="red">' . ($i + 1) . ' ' . $line . '</font>') : (($i + 1) . ' ' . $line);
            $str .= "<br />\r\n";
        }
        $str .= '</div>';
        return $str;
    }

    /**
     * 调式模式异常异常处理
     * @param Hi_Exception $ex
     */
    public static function handler($ex) {
        if (SYSTEM_DEBUG) {
            echo $ex;
            exit();
        } else {
            $tpl = 'error.html';
            $view = Hi_Registry::get('view');
            if (is_file($view->get_path($tpl))) {
                $view->error = $ex->message;
                $view->detail = $ex->detail;
                $view->file = $ex->file;
                $view->display('error.html');
            } else {
                echo $ex->message;
            }
        }
    }

}

?>
