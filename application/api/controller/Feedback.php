<?php

namespace app\api\controller;

use think\Db;
use think\response\Json;

class Feedback extends Base
{

    public function submit(): Json
    {

        $cache_key = 'submit_feedback_' . $this->user->id;

        if (cache($cache_key)) {
            return self::resp(t("feedback", "submit more"));
        }

        Db::name("feedback")->insert([
            'user_id' => $this->user->id,
            'content' => $this->p['content'] ?? '',
            'images' => join(',', array_filter($this->p['images'] ?? [])),
            'create_time' => datetime(time()),
            'switch' => 0
        ]);
        cache($cache_key, 1, 60);
        return self::resp(t("feedback", "ok",), 1);
    }

}