<?php

namespace app\api\controller;

use app\api\model\FamilyMorModel;
use think\Db;
use think\response\Json;
use think\Validate;

class Mor extends Base
{

    public function list(): Json
    {

        $data = FamilyMorModel::where(function ($q) {
            $q->where('user_id', $this->user->id);
            if (isset($this->p['fm_id']) && $this->p['fm_id']) {
                $q->where('fm_id', $this->p['fm_id']);
            }

            if (isset($this->p['start_time']) && $this->p['start_time']) {
                $q->where('utime', '>=', $this->p['start_time']);
            }

            if (isset($this->p['end_time']) && $this->p['end_time']) {
                $q->where('utime', '<=', $this->p['end_time']);
            }

        })->limit($this->p['offset'] ?? 0, $this->p['limit'] ?? 10)
            ->order("id desc")
            ->field("user_id", true)
            ->select();

        return self::resp(t("ok"), 1, $data);
    }

    private static function checkParams(): array
    {
        return [
            [
                'fm_id' => 'require|number',
                'time' => 'require|dateFormat:Y-m-d H:i:s',
                'symptoms' => 'require|array',
                'cooling_mode' => 'require|array',
            ],
            [
                'fm_id.require' => t("mor", "add", "fm_id require"),
                'time.require' => t("mor", "add", "time require"),
                'time.dateFormat' => t("mor", "add", "time number"),
                'symptoms.require' => t("mor", "add", "symptoms require"),
                'cooling_mode.require' => t("mor", "add", "cooling_mode require"),
            ]
        ];
    }

    public function add(): Json
    {

        $vali = new Validate(...self::checkParams());

        if (!$vali->check($this->p)) {
            return self::resp($vali->getError());
        }

        $ck = "mor_add_" . $this->user->id . $this->p['fm_id'];

        if (cache($ck)) {
            return self::resp(t("mor", "add", "add more"), 1);
        }

        FamilyMorModel::create([
            'fm_id' => $this->p['fm_id'],
            'utime' => $this->p['time'],
            'symptoms' => $this->p['symptoms'],
            'cooling_mode' => $this->p['cooling_mode'],
            'remark' => $this->p['remark'] ?? '',
            'user_id' => $this->user->id
        ]);

        cache($ck, 1, 3);

        return self::resp(t("mor", "add", "ok"), 1);
    }

    public function del($id): Json
    {

        $row = FamilyMorModel::get($id);
        if (!$row) {
            return self::resp(t("mor", "del", "not found"), 1);
        }

        $row->delete();

        return self::resp(t("mor", "del", "ok"), 1);
    }

    public function edit(): Json
    {

        $vali = new Validate(...self::checkParams());
        if (!$vali->check($this->p['data'])) {
            return self::resp($vali->getError());
        }

        $row = FamilyMorModel::get($this->p['id']);
        if (!$row) {
            return self::resp(t("mor", "edit", "not found"), 1);
        }

        $row->fm_id = $this->p['data']['fm_id'] ?? $row->fm_id;
        $row->utime = $this->p['data']['time'] ?? $row->utime;
        $row->symptoms = $this->p['data']['symptoms'] ?? $row->symptoms;
        $row->cooling_mode = $this->p['data']['cooling_mode'] ?? $row->cooling_mode;
        $row->remark = $this->p['data']['remark'] ?? $row->remark;
        $row->save();

        return self::resp(t("mor", "edit", "ok"), 1);
    }

    public function checkbox_data($type)
    {
        if (!in_array($type, ['symptoms', 'cooling_mode'])) {
            return self::resp(t("mor", "checkbox_data", "type error"), 1);
        }
        $data = Db::name($type . '_data')->where("switch", 1)
            ->order("weigh desc")
            ->field('text_zh,text_en')
            ->select();

        $_data = [];
        foreach ($data as $v) {
            if ($this->ulang == "zh") {
                $_data[] = $v['text_zh'];
            } else {
                $_data[] = $v['text_en'];
            }
        }

        return self::resp("ok", 1, $_data);

    }

}