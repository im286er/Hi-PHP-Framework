<?php
!defined('IN_HI') && die('Access Denied!');
/**
 * 字符串实用操作类
 */
class Utility_String {
    /**
     * 转换UNICODE编码为UTF8
     * @param string $str
     */
    public static function unicode2utf8($str) {
        $c = '';
        foreach($str as $val) {
            $val = intval(substr($val, 2), 16);
            if ($val < 0x7F) {        // 0000-007F 单字节
                $c .= chr($val);
            } elseif ($val < 0x800) { // 0080-0800 双字节
                $c .= chr(0xC0 | ($val / 64));
                $c .= chr(0x80 | ($val % 64));
            } else {                // 0800-FFFF 三字节
                $c .= chr(0xE0 | (($val / 64) / 64));
                $c .= chr(0x80 | (($val / 64) % 64));
                $c .= chr(0x80 | ($val % 64));
            }
        }
        return $c;
    }

    /**
     * 转换utf8编码为unicode
     */
    public static function utf82unicode($str) {
        preg_match_all("/[\x80-\xff]?./",$str,$result);
        $r='';
        foreach($result[0] as $v) {
            $c = '';
            switch(strlen($v)) {
                case 1:
                    $c = ord($v);
                    break;
                case 2:
                    $n = (ord($c[0]) & 0x3f) << 6;
                    $n += ord($c[1]) & 0x3f;
                    $c = $n;
                    break;
                case 3:
                    $n = (ord($c[0]) & 0x1f) << 12;
                    $n += (ord($c[1]) & 0x3f) << 6;
                    $n += ord($c[2]) & 0x3f;
                    $c = $n;
                    break;
                case 4:
                    $n = (ord($c[0]) & 0x0f) << 18;
                    $n += (ord($c[1]) & 0x3f) << 12;
                    $n += (ord($c[2]) & 0x3f) << 6;
                    $n += ord($c[3]) & 0x3f;
                    $c = $n;
            }
            $r .= '&#'.$r.';';
        }
        return $r;
    }
    public static function str2unicode($str,$charset='GBK') {
        preg_match_all("/[\x80-\xff]?./",$str,$ar);
        $r='';
        foreach($ar[0] as $v) $r.="&#".self::utf8_unicode(iconv($charset,"UTF-8",$v)).";";
        return $r;
    }
    private static function utf8_unicode($c) {
        switch(strlen($c)) {
            case 1:
                return ord($c);
            case 2:
                $n = (ord($c[0]) & 0x3f) << 6;
                $n += ord($c[1]) & 0x3f;
                return $n;
            case 3:
                $n = (ord($c[0]) & 0x1f) << 12;
                $n += (ord($c[1]) & 0x3f) << 6;
                $n += ord($c[2]) & 0x3f;
                return $n;
            case 4:
                $n = (ord($c[0]) & 0x0f) << 18;
                $n += (ord($c[1]) & 0x3f) << 12;
                $n += (ord($c[2]) & 0x3f) << 6;
                $n += ord($c[3]) & 0x3f;
                return $n;
        }
    }
    /**
     * 获取中文首字母
     */
    public static function first_letter($input) {
        $dict = array(
                'A' => 0XB0C4, 'B' => 0XB2C0, 'C' => 0XB4ED, 'D' => 0XB6E9, 'E' => 0XB7A1,
                'F' => 0XB8C0, 'G' => 0XB9FD, 'H' => 0XBBF6, 'J' => 0XBFA5, 'K' => 0XC0AB,
                'L' => 0XC2E7, 'M' => 0XC4C2, 'N' => 0XC5B5, 'O' => 0XC5BD, 'P' => 0XC6D9,
                'Q' => 0XC8BA, 'R' => 0XC8F5, 'S' => 0XCBF9, 'T' => 0XCDD9, 'W' => 0XCEF3,
                'X' => 0XD188, 'Y' => 0XD4D0, 'Z' => 0XD7F9,
        );
        $str_1 = substr($input, 0, 1);
        if ($str_1 >= chr(0x81) && $str_1 <= chr(0xfe)) {
            $num = hexdec(bin2hex(substr($input, 0, 2)));
            foreach ($dict as $k => $v) {
                if($v>=$num)
                    break;
            }
            return $k;
        } else {
            return strtoupper($str_1);
        }
    }

    /*
    *将一个字串中含有全角或半角的数字字符、字母、空格或'%+-()'字符互换
    *
    *@static
    *@access  public
    * @param   string       $str         待转换字串
    *@param   boolean      $reverse     默认true为全角转半角, false为半角转全角
    *@return  string       $str         处理后字串
    */
    public static function convert2semi($str, $reverse = true) {
        $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
                '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
                'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
                'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
                'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
                'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
                'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
                'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
                'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
                'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
                'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
                'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
                'ｙ' => 'y', 'ｚ' => 'z',
                '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
                '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
                '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
                '》' => '>',
                '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
                '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
                '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
                '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
                '　' => ' ');
        if (false === $reverse) {
            $arr = array_flip($arr);
        }
        return strtr($str, $arr);
    }

    /**
     *
     * @param <type> $str
     * @param <type> $len
     * @param <type> $charset
     * @return <type> 截断字符串
     */
    public static function substring($str, $len, $charset = 'gbk') {
        if (empty($str)) {
            return false;
        }
        if ($len >= strlen($str) || $len < 1) {
            return $str;
        }

        $str = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;','&nbsp;'), array('&', '"', '<', '>','`'), $str);

        $strcut = array();
        $temp_str = '';
        $sublen = (strtolower($charset) == 'utf-8') ? 3 : 2;
        for ($i = 0; $i < $len; ++ $i) {
            $temp_str = substr($str, 0, 1);

            if (ord($temp_str) > 127) {
                ++ $i;
                if ($sublen == 3) {
                    ++ $i;
                }
                if($i < $len) {
                    $strcut[] = substr($str, 0, $sublen);
                    $str = substr($str, $sublen);
                }
            }
            else {
                if ($i < $len) {
                    $strcut[] = substr($str, 0, 1);
                    $str = substr($str, 1);
                }
            }
        }
        if (!empty($strcut)) {
            $strcut = join($strcut);
            $strcut = str_replace(array('&', '"', '<', '>', '`'), array('&amp;', '&quot;', '&lt;', '&gt;', '&nbsp;'), $strcut);

            return $strcut;
        }
        else {
            return '';
        }
    }

    /**
     * Mcrypt加密方法
     * @param string $str 待加密字符串
     * @param string $key  密匙
     * @param string $type 加密算法
     */
    public static function mcrypt_encrypt($str,$key,$type = MCRYPT_3DES) {
        $iv=mcrypt_create_iv(mcrypt_get_iv_size($type,MCRYPT_MODE_ECB), MCRYPT_RAND);
        $enstr = mcrypt_encrypt($type, $key, $str, MCRYPT_MODE_ECB, $iv);
        return bin2hex($enstr);
    }
    /**
     * Mcrypt解密方法
     * @param string $str 待解密字符串
     * @param string $key  密匙
     * @param string $type 加密算法
     * @return string
     */
    public static function mcrypt_decrypt($str,$key,$type = MCRYPT_3DES) {
        $iv=mcrypt_create_iv(mcrypt_get_iv_size($type,MCRYPT_MODE_ECB), MCRYPT_RAND);
        $str=pack("H*",$str);
        $destr = mcrypt_decrypt($type, $key, $str, MCRYPT_MODE_ECB, $iv);
        return $destr;
    }

    /**
     * 过滤字符串中的特殊字符
     * @return string
     * @param string $str 需要过滤的字符
     * @param string $filtStr 需要过滤字符的数组（下标为需要过滤的字符, 值为过滤后的字符）
     * @param boolen $regexp 是否进行正则表达试进行替换, 默认false
     */
    public static function filt_string($str, $filtStr, $regexp=false) {
        if (!is_array($filtStr)) {
            return $str;
        }
        $search  = array_keys($filtStr);
        $replace = array_values($filtStr);

        if ($regexp) {
            return preg_replace($search, $replace, $str);
        }
        else {
            return str_replace($search, $replace, $str);
        }
    }

    /**
     * 隐藏IP后几位
     * @param string $ip
     * @return boolean
     */
    public  static function hidden_ip($ip,$bit) {
        $arr = explode('.',$ip);
        switch ($bit) {
            case 1:
                return $arr[0].'.'.$arr[1].'.'.$arr[2].'.*';
                break;
            case 2:
                return $arr[0].'.'.$arr[1].'.*.*';
                break;
            case 3:
                return $arr[0].'.*.*.*';
                break;
            default:
                return '*.*.*.*';
                break;
        }
    }

    /**
     * IP地址转化为long
     * @param string $ip Ip地址
     * @return long
     */
    public static function ip2long($ip) {
        $long =sprintf("%u",ip2long($ip));
        return $long;
    }

    /**
     * 对变量进行字符集转换
     * @param mixed $value 转换
     * @param string $in_charset 源字符集
     * @param string $out_charset 目标字符集
     */
    public static function convert_mixed_charset($value,$in_charset,$out_charset) {
        $result = array();
        foreach ($value as $k=>$v) {
            $type = gettype($v);
            if($type == 'array') {
                $result[$k] = String::convert_mixed_charset($v,$in_charset,$out_charset);
            }elseif($type == 'object') {
                $result[$k] = String::convert_mixed_charset(get_object_vars($v),$in_charset,$out_charset);
            }elseif($type == 'string') {
                $result[$k] = iconv($in_charset, $out_charset, $v);
            }else {
                $result[$k] = $v;
            }
        }
        return $result;
    }
    /**
     * 对变量进行 JSON 编码
     * @param mixed $value 待编码对象
     * @return string
     */
    public static function json_encode($value,$in_charset='utf-8') {
        if($in_charset != 'utf-8') {
            $value = String::convert_mixed_charset($value,$in_charse,'utf-8');
        }
        if (function_exists('json_encode')) {
            return json_encode($value);
        }else {
            if (is_null($value)) return 'null';
            if ($value === false) return 'false';
            if ($value === true) return 'true';
            if (is_scalar($value)) {
                if (is_float($value)) {
                    // Always use "." for floats.
                    return floatval(str_replace(",", ".", strval($value)));
                }

                if (is_string($value)) {
                    static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                    return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $value) . '"';
                }
                else
                    return $value;
            }
            $isList = true;
            for ($i = 0, reset($value); $i < count($value); $i++, next($value)) {
                if (key($value) !== $i) {
                    $isList = false;
                    break;
                }
            }
            $result = array();
            if ($isList) {
                foreach ($value as $v) $result[] = json_encode($v);
                return '[' . join(',', $result) . ']';
            }
            else {
                foreach ($value as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
                return '{' . join(',', $result) . '}';
            }
        }
    }

    public  static function clear_html($content) {
        $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记
                "'([\r\n])[\s]+'",                 // 去掉空白字符
                "'&(quot|#34);'i",                 // 替换 HTML 实体
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(\d+);'e");                    // 作为 PHP 代码运行

        $replace = array ("",
                "",
                "\\1",
                "\"",
                "&",
                "<",
                ">",
                " ",
                chr(161),
                chr(162),
                chr(163),
                chr(169),
                "chr(\\1)");

        $text = preg_replace ($search, $replace, $content);
        return $text;
    }

    /**
     * 数组格式化
     * @param <type> $array
     * @param <type> $level
     * @return <type>
     */
    public static function array2string($array, $level = 0) {
        $space = '';
        for($i = 0; $i <= $level; $i++) {
            $space .= "\t";
        }
        $evaluate = "array(\n";
        $comma = $space;
        foreach($array as $key => $val) {
            $key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
            $val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
            if(is_array($val)) {
                $evaluate .= "$comma$key => ".String::array2string($val, $level + 1);
            } else {
                $evaluate .= "$comma$key => $val";
            }
            $comma = ",\n$space";
        }
        $evaluate .= "\n$space)";
        return $evaluate;
    }
}
?>
