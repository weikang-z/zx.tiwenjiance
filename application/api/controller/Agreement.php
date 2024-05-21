<?php

namespace app\api\controller;

use think\Db;
use think\response\Json;

class Agreement extends Base
{

    /**
     * @noauth
     * @param $skey
     * @return Json
     */
    public function detail($skey = null): Json
    {
        $row = Db::name("agreement")->where("skey", $skey)->find();
        if (!$row) {
            return self::resp(t("agreement", 'not found'));
        }

        $title = $row['title'];
        if ($this->ulang <> 'zh') {
            $title = $row['title_' . $this->ulang];
        }
        $content = $row['content'];
        if ($this->ulang <> 'zh') {
            $content = $row['content_' . $this->ulang];
        }
        $z = [
            'skey' => $skey,
            'title' => $title,
            'content' => $content
        ];

        return self::resp(t("ok"), 1, $z);
    }

    /**
     * @noauth
     * @return Json
     */
    public function aboutus(): Json
    {
        $data = Db::name("agreement")->where("skey", "in", [
            'gsjj', 'sytk', 'yszc'
        ])
            ->field(['skey', 'title', 'title_en'])->select();
        $ndata = [];
        foreach ($data as $v) {

            $title = $v['title'];
            if ($this->ulang <> 'zh') {
                $title = $v['title_' . $this->ulang];
            }
            $ndata[] = [
                'skey' => $v['skey'],
                'title' => $title,
            ];

        }
        return self::resp(t("ok"), 1, $ndata);
    }

}