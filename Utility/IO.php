<?php

!defined('IN_HI') && die('Access Denied!');

/**
 * IO 操作
 */
class Utility_IO {

    /**
     * IO操作
     * @param unknown_type $dir
     * @return unknown
     */
    public static function delDir($dir) {
        $dir = realpath($dir);
        if (!$dir || !is_dir($dir))
            return false;
        $handle = opendir($dir);
        if ($dir[strlen($dir) - 1] != DIRECTORY_SEPARATOR)
            $dir .= DIRECTORY_SEPARATOR;

        while ($file = readdir($handle)) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir . $file) && !is_link($dir . $file))
                    self::delDir($dir . $file);
                else
                    unlink($dir . $file);
            }
        }
        closedir($handle);
        rmdir($dir);
        return !is_file($dir);
    }

    /**
     *
     * @param unknown_type $dir
     * @return unknown
     */
    public static function createDir($dir) {
        if (is_dir($dir))
            return true;
        $dir = dirname($dir . '/a');
        $_dir = str_replace("\\", "/", $dir);
        $dir_arr = explode("/", $_dir);
        $count = count($dir_arr);
        for ($i = count($dir_arr) - 1; $i >= 0; $i--) {
            $_dir = dirname($_dir);
            if (is_dir($_dir)) {
                for ($j = $i; $j < $count; $j++) {
                    $_dir .= "/" . $dir_arr[$j];
                    if (is_dir($_dir))
                        continue;
                    $succ = @mkdir($_dir, 0777);
                    if (!$succ)
                        return false;
                }
                return true;
            }
        }
        return false;
    }

    public static function readFile($file) {
        if (function_exists('file_get_contents')) {
            return file_get_contents($file);
        } else {
            if (!@$fp = fopen($file, 'rb')) {
                return false;
            }
            flock($fp, LOCK_EX);
            $content = @fread($fp, filesize($file));
            flock($fp, LOCK_UN);
            fclose($fp);
            return $content;
        }
    }

    /**
     *
     * @param unknown_type $file_name
     * @param unknown_type $content
     * @return unknown
     */
    public static function write_file($file, $content = '') {
        if (function_exists('file_put_contents')) {
            return file_put_contents($file, $content);
        } else {
            if (!@$fp = fopen($file, 'wb')) {
                return false;
            }
            flock($fp, LOCK_EX);
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
            return true;
        }
    }

    /**
     *
     * @param string $file
     * @param string $content
     * @return bool
     */
    public static function append_file($file, $content = '') {
        if (!$fp = fopen($file, 'ab')) {
            return false;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $content);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }

}

?>