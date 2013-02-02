<?php

!defined('IN_HI') && die('Access Denied!');

/**
 * 前端优化类
 *
 * @author ww
 */
class Utility_Optimiz {

    /**
     * 压缩Css文件
     * @return string
     * @example css_compress('/css/style1.css','/css/style2.css')
     */
    public static function css_compress() {
        require_once SYSTEM_ROOT . 'ThirdParty/yui/CssCompressor.php';
        $args = func_get_args();
        $css = '';
        foreach ($args as $file) {
            $css .= Utility_IO::readFile($file);
            $css .="\r\n";
        }
        $cc = new Minify_YUI_CssCompressor();
        $css = $cc->compress($css);
        return $css;
    }

    /**
     * 压缩Javasctit文件
     * @return string
     * @example css_compress('/javascript/js1.js','/javascript/js2.js')
     */
    public static function js_compress() {
        require_once SYSTEM_ROOT . 'ThirdParty/JsMin/JsMin.php';
        $args = func_get_args();
        $js = '';
        foreach ($args as $file) {
            $js .= Utility_IO::readFile($file);
            $js .="\r\n";
        }
        $js = JSMin::minify($js);
        return $js;
    }

}

?>
