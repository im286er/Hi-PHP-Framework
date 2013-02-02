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
 * 视图类
 */
class Hi_View extends Hi_Abstract_Iterator {

    /**
     * 模板文件信息
     * @var Hi_View_Template
     */
    private $_template;
    /*
     * 内容
     * @var string
    */
    public $_connent;
    /**
     * 绑定数据
     * @var Array
     */
    protected $_data = array();

    public function Hi_View() {
        parent::__construct($this->_data);
        $this->_template = new Hi_View_Template();
    }

    /**
     * 设置皮肤
     * @param string $v
     */
    public function set_skin($v) {
        $this->_template->skin = $v;
    }

    /**
     * 设置视图字符集
     * @param string $v
     */
    public function set_charset($v) {
        $this->_template->charset = $v;
    }

    /**
     * 设置视图类型
     * @param <type> $v
     */
    public function set_type($v) {
        $this->_template->type = $v;
    }

    public function clear() {
        $this->_data = array();
    }

    /**
     * 获取当前模板路径
     * @param <type> $file
     * @return <type>
     */
    public function get_path($file) {
        return APP_VIEW . $this->_template->skin . SYSTEM_DS . $file;
    }

    /**
     * 获取视图
     */
    public function fetch($file) {
        $this->_template->file = $file;
        $provider_class = 'Hi_View_Provider_'. ucfirst(APP_VIEW_PROVIDER);
        $provider = new $provider_class($this->_template);
        $provider->assign($this->_data);
        return $provider->fetch();
    }

    /**
     * 输出视图
     */
    public function display($file) {
        if(empty($file)) {
            trigger_error("file empty " ,E_USER_ERROR);
        }
        //视图回发前
        $this->_connent = $this->fetch($file);
        $response = Hi_Registry::get('response');
        $response->set_header('Content-Type', $this->get_content_type($this->_template->type));
        $response->set_status(Hi_Response::HTTP_OK);
        $response->flush($this->_connent);
    }

    /**
     *  获取Header头Content-Type值
     * @param string $type
     */
    private function get_content_type($type) {
        $content_type = '';
        $mimes = array(
                'gif' => 'image/gif',
                'png' => 'image/png',
                'bmp' => 'image/bmp',
                'jpeg' => 'image/jpeg',
                'pjpg' => 'image/pjpg',
                'jpg' => 'image/jpeg',
                'tif' => 'image/tiff',
                'js' => "application/javascript",
                'htm' => 'text/html',
                'css' => 'text/css',
                'html' => 'text/html',
                'shtml' => 'text/html',
                'txt' => 'text/plain',
                'xml' => 'text/xml',
                'xsl' => 'text/xml',
                'gz' => 'application/x-gzip',
                'tgz' => 'application/x-gzip',
                'tar' => 'application/x-tar',
                'zip' => 'application/zip',
                'hqx' => 'application/mac-binhex40',
                'doc' => 'application/msword',
                'pdf' => 'application/pdf',
                'ps' => 'application/postcript',
                'rtf' => 'application/rtf',
                'dvi' => 'application/x-dvi',
                'latex' => 'application/x-latex',
                'swf' => 'application/x-shockwave-flash',
                'tex' => 'application/x-tex',
                'mid' => 'audio/midi',
                'au' => 'audio/basic',
                'mp3' => 'audio/mpeg',
                'ram' => 'audio/x-pn-realaudio',
                'ra' => 'audio/x-realaudio',
                'rm' => 'audio/x-pn-realaudio',
                'wav' => 'audio/x-wav',
                'wma' => 'audio/x-ms-media',
                'wmv' => 'video/x-ms-media',
                'mpg' => 'video/mpeg',
                'mpga' => 'video/mpeg',
                'wrl' => 'model/vrml',
                'mov' => 'video/quicktime',
                'avi' => 'video/x-msvideo'
        );
        $charset_type = array('htm', 'css', 'html', 'shtml', 'txt', 'js', 'rtf', 'xml', 'xsl');
        $content_type = isset($mimes[$type]) ? $mimes[$type] : $mimes['html'];
        if (in_array($type, $charset_type)) {
            $content_type .= ( '; charset=' . $this->_template->charset);
        }
        return $content_type;
    }

}

?>
