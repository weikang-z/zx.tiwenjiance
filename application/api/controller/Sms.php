<?php

namespace app\api\controller;

use app\common\library\Curl;
use libphonenumber\PhoneNumberUtil;
use think\Request;
use think\response\Json;
use think\Validate;

class Sms extends Base
{

    /**
     * @noauth
     * @param Request $req
     * @return Json
     */
    public function send(Request $req): Json
    {

        $validation = new Validate(
            [
                'type' => 'require',
                'mobile' => ['require'],
            ],
            [
                'type.require' => t("sms", "type.require"),
                'mobile.require' => t("sms", "mobile.require"),
            ]
        );

        if (!$validation->check($req->param())) {
            return self::resp($validation->getError());
        }

        if (cache('send' . $req->param('type') . $req->param('mobile'))) {
//            return self::resp(t("sms", "send more"), 0);
        }

        $countryCode = $req->param('countryCode');
        if (empty($countryCode)) {
            $countryCode = 86;
        }
        $mobile = '+' . $countryCode . $req->param('mobile');
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumberObject = $phoneNumberUtil->parse($mobile);
        if (!$phoneNumberUtil->isValidNumber($phoneNumberObject)) {
            return self::resp(t("sms", "mobile error"), 0);
        }

        $is_cn_mobile = $phoneNumberUtil->getRegionCodeForNumber($phoneNumberObject) == 'CN';

        $cache_key = sprintf("sms_code_%s_%s", $req->param('type'), $req->param('mobile'));
        $code = getCode(4, 0);
        cache($cache_key, $code, 600);

        if ($is_cn_mobile) {
            $url = "https://smssh1.253.com/msg/v1/send/json";
            $cl_params = json_encode([
                'account' => 'N313995_N4557746',
                'password' => '2j2bYJDtVx951f',
                'msg' => "【睿知健康】您的验证码为：{$code}，请勿泄露于他人！",
                'phone' => $req->param('mobile'),
            ], 256);

            $res = (new Curl())
                ->setHeader([
                    'Content-Type: application/json',
                ])
                ->setParams($cl_params)->post($url, "json");

            if ($res['code'] <> '0') {
                back('sms error:' . $res['errorMsg'], 0);
            }

            cache('send' . $req->param('type') . $req->param('mobile'), 1, 60);
        } else {
            $url = 'https://intapi.253.com/send/sms';

            $params = [
                'account' => 'I194454_I7072705',
                'mobile' => $countryCode . $req->param('mobile'),
                'msg' => '【CW】Your verification code is ' . $code,
            ];

            $timeStamp = time();
            $headers = [
                'nonce' => $timeStamp . '000'
            ];

            $api_password = 'vEHPsMQNpYacb7';

            $headers['sign'] = (function () use ($api_password, $headers, $params) {
                $p = array_merge($headers, $params);
                /**
                 * 将请求头参数名 nonce 及所有请求体参数名（key）以 ASCII 码表顺序升序排列；
                 * 排序后把参数值非空的参数名（key）参数值（value）依次进行字符串拼接，拼接处不包含其他字符；
                 * 拼接成包含 kv 的长串后，在该长串的尾部拼接账号的 password，得到签名字符串的组装；
                 * 最后对签名字符串，使用 MD5 算法加密后，得到 MD5 加密密文后转为小写，即为请求头 sign 值
                 */

                ksort($p);
                $p = array_filter($p, function ($v) {
                    return $v;
                });
                $p = array_map(function ($k, $v) {
                    return $k . $v;
                }, array_keys($p), array_values($p));
                $p = implode('', $p);
                $p .= $api_password;
                return strtolower(md5($p));
            })();

            $res = (new Curl())
                ->setHeader([
                    'sign: ' . $headers['sign'],
                    'Content-Type: application/json',
                    'nonce: ' . $headers['nonce'],
                ])
                ->setParams(json_encode($params, 256))->post($url, "json");
            dd($res);
        }

        return self::resp(t("sms", "ok"), 1, [
            'code' => '******'
        ]);

    }

}