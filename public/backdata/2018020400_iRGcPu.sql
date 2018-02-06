-- PHP SQL Backup
-- Time:2018-02-04 00:05:25
-- https://www.faryao.cn-- E-mail:bainao888@163.com
-- QQ:735613158

--
-- 表的结构 `tp_addons`
-- 
DROP TABLE IF EXISTS `tp_addons`;
CREATE TABLE `tp_addons` (
  `id_addons` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `addons_name` varchar(40) NOT NULL COMMENT '插件英文标识，区分大小写',
  `addons_title` varchar(20) NOT NULL COMMENT '插件中文名',
  `addons_description` text NOT NULL COMMENT '插件描述',
  `addons_config` text NOT NULL COMMENT '配置 序列化存放',
  `addons_author` varchar(40) NOT NULL COMMENT '作者',
  `addons_version` varchar(20) NOT NULL COMMENT '版本号',
  `addons_create_time` int(10) unsigned NOT NULL COMMENT '安装时间',
  `addons_has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1有后台列表0无后台列表',
  `addons_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1启用0禁用',
  `addons_isfree` tinyint(1) NOT NULL DEFAULT '0' COMMENT '收费0免费1收费',
  `addons_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  PRIMARY KEY (`id_addons`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件';
--
-- 表的结构 `tp_admin`
-- 
DROP TABLE IF EXISTS `tp_admin`;
CREATE TABLE `tp_admin` (
  `id_admin` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user` varchar(20) NOT NULL COMMENT '用户名',
  `admin_password` char(32) NOT NULL COMMENT 'md5密码',
  `admin_code` char(6) NOT NULL COMMENT '随机验证码',
  `admin_nickname` char(20) NOT NULL COMMENT '姓名',
  `admin_ip` char(20) NOT NULL COMMENT 'IP',
  `admin_status` int(1) NOT NULL DEFAULT '0' COMMENT '-1禁用0正常',
  `admin_remark` varchar(255) NOT NULL COMMENT '禁用备注',
  `admin_create_time` int(1) unsigned NOT NULL COMMENT '创建时间',
  `admin_update_time` int(1) unsigned NOT NULL COMMENT '更新时间',
  `admin_delete_time` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id_admin`),
  KEY `admin_user` (`admin_user`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='后台用户';

-- 
-- 导出`tp_admin`表中的数据 `tp_admin`
--
INSERT INTO `tp_admin` VALUES (1,'admin','d45321f42512f02d92f96944ac9fe3e0','zTbkcQ','admin','127.0.0.1',0,'',1517410959,1517410959,0);
INSERT INTO `tp_admin` VALUES (2,'demo','4b7b403f314a9575b7e3b49a832b52c7','YZFaqx','demo','127.0.0.1',0,'',1517411184,1517411184,0);
INSERT INTO `tp_admin` VALUES (3,'public','906f9579e7f0415f47c0a2dbe9369f21','RhTkex','public','127.0.0.1',0,'',1517413241,1517413241,0);
INSERT INTO `tp_admin` VALUES (4,'public1','ca272f6d26b8fde2613aec57c1eb2366','BVepsG','public1','127.0.0.1',0,'',1517413762,1517413762,0);
INSERT INTO `tp_admin` VALUES (5,'public1','9095f59ce79c1642630525217e51b738','GfRyXT','public1','127.0.0.1',0,'',1517413834,1517413834,0);
--
-- 表的结构 `tp_auth_access`
-- 
DROP TABLE IF EXISTS `tp_auth_access`;
CREATE TABLE `tp_auth_access` (
  `uid` int(10) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户用户组关系对应表';

-- 
-- 导出`tp_auth_access`表中的数据 `tp_auth_access`
--
INSERT INTO `tp_auth_access` VALUES (1,1);
INSERT INTO `tp_auth_access` VALUES (2,4);
INSERT INTO `tp_auth_access` VALUES (3,4);
INSERT INTO `tp_auth_access` VALUES (4,3);
INSERT INTO `tp_auth_access` VALUES (5,3);
--
-- 表的结构 `tp_auth_group`
-- 
DROP TABLE IF EXISTS `tp_auth_group`;
CREATE TABLE `tp_auth_group` (
  `id_auth_group` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `auth_group_title` char(20) NOT NULL COMMENT '名称',
  `auth_group_description` varchar(80) NOT NULL COMMENT '描述',
  `auth_group_status` tinyint(1) NOT NULL COMMENT '状态1正常0禁用',
  PRIMARY KEY (`id_auth_group`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='权限组';

-- 
-- 导出`tp_auth_group`表中的数据 `tp_auth_group`
--
INSERT INTO `tp_auth_group` VALUES (1,'超级管理员','最高权限',1);
INSERT INTO `tp_auth_group` VALUES (2,'管理员','管理员',1);
INSERT INTO `tp_auth_group` VALUES (3,'运营人员','进行常规增删改查',1);
INSERT INTO `tp_auth_group` VALUES (4,'注册人员','只能进行预览不能更改或删除',1);
--
-- 表的结构 `tp_auth_role`
-- 
DROP TABLE IF EXISTS `tp_auth_role`;
CREATE TABLE `tp_auth_role` (
  `gid` int(11) NOT NULL,
  `rules` text NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='组合权限关系表';
--
-- 表的结构 `tp_cate_article`
-- 
DROP TABLE IF EXISTS `tp_cate_article`;
CREATE TABLE `tp_cate_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(1) DEFAULT NULL,
  `update_time` int(1) DEFAULT NULL,
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章分类';

-- 
-- 导出`tp_cate_article`表中的数据 `tp_cate_article`
--
INSERT INTO `tp_cate_article` VALUES (1,'默认分类',0,0,0,0);
--
-- 表的结构 `tp_cate_download`
-- 
DROP TABLE IF EXISTS `tp_cate_download`;
CREATE TABLE `tp_cate_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(1) DEFAULT NULL,
  `update_time` int(1) DEFAULT NULL,
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='下载分类';

-- 
-- 导出`tp_cate_download`表中的数据 `tp_cate_download`
--
INSERT INTO `tp_cate_download` VALUES (1,'默认分类',0,0,0,0);
--
-- 表的结构 `tp_cate_news`
-- 
DROP TABLE IF EXISTS `tp_cate_news`;
CREATE TABLE `tp_cate_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(1) DEFAULT NULL,
  `update_time` int(1) DEFAULT NULL,
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='新闻分类';

-- 
-- 导出`tp_cate_news`表中的数据 `tp_cate_news`
--
INSERT INTO `tp_cate_news` VALUES (1,'企业新闻',0,0,0,0);
INSERT INTO `tp_cate_news` VALUES (2,'行业新闻',0,0,0,0);
--
-- 表的结构 `tp_cate_picture`
-- 
DROP TABLE IF EXISTS `tp_cate_picture`;
CREATE TABLE `tp_cate_picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(1) DEFAULT NULL,
  `update_time` int(1) DEFAULT NULL,
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='图片分类';

-- 
-- 导出`tp_cate_picture`表中的数据 `tp_cate_picture`
--
INSERT INTO `tp_cate_picture` VALUES (1,'默认分类',0,0,0,0);
--
-- 表的结构 `tp_document`
-- 
DROP TABLE IF EXISTS `tp_document`;
CREATE TABLE `tp_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `module_id` int(10) unsigned NOT NULL COMMENT '模型id',
  `category_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `thumb_id` mediumint(8) unsigned NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `is_new` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否最新',
  `is_top` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '数据状态（0-禁用，1-正常，2-待审核，3-草稿）',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级（越高排序越靠前）',
  `create_time` int(1) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(1) unsigned NOT NULL COMMENT '更新时间',
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表';

-- 
-- 导出`tp_document`表中的数据 `tp_document`
--
INSERT INTO `tp_document` VALUES (1,1,1,1,20,'价格9999257488dff','见客户1234fffrer',0,1,1,0,0,1516967094,1517474256,0);
INSERT INTO `tp_document` VALUES (2,1,2,1,7,'gdg','gdgd',0,1,1,0,0,1516967342,1517570042,0);
INSERT INTO `tp_document` VALUES (3,1,1,1,17,'可立即离开离开家887878','脚后跟和借记卡健康良好',1,0,1,0,0,1517019159,1517382102,0);
INSERT INTO `tp_document` VALUES (4,1,1,1,9,'给对方','人的共和党',1,1,1,0,0,1517019364,1517019364,0);
INSERT INTO `tp_document` VALUES (5,1,1,1,9,'给对方','人的共和党',1,1,1,0,0,1517019407,1517019407,0);
INSERT INTO `tp_document` VALUES (6,1,1,1,9,'给对方865','人的共和党',1,1,1,0,0,1517019442,1517383154,0);
INSERT INTO `tp_document` VALUES (7,1,1,1,0,'给对方889','人的共和党',1,1,1,0,0,1517210261,1517210261,0);
INSERT INTO `tp_document` VALUES (8,1,1,1,0,'价格558','见客户',1,1,1,0,0,1517305166,1517305166,0);
INSERT INTO `tp_document` VALUES (9,1,1,1,0,'价格999','见客户',1,1,1,0,0,1517305182,1517305182,0);
INSERT INTO `tp_document` VALUES (10,1,1,1,21,'demo for article','hahah',1,1,0,0,0,1517383370,1517626155,0);
INSERT INTO `tp_document` VALUES (11,1,2,1,22,'进口红酒888','就会加快',0,1,1,0,0,1517569089,1517570353,0);
INSERT INTO `tp_document` VALUES (12,1,2,2,0,'564654','865498465',1,1,1,0,0,1517570938,1517570938,0);
--
-- 表的结构 `tp_document_article`
-- 
DROP TABLE IF EXISTS `tp_document_article`;
CREATE TABLE `tp_document_article` (
  `id` int(10) unsigned NOT NULL,
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL COMMENT '文档收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型';

-- 
-- 导出`tp_document_article`表中的数据 `tp_document_article`
--
INSERT INTO `tp_document_article` VALUES (3,'&lt;p&gt;环境更健康接口看&lt;/p&gt;','info',0);
INSERT INTO `tp_document_article` VALUES (6,'&lt;p&gt;大范甘迪&lt;/p&gt;','选择模板',0);
INSERT INTO `tp_document_article` VALUES (7,'&lt;p&gt;大范甘迪&lt;/p&gt;','info',0);
INSERT INTO `tp_document_article` VALUES (8,'&lt;p&gt;fsdfg&amp;nbsp;&lt;/p&gt;','选择模板',0);
INSERT INTO `tp_document_article` VALUES (9,'&lt;p&gt;fvbfxcb&lt;/p&gt;','选择模板',0);
INSERT INTO `tp_document_article` VALUES (1,'&lt;p&gt;更符合法规和888cxvdfgd&amp;nbsp;&lt;/p&gt;','选择模板',0);
INSERT INTO `tp_document_article` VALUES (10,'&lt;p&gt;gdfg tgrehgth&lt;/p&gt;','选择模板',0);
--
-- 表的结构 `tp_document_download`
-- 
DROP TABLE IF EXISTS `tp_document_download`;
CREATE TABLE `tp_document_download` (
  `id` int(10) unsigned NOT NULL,
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='下载模型';
--
-- 表的结构 `tp_document_news`
-- 
DROP TABLE IF EXISTS `tp_document_news`;
CREATE TABLE `tp_document_news` (
  `id` int(10) unsigned NOT NULL,
  `content` text NOT NULL COMMENT '新闻内容',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL COMMENT '文档收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻模型';

-- 
-- 导出`tp_document_news`表中的数据 `tp_document_news`
--
INSERT INTO `tp_document_news` VALUES (11,'&lt;p&gt;与他故意煱交换机&lt;/p&gt;&lt;p&gt;&lt;img src=&quot;http://www.test.tp5.com\public\uploads\ueditor\20180202\56799365f99234f0bfe259852182d674.png&quot; title=&quot;&quot; alt=&quot;&quot; width=&quot;227&quot; height=&quot;195&quot;/&gt;585&lt;/p&gt;','选择模板',0);
INSERT INTO `tp_document_news` VALUES (12,'&lt;p&gt;yhjghjghkj&lt;/p&gt;','info',0);
--
-- 表的结构 `tp_document_picture`
-- 
DROP TABLE IF EXISTS `tp_document_picture`;
CREATE TABLE `tp_document_picture` (
  `id` int(10) unsigned NOT NULL,
  `content` text NOT NULL COMMENT '图片描述',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL COMMENT '图片ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片模型';
--
-- 表的结构 `tp_goods`
-- 
DROP TABLE IF EXISTS `tp_goods`;
CREATE TABLE `tp_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `module_id` int(10) unsigned NOT NULL COMMENT '模型id',
  `category_id` int(10) unsigned NOT NULL COMMENT '分类id',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `is_new` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否最新',
  `is_top` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价格',
  `sale_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `stock` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '库存数',
  `picture_id` int(10) unsigned NOT NULL COMMENT '缩略图id',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '数据状态（-1-删除，0-禁用，1-正常，2-待审核，3-草稿）',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级（越高排序越靠前）',
  `create_time` int(1) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(1) unsigned NOT NULL COMMENT '更新时间',
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `title_2` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品模型基础表';
--
-- 表的结构 `tp_goods_cate`
-- 
DROP TABLE IF EXISTS `tp_goods_cate`;
CREATE TABLE `tp_goods_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(1) DEFAULT NULL,
  `update_time` int(1) DEFAULT NULL,
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品分类';
--
-- 表的结构 `tp_goods_goods`
-- 
DROP TABLE IF EXISTS `tp_goods_goods`;
CREATE TABLE `tp_goods_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '图片描述',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品模型';
--
-- 表的结构 `tp_images`
-- 
DROP TABLE IF EXISTS `tp_images`;
CREATE TABLE `tp_images` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='图片资源';

-- 
-- 导出`tp_images`表中的数据 `tp_images`
--
INSERT INTO `tp_images` VALUES (1,'\public\uploads\document\20180126\a869c71e7621ac0471441a3a859302e1.jpg');
INSERT INTO `tp_images` VALUES (2,'\public\uploads\document\20180126\6213fdbb546e7b93af271760660c69e0.jpg');
INSERT INTO `tp_images` VALUES (3,'\public\uploads\document\20180126\c19cd1bedcd8cbea4f727c04f625de24.jpg');
INSERT INTO `tp_images` VALUES (4,'\public\uploads\document\20180126\58a112a5598d20ed94c72a4d43199993.jpg');
INSERT INTO `tp_images` VALUES (5,'\public\uploads\document\20180126\720d73726f609625f80784f1beb70c42.jpg');
INSERT INTO `tp_images` VALUES (6,'\public\uploads\document\20180126\18db81f386015e769db91b15b25da8ef.jpg');
INSERT INTO `tp_images` VALUES (7,'\public\uploads\document\20180126\5cda9a114f74ef8cf888879a32cc9514.jpg');
INSERT INTO `tp_images` VALUES (17,'\public\uploads\document\20180131\5ef99d22bc4b5d77e9de16a4061d9bc8.png');
INSERT INTO `tp_images` VALUES (9,'\public\uploads\document\20180127\b5090b1034bf3c0aed69b8bd2d9b57b2.jpg');
INSERT INTO `tp_images` VALUES (10,'\public\uploads\document\20180130\1d57dc1f3dc8065b78da6414641d6b64.png');
INSERT INTO `tp_images` VALUES (11,'\public\uploads\document\20180130\9004054a744091467f65cdf354e3b84c.png');
INSERT INTO `tp_images` VALUES (12,'\public\uploads\document\20180130\945236b21e71f5314ae418147df4f7ab.png');
INSERT INTO `tp_images` VALUES (13,'\public\uploads\ueditor\20180130\0dd0c763a8c8d5a156ad2caa254e0a40.png');
INSERT INTO `tp_images` VALUES (21,'\public\uploads\document\20180131\2482ddefd7bde662996c0dd4d4e0b331.png');
INSERT INTO `tp_images` VALUES (15,'\public\uploads\document\20180131\e216ca6ee2680640443a864a5c6bc09e.png');
INSERT INTO `tp_images` VALUES (20,'\public\uploads\document\20180131\98fb70692bf4a0c5d61cc714912b09ad.png');
INSERT INTO `tp_images` VALUES (22,'\public\uploads\document\20180202\56f00842ac7e35fdc95a4baa7e37447c.jpg');
INSERT INTO `tp_images` VALUES (23,'\public\uploads\document\20180202\f69c9f7c970e4bdf44aee3852526b107.jpg');
--
-- 表的结构 `tp_menu`
-- 
DROP TABLE IF EXISTS `tp_menu`;
CREATE TABLE `tp_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `url` varchar(255) NOT NULL,
  `param` varchar(255) NOT NULL COMMENT '参数',
  `listorder` int(11) NOT NULL DEFAULT '0',
  `pid` int(1) NOT NULL DEFAULT '0',
  `create_time` int(1) DEFAULT NULL,
  `update_time` int(1) DEFAULT NULL,
  `delete_time` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COMMENT='后台菜单';

-- 
-- 导出`tp_menu`表中的数据 `tp_menu`
--
INSERT INTO `tp_menu` VALUES (1,'内容管理','am-icon-list','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (2,'系统设置','am-icon-gears','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (3,'用户管理','am-icon-user','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (4,'权限管理','am-icon-tasks','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (5,'日志管理','am-icon-history','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (6,'数据备份','am-icon-database','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (7,'回收站','am-icon-trash-o','','',0,0,1517384449,1517384449,0);
INSERT INTO `tp_menu` VALUES (8,'文档','','admin/document/lists','id=1&m=article',0,1,1517384534,1517384534,0);
INSERT INTO `tp_menu` VALUES (9,'新闻','','admin/document/lists','id=2&m=news',0,1,1517384534,1517384534,0);
INSERT INTO `tp_menu` VALUES (10,'下载','','admin/document/lists','id=3&m=download',0,1,1517384534,1517384534,0);
INSERT INTO `tp_menu` VALUES (11,'图片','','admin/document/lists','id=4&m=picture',0,1,1517384534,1517384534,0);
INSERT INTO `tp_menu` VALUES (12,'产品','','admin/product/goods','',0,1,1517384534,1517384534,0);
INSERT INTO `tp_menu` VALUES (13,'网站设置','','admin/set/site','',0,2,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (14,'分类管理','','admin/cate/lists','',0,2,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (15,'模型管理','','admin/module/getlist','',0,2,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (16,'配置管理','','admin/set/config','',0,2,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (17,'菜单管理','','admin/menu/lists','',0,2,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (18,'导航管理','','admin/set/nav','',0,2,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (19,'用户组','','admin/group/lists','',0,3,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (20,'积分管理','','admin/user/score','',0,3,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (21,'用户列表','','admin/user/lists','',0,3,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (22,'用户组','','admin/role/group','',0,4,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (23,'用户列表','','admin/role/lists','',0,4,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (24,'行为日志','','admin/logs/lists','',0,5,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (25,'备份数据','','admin/sql/back','',0,6,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (26,'还原数据','','admin/sql/restore','',0,6,1517384941,1517384941,0);
INSERT INTO `tp_menu` VALUES (27,'已删除模型','','admin/module/trash','',0,7,1517384941,1517384941,0);
--
-- 表的结构 `tp_module`
-- 
DROP TABLE IF EXISTS `tp_module`;
CREATE TABLE `tp_module` (
  `id_module` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(20) NOT NULL COMMENT '模块名字英文',
  `module_title` varchar(20) NOT NULL COMMENT '模块名中文',
  `module_create_time` int(1) NOT NULL COMMENT '创建时间',
  `module_update_time` int(1) NOT NULL COMMENT '更新时间',
  `module_delete_time` int(1) DEFAULT NULL,
  `module_status` int(1) NOT NULL DEFAULT '1' COMMENT '状态1正常0禁用',
  `is_sys` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统',
  PRIMARY KEY (`id_module`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='模块';

-- 
-- 导出`tp_module`表中的数据 `tp_module`
--
INSERT INTO `tp_module` VALUES (1,'article','文档模型',1516806506,1516806506,0,1,1);
INSERT INTO `tp_module` VALUES (2,'news','新闻模型',1516806609,1516806609,0,1,1);
INSERT INTO `tp_module` VALUES (3,'download','下载模型',1516806743,1516806743,0,1,1);
INSERT INTO `tp_module` VALUES (4,'picture','图片模型',1516806763,1516806763,0,1,1);
INSERT INTO `tp_module` VALUES (5,'goods','产品模型',1516806780,1516806780,0,1,1);
--
-- 表的结构 `tp_user`
-- 
DROP TABLE IF EXISTS `tp_user`;
CREATE TABLE `tp_user` (
  `uid` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `create_time` int(1) NOT NULL,
  `update_time` int(1) NOT NULL,
  `ip` varchar(20) NOT NULL COMMENT 'ip',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `email` varchar(40) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户';

-- 
-- 导出`tp_user`表中的数据 `tp_user`
--
INSERT INTO `tp_user` VALUES (1,'无赖','714bca55eae496a2c8cf110bdf2c3f52',1516346359,1516346359,'127.0.0.1',0,'bainao888@163.com');