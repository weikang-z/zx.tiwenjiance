<?php

namespace app\api\controller;

use think\Db;
use think\response\Json;

class Startpage extends Base
{

    /**
     * @noauth
     * @return Json
     */
    public function images() :Json
    {

        $data = Db::name("start_page_config")->order("weigh desc")->select();
        $ndata = [];
        foreach ($data as $v) {
            $ndata[] = [
                'text' => $v[$this->ulang.'_text'],
                'image' => imgUrl($v[$this->ulang.'_image'])
            ];
        }
        return self::resp(t("ok"),1, $ndata);
    }

}