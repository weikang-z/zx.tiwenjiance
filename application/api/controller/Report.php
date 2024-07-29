<?php

namespace app\api\controller;

use app\api\model\FamilyMorModel;
use app\api\model\FamilyTempLogModel;
use app\api\model\UserModel;
use think\Db;

class Report extends Base
{

    public function search(): \think\response\Json
    {


        // 查询该数据的最后一条guid
//        $last_data = FamilyTempLogModel::where(function ($q) {
//            $q->where('fm_id', $this->p['fm_id']);
//
//            $start_time = $this->p['start_time'] ?? null;
//            if ($start_time) {
//                $q->where('up_time', '>=', datetime($start_time));
//            }
//
//            $end_time = $this->p['end_time'] ?? null;
//            if ($end_time) {
//                $q->where('up_time', '<=', datetime($end_time));
//            }
//        })
//            ->order("up_time desc")
//            ->find();
//
//
//        $guid = $last_data['guid'] ?? null;

        $data = FamilyTempLogModel::where(function ($q) use ($guid) {
            $q->where('fm_id', $this->p['fm_id']);

            $start_time = $this->p['start_time'] ?? null;
            if ($start_time) {
                $q->where('up_time', '>=', datetime($start_time));
            }

            $end_time = $this->p['end_time'] ?? null;
            if ($end_time) {
                $q->where('up_time', '<=', datetime($end_time));
            }

//            $q->where('guid', $guid);
        })
            ->order("up_time asc")
            ->field("temp,up_time")
            ->select();

        $data = collection((array)$data)->toArray();

//        dd($data);

        if (empty($data)) {
            return self::resp(t("report", "search", "no data"), 1, []);
        }

        $temp_setting = UserModel::get($this->user->id)->temp_setting;

        $all_temps = array_map(function ($item) use ($temp_setting) {
            return (float)$item['temp'][$temp_setting['unit']];
        }, $data);

        return self::resp(t("ok"), 1, [
            'temp_data' => [
                'total_time' => (function () use ($data): string {
                    if (count($data) <= 1) {
                        return '-';
                    }
                    $last_data = $data[count($data) - 1];
                    $last_time = strtotime($last_data['up_time']);
                    $start_time = strtotime($data[0]['up_time']);
                    $total_time = $last_time - $start_time;

                    return secondsToTime($total_time);
                })(),
                'normal_time' => (function () use ($data, $temp_setting): string {
                    if (count($data) <= 1) {
                        return '-';
                    }
                    $low_temp = $temp_setting['low_temp'];
                    $high_temp = $temp_setting['high_temp'];

                    if (!$low_temp['status']) {
                        $low_temp['number'] = -999999;
                    }

                    if (!$high_temp['status']) {
                        $high_temp['number'] = 999999;
                    }

                    $low_temp = $low_temp['number'];
                    $high_temp = $high_temp['number'];

                    $total_time = 0;
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }

                        if ($v['temp'][$temp_setting['unit']] > $low_temp && $v['temp'][$temp_setting['unit']] < $high_temp) {
                            if ($data[$k - 1]['temp'][$temp_setting['unit']] > $low_temp && $data[$k - 1]['temp'][$temp_setting['unit']] < $high_temp) {
                                $total_time += strtotime($v['up_time']) - strtotime($data[$k - 1]['up_time']);
                            }
                        }
                    }

                    return secondsToTime($total_time);
                })(),
                'avg' => (function () use ($all_temps) {
                    if (count($all_temps) < 1) {
                        return 0;
                    }
                    return array_sum($all_temps) / count($all_temps);
                })(),
                'max' => max($all_temps),
                'min' => min($all_temps)
            ],
            'temp_log' => array_map(function ($item) use ($temp_setting) {
                return [
                    'temp' => $item['temp'][$temp_setting['unit']],
                    'up_time' => $item['up_time'],
                ];
            }, $data),
            'last_remark' => FamilyMorModel::where("fm_id", $this->p['fm_id'])->order("id desc")
                ->field("utime,symptoms,cooling_mode,remark")
                ->find(),
            'monitoring_time' => date("Y.m.d H:i:s", strtotime($data[0]['up_time'])),
            'create_time' => date("Y.m.d H:i:s", time()),
        ]);

    }

}