<?php

namespace addons\miniappjump\controller\api;

use think\Config;
use think\Exception;

class Home extends Base
{
    protected $noNeedLogin = ['index'];

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $addoncfg = get_addon_config('miniappjump');

        $platform = $this->request->post('platform');

        $platform_array = ['wxapp', 'aliapp', 'baiduapp', 'toutiaoapp', 'qqapp'];

        if (!in_array($platform, $platform_array)) {
            $where['platform'] = 'wxapp';
        } else {
            $where['platform'] = $platform;
        }

        $where['status'] = 'normal';

        $data = [];

        $app = model('addons\miniappjump\model\App')
            ->field('id, platform, createtime, updatetime, status', true)
            ->where($where)->select();

        foreach ($app as $key => &$value) {
            $value['icon'] = cdnurl($value['icon'], true);
            $value['background'] = cdnurl($value['background'], true);
        }

        $data['setting'] = [
            'title' => $addoncfg['title'],
            'template' => $addoncfg['template'],
        ];
        
        $data['app'] = $app;

        $this->success('success', $data);
    }
}
