<?php

return [
    [
        'name' => 'wechat',
        'title' => '微信',
        'type' => 'array',
        'content' => [],
        'value' => [
            'appid' => '',
            'app_id' => '',
            'app_secret' => '',
            'miniapp_id' => '',
            'mch_id' => '',
            'key' => '',
            'mode' => 'normal',
            'sub_mch_id' => '',
            'sub_appid' => '',
            'sub_app_id' => '',
            'sub_miniapp_id' => '',
            'notify_url' => '/addons/epay/api/notifyx/type/wechat',
            'cert_client' => '/addons/epay/certs/apiclient_cert.pem',
            'cert_key' => '/addons/epay/certs/apiclient_key.pem',
            'log' => '1',
        ],
        'rule' => '',
        'msg' => '',
        'tip' => '微信参数配置',
        'ok' => '',
        'extend' => '',
    ],
    [
        'name' => 'alipay',
        'title' => '支付宝',
        'type' => 'array',
        'content' => [],
        'value' => [
            'app_id' => '',
            'mode' => 'normal',
            'notify_url' => '/addons/epay/api/notifyx/type/alipay',
            'return_url' => '/addons/epay/api/returnx/type/alipay',
            'private_key' => '',
            'ali_public_key' => '',
            'app_cert_public_key' => '',
            'alipay_root_cert' => '',
            'log' => '1',
            'scanpay' => '0',
        ],
        'rule' => 'required',
        'msg' => '',
        'tip' => '支付宝参数配置',
        'ok' => '',
        'extend' => '',
    ],
];
