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
 * 缓存实例抽象类
 * 统一由此提供缓存访问，逐步淘汰在应用程序中、在model中缓存的方法
 * 启用压缩可能会产生序列化两次(压缩前，存入缓存介质前)/取决于存储介质是否支持对象存储
 * @author weird
 * @version 1.1
 * Update 1.1
 * 注册缓存内容到助手缓存池，子类刷新方法不再需要返回缓存内容
 */
abstract class Hi_Cache_Abstract {

    //缓存生命周期
    protected $_expire = 3600;
    //压缩等级0-9 0表示不压缩
    protected $_compress_level = 0;
    //自动更新缓存
    public $auto_refresh = true;
    //缓存供给者
    protected $_instance;
    //实现者标识，不允许子类进行覆盖
    private $_class;
    //缓存池
    private $_pool = array();

    public function Lib_Cache_Abstract() {
        $this->_class = substr(get_class($this), 10);
    }

    /**
     * 获取缓存
     * @param string $k 缓存项
     */
    public function get($k) {
        $cache_key = $this->cache_key($k);
        if (isset($this->_pool[$cache_key])) {
            $data = $this->_pool[$cache_key];
        } else {
            $data = Hi_Cache_Helper::instance($this->_instance)->fetch($cache_key);
            //解压
            if ($this->_compress_level != 0) {
                $data = unserialize(gzinflate($data));
            }
            if ($data === false && $this->auto_refresh) {
                $this->refresh($k);
                //从内存池读取
                $data = $this->_pool[$cache_key];
            }
        }
        return $data;
    }

    /**
     * 获取缓存键名
     * @param <type> $k
     * @return <type>
     */
    protected function cache_key($k) {
        if (is_array($k)) {
            ksort($k);
        } else {
            $k = array('cache_key' => $k);
        }
        return $this->_class . '_' . http_build_query($k);
    }

    /**
     * 设置缓存项
     * @param string $k
     * @param mixed $v
     */
    public function set($k, $v) {
        $cache_key = $this->cache_key($k);
        //保存到内存池
        $this->_pool[$cache_key] = $v;
        if ($this->_compress_level != 0) {
            $v = gzdeflate(serialize($v), $this->_compress_level);
        }
        Hi_Cache_Helper::instance($this->_instance)->store($cache_key, $v, $this->_expire);
    }

    /**
     * 删除缓存
     * @param <type> $k
     */
    public function del($k) {
        $cache_key = $this->cache_key($k);
        unset($this->_pool[$k]);
        Hi_Cache_Helper::instance($this->_instance)->del($cache_key);
    }

    /**
     * 实体类必须实现刷新缓存方法
     */
    abstract public function refresh($k);
}

?>
