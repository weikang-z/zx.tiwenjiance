<?php

namespace app\admin\model;

use stdClass;
use think\Model;


/**
*
* @property array|bool|float|int|mixed|object|stdClass|null $id
* @property array|bool|float|int|mixed|object|stdClass|null $avatar
* @property array|bool|float|int|mixed|object|stdClass|null $name
* @property array|bool|float|int|mixed|object|stdClass|null $mobile
* @property array|bool|float|int|mixed|object|stdClass|null $password
* @property array|bool|float|int|mixed|object|stdClass|null $salt
* @property array|bool|float|int|mixed|object|stdClass|null $create_time
* @property array|bool|float|int|mixed|object|stdClass|null $create_ip
* @property array|bool|float|int|mixed|object|stdClass|null $last_login_time
* @property array|bool|float|int|mixed|object|stdClass|null $temp_setting
* @property array|bool|float|int|mixed|object|stdClass|null $is_cancel
*
*/
class User extends Model
{

    

    

    // 表名
    protected $table = 'user';
    
    public $name = 'user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'is_cancel_text'
    ];
    

    
    public function getIsCancelList()
    {
        return ['y' => __('Is_cancel y'), 'n' => __('Is_cancel n')];
    }


    public function getIsCancelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_cancel']) ? $data['is_cancel'] : '');
        $list = $this->getIsCancelList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
