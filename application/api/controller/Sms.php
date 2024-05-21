<?php

namespace app\api\controller;

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

        // todo 发送短信

        cache('send' . $req->param('type') . $req->param('mobile'), 1, 60);
        return self::resp(t("sms", "ok"), 1, [
            'code' => $code
        ]);

    }

}