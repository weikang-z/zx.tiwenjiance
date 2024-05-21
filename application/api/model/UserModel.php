<?php
namespace app\api\model;

class UserModel extends \think\Model
{

    protected $table = "user";

    public function setTempSettingAttr($val)
    {
        if (empty($val)) {
            return "[]";
        }
        return json_encode($val, 256);
    }

    public function getTempSettingAttr($val)
    {
        return json_decode($val, true);
    }
}