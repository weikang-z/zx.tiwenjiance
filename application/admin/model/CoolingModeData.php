<?php

namespace app\admin\model;

use stdClass;
use think\Model;


/**
*
* @property array|bool|float|int|mixed|object|stdClass|null $id
* @property array|bool|float|int|mixed|object|stdClass|null $text_zh
* @property array|bool|float|int|mixed|object|stdClass|null $text_en
* @property array|bool|float|int|mixed|object|stdClass|null $switch
* @property array|bool|float|int|mixed|object|stdClass|null $weigh
*
*/
class CoolingModeData extends Model
{

    

    

    // 表名
    protected $table = 'cooling_mode_data';
    
    public $name = 'cooling_mode_data';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    







}
