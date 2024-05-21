<?php

namespace app\api\model;

use think\Model;

class FaqModel extends Model
{

    protected $table = "faq";

    public static function getList($lang): array
    {

        $data = self::where('switch', 1)->order("weigh desc")->select();
        $data = collection((array)$data)->toArray();

        $ndata = [];
        foreach ($data as $v) {
            if ($lang == 'zh') {
                $ndata[] = [
                    'title' => $v['title'],
                    'answer' => $v['answer'],
                ];
            } else {
                if (isset($v['title_'.$lang])) {
                    $ndata[] = [
                        'title' => $v['title_'.$lang] ?? '',
                        'answer' => $v['answer_'.$lang] ?? '',
                    ];
                }

            }
        }



        return $ndata;
    }

}