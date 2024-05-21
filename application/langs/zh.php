<?php

return [
    'ok' => '操作成功',
    'fail' => '操作失败',
    'user' => [
        'register' => [
            'avatar.require' => '请上传头像',
            'name.require' => '请输入昵称',
            'mobile.require' => '请输入手机号',
            'sms_code.require' => '请输入手机验证码',
            'sms_code.error' => '验证码错误',
            'password.require' => '请输入密码',
            're_password.require' => '请输入确认密码',
            're_password.confirm' => '输入的密码不一致',
            'mobile_is_alive' => '手机号已经被注册过了',
            "ok" => '注册成功',
        ],
        "login" => [
            "ok" => '登录成功',
            'type.require' => '请选择登录方式',
            'type.emum' => '错误的登录方式',
            'mobile.require' => '请输入手机号',
            'v.require' => '请输入验证码或密码',
            'mobile.not found' => '手机号还未注册',
            'code.v.error' => '验证码错误',
            'password.v.error' => '登录密码错误',
        ],
        'reset_password' => [
            'ok' => '密码重置成功',
            'mobile.require' => '请输入手机号',
            'code.require' => '请输入验证码',
            'new_password.require' => '请输入新密码',
            'code.error' => '验证码错误',
            'mobile.not found' => '手机号还未注册',

        ],
        'edit_basic_info' => [
            'ok' => '修改成功',
            'avatar.require' => '头像不能空',
            'name.require' => '昵称不能为空'
        ],
        "cancel" => [
            "ok" => '账号已注销, 请重新登录'
        ],
        'save_temp_setting' => [
            'compareArrays error' => '请传入正确的温度数据结构'
        ]
    ],
    'sms' => [
        'ok' => '发送成功',
        'fail' => '发送失败',
        'type.require' => '请选择短信类型',
        'mobile.require' => '请输入要发送的手机号',
        'mobile.mobile' => '请输入正确的手机号',
        'send more' => '发送频繁, 请稍候再试'
    ],
    'feedback' => [
        'ok' => '提交成功',
        'submit more' => '您提交的太频繁了, 请稍候再试'
    ],
    'agreement' => [
        'not found' => '协议不存在'
    ],
    'device' => [
        'add ok' => '绑定成功',
        'del ok' => '删除成功',
        'no device' => '设备不存在',
        'uptemp' => [
            'ok' => '上报成功',
            'device_id require' => '请传入设备ID',
            'fm_id require' => '请传入成员ID',
            'temp_oc require' => '请传入温度',
            'time require' => '请传入时间',
            'temp_oc number' => '请传入正确的摄氏度',
            'time number' => '请传入正确的时间戳',
            'up more' => '提交过于频繁'

        ],
        'temp_log' => [
            'fm_id require' => '请选择要获取的成员'
        ]
    ],
    'fm' => [
        'add ok' => '成员添加成功',
        'not found' => '成员不存在',
        'add' => [
            'avatar.require' => '请上传头像',
            'nickname.require' => '请输入昵称',
            'age.require' => '请输入年龄',
            'age.number' => '请输入正确的年龄',
            'sex.require' => '请选择性别',
            'sex.enum' => '性别只能为男或女',
            'height.require' => '请输入身高',
            'height.number' => '请输入正确的身高',
            'weight.require' => '请输入体重',
            'weight.number' => '请输入正确的体重',

        ],
        'del' => [
            "ok" => '删除成功'
        ],
        'edit' => [
            'ok' => '修改成功'
        ]
    ],
    'mor' => [
        'list' => [],
        'add' => [
            'fm_id require' => '请选择成员',
            'time require' => '请选择时间',
            'time number' => '请选择正确的时间',
            'cooling_mode.require' => '请选择降温方式',
            'symptoms.require' => '请选择症状',
            'add more' => '提交的太快了',
            "ok" => '记录成功'
        ],
        'del' => [
            'not found' => '记录不存在',
            'ok' => '删除成功'
        ],
        'edit' => [
            'ok' => '修改成功',
            'not found' => '记录不存在',

        ]
    ],
    'report' => [
        'search' => [
            'no data' => '暂无体温数据'
        ]
    ]
];