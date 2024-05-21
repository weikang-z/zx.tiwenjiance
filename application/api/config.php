<?php


//配置文件
return [
    'error_message' => '发生错误',
    // 显示错误信息
    'show_error_msg' => true,
    'controller_auto_search' => TRUE,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => function (Exception $e) {
        return json([
            'code' => 500,
            'msg' => '程序异常 -> ' . $e->getMessage(),
            'data' => null,
            'file' => str_replace(ROOT_PATH, '[PROJECT_DIR]/', $e->getFile()),
            'line' => $e->getLine(),
        ], 500);
    },
];
