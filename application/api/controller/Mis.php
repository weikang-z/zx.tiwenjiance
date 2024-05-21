<?php

namespace app\api\controller;

use app\api\model\ConfigModel;
use app\common\library\Curl;
use OpenApi\Annotations as OA;
use think\Db;
use think\response\Json;

class Mis extends Base
{

    /**
     * @noauth
     * @return void
     */
    public function upload()
    {
        parent::upload();
    }

    /**
     * 客服电话
     * @noauth
     * @return Json
     */
    public function ctel(): Json
    {

        return self::resp(t("ok"), 1, [
            'tel' => ConfigModel::get_value('kf_tel')
        ]);

    }

}
