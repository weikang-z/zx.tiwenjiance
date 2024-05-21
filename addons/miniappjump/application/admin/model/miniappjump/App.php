<?php

namespace app\admin\model\miniappjump;

use think\Model;


class App extends Model
{

    // 表名
    protected $name = 'miniappjump_app';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'platform_text',
        'status_text'
    ];

    public function getImageAttr($value, $data)
    {
        return cdnurl($value, true);
    }

    /**
     * 获取平台列表
     * @return [type] [description]
     */
    public function getPlatformList()
    {
        return ['wxapp' => __('Wxapp'), 'aliapp' => __('Aliapp'), 'baiduapp' => __('Baiduapp'), 'toutiaoapp' => __('Toutiaoapp'), 'qqapp' => __('Qqapp')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function getPlatformTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['platform']) ? $data['platform'] : '');
        $list = $this->getPlatformList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
