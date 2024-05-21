<?php

namespace app\admin\model;

use stdClass;
use think\Model;


/**
*
* @property array|bool|float|int|mixed|object|stdClass|null $id
* @property array|bool|float|int|mixed|object|stdClass|null $title
* @property array|bool|float|int|mixed|object|stdClass|null $title_en
* @property array|bool|float|int|mixed|object|stdClass|null $answer
* @property array|bool|float|int|mixed|object|stdClass|null $answer_en
* @property array|bool|float|int|mixed|object|stdClass|null $weigh
* @property array|bool|float|int|mixed|object|stdClass|null $switch
*
*/
class Faq extends Model
{

    

    

    // 表名
    protected $table = 'faq';
    
    public $name = 'faq';
    
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
