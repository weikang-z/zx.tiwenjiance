<?php

namespace app\api\controller;

use app\api\model\FamilyMemberModel;
use mysql_xdevapi\RowResult;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\response\Json;
use think\Validate;

class FamilyMember extends Base
{

    private static function getValidationData(): array
    {
        return [
            [
                'avatar' => 'require',
                'nickname' => 'require',
                'age' => 'require|number',
                'sex' => ['require', 'enum' => fn($val) => in_array($val, ['男', '女'])],
                'height' => 'require|number',
                'weight' => 'require|number',
            ],
            [
                'avatar.require' => t("fm", "add", "avatar.require"),
                'nickname.require' => t("fm", "add", "nickname.require"),
                'age.require' => t("fm", "add", "age.require"),
                'age.number' => t('fm', "add", "age.number"),
                'sex.require' => t("fm", "add", "sex.require"),
                'sex.enum' => t("fm", "add", "sex.enum"),
                'height.require' => t("fm", "add", "height.require"),
                'height.number' => t("fm", "add", "height.number"),
                'weight.require' => t("fm", "add", "weight.require"),
                'weight.number' => t("fm", "add", "weight.number")
            ]
        ];
    }

    /**
     * 成员列表  1
     * @return Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function list(): Json
    {
        $data = (new FamilyMemberModel)->where('user_id', '=', $this->user->id)->select();
        return self::resp(t("ok"), 1, $data);
    }

    public function add(): Json
    {

        $validation = new Validate(...self::getValidationData());
        if (!$validation->check($this->p)) {
            return self::resp($validation->getError());
        }

        $this->p['user_id'] = $this->user->id;
        FamilyMemberModel::create($this->p);

        return self::resp(t("fm", "add ok"), 1);
    }

    public function del($id): Json
    {
        $t = FamilyMemberModel::get($id);
        if (!$t) return self::resp(t("fm", "not found"));
        $t->delete();
        return self::resp(t("fm", "del", "ok"), 1);
    }

    public function edit(): Json
    {
        $validation = new Validate(...self::getValidationData());
        if (!$validation->check($this->p['data'])) {
            return self::resp($validation->getError());
        }
        (new FamilyMemberModel)->save($this->p['data'], ['id' => $this->p['id']]);
        return self::resp(t("fm", "edit", "ok"), 1);

    }
}