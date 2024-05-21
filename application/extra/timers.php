<?php
// 
// @FileName: buildadmin.${DIR_PATH}
// @Description: ${NAME}
// @Author: ekr123 / zwk480314826@163.com
// @Copyright: © 2023
// @Version: V1.0.0
// @Created: 2023/7/15
//


return [
    //    [
    //        'status' => true, // 是否开启
    //        'name' => '', // 任务名称
    //        'class' => \app\timer\Dump::class, // 对象的类名
    //        'method' => '', // 对象的方法名
    //        'time' => 1, // 间隔时间 单位秒
    //        'uses' => 1 // 进程数量
    //    ]
    [
        'status' => true,
        'name' => '测试输出',
        'class' => app\api\timer\Dump::class,
        'method' => 'run',
        'time' => 1,
        'uses' => 1
    ]
];
