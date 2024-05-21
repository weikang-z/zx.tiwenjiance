<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

use app\command\Createapimodel;
use app\command\Installsql;
use app\command\Run;
use app\command\Swag;
use app\command\Timer;
use app\Test;

return [
    'app\admin\command\Crud',
    'app\admin\command\Menu',
    'app\admin\command\Install',
    'app\admin\command\Min',
    'app\admin\command\Addon',
    'app\admin\command\Api',
    Installsql::class,
    Createapimodel::class,
    Timer::class,
    Swag::class,
    Run::class,
    Test::class
];
