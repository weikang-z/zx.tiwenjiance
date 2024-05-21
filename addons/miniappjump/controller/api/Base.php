<?php

namespace addons\miniappjump\controller\api;

use app\common\controller\Api;
use think\Config;

class Base extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();

        Config::set('default_return_type', 'json');
    }
}
