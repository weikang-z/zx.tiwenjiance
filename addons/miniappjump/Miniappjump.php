<?php

namespace addons\miniappjump;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Miniappjump extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'miniappjump',
                'title'   => '小程序跳转助手',
                'icon'    => 'fa fa-link',
                'sublist' => [
                    [
                        'name'    => 'miniappjump/app',
                        'title'   => '小程序管理',
                        'icon'    => 'fa fa-comments',
                        'sublist' => [
                            ['name' => 'miniappjump/app/index', 'title' => '查看'],
                            ['name' => 'miniappjump/app/add', 'title' => '添加'],
                            ['name' => 'miniappjump/app/edit', 'title' => '修改'],
                            ['name' => 'miniappjump/app/del', 'title' => '删除'],
                            ['name' => 'miniappjump/app/multi', 'title' => '批量更新'],
                            ['name' => 'miniappjump/app/app', 'title' => '生成小程序权限列表', 'remark' => ''],
                        ],
                    ],
                ]
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('miniappjump');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('miniappjump');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('miniappjump');
        return true;
    }

}
