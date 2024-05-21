<?php
// 
// @FileName: buildadmin.${DIR_PATH}
// @Description: ${NAME}
// @Author: ekr123 / zwk480314826@163.com
// @Copyright: © 2023
// @Version: V1.0.0
// @Created: 2023/7/14
//

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int $time   时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime(?int $time, string $format = 'Y-m-d H:i:s'): string
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }
}


function encrypt($string, $operation, $key = '')
{
    $key = md5($key);
    $key_length = strlen($key);
    $string = 'D' == $operation ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = [];
    $result = '';
    for ($i = 0; $i <= 255; ++$i) {
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; ++$i) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; ++$i) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ('D' == $operation) {
        if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
            return substr($result, 8);
        } else {
            return '';
        }
    } else {
        return str_replace('=', '', base64_encode($result));
    }
}





function GetTree($arr, $pid, $step, $c = 'title', $needflg = true)
{
    global $tree;
    foreach ($arr as $key => $val) {
        if ($val['pid'] == $pid) {
            if ($needflg) {
                $flg = str_repeat('-', $step);
            } else {
                $flg = '';
            }
            $val[$c] = $flg . $val[$c];
            $tree[] = $val;
            GetTree($arr, $val['id'], $step + 1, $c, $needflg);
        }
    }
    return $tree;
}

function generateTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0): array
{
    $tree = [];
    $packData = [];
    foreach ($list as $data) {
        $packData[$data[$pk]] = $data;
    }
    foreach ($packData as $key => $val) {
        if ($val[$pid] == $root) {
            $tree[] = &$packData[$key];
        } else {
            $packData[$val[$pid]][$child][] = &$packData[$key];
        }
    }
    return $tree;
}

function getCode($m = 4, $type = 0): string
{
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $t = [9, 35, strlen($str) - 1];
    $c = '';
    for ($i = 0; $i < $m; ++$i) {
        $c .= $str[rand(0, $t[$type])];
    }
    return $c;
}

/**
 *获取发布时间与当前时间差.
 */
function tranTime($time)
{
    $rtime = date('m-d H:i', $time);
    $htime = date('H:i', $time);
    $time = time() - $time;
    if ($time < 60) {
        $str = '刚刚';
    } elseif ($time < 60 * 60) {
        $min = floor($time / 60);
        $str = $min . '分钟前';
    } elseif ($time < 60 * 60 * 24) {
        $h = floor($time / (60 * 60));
        $str = $h . '小时前 ' . $htime;
    } elseif ($time < 60 * 60 * 24 * 3) {
        $d = floor($time / (60 * 60 * 24));
        if (1 == $d) {
            $str = '昨天 ' . $rtime;
        } else {
            $str = '前天 ' . $rtime;
        }
    } else {
        $str = $rtime;
    }

    return $str;
}
