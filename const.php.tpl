<?php

/**
 * 系统常量定义文件
 *
 */

// 应用名称(en)

const APP_NANE = 'app';

const APP_DEV_PORT = 23001;



// 系统是否为调试模式
const APP_DEBUG = true;

// 是否开启日志系统
const APP_LOG = false;


// 系统是否涉及到发送邮件
const NEED_EMAIL = FALSE;

// 用户token加密密钥
const JWT_SECRET = 'bde658c1cc6a1a2249f2d03b08705bf8c0ba26d4';

// 微信小程序
const WX_MINI_APPID = '';
const WX_MINI_APPSECRET = '';

// 微信公众号
const WX_MP_APPID = '';
const WX_MP_APPSECRET = '';

const WX_PAY_MCHID = '';
const WX_PAY_KEY = '';
const WX_PAY_CERT_PATH = '';
const WX_PAY_KEY_PATH = '';


// Redis
const Redis_Host = '127.0.0.1';
const Redis_Port = 6379;
const Redis_Auth = '';
const Redis_Prefix = APP_NANE . '-redis-';


// 地图key
const TENCENT_MAP_KEY = 'H46BZ-RFGKV-YXNPA-U7EDH-PCESF-K5BKW';
const GAODE_MAP_KEY = '';
const BAIDU_MAP_KEY = '';




// 数据库
!defined('DATABASE_CONFIG') && define('DATABASE_CONFIG', [
	'type' => 'mysql',
'hostname' => '127.0.0.1',
'database' => '',
'username' => 'root',
'hostport' => 3306,
'password' => '',
]);

/**
* 线上调试模式
* headers中带有对应秘钥的请求才能通过
*/
!defined('ONLOAD_DEBUG') && define('ONLOAD_DEBUG', [
'debug' => FALSE,
'secret' => '',
'wl' => ['0.0.0.0', '127.0.0.1', 'localhost']
]);

// 黑名单
!defined('BLACKLIST') && define('BLACKLIST', []);

//require_once __DIR__ . '/base/online_debug.php';

