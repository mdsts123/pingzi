# Host: localhost  (Version: 5.5.40)
# Date: 2016-10-18 00:40:41
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "ly_admin_access"
#

CREATE TABLE `ly_admin_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户组',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台管理员与用户组对应关系表';

/*Data for the table `ly_admin_access` */

insert  into `ly_admin_access`(`id`,`uid`,`group`,`create_time`,`update_time`,`sort`,`status`) values (1,1,1,1438651748,1438651748,0,1);

CREATE TABLE `ly_admin_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '配置标题',
  `name` varchar(32) DEFAULT NULL COMMENT '配置名称',
  `value` text NOT NULL COMMENT '配置值',
  `group` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `type` varchar(16) NOT NULL DEFAULT '' COMMENT '配置类型',
  `options` varchar(255) NOT NULL DEFAULT '' COMMENT '配置额外值',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

/*Data for the table `ly_admin_config` */

insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (1,'站点开关','TOGGLE_WEB_SITE','1',1,'select','0:关闭\r\n1:开启','站点关闭后将不能访问',1378898976,1406992386,1,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (2,'网站标题','WEB_SITE_TITLE','刷码支付',1,'text','','网站标题前台显示标题',1378898976,1379235274,2,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (3,'网站口号','WEB_SITE_SLOGAN','快速充值中心',1,'text','','网站口号、宣传标语、一句话介绍',1434081649,1434081649,3,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (4,'网站LOGO','WEB_SITE_LOGO','',1,'picture','','网站LOGO,格式PNG,宽600px高149px',1407003397,1407004692,4,0);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (6,'网站描述','WEB_SITE_DESCRIPTION','快速充值中心',1,'textarea','','网站搜索引擎描述',1378898976,1379235841,6,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (7,'网站关键字','WEB_SITE_KEYWORD','快三线上',1,'textarea','','网站搜索引擎关键字',1378898976,1381390100,7,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (8,'版权信息','WEB_SITE_COPYRIGHT','Copyright © ovidc All rights reserved.',1,'text','','设置在网站底部显示的版权信息，如“版权所有 © 2014-2015 科斯克网络科技”',1406991855,1406992583,8,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (9,'网站备案号','WEB_SITE_ICP','网站备案号123',1,'text','','设置在网站底部显示的备案号，如“苏ICP备1502009号\"',1378900335,1415983236,9,0);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (10,'站点统计','WEB_SITE_STATISTICS','',1,'textarea','','支持百度、Google、cnzz等所有Javascript的统计代码',1378900335,1415983236,10,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (11,'文件上传大小','UPLOAD_FILE_SIZE','10',2,'num','','文件上传大小单位：MB',1428681031,1428681031,1,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (12,'图片上传大小','UPLOAD_IMAGE_SIZE','2',2,'num','','图片上传大小单位：MB',1428681071,1428681071,2,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (13,'后台多标签','ADMIN_TABS','1',2,'radio','0:关闭\r\n1:开启','',1453445526,1453445526,3,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (14,'分页数量','ADMIN_PAGE_ROWS','10',2,'num','','分页时每页的记录数',1434019462,1434019481,4,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (15,'后台主题','ADMIN_THEME','admin',2,'select','admin:默认主题\r\naliyun:阿里云风格','后台界面主题',1436678171,1436690570,5,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (16,'导航分组','NAV_GROUP_LIST','top:顶部导航\r\nmain:主导航\r\nbottom:底部导航',2,'array','','导航分组',1458382037,1458382061,6,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (17,'配置分组','CONFIG_GROUP_LIST','1:基本\r\n2:系统\r\n3:开发\r\n4:部署',2,'array','','配置分组',1379228036,1426930700,7,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (18,'开发模式','DEVELOP_MODE','0',3,'select','1:开启\r\n0:关闭','开发模式下会显示菜单管理、配置管理、数据字典等开发者工具',1432393583,1432393583,1,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (19,'是否显示页面Trace','SHOW_PAGE_TRACE','0',3,'select','0:关闭\r\n1:开启','是否显示页面Trace信息',1387165685,1387165685,2,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (20,'系统加密KEY','AUTH_KEY','%A[Qc#V<ugo|\"b_.orrjV&(l-Xs-{BlC>LDAN{}xTk_Kx`z<No>g+rpmk:!VcH-=',3,'textarea','','轻易不要修改此项，否则容易造成用户无法登录；如要修改，务必备份原key',1438647773,1438647815,3,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (21,'URL模式','URL_MODEL','2',4,'select','1:PATHINFO模式\r\n2:REWRITE模式\r\n3:兼容模式','',1438423248,1438423248,1,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (22,'支付最小金额','PAY_MIN','10',1,'num','','系统最小金额',1478345496,1492001427,10,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (23,'客服链接','SERURL','http://kf1.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=794671&configID=59264&jid=4794205311',1,'text','','',1478664998,1478664998,255,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (24,'网站域名','WEB_SITE_DOMAIN','www.q.com',1,'text','','',1478664998,1478664998,5,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (25,'用户名黑名单','disable_user','123',1,'textarea','','',1490626733,1490626838,12,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (26,'IP黑名单','disable_ips','',1,'textarea','','',1490626772,1492001499,12,1);
insert  into `ly_admin_config`(`id`,`title`,`name`,`value`,`group`,`type`,`options`,`tip`,`create_time`,`update_time`,`sort`,`status`) values (27,'支付最大金额','PAY_MAX','50000',1,'num','','0为不限制',1492001464,1492001464,11,1);

/*Table structure for table `ly_admin_group` */

CREATE TABLE `ly_admin_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级部门ID',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '部门名称',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '图标',
  `menu_auth` text NOT NULL COMMENT '权限列表',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='部门信息表';

/*Data for the table `ly_admin_group` */

insert  into `ly_admin_group`(`id`,`pid`,`title`,`icon`,`menu_auth`,`create_time`,`update_time`,`sort`,`status`) values (1,0,'超级管理员','','',1426881003,1427552428,0,1);

/*Table structure for table `ly_admin_hook` */

CREATE TABLE `ly_admin_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '钩子ID',
  `name` varchar(32) DEFAULT NULL COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='钩子表';

/*Data for the table `ly_admin_hook` */

insert  into `ly_admin_hook`(`id`,`name`,`description`,`addons`,`type`,`create_time`,`update_time`,`status`) values (1,'AdminIndex','后台首页小工具','后台首页小工具',1,1446522155,1446522155,1);
insert  into `ly_admin_hook`(`id`,`name`,`description`,`addons`,`type`,`create_time`,`update_time`,`status`) values (2,'FormBuilderExtend','FormBuilder类型扩展Builder','',1,1447831268,1447831268,1);
insert  into `ly_admin_hook`(`id`,`name`,`description`,`addons`,`type`,`create_time`,`update_time`,`status`) values (3,'UploadFile','上传文件钩子','',1,1407681961,1407681961,1);
insert  into `ly_admin_hook`(`id`,`name`,`description`,`addons`,`type`,`create_time`,`update_time`,`status`) values (4,'PageHeader','页面header钩子，一般用于加载插件CSS文件和代码','',1,1407681961,1407681961,1);
insert  into `ly_admin_hook`(`id`,`name`,`description`,`addons`,`type`,`create_time`,`update_time`,`status`) values (5,'PageFooter','页面footer钩子，一般用于加载插件CSS文件和代码','RocketToTop',1,1407681961,1407681961,1);
insert  into `ly_admin_hook`(`id`,`name`,`description`,`addons`,`type`,`create_time`,`update_time`,`status`) values (6,'CommonHook','通用钩子，自定义用途，一般用来定制特殊功能','',1,1456147822,1456147822,1);

/*Table structure for table `ly_admin_module` */

CREATE TABLE `ly_admin_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(31) DEFAULT NULL COMMENT '名称',
  `title` varchar(63) NOT NULL DEFAULT '' COMMENT '标题',
  `logo` varchar(63) NOT NULL DEFAULT '' COMMENT '图片图标',
  `icon` varchar(31) NOT NULL DEFAULT '' COMMENT '字体图标',
  `icon_color` varchar(7) NOT NULL DEFAULT '' COMMENT '字体图标颜色',
  `description` varchar(127) NOT NULL DEFAULT '' COMMENT '描述',
  `developer` varchar(31) NOT NULL DEFAULT '' COMMENT '开发者',
  `version` varchar(7) NOT NULL DEFAULT '' COMMENT '版本',
  `user_nav` text NOT NULL COMMENT '个人中心导航',
  `config` text NOT NULL COMMENT '配置',
  `admin_menu` text NOT NULL COMMENT '菜单节点',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许卸载',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='模块功能表';

/*Data for the table `ly_admin_module` */

insert  into `ly_admin_module`(`id`,`name`,`title`,`logo`,`icon`,`icon_color`,`description`,`developer`,`version`,`user_nav`,`config`,`admin_menu`,`is_system`,`create_time`,`update_time`,`sort`,`status`) values (1,'Admin','系统','','fa fa-cog','#3CA6F1','核心系统','光谷科技有限公司','1.0.0','','','{\"1\":{\"pid\":\"0\",\"title\":\"\\u7cfb\\u7edf\",\"icon\":\"fa fa-cog\",\"level\":\"system\",\"id\":\"1\"},\"2\":{\"pid\":\"1\",\"title\":\"\\u7cfb\\u7edf\\u529f\\u80fd\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"2\"},\"3\":{\"pid\":\"2\",\"title\":\"\\u7cfb\\u7edf\\u8bbe\\u7f6e\",\"icon\":\"fa fa-wrench\",\"url\":\"Admin\\/Config\\/group\",\"id\":\"3\"},\"4\":{\"pid\":\"3\",\"title\":\"\\u4fee\\u6539\\u8bbe\\u7f6e\",\"url\":\"Admin\\/Config\\/groupSave\",\"id\":\"4\"},\"5\":{\"pid\":\"2\",\"title\":\"\\u5bfc\\u822a\\u7ba1\\u7406\",\"icon\":\"fa fa-map-signs\",\"url\":\"Admin\\/Nav\\/index\",\"id\":\"5\"},\"6\":{\"pid\":\"5\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Nav\\/add\",\"id\":\"6\"},\"7\":{\"pid\":\"5\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Nav\\/edit\",\"id\":\"7\"},\"8\":{\"pid\":\"5\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Nav\\/setStatus\",\"id\":\"8\"},\"13\":{\"pid\":\"2\",\"title\":\"\\u914d\\u7f6e\\u7ba1\\u7406\",\"icon\":\"fa fa-cogs\",\"url\":\"Admin\\/Config\\/index\",\"id\":\"13\"},\"14\":{\"pid\":\"13\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Config\\/add\",\"id\":\"14\"},\"15\":{\"pid\":\"13\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Config\\/edit\",\"id\":\"15\"},\"16\":{\"pid\":\"13\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Config\\/setStatus\",\"id\":\"16\"},\"17\":{\"pid\":\"2\",\"title\":\"\\u4e0a\\u4f20\\u7ba1\\u7406\",\"icon\":\"fa fa-upload\",\"url\":\"Admin\\/Upload\\/index\",\"id\":\"17\"},\"18\":{\"pid\":\"17\",\"title\":\"\\u4e0a\\u4f20\\u6587\\u4ef6\",\"url\":\"Admin\\/Upload\\/upload\",\"id\":\"18\"},\"19\":{\"pid\":\"17\",\"title\":\"\\u5220\\u9664\\u6587\\u4ef6\",\"url\":\"Admin\\/Upload\\/delete\",\"id\":\"19\"},\"20\":{\"pid\":\"17\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Upload\\/setStatus\",\"id\":\"20\"},\"21\":{\"pid\":\"17\",\"title\":\"\\u4e0b\\u8f7d\\u8fdc\\u7a0b\\u56fe\\u7247\",\"url\":\"Admin\\/Upload\\/downremoteimg\",\"id\":\"21\"},\"22\":{\"pid\":\"17\",\"title\":\"\\u6587\\u4ef6\\u6d4f\\u89c8\",\"url\":\"Admin\\/Upload\\/fileManager\",\"id\":\"22\"},\"23\":{\"pid\":\"1\",\"title\":\"\\u7cfb\\u7edf\\u6743\\u9650\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"23\"},\"24\":{\"pid\":\"23\",\"title\":\"\\u7528\\u6237\\u7ba1\\u7406\",\"icon\":\"fa fa-user\",\"url\":\"Admin\\/User\\/index\",\"id\":\"24\"},\"25\":{\"pid\":\"24\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/User\\/add\",\"id\":\"25\"},\"26\":{\"pid\":\"24\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/User\\/edit\",\"id\":\"26\"},\"27\":{\"pid\":\"24\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/User\\/setStatus\",\"id\":\"27\"},\"28\":{\"pid\":\"23\",\"title\":\"\\u7ba1\\u7406\\u5458\\u7ba1\\u7406\",\"icon\":\"fa fa-lock\",\"url\":\"Admin\\/Access\\/index\",\"id\":\"28\"},\"29\":{\"pid\":\"28\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Access\\/add\",\"id\":\"29\"},\"30\":{\"pid\":\"28\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Access\\/edit\",\"id\":\"30\"},\"31\":{\"pid\":\"28\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Access\\/setStatus\",\"id\":\"31\"},\"32\":{\"pid\":\"23\",\"title\":\"\\u7528\\u6237\\u7ec4\\u7ba1\\u7406\",\"icon\":\"fa fa-sitemap\",\"url\":\"Admin\\/Group\\/index\",\"id\":\"32\"},\"33\":{\"pid\":\"32\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Group\\/add\",\"id\":\"33\"},\"34\":{\"pid\":\"32\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Group\\/edit\",\"id\":\"34\"},\"35\":{\"pid\":\"32\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Group\\/setStatus\",\"id\":\"35\"},\"36\":{\"pid\":\"1\",\"title\":\"\\u5b9a\\u5355\\u7ba1\\u7406\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"36\"},\"44\":{\"pid\":\"36\",\"title\":\"\\u8ba2\\u5355\\u7ba1\\u7406\",\"icon\":\"fa fa-bars\",\"url\":\"Admin\\/Order\\/index\",\"id\":\"44\"},\"45\":{\"pid\":\"44\",\"title\":\"\\u5220\\u9664\",\"url\":\"Admin\\/Order\\/delete\",\"id\":\"45\"},\"46\":{\"pid\":\"44\",\"title\":\"\\u8bbe\\u7f6e\\u7ed3\\u7b97\\u72b6\\u6001\",\"url\":\"Admin\\/Order\\/setStatus\",\"id\":\"46\"},\"47\":{\"pid\":\"44\",\"title\":\"\\u66f4\\u65b0\\u4ed8\\u6b3e\\u4fe1\\u606f\",\"url\":\"Admin\\/Order\\/updateInfo\",\"id\":\"47\"},\"48\":{\"pid\":\"36\",\"title\":\"\\u652f\\u4ed8\\u63a5\\u53e3\",\"icon\":\"fa fa-cogs\",\"url\":\"Admin\\/Payapi\\/index\",\"id\":\"48\"},\"49\":{\"pid\":\"36\",\"title\":\"\\u4ee3\\u4ed8\\u7ba1\\u7406\",\"icon\":\"fa fa-th\",\"url\":\"Admin\\/Payify\\/index\",\"id\":\"49\"},\"50\":{\"pid\":\"49\",\"title\":\"\\u6dfb\\u52a0\\u4ee3\\u4ed8\",\"url\":\"Admin\\/Payify\\/add\",\"id\":\"50\"},\"51\":{\"pid\":\"49\",\"title\":\"\\u5220\\u9664\",\"url\":\"Admin\\/Payify\\/del\",\"id\":\"51\"},\"52\":{\"pid\":\"49\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Payify\\/setStatus\",\"id\":\"52\"},\"58\":{\"pid\":\"48\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Payapi\\/add\",\"id\":\"58\"},\"59\":{\"pid\":\"48\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Payapi\\/edit\",\"id\":\"59\"},\"60\":{\"pid\":\"48\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Payapi\\/setStatus\",\"id\":\"60\"}}',1,1438651748,1478492426,0,1);

/*Table structure for table `ly_admin_nav` */

CREATE TABLE `ly_admin_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group` varchar(11) NOT NULL DEFAULT '' COMMENT '分组',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(31) NOT NULL DEFAULT '' COMMENT '导航标题',
  `type` varchar(15) NOT NULL DEFAULT '' COMMENT '导航类型',
  `value` text COMMENT '导航值',
  `target` varchar(11) NOT NULL DEFAULT '' COMMENT '打开方式',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '图标',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='前台导航表';

/*Data for the table `ly_admin_nav` */

insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (1,'bottom',0,'关于','link','','','',1449742225,1449742255,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (2,'bottom',1,'关于我们','page','','','',1449742312,1449742312,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (4,'bottom',1,'服务产品','page','','','',1449742597,1449742651,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (5,'bottom',1,'商务合作','page','','','',1449742664,1449742664,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (6,'bottom',1,'加入我们','page','','','',1449742678,1449742697,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (7,'bottom',0,'帮助','page','','','',1449742688,1449742688,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (8,'bottom',7,'用户协议','page','','','',1449742706,1449742706,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (9,'bottom',7,'意见反馈','page','','','',1449742716,1449742716,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (10,'bottom',7,'常见问题','page','','','',1449742728,1449742728,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (11,'bottom',0,'联系方式','page','','','',1449742742,1449742742,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (12,'bottom',11,'联系我们','page','','','',1449742752,1449742752,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (13,'bottom',11,'新浪微博','page','','','',1449742802,1449742802,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (14,'main',0,'首页','link','','','',1457084559,1472993801,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (15,'main',0,'产品中心','page','','','',1457084559,1457084559,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (16,'main',0,'客户服务','page','','','',1457084572,1457084572,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (17,'main',0,'案例展示','page','','','',1457084583,1457084583,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (18,'main',0,'新闻动态','page','','','',1457084714,1457084714,0,1);
insert  into `ly_admin_nav`(`id`,`group`,`pid`,`title`,`type`,`value`,`target`,`icon`,`create_time`,`update_time`,`sort`,`status`) values (19,'main',0,'联系我们','page','','','',1457084725,1457084725,0,1);

/*Table structure for table `ly_admin_post` */

CREATE TABLE `ly_admin_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(127) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `abstract` varchar(255) DEFAULT '' COMMENT '摘要',
  `content` text COMMENT '内容',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章列表';

/*Data for the table `ly_admin_post` */

/*Table structure for table `ly_admin_upload` */

CREATE TABLE `ly_admin_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `url` varchar(255) DEFAULT NULL COMMENT '文件链接',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) DEFAULT NULL COMMENT '文件md5',
  `sha1` char(40) DEFAULT NULL COMMENT '文件sha1编码',
  `location` varchar(15) NOT NULL DEFAULT '' COMMENT '文件存储位置',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  UNIQUE KEY `md5` (`md5`),
  UNIQUE KEY `sha1` (`sha1`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文件上传表';

/*Data for the table `ly_admin_upload` */

insert  into `ly_admin_upload`(`id`,`uid`,`name`,`path`,`url`,`ext`,`size`,`md5`,`sha1`,`location`,`download`,`create_time`,`update_time`,`sort`,`status`) values (1,1,'192000036733e8cd2ffc.jpg','/Uploads/2017-03-20/58cf613035f54.jpg','','jpg',64252,'6cb3139bf7ea641b44b61c3ff10c49af','cc41f8c3641d900e43d12908e3863b59c99c1998','Local',0,1489985840,1489985840,0,1);

/*Table structure for table `ly_admin_user` */

CREATE TABLE `ly_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'UID',
  `user_type` int(11) NOT NULL DEFAULT '1' COMMENT '用户类型',
  `nickname` varchar(63) DEFAULT NULL COMMENT '昵称',
  `username` varchar(31) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(63) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(63) NOT NULL DEFAULT '' COMMENT '邮箱',
  `email_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `mobile_bind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_type` varchar(15) NOT NULL DEFAULT '' COMMENT '注册方式',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户账号表';

/*Table structure for table `ly_category` */

CREATE TABLE `ly_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT '分类名称',
  `title` text COMMENT '标题描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='通道分类表';

/*Data for the table `ly_category` */

insert  into `ly_category`(`id`,`name`,`title`) values (1,'weixin','微信扫码');
insert  into `ly_category`(`id`,`name`,`title`) values (2,'bank','网银支付');
insert  into `ly_category`(`id`,`name`,`title`) values (3,'alipay','支付宝扫码');

/*Table structure for table `ly_order` */

CREATE TABLE `ly_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(250) NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `payid` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0待付1已付',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `payno` varchar(250) NOT NULL,
  `payzt` int(1) NOT NULL DEFAULT '0' COMMENT '0待结1已结',
  `ip` varchar(20) NOT NULL DEFAULT '0',
  `cid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderno` (`orderno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ly_order` */

/*Table structure for table `ly_payapi` */

CREATE TABLE `ly_payapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL DEFAULT '1',
  `payclass` varchar(250) NOT NULL,
  `desc` varchar(255) NOT NULL DEFAULT '',
  `accountId` varchar(250) NOT NULL,
  `accountName` varchar(250) NOT NULL,
  `accountKey` text NOT NULL,
  `private_key` text NOT NULL,
  `public_key` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1启用0禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `ly_payapi` */

insert  into `ly_payapi`(`id`,`cid`,`payclass`,`desc`,`accountId`,`accountName`,`accountKey`,`private_key`,`public_key`,`sort`,`status`) values (13,3,'Qfupay','启付','123','123','','','',0,1);
insert  into `ly_payapi`(`id`,`cid`,`payclass`,`desc`,`accountId`,`accountName`,`accountKey`,`private_key`,`public_key`,`sort`,`status`) values (14,2,'Ownbank','金海哲测试','SFTTEST','','','MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAJ7//05sfPeybycqvGl+svumakuwPrqmYI1rl2cTrqy6p1SvFk445KTsPTQp/puTjMfO6+PiFE0rqA918hySrhsW5swg2tD/VxlFpC5ybzLaOLGG43cp1J9taWaRPJBkpK7BRiSxGdFGxHqO1Lh9eIwiwyQ5HCyI7vmgkXu0h8qVAgMBAAECgYEAlExlDfp2HHla3wcnMKYxvjGbVvkNqP1hdMXzMvrSotx9Eak0fsTlzUViWHMKvITEe+Btd+D32nprh/AUX74XkERYfxatjAZADepVeRVPZMFS1HMdoM3LUfNF0iR3Yf50BP+ni78/DEPtdSde8NcYn38PsCC9AlbFR+9L+AyIGUkCQQDpT+FjzxMw6Hgnm8WN5GRGjrc2YFD0rBbpQ0TTyGIE3Y6BRSwmQ8/eDiR2pwXVVhOytKx3Omz1xEtMcbR8OCrHAkEArnYtTp7bZm8Dk8U8S33KRt+NczS5SWcnhJxQLz7mCdjRlSlnrplH0JX/962+OVzIXTr5QC5PZOYEz1IhQuojwwJABie+WYTAC91GNj1M0/Z/ksD3Im8eE6ZqoFLPAQtbUqeJt+1cQdIuLLyQx7SWWc+Ai4lqt2aKM12vYt/pFBHjRwJADlXFiUVCT9tlVtoJB0bxvPsXe1TkuKRSOfJCIG+xpTN8nR4G1/DeVsRMgQR0se/uwsJawqxLd8XytTpRY1cr0wJBAIM4daah4ow+YcAfyWLcULq/17BkBoTNhN64wSQ+g4I6BFEd15sw59WUtelwEP98M25nltWI9hM7zHvoEFGzC1I=','MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCL4nMv6qK7Lt1MzfK20LrVd/0g0pXIvV281sT16s4xIWEg/Hfv0su0MHdbTobZfHcziyO/xdmItCzkcJOIIskuC3QukNrWnt7kf1wZ1OmIMWAcS5s9wnMd0QcpDpcyfZfJvlZgFDtgJtApXvCBBVIEX65W1FnmlZ7wccO3Ca+J8QIDAQAB',0,1);
insert  into `ly_payapi`(`id`,`cid`,`payclass`,`desc`,`accountId`,`accountName`,`accountKey`,`private_key`,`public_key`,`sort`,`status`) values (11,1,'Qfupay','启付','Mer201704060515','eezecmrrwgRdAwurUzUYfQy6','C3BC2C19C3CA7A1F7D2E6162350B64AD','','0',0,1);
insert  into `ly_payapi`(`id`,`cid`,`payclass`,`desc`,`accountId`,`accountName`,`accountKey`,`private_key`,`public_key`,`sort`,`status`) values (15,1,'Ownpay','金海哲测试','SFTTEST','','','MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAJ7//05sfPeybycqvGl+svumakuwPrqmYI1rl2cTrqy6p1SvFk445KTsPTQp/puTjMfO6+PiFE0rqA918hySrhsW5swg2tD/VxlFpC5ybzLaOLGG43cp1J9taWaRPJBkpK7BRiSxGdFGxHqO1Lh9eIwiwyQ5HCyI7vmgkXu0h8qVAgMBAAECgYEAlExlDfp2HHla3wcnMKYxvjGbVvkNqP1hdMXzMvrSotx9Eak0fsTlzUViWHMKvITEe+Btd+D32nprh/AUX74XkERYfxatjAZADepVeRVPZMFS1HMdoM3LUfNF0iR3Yf50BP+ni78/DEPtdSde8NcYn38PsCC9AlbFR+9L+AyIGUkCQQDpT+FjzxMw6Hgnm8WN5GRGjrc2YFD0rBbpQ0TTyGIE3Y6BRSwmQ8/eDiR2pwXVVhOytKx3Omz1xEtMcbR8OCrHAkEArnYtTp7bZm8Dk8U8S33KRt+NczS5SWcnhJxQLz7mCdjRlSlnrplH0JX/962+OVzIXTr5QC5PZOYEz1IhQuojwwJABie+WYTAC91GNj1M0/Z/ksD3Im8eE6ZqoFLPAQtbUqeJt+1cQdIuLLyQx7SWWc+Ai4lqt2aKM12vYt/pFBHjRwJADlXFiUVCT9tlVtoJB0bxvPsXe1TkuKRSOfJCIG+xpTN8nR4G1/DeVsRMgQR0se/uwsJawqxLd8XytTpRY1cr0wJBAIM4daah4ow+YcAfyWLcULq/17BkBoTNhN64wSQ+g4I6BFEd15sw59WUtelwEP98M25nltWI9hM7zHvoEFGzC1I=','MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCL4nMv6qK7Lt1MzfK20LrVd/0g0pXIvV281sT16s4xIWEg/Hfv0su0MHdbTobZfHcziyO/xdmItCzkcJOIIskuC3QukNrWnt7kf1wZ1OmIMWAcS5s9wnMd0QcpDpcyfZfJvlZgFDtgJtApXvCBBVIEX65W1FnmlZ7wccO3Ca+J8QIDAQAB',0,1);
insert  into `ly_payapi`(`id`,`cid`,`payclass`,`desc`,`accountId`,`accountName`,`accountKey`,`private_key`,`public_key`,`sort`,`status`) values (16,3,'Ownpay','金海哲测试','SFTTEST','','','MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAJ7//05sfPeybycqvGl+svumakuwPrqmYI1rl2cTrqy6p1SvFk445KTsPTQp/puTjMfO6+PiFE0rqA918hySrhsW5swg2tD/VxlFpC5ybzLaOLGG43cp1J9taWaRPJBkpK7BRiSxGdFGxHqO1Lh9eIwiwyQ5HCyI7vmgkXu0h8qVAgMBAAECgYEAlExlDfp2HHla3wcnMKYxvjGbVvkNqP1hdMXzMvrSotx9Eak0fsTlzUViWHMKvITEe+Btd+D32nprh/AUX74XkERYfxatjAZADepVeRVPZMFS1HMdoM3LUfNF0iR3Yf50BP+ni78/DEPtdSde8NcYn38PsCC9AlbFR+9L+AyIGUkCQQDpT+FjzxMw6Hgnm8WN5GRGjrc2YFD0rBbpQ0TTyGIE3Y6BRSwmQ8/eDiR2pwXVVhOytKx3Omz1xEtMcbR8OCrHAkEArnYtTp7bZm8Dk8U8S33KRt+NczS5SWcnhJxQLz7mCdjRlSlnrplH0JX/962+OVzIXTr5QC5PZOYEz1IhQuojwwJABie+WYTAC91GNj1M0/Z/ksD3Im8eE6ZqoFLPAQtbUqeJt+1cQdIuLLyQx7SWWc+Ai4lqt2aKM12vYt/pFBHjRwJADlXFiUVCT9tlVtoJB0bxvPsXe1TkuKRSOfJCIG+xpTN8nR4G1/DeVsRMgQR0se/uwsJawqxLd8XytTpRY1cr0wJBAIM4daah4ow+YcAfyWLcULq/17BkBoTNhN64wSQ+g4I6BFEd15sw59WUtelwEP98M25nltWI9hM7zHvoEFGzC1I=','MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCL4nMv6qK7Lt1MzfK20LrVd/0g0pXIvV281sT16s4xIWEg/Hfv0su0MHdbTobZfHcziyO/xdmItCzkcJOIIskuC3QukNrWnt7kf1wZ1OmIMWAcS5s9wnMd0QcpDpcyfZfJvlZgFDtgJtApXvCBBVIEX65W1FnmlZ7wccO3Ca+J8QIDAQAB',0,1);

/*Table structure for table `ly_payify` */

CREATE TABLE `ly_payify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(20) NOT NULL,
  `bankcode` varchar(100) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0待付1已付',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `payno` varchar(250) NOT NULL,
  `accountname` varchar(100) NOT NULL DEFAULT '',
  `accountno` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderNo` (`orderno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ly_admin_user` VALUES (1,1,'超级管理员','admin','3a123a2a57235e37bd8df4ab88d99d98','',0,'',0,0,0,0.00,0,'',1438651748,1438651748,1);
