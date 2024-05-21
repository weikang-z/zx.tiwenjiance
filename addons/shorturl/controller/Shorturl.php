<?php

namespace addons\shorturl\controller;

use think\addons\Controller;
use addons\shorturl\library\Helper;

class Shorturl extends Controller
{

	protected $model = null;

	public function _initialize()
    {
        parent::_initialize();
        $this->model = model('addons\shorturl\model\Url');
    }

    // 短网址
    public function index()
    {
    	// 配置
        $addoncfg = get_addon_config('shorturl');

    	$hash = $this->request->param('hash');

        if (empty($hash)) {
            return $this->fetch();
        }

        $where['hash'] = $hash;

        $row = $this->model->where($where)
            ->find();

        if (empty($row)) {
            return $this->fetch();
        }

        if ($row->status == 0) {
            return $this->fetch();
        }

        if ($row->expire == 1 && (time() > $row->expiretime)) {
            return $this->fetch();
        }

        // 限制访问
        if ($row->allow_qq != 1 && (Helper::is_qq())) {
    		return $this->fetch();
    	}

    	if ($row->allow_wechat != 1 && Helper::is_weixin()) {
    		return $this->fetch();
    	}

    	if ($row->allow_pc_browser != 1 && !Helper::is_mobile()) {
    		return $this->fetch();
    	}

    	if ($row->allow_mobile_browser != 1 && Helper::is_mobile()) {
    		return $this->fetch();
    	}

        $this->model->where($where)->setInc('views');

    	$this->redirect($row['url'], 302);
    }

}
