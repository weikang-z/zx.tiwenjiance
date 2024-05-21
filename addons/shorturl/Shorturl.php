<?php

namespace addons\shorturl;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Shorturl extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'shorturl',
                'title'   => '短网址',
                'icon'    => 'fa fa-link',
                'sublist' => [
                    [
                        'name'    => 'shorturl/url',
                        'title'   => '短链管理',
                        'icon'    => 'fa fa-link',
                        'sublist' => [
                            ['name' => 'shorturl/url/index', 'title' => '查看'],
                            ['name' => 'shorturl/url/add', 'title' => '添加'],
                            ['name' => 'shorturl/url/edit', 'title' => '修改'],
                            ['name' => 'shorturl/url/del', 'title' => '删除'],
                        ]
                    ]
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
        Menu::delete('shorturl');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable('shorturl');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable('shorturl');
        return true;
    }

}
