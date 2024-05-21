<?php

namespace app\api\model;

use think\Model;

class UserDeviceModel extends Model
{

    protected $table = "user_device";

    public function setExtendAttr($value)
    {
        if (empty($value)) {
            return "[]";
        }
        return json_encode($value, 256);
    }

    public function getExtendAttr($value)
    {
        if (empty($value)) {
            return [];
        }
        return json_decode($value, true);
    }

}