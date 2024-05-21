<?php

namespace app\admin\model\shorturl;

use think\Model;
use addons\shorturl\library\Hash;

class Url extends Model {

    // 表名
    protected $name = 'shorturl_url';

    // 只读字段
    protected $readonly = ['hash'];

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'hash_url',
        'expiretime_text',
    ];

    public function getHashurlAttr($value, $data) {
        return addon_url('shorturl/shorturl/index', [':hash' => $data['hash']], true, true);
    }

    protected static function init() {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();

            $salt = '';
            $length = 5;
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

            $hashobj = new Hash($salt, $length, $alphabet);
            $hashkey = $hashobj->encode($row[$pk]);

            $row->getQuery()->where($pk, $row[$pk])->update(['hash' => $hashkey]);
        });
    }

    public function getExpiretimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['expiretime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setExpiretimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }
}
