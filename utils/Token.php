<?php

namespace utils;


// 
// @FileName: buildadmin.${DIR_PATH}
// @Description: Token
// @Author: ekr123 / zwk480314826@163.com
// @Copyright: © 2023
// @Version: V1.0.0
// @Created: 2023/7/15
//
class Token
{


    /**
     * 生成token
     * @param int $user_id
     * @return string
     */
    public static function set(int $user_id): string
    {
        $rand_number = rand(100000, 999999);
        $token = sprintf("%s|%s|%s", time(), $user_id + $rand_number, getCode(12, 1));
        $token = base64_encode($token);
        return encrypt($token, "E", JWT_SECRET) . '@' . $rand_number;
    }

    /**
     * 解析token并返回user_id
     * @param string $token
     * @return int
     */
    public static function get(string $token): int
    {
        $tokenSliceByS2 = explode('@', $token);
        $rand_number = (int)$tokenSliceByS2[count($tokenSliceByS2) - 1];
        array_pop($tokenSliceByS2);
        $token = join("@", $tokenSliceByS2);
        $token = encrypt($token, "D", JWT_SECRET);
        $token = base64_decode($token);
        $token = explode('|', $token);
        if (count($token) == 3) {
            return (int)($token[1] - $rand_number);
        }

        return -1;
    }
}
