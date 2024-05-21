<?php
namespace app\admin\controller\shorturl;

use app\common\controller\Backend;

/**
 * 短网址管理
 */
class Url extends Backend {

    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = new \app\admin\model\shorturl\Url;
    }

    /**
     * 检测网址是否可以缩短
     * @internal
     */
    public function check_url_available() {
        $url = $this->request->request('url');
        $domain = $this->request->domain();

        if (strpos($url, $domain) !== false) {
            $this->error('跳转网址无法被缩短！');
        }
        $this->success();
    }

}
