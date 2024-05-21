<?php

namespace app\admin\model;

use stdClass;
use think\Model;


/**
*
* @property array|bool|float|int|mixed|object|stdClass|null $id
* @property array|bool|float|int|mixed|object|stdClass|null $skey
* @property array|bool|float|int|mixed|object|stdClass|null $title
* @property array|bool|float|int|mixed|object|stdClass|null $title_en
* @property array|bool|float|int|mixed|object|stdClass|null $content
* @property array|bool|float|int|mixed|object|stdClass|null $content_en
*
*/
class Agreement extends Model
{

    

    

    // 表名
    protected $table = 'agreement';
    
    public $name = 'agreement';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







}
