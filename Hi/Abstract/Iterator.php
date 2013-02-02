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
 * Hi_Iterator
 *
 * @author weird
 */
abstract class Hi_Abstract_Iterator implements Iterator {
    /**
     *
     * @var array
     */
    protected $_data;
    /**
     *  current key
     * @var string
     */
    protected $_k;
    protected $_keys = array();
    protected $_count;

    public function __construct(&$data=array()) {
        $this->_k = false;
        $this->_data = array();
        if (is_array($data)) {
            if (count($data)) {
                foreach ($data as $k => $v) {
                    if (is_array($v)) {
                        $class = get_class($this);
                        $v = new $class($v);
                    }
                    $this->_data[$k] = $v;
                    $this->_keys[] = $k;
                }
            }
        } else {
            $this->_data[] = $data;
            $this->_keys[] = 0;
        }
        $this->_count = count($this->_data);
    }

    public function current() {
        return $this->_data[$this->_k];
    }

    public function next() {
        return $this->_k = next($this->_keys);
    }

    public function key() {
        return $this->_k;
    }

    public function valid() {
        return $this->_k !== false;
    }

    public function rewind() {
        $this->_k = reset($this->_keys);
    }

    public function __get($k) {
        return $this->get($k);
    }

    public function __set($key, $value) {
        $this->_data[$key] = $value;
        $this->_keys[] = $key;
        $this->_count += count($value);
    }

    public function __isset($k) {
        return isset($this->_data[$k]);
    }
}

?>
