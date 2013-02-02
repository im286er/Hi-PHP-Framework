<?php
!defined('IN_HI') && die('Access Denied!');

/**
 * 字符串实用验证类
 */
class Utility_Validity {
    /**
     * 检查URL的合法性, 检测URL头是否为 http, https, ftp
     * @return boolean
     * @param string $str 年份字符串
     */
    public static function url($str) {
        $allow = array('http', 'https', 'ftp');
        $matches = array();
        if (preg_match('!^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?!', $str, $matches)) {
            $scheme = $matches[2];
            if (in_array($scheme, $allow)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 验证Email的合法性
     * @return boolean
     * @param string $str Email字符串
     */
    public static function email($str) {
        return preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.){1,4}[a-z]{2,3}$/', $str) ? true : false;
    }

    /**
     * 验证年份的合法性
     * @return boolean
     * @param string $str 年份字符串4位数字19**-20**
     */
    public static function year($str) {
        if (is_numeric($str)) {
            preg_match('/^19|20[0-9]{2}$/', $str) ? true : false;
        }
        return false;
    }

    /**
     * 验证月份的合法性
     * @return boolean
     * @param string $str 月份字符串
     */
    public static function month($str) {
        if (is_numeric($str) && $str > 0 && $str < 13) {
            return true;
        }
        return false;
    }

    /**
     * 验证日期的合法性
     * @return boolean
     * @param string $str 月份字符串
     */
    public static function day($str) {
        if (is_numeric($str) && $str > 0 && $str < 32) {
            return true;
        }
        return false;
    }

    /**
     * 验证字符串是否包含非法字符
     * @return boolean
     * @param string $str 验证字符串
     * @package boolean $allowSpace 是否允许空格
     */
    public static  function badchar($str, $allowSpace=false) {
        if ($allowSpace) {
            return preg_match ("/[><,.\][{}?\/+=|\\\'\":;~!@#*$%^&()`\t\r\n-]/i", $str) ? true : false;
        }
        else {
            return preg_match ("/[><,.\][{}?\/+=|\\\'\":;~!@#*$%^&()` \t\r\n-]/i", $str) ? true : false;
        }
    }

    /**
     * 验证字符串是否全英文
     * @param $str 检测字符串
     * @return boolean
     */
    public static  function enstring($str) {
        return preg_match ("/^[a-z]+$/i", $str) ? true : false;
    }

    /**
     * 检查字符串数否为单字节
     * @param $str 检测字符串
     * @return boolean
     */
    public static function bytestr($str) {
        return preg_match ("/^[\x00-\xff]+$/i", $str) ? true : false;
    }

    /**
     * 检测是否为中文，不包括半角字符串
     * @param string $str
     * @return boolean
     */
    public static function gbstring($str) {
        return preg_match ("/^[\u4E00-\u9FA5]+$/i", $str) ? true : false;
    }

    /**
     * 是否包含空白、控制字符
     */
    public static function isSpace($str) {
        return preg_match( '/[\s\a\f\n\e\0\r\t\x0B]/is', $str) ? true : false;
    }

    /**
     * 检测是否为手机号
     * @param string $str 字符串
     * @return boolean
     */
    public static function mobile($str) {
        return preg_match ("/^1[358]{1}[0-9]{9}$/i", $str) ? true : false;
    }

    /**
     * 检测是否为QQ号
     * @param string $str 字符串
     * @return boolean
     */
    public static function qq($str) {
        return preg_match ("/^[1-9]{1}[0-9]{4,9}$/i", $str) ? true : false;
    }

    /**
     * 检测是否为固定电话
     * @param string $str 字符串
     * @return boolean
     */
    public static function tel($str) {
        return preg_match ("/^0[1-9]{1}[0-9]{9}$/i", $str) ? true : false;
    }

    /**
     * 检测是否为邮政编码
     * @param string $str 字符串
     * @return boolean
     */
    public static function post($str) {
        return preg_match ("/^[1-9]{1}[0-9]{5}$/i", $str) ? true : false;
    }

    /**
     * 是否是一个合法域名
     * @param string $str 字符串
     * @return boolean
     */
    public static function domain($str) {
        return preg_match("/^[a-z0-9]([a-z0-9-]+\.){1,4}[a-z]{2,5}$/i", $str) ? true : false;
    }

    /**
     * 检测IP地址
     * @param string $ip
     * @return <type>
     */
    public static function ip($str) {
        return preg_match("/^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}$/", $str) ? true : false;
    }

    /**
     * 二代身份证检测
     * @param int 性别[1=>男,2=>女]
     * @param string 生日 如20100101
     */
    public static function id_card($str,$sex = false , $birthday = false) {
        //位数校检
        if(preg_match("/^(\d{17}[\dx])$/i", $str) == 0 ) return false;
        //六位数字地址码 [不进行校检]

        //八位数字出生日期码
        if($birthday !== false ) {
            $day_len = strlen($birthday);
            if(substr($str,6,$day_len) != $birthday) return false;
        }
        //三位数字顺序码[性别校检]
        if($sex !== false ) {
            $sex_v = intval(substr($str,16,1));
            if($sex_v ==0 || $sex_v % 2 == 0 ) {
                //女性
                if($sex != 2 ) return false;
            }else {
                //男性
                if($sex != 1 ) return false;
            }
        }
        //一位校验码校检
        $wi = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);//表示第i位置上的加权因子
        $ai = array(1,0,'X',9,8,7,6,5,4,3 ,2);//第i位置上的身份证号码数字值
        //如果最后一位是X，保证是大写
        $str = strtoupper($str);
        $count = $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $count += $str[$i] * (pow(2,17-$i) % 11);//模运算校检
            $sum += $str[$i] * $wi[$i];//加权求和校检
        }
        $key = $ai[$count%11];
        if($key == $ai[$count%11] &&  $key == substr($str, -1, 1)) {
            return true;
        }else {
            return false;
        }
    }

    public static  function clear_html($html) {
        $search = array ("@<script[^>]*?>.*?</script>@si",
                "@<[\/\!]*?[^<>]*?>@si",
                "@([\r\n])[\s]+@",
                "@&(quot|#34);@i",
                "@&(amp|#38);@i",
                "@&(lt|#60);@i",
                "@&(gt|#62);@i",
                "@&(nbsp|#160);@i",
                "\"",
                "\'",
        );
        $replace = array ("",
                "",
                "\\1",
                "\"",
                "&",
                "<",
                ">",
                " ",
                " ",
                " "
        );
        return preg_replace ($search, $replace, $html);
    }

}
?>
