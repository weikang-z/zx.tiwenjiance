<?php

namespace app\api\model;

use think\Model;

class FamilyTempLogModel extends Model
{

    protected $table = "family_temp_log";

    public function setUpTimeAttr($value): string
    {
        if (empty($value)) {
            return "";
        }
        return datetime($value);
    }

    public function getTempAttr($value): array
    {

        return [
            'oc' => (float)$value,
            'of' => ($value * 9 / 5) + 32
        ];
    }

}