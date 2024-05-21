CREATE TABLE IF NOT EXISTS `__PREFIX__shorturl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT '' COMMENT '标题',
  `hash` varchar(50) NOT NULL DEFAULT '' COMMENT '短码',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'URL',
  `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `expire` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启过期',
  `expiretime` int(10) DEFAULT NULL COMMENT '过期时间',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `memo` varchar(100) DEFAULT '' COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短网址表';

--
-- 修复原链接过长保存不全的问题（v1.0.1）
--
BEGIN;
ALTER TABLE `__PREFIX__shorturl` MODIFY COLUMN `url` text NOT NULL COMMENT 'URL' AFTER `hash`;
COMMIT;

--
-- 增加访问限制功能（v1.0.2）
--
BEGIN;
ALTER TABLE `__PREFIX__shorturl` ADD COLUMN `allow_qq` tinyint(1) NOT NULL DEFAULT '1' COMMENT '允许QQ访问' AFTER `views`;
ALTER TABLE `__PREFIX__shorturl` ADD COLUMN `allow_wechat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '允许微信访问' AFTER `allow_qq`;
ALTER TABLE `__PREFIX__shorturl` ADD COLUMN `allow_pc_browser` tinyint(1) NOT NULL DEFAULT '1' COMMENT '允许PC浏览器访问' AFTER `allow_wechat`;
ALTER TABLE `__PREFIX__shorturl` ADD COLUMN `allow_mobile_browser` tinyint(1) NOT NULL DEFAULT '1' COMMENT '允许手机浏览器访问' AFTER `allow_pc_browser`;
COMMIT;

--
-- v1.0.3 规范插件结构
--
ALTER TABLE `__PREFIX__shorturl` RENAME TO `__PREFIX__shorturl_url`;