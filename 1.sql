create table user
(
    id              int auto_increment primary key,
    avatar          varchar(255) comment '头像',
    name            varchar(50) comment '昵称',
    mobile          varchar(20)    not null unique comment '手机号',
    password        char(32)       not null comment '登录密码',
    salt            char(6)        not null comment 'password.salt',
    create_time     datetime comment '注册时间',
    create_ip       varchar(30) comment '注册IP',
    last_login_time datetime comment '最后一次登录时间',
    temp_setting    json comment '温度设置',
    is_cancel       enum ('y','n') not null default 'n' comment '是否已注销:y=是,n=否'
) comment '用户管理';

create table faq
(
    id        int auto_increment primary key,
    title     varchar(255) not null comment '标题',
    title_en  varchar(255) not null comment '标题(英文)',
    answer    text comment '结果',
    answer_en text comment '结果(英文)',
    weigh     int(10) comment '权重',
    switch    tinyint(1)   not null default 1 comment '是否显示'
) comment 'FAQ';


create table feedback
(
    id          int auto_increment primary key,
    user_id     int,
    content     text comment '反馈内容',
    images      text comment '反馈图片',
    create_time datetime comment '反馈时间',
    switch      tinyint(1) not null default 0 comment '处理状态'
) comment '意见反馈';

create table agreement
(
    id         int auto_increment primary key,
    skey       varchar(20) not null unique comment '协议标识',
    title      varchar(255) comment '标题',
    title_en   varchar(255) comment '标题(英文)',
    content    longtext comment '正文',
    content_en longtext comment '正文(英文)'
) comment '文章&协议';


create table start_page_config
(
    id       int auto_increment primary key,
    zh_image varchar(255) comment '图片(中文)',
    zh_text  text comment '描述(中文)',
    en_image varchar(255) comment '图片(英文)',
    en_text  text comment '描述(英文)',
    weigh    int comment '排序'
) comment '启动页配置';


create table user_device
(
    id      int auto_increment primary key,
    user_id int,
    foreign key (user_id) references user (id) on delete cascade,
    name    varchar(255) comment '设备名称',
    extend  json comment '设备其他信息'
) comment '用户设备列表';

create table device_use_comment
(
    id       int auto_increment primary key,
    zh_image varchar(255) comment '图片(中文)',
    zh_text  text comment '描述(中文)',
    en_image varchar(255) comment '图片(英文)',
    en_text  text comment '描述(英文)',
    weigh    int comment '排序'
) comment '设备使用说明';


# drop table if exists family_member;
create table family_member
(
    id          int auto_increment primary key,
    user_id     int,
    foreign key (user_id) references user (id) on delete cascade,
    avatar      varchar(255) comment '头像',
    nickname    varchar(50) comment '昵称',
    age         varchar(30) comment '年龄',
    sex         enum ('男','女') comment '性别',
    height      varchar(30) comment '身高',
    weight      varchar(30) comment '体重',
    create_time datetime default current_timestamp
) comment '家庭成员';


create table family_temp_log
(
    id          int auto_increment primary key,
    user_id     int,
    foreign key (user_id) references user (id) on delete cascade,
    fm_id       int,
    foreign key (fm_id) references family_member (id) on delete cascade,
    device_id   int,
    temp        varchar(30) comment '温度(摄氏度)',
    up_time     datetime comment '上报时间',
    create_time datetime default current_timestamp comment '记录时间'
) comment '成员温度记录';

create table symptoms_data
(
    id      int auto_increment primary key,
    text_zh varchar(255) comment '中文',
    text_en varchar(255) comment '英文',
    switch  tinyint(1) not null default 1 comment '启用状态',
    weigh   int comment '权重'
) comment '症状数据管理';

create table cooling_mode_data
(
    id      int auto_increment primary key,
    text_zh varchar(255) comment '中文',
    text_en varchar(255) comment '英文',
    switch  tinyint(1) not null default 1 comment '启用状态',
    weigh   int comment '权重'
) comment '降温方式数据管理';


create table family_mor
(
    id           int auto_increment primary key,
    fm_id        int,
    foreign key (fm_id) references family_member (id) on delete cascade,
    utime        datetime comment '上报时间',
    symptoms     varchar(500) comment '症状',
    cooling_mode varchar(500) comment '降温方式',
    remark       varchar(500) comment '备注',
    create_time  datetime default current_timestamp comment '记录时间'
) comment '成员备忘记录';