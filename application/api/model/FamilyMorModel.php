<?php

namespace app\api\model;

use think\Model;

class FamilyMorModel extends Model
{

    protected $table = "family_mor";

    public function setSymptomsAttr($value): string
    {
        if (empty($value)) {
            return "";
        }
        return join("#@", $value);
    }

    public function getSymptomsAttr($value): array
    {
        if (empty($value)) {
            return [];
        }
        return explode("#@", $value);
    }

    public function setCoolingModeAttr($value): string
    {
        if (empty($value)) {
            return "";
        }
        return join("#@", $value);
    }

    public function getCoolingModeAttr($value): array
    {
        if (empty($value)) {
            return [];
        }
        return explode("#@", $value);
    }

    public function getUtimeAttr($val): string
    {
        if (empty($val)) return "";
        return datetime(strtotime($val));
    }

}