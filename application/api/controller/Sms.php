<?php

namespace app\api\controller;

use app\common\library\Curl;
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
                'mobile' => ['require', 'regex' => '/^1[345789]\d{9}$/'],
            ],
            [
                'type.require' => t("sms", "type.require"),
                'mobile.require' => t("sms", "mobile.require"),
                'mobile.regex' => t("sms", "mobile.mobile"),
            ]
        );

        if (!$validation->check($req->param())) {
            return self::resp($validation->getError());
        }

        if (cache('send' . $req->param('type') . $req->param('mobile'))) {
            return self::resp(t("sms", "send more"), 0);
        }

        $cache_key = sprintf("sms_code_%s_%s", $req->param('type'), $req->param('mobile'));
        $code = getCode(4, 0);
        cache($cache_key, $code, 600);

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
            back('sms error:'.$res['errorMsg'], 0);
        }

        cache('send' . $req->param('type') . $req->param('mobile'), 1, 60);
        return self::resp(t("sms", "ok"), 1, [
            'code' => '******'
        ]);

    }

}