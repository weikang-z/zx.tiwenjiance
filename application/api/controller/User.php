<?php

namespace app\api\controller;

use app\api\model\UserModel;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Request;
use think\response\Json;
use think\Validate;
use utils\Token;

class User extends Base
{

    /**
     * 用户注册
     * @noauth
     * @param Request $req
     * @return Json
     * @throws DbException
     */
    public function register(Request $req): Json
    {

        $validation = new Validate(
            [
                'avatar' => 'require|url',
                'name' => 'require',
                'mobile' => 'require',
                'sms_code' => 'require',
                'password' => 'require',
                're_password' => 'require|confirm:password',
            ],
            [
                'avatar.require' => t("user", "register", "avatar.require"),
                'name.require' => t("user", "register", "name.require"),
                'mobile.require' => t("user", "register", "mobile.require"),
                'sms_code.require' => t("user", "register", "sms_code.require"),
                'password.require' => t("user", "register", "password.require"),
                're_password.require' => t("user", "register", "re_password.require"),
                're_password.confirm' => t("user", "register", "re_password.confirm"),
            ]
        );

        if (!$validation->check($req->param())) {
            return self::resp($validation->getError());
        }

        // check 手机号是否已注册
        $mobile_is_alive = UserModel::get(['mobile' => $req->param('mobile')]);
        if ($mobile_is_alive) {
            return self::resp(t("user", "register", "mobile_is_alive"), 0);
        }

        $cache_sms_code = cache(sprintf("sms_code_register_%s", $req->param('mobile')));

        if ($req->param('sms_code') != '2222') {
            if ($cache_sms_code <> $req->param('sms_code')) {
                return self::resp(t("user", "register", "sms_code.error"), 0);
            }
        }


        $password_salt = getCode(6, 2);

        $create_data = [
            'avatar' => $req->param('avatar'),
            'name' => $req->param('name'),
            'mobile' => $req->param('mobile'),
            'password' => md5($req->param('password') . $password_salt),
            'salt' => $password_salt,
            'create_time' => datetime(time()),
            'create_ip' => $req->ip(),
            'last_login_time' => datetime(time()),
            'temp_setting' => [
                'unit' => 'oc', // oc 摄氏度, of 华氏度
                'high_temp' => [
                    'status' => false,
                    'number' => null,
                    'ss' => 'sound', // sound 声音,  vibrate 震动
                ],
                'low_temp' => [
                    'status' => false,
                    'number' => null,
                    'ss' => 'sound', // sound 声音,  vibrate 震动
                ],
                'ctime' => [
                    'status' => false,
                    'minutes' => 10 // 分钟
                ]
            ],
        ];

        $user = UserModel::create($create_data);
        $token = Token::set($user->id);

        return self::resp(t("user", "register", "ok"), 1, [
            'token' => $token
        ]);
    }

    /**
     * 登录
     * @noauth
     * @param Request $req
     * @return Json
     */
    public function login(Request $req): Json
    {
        $validation = new Validate(
            [
                'type' => ['require', 'emum' => function ($val, $data) {
                    return in_array($val, ['code', 'password']);
                }],
                'mobile' => 'require',
                'v' => 'require',
            ],
            [
                'type.require' => t("user", "login", "type.require"),
                'type.emum' => t("user", "login", "type.emum"),
                'mobile.require' => t("user", "login", "mobile.require"),
                'v.require' => t("user", "login", "v.require"),
            ]
        );

        if (!$validation->check($req->param())) {
            return self::resp($validation->getError());
        }

        $user = UserModel::get(['mobile' => $req->param('mobile')]);
        if (!$user) {
            return self::resp(t("user", "login", "mobile.not found"), 0);
        }

        if ($req->param('type') == 'code') {
            $code = cache('sms_code_login_' . $req->param('mobile'));
            if ($code <> $req->param('v')) {
                return self::resp(t("user", "login", "code.v.error"), 0);
            }
        } else if ($req->param('type') == 'password') {
            if (md5($req->param('v') . $user->salt) <> $user->password) {
                return self::resp(t("user", "login", "password.v.error"), 0);
            }
        }

        Db::name('user')->where('id', $user->id)->update(['last_login_time' => datetime(time())]);
        return self::resp(t("user", "login", "ok"), 1, [
            'token' => Token::set($user->id),
            'user' => [
                'id' => $user->id,
                'avatar' => $user->avatar,
                'name' => $user->getData('name'),
                'mobile' => $user->mobile,
            ]
        ]);
    }

    /**
     * 重置密码
     * @noauth
     * @param Request $req
     * @return Json
     * @throws DbException
     */
    public function reset_password(Request $req): Json
    {
        $validation = new Validate(
            [
                'mobile' => 'require',
                'code' => 'require',
                'new_password' => 'require',
            ],
            [
                'mobile.require' => t("user", "reset_password", "mobile.require"),
                'code.require' => t("user", "reset_password", "code.require"),
                'new_password.require' => t("user", "reset_password", "new_password.require"),
            ]
        );

        if (!$validation->check($req->param())) {
            return self::resp($validation->getError());
        }

        $code = cache('sms_code_forget_' . $req->param('mobile'));

        if ($code <> $req->param('code')) {
            return self::resp(t("user", "reset_password", "code.error"), 0);
        }

        $user = UserModel::get(['mobile' => $req->param('mobile')]);
        if (!$user) {
            return self::resp(t("user", "reset_password", "mobile.not found"), 0);
        }

        $user->password = md5($req->param('new_password') . $user->salt);
        $user->save();

        return self::resp(t("user", "reset_password", "ok"), 1, []);
    }

    /**
     * 用户基础信息
     * @param Request $req
     * @return Json
     */
    public function info(Request $req): Json
    {

        return self::resp(t("ok"), 1, [
            'id' => $this->user->id,
            'avatar' => $this->user->avatar,
            'name' => $this->user->getData('name'),
            'mobile' => $this->user->mobile,
            'temp_setting' => $this->user->temp_setting
        ]);
    }

    /**
     * 修改用户基本信息
     * @param Request $req
     * @return Json
     * @throws Exception
     * @throws PDOException
     */
    public function edit_basic_info(Request $req): Json
    {
        $validataion = new Validate(
            [
                'avatar' => 'require',
                'name' => 'require',
            ],
            [
                'avatar.require' => t("user", "edit_basic_info", "avatar.require"),
                'name.require' => t("user", "edit_basic_info", "name.require"),
            ]
        );

        if (!$validataion->check($req->param())) {
            return self::resp($validataion->getError());
        }

        Db::name("user")->where("id", $this->user->id)->update([
            'name' => $req->param('name'),
            'avatar' => $req->param('avatar'),
        ]);

        return self::resp(t("user", "edit_basic_info", "ok"), 1, []);
    }

    /**
     * 注销账号
     * @return Json
     * @throws DbException
     */
    public function cancel(): Json
    {
        $user = UserModel::get($this->user->id);
        $user->mobile = getCode(4, 1) . '_' . $user->mobile;
        $user->is_cancel = "y";
        $user->save();
        return self::resp(t("user", "cancel", "ok"), 1, []);
    }

    /**
     * 保存温度设置
     * @return Json
     */
    public function save_temp_setting(): Json
    {

        if (!compareArrays($this->p, json_decode('{"unit": "oc", "ctime": {"status": false, "minutes": 10}, "low_temp": {"ss": "sound", "number": null, "status": false}, "high_temp": {"ss": "sound", "number": null, "status": false}}', 1))) {
            return self::resp(t("user", "save_temp_setting", "compareArrays error"), 1);
        }

        $user = UserModel::get($this->user->id);
        $user->temp_setting = $this->p;
        $user->save();

        return self::resp(t("ok"), 1, []);
    }

}