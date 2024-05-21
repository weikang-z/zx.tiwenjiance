<?php

namespace app\admin\model;

use stdClass;
use think\Model;


/**
*
* @property array|bool|float|int|mixed|object|stdClass|null $id
* @property array|bool|float|int|mixed|object|stdClass|null $user_id
* @property array|bool|float|int|mixed|object|stdClass|null $content
* @property array|bool|float|int|mixed|object|stdClass|null $images
* @property array|bool|float|int|mixed|object|stdClass|null $create_time
* @property array|bool|float|int|mixed|object|stdClass|null $switch
*
*/
class Feedback extends Model
{

    

    

    // 表名
    protected $table = 'feedback';
    
    public $name = 'feedback';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
