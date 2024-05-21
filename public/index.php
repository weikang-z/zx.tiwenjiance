<?php



header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, X-Requested-With, Origin, accesstoken, version, function, app, from, token');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// 加载配置文件
define('APP_PATH', __DIR__ . '/../application/');
require_once '../const.php';

// 定义应用目录
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
