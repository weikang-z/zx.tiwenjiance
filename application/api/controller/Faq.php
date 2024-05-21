<?php

namespace app\api\controller;

use app\api\model\FaqModel;

class Faq extends Base
{

    /**
     * @noauth
     * @return \think\response\Json
     */
    public function get(): \think\response\Json
    {
        return self::resp(t("ok"), 1, FaqModel::getList($this->request->header('u-lang')));
    }

}