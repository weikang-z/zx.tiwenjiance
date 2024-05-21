<?php

/**
 * 消息队列配置
 * 内置驱动：redis、database、topthink、sync
 */

use think\Env;
require_once ROOT_PATH.'const.php';
return [
    //sync驱动表示取消消息队列还原为同步执行
    // 'connector' => 'Sync',

    //Redis驱动
    'connector' => 'redis',
    "expire" => null, //任务过期时间默认为秒，禁用为null
    "default" => "default", //默认队列名称
    "host" => Redis_Host, //Redis主机IP地址
    "port" => Redis_Port, //Redis端口
    "password" => Redis_Auth, //Redis密码
    "select" => 1, //Redis数据库索引
    "timeout" => 0, //Redis连接超时时间
    "persistent" => false, //是否长连接

];
