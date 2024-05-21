<?php

namespace app\api\controller;

use app\common\library\wechat\Miniapp;
use OpenApi\Annotations as OA;
use think\response\Json;

class Wechat extends Base
{


    /**
     * @noauth
     * @OA\Get (
     *     path = "/api/wechat/mp_openid",
     *     tags = {"微信"},
     *     summary = "获取小程序的openid",
     *      @OA\Response(response="200",description="success"),
     *      @OA\Response(response="500",description="系统错误"),
     *      @OA\Parameter (
     *          in="query",
     *          description="小程序的code",
     *          name="code",
     *          required=true,
     *          @OA\Schema (type="string")
     *      )
     * )
     * @return Json
     */
    function mp_openid($code): Json
    {

        $code = $this->request->get('code', NULL);
        if (!$code) return self::resp('need `code` ');
        [$ret, $res] = Miniapp::getOpenid($code);
        if (!$ret) {
            return self::resp($res);
        } else {
            return self::resp('success', 200, $res);
        }
    }


    /**
     * @param $code
     * @return Json
     * @noauth
     * @OA\Get (
     *     path = "/api/wechat/mp_exchangeMobilev3",
     *     tags = {"微信"},
     *     summary = "获取小程序的手机号",
     *     @OA\Response(response="200",description="success"),
     *     @OA\Response(response="500",description="系统错误"),
     *     @OA\Parameter (
     *     in="query",
     *     description="小程序的code",
     *     name="code",
     *     required=true,
     * )
     * )
     */
    function mp_exchangeMobilev3($code = null): Json
    {
        list($status, $mobile) = Miniapp::getMobileV2($code);
        if ($status) {
            return self::resp("ok", 1, ['mobile' => $mobile]);
        } else {
            return self::resp("error", 0);
        }
    }
}
