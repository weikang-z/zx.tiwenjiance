<?php

namespace app\api\model;

use think\Model;

class ConfigModel extends Model
{

    protected $table = 'config';

    public static function get_value($name)
    {
        return self::where('name', $name)->value('value');
    }


}