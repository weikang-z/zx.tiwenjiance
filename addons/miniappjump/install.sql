SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 表的结构 `__PREFIX__miniappjump_app`
--

CREATE TABLE IF NOT EXISTS `__PREFIX__miniappjump_app` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `platform` enum('wxapp', 'aliapp', 'baiduapp', 'toutiaoapp', 'qqapp') NOT NULL DEFAULT 'wxapp' COMMENT '平台',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `background` varchar(255) NOT NULL DEFAULT '' COMMENT '背景图',
  `appid` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序appId',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '页面路径',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='小程序表' ROW_FORMAT=COMPACT;