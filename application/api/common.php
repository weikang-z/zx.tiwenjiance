<?php

use think\Request;
use app\common\library\Aes;

/**
 *  解析用户登陆Token
 */
if (!function_exists('parseToken')) {
    function parseToken($token)
    {
        $q = unserialize(encrypt($token, 'D', TOKEN_KEY));
        return [$q['mid'], $q['openid']];
    }
}

/**
 * 加密用户登录信息
 */
if (!function_exists('encryptToken')) {
    function encryptToken($member_id, $openid = null)
    {
        $z = serialize(array(
            'bz' =>  uniqid(),
            'mid' => $member_id,
            'openid' => $openid,
            'time' => time()
        ));
        return encrypt($z, 'E', TOKEN_KEY);
    }
}


/**
 * 将接口返回的数据进行对称加密
 *
 * @return void
 */
function aes_decrypt_response($msg, $code, $data)
{
    $str = json_encode([
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ], 256);
    $data = (new Aes())->encrypt($str);
    return $data;
}


Request::hook('member', 'checkUserInfo');
function checkUserInfo()
{
    // 验证用户完整性
}
