<?php

namespace app\api\controller;

use app\api\model\FamilyTempLogModel;
use app\api\model\UserDeviceModel;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\response\Json;
use think\Validate;

class Device extends Base
{
    public function list(): Json
    {
        $list = UserDeviceModel::where(["user_id" => $this->user->id])
            ->field("id,name,extend")
            ->select();

        return self::resp(t("ok"), 1, $list);
    }

    public function add(): Json
    {
        $name = $this->p["name"] ?? "设备 " . getCode(5, 2);
        $extend = $this->p["extend"] ?? [];

        UserDeviceModel::create([
            "user_id" => $this->user->id,
            "name" => $name,
            "extend" => $extend,
            "create_time" => datetime(time()),
        ]);

        return self::resp(t("device", "add ok"), 1);
    }

    public function del(): Json
    {
        $id = $this->p["id"] ?? null;
        $row = UserDeviceModel::get($id);
        if (!$row) {
            return self::resp(t("device", "no device"), 0);
        }
        $row->delete();
        return self::resp(t("device", "del ok"), 1);
    }

    /**
     * 设备使用说明
     * @noauth
     * @return Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function use_comment(): Json
    {
        $data = Db::name("device_use_comment")->order("weigh desc")->select();
        $ndata = [];
        foreach ($data as $v) {
            $ndata[] = [
                "text" => $v[$this->ulang . "_text"],
                "image" => imgUrl($v[$this->ulang . "_image"]),
            ];
        }
        return self::resp(t("ok"), 1, $ndata);
    }

    public function uptemp(): Json
    {
        $validation = new Validate(
            [
                "device_id" => "require|number",
                "fm_id" => "require|number",
                "temp_oc" => "require|number",
                "time" => "require|number",
                "guid" => 'require',
            ],
            [
                "device_id.require" => t(
                    "device",
                    "uptemp",
                    "device_id require"
                ),
                "fm_id.require" => t("device", "uptemp", "fm_id require"),
                "temp_oc.require" => t("device", "uptemp", "temp_oc require"),
                "temp_oc.number" => t("device", "uptemp", "temp_oc number"),
                "time.require" => t("device", "uptemp", "time require"),
                "time.number" => t("device", "uptemp", "time number"),
            ]
        );

        if (!$validation->check($this->p)) {
            return self::resp($validation->getError());
        }

        // 限制10秒最多提交一次
        $ThresholdCacheName = sprintf(
            "uptime_tc_%s_%s_%s",
            $this->user->id,
            $this->p["device_id"],
            $this->p["fm_id"]
        );
        if (cache($ThresholdCacheName)) {
            return self::resp(t("device", "uptemp", "up more"), 1);
        }

        FamilyTempLogModel::create([
            "user_id" => $this->user->id,
            "fm_id" => $this->p["fm_id"],
            "device_id" => $this->p["device_id"],
            "temp" => $this->p["temp_oc"],
            "up_time" => $this->p["time"],
            "guid" => $this->p["guid"] ?? 'none',
        ]);
        cache($ThresholdCacheName, 0x1, 10);
        return self::resp(t("device", "uptemp", "ok"), 1);
    }

    public function uptemps(): Json
    {

        // 限制10秒最多提交一次
        $ThresholdCacheName = sprintf(
            "uptime_tc_%s_%s_%s",
            $this->user->id,
            $this->p["device_id"],
            $this->p["fm_id"]
        );
        if (cache($ThresholdCacheName)) {
            return self::resp(t("device", "uptemp", "up more"), 1);
        }

        $temps = $this->p['temps'];
        if (empty($temps)) {
            return self::resp(t("device", "uptemp", "temp_oc require"), 0);
        }

        foreach ($temps as $v) {
            FamilyTempLogModel::create([
                "user_id" => $this->user->id,
                "fm_id" => $v["fm_id"],
                "device_id" => $this->p["device_id"],
                "temp" => $v["temp_oc"],
                "up_time" => $v["time"],
                "guid" => $v["guid"] ?? 'none',
            ]);
        }


        cache($ThresholdCacheName, 0x1, 10);
        return self::resp(t("device", "uptemp", "ok"), 1);
    }

    public function temp_log(): Json
    {
        $fm_id = $this->p["fm_id"] ?? null;
        if (empty($fm_id)) {
            return self::resp(t("device", "temp_log", "fm_id require"), 0);
        }

        $data = FamilyTempLogModel::where(function ($q) use ($fm_id) {
            $q->where("fm_id", $fm_id);

            $start_time = $this->p["start_time"] ?? null;
            if ($start_time) {
                $q->where("up_time", ">=", datetime($start_time));
            }

            $end_time = $this->p["end_time"] ?? null;
            if ($end_time) {
                $q->where("up_time", "<=", datetime($end_time));
            }
        })
            ->field("temp,up_time")
            ->order("up_time asc")
            ->select();

        return self::resp(t("ok"), 1, $data);
    }

    public function temp_atest_log(): Json
    {
        $fm_id = $this->p["fm_id"] ?? null;
        if (empty($fm_id)) {
            return self::resp(t("device", "temp_log", "fm_id require"), 0);
        }

        $data = FamilyTempLogModel::where(function ($q) use ($fm_id) {
            $q->where("fm_id", $fm_id);
        })
            ->field("temp,up_time")
            ->order("up_time desc")
            ->find();

        return self::resp(t("ok"), 1, $data);
    }
}
