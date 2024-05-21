<?php

namespace utils;


// 
// @FileName: buildadmin.${DIR_PATH}
// @Description: Wechat
// @Author: ekr123 / zwk480314826@163.com
// @Copyright: © 2023
// @Version: V1.0.0
// @Created: 2023/7/15
//
use EasyWeChat\Factory;
use EasyWeChat\OfficialAccount\Application;

/**
 * EasyWechat v4
 * https://easywechat.com/4.x/overview.html
 */
class Wechat
{


    /**
     * 微信小程序
     * https://easywechat.com/4.x/mini-program/index.html
     * @return \EasyWeChat\MiniProgram\Application
     */
    static function miniapp(): \EasyWeChat\MiniProgram\Application
    {
        $miniapp_config = [
            'app_id' => WX_MINI_APPID,
            'secret' => WX_MINI_APPSECRET,
        ];
        return Factory::miniProgram([
            'app_id' => $miniapp_config['app_id'],
            'secret' => $miniapp_config['secret'],
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => app()->getRuntimePath() . 'easywechat-miniapp.log'
            ],
        ]);
    }

    /**
     * 微信公众号
     * https://easywechat.com/4.x/official-account/index.html
     * @return Application
     */
    static function mp(): Application
    {
        $mp_config = [
            'app_id' => WX_MP_APPID,
            'secret' => WX_MP_APPSECRET,
            'token' => "",
            'aes_key' => "",
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => "",
            ],
        ];

        return Factory::officialAccount([
            'app_id' => $mp_config['app_id'],
            'secret' => $mp_config['secret'],
            'token' => $mp_config['token'],
            'aes_key' => $mp_config['aes_key'],
            'response_type' => 'array',
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => $mp_config['oauth']['callback'],
            ],
        ]);
    }

    /**
     * 微信支付
     * https://easywechat.com/4.x/payment/index.html
     * @param string $appid
     * @return \EasyWeChat\Payment\Application
     */
    static function pay(string $appid): \EasyWeChat\Payment\Application
    {
        $config = [
            // 必要配置
            'app_id'             => $appid,
            'mch_id'             => WX_PAY_MCHID,
            'key'                => WX_PAY_KEY,   // API 密钥
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => WX_PAY_CERT_PATH, // XXX: 绝对路径！！！！
            'key_path'           => WX_PAY_KEY_PATH,      // XXX: 绝对路径！！！！
        ];
        return Factory::payment($config);
    }

}