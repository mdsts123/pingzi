/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50643
Source Host           : localhost:3306
Source Database       : lingyun

Target Server Type    : MYSQL
Target Server Version : 50643
File Encoding         : 65001

Date: 2019-06-30 18:28:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ly_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_access`;
CREATE TABLE `ly_admin_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户组',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  `group_name` varchar(60) DEFAULT NULL COMMENT '组别',
  `level` tinyint(4) DEFAULT NULL COMMENT '权限级别  0-超管 1-推广组长  2-推广组员',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='后台管理员与用户组对应关系表';

-- ----------------------------
-- Records of ly_admin_access
-- ----------------------------
INSERT INTO `ly_admin_access` VALUES ('1', '1', '1', '1438651748', '1438651748', '0', '1', 'admin', null);
INSERT INTO `ly_admin_access` VALUES ('2', '2', '2', '1561889821', '1561889821', '0', '1', 'fhcp11', '1');
INSERT INTO `ly_admin_access` VALUES ('3', '3', '2', '1561889907', '1561889907', '0', '1', 'fhcp22', '1');
INSERT INTO `ly_admin_access` VALUES ('4', '4', '2', '1561890020', '1561890020', '0', '1', 'fhcp33', '1');
INSERT INTO `ly_admin_access` VALUES ('5', '5', '2', '1561890103', '1561890103', '0', '1', 'fhcp55', '1');
INSERT INTO `ly_admin_access` VALUES ('6', '6', '2', '1561890155', '1561890155', '0', '1', 'fhcp66', '1');
INSERT INTO `ly_admin_access` VALUES ('7', '7', '2', '1561890192', '1561890192', '0', '1', 'fhcp77', '1');

-- ----------------------------
-- Table structure for ly_admin_addon
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_addon`;
CREATE TABLE `ly_admin_addon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(32) DEFAULT NULL COMMENT '插件名或标识',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text NOT NULL COMMENT '插件描述',
  `config` text COMMENT '配置',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(8) NOT NULL DEFAULT '' COMMENT '版本号',
  `adminlist` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '插件类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='插件表';

-- ----------------------------
-- Records of ly_admin_addon
-- ----------------------------

-- ----------------------------
-- Table structure for ly_admin_config
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_config`;
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统配置表';

-- ----------------------------
-- Records of ly_admin_config
-- ----------------------------
INSERT INTO `ly_admin_config` VALUES ('1', '站点开关', 'TOGGLE_WEB_SITE', '1', '1', 'select', '0:关闭\r\n1:开启', '站点关闭后将不能访问', '1378898976', '1406992386', '1', '1');
INSERT INTO `ly_admin_config` VALUES ('2', '网站标题', 'WEB_SITE_TITLE', '幸运彩票', '1', 'text', '', '网站标题前台显示标题', '1378898976', '1379235274', '2', '1');
INSERT INTO `ly_admin_config` VALUES ('3', '网站口号', 'WEB_SITE_SLOGAN', '快速充值中心', '1', 'text', '', '网站口号、宣传标语、一句话介绍', '1434081649', '1434081649', '3', '1');
INSERT INTO `ly_admin_config` VALUES ('4', '网站LOGO', 'WEB_SITE_LOGO', '', '1', 'picture', '', '网站LOGO,格式PNG,宽600px高149px', '1407003397', '1407004692', '4', '0');
INSERT INTO `ly_admin_config` VALUES ('6', '网站描述', 'WEB_SITE_DESCRIPTION', '快速充值中心', '1', 'textarea', '', '网站搜索引擎描述', '1378898976', '1379235841', '6', '1');
INSERT INTO `ly_admin_config` VALUES ('7', '网站关键字', 'WEB_SITE_KEYWORD', '快速充值中心', '1', 'textarea', '', '网站搜索引擎关键字', '1378898976', '1381390100', '7', '1');
INSERT INTO `ly_admin_config` VALUES ('8', '版权信息', 'WEB_SITE_COPYRIGHT', 'Copyright © ovidc All rights reserved.', '1', 'text', '', '设置在网站底部显示的版权信息，如“版权所有 © 2014-2015 科斯克网络科技”', '1406991855', '1406992583', '8', '1');
INSERT INTO `ly_admin_config` VALUES ('9', '网站备案号', 'WEB_SITE_ICP', '网站备案号123', '1', 'text', '', '设置在网站底部显示的备案号，如“苏ICP备1502009号\"', '1378900335', '1415983236', '9', '0');
INSERT INTO `ly_admin_config` VALUES ('10', '站点统计', 'WEB_SITE_STATISTICS', '', '1', 'textarea', '', '支持百度、Google、cnzz等所有Javascript的统计代码', '1378900335', '1415983236', '10', '1');
INSERT INTO `ly_admin_config` VALUES ('11', '文件上传大小', 'UPLOAD_FILE_SIZE', '10', '2', 'num', '', '文件上传大小单位：MB', '1428681031', '1428681031', '1', '1');
INSERT INTO `ly_admin_config` VALUES ('12', '图片上传大小', 'UPLOAD_IMAGE_SIZE', '2', '2', 'num', '', '图片上传大小单位：MB', '1428681071', '1428681071', '2', '1');
INSERT INTO `ly_admin_config` VALUES ('13', '后台多标签', 'ADMIN_TABS', '0', '2', 'radio', '0:关闭\r\n1:开启', '', '1453445526', '1453445526', '3', '1');
INSERT INTO `ly_admin_config` VALUES ('14', '分页数量', 'ADMIN_PAGE_ROWS', '10', '2', 'num', '', '分页时每页的记录数', '1434019462', '1434019481', '4', '1');
INSERT INTO `ly_admin_config` VALUES ('15', '后台主题', 'ADMIN_THEME', 'admin', '2', 'select', 'admin:默认主题\r\naliyun:阿里云风格', '后台界面主题', '1436678171', '1436690570', '5', '1');
INSERT INTO `ly_admin_config` VALUES ('16', '导航分组', 'NAV_GROUP_LIST', 'top:顶部导航\r\nmain:主导航\r\nbottom:底部导航', '2', 'array', '', '导航分组', '1458382037', '1458382061', '6', '1');
INSERT INTO `ly_admin_config` VALUES ('17', '配置分组', 'CONFIG_GROUP_LIST', '1:基本\r\n2:系统\r\n3:开发\r\n4:部署', '2', 'array', '', '配置分组', '1379228036', '1426930700', '7', '1');
INSERT INTO `ly_admin_config` VALUES ('18', '开发模式', 'DEVELOP_MODE', '0', '3', 'select', '1:开启\r\n0:关闭', '开发模式下会显示菜单管理、配置管理、数据字典等开发者工具', '1432393583', '1432393583', '1', '1');
INSERT INTO `ly_admin_config` VALUES ('19', '是否显示页面Trace', 'SHOW_PAGE_TRACE', '0', '3', 'select', '0:关闭\r\n1:开启', '是否显示页面Trace信息', '1387165685', '1387165685', '2', '1');
INSERT INTO `ly_admin_config` VALUES ('20', '系统加密KEY', 'AUTH_KEY', 'k<eT@_!a/SA?B&ioit+Y{/:q*$l(`.,hNIFDqA=>m:?\"mBsqiLG~)*%fQMh-cuy:', '3', 'textarea', '', '轻易不要修改此项，否则容易造成用户无法登录；如要修改，务必备份原key', '1438647773', '1438647815', '3', '1');
INSERT INTO `ly_admin_config` VALUES ('21', 'URL模式', 'URL_MODEL', '2', '4', 'select', '1:PATHINFO模式\r\n2:REWRITE模式\r\n3:兼容模式', '', '1438423248', '1438423248', '1', '1');
INSERT INTO `ly_admin_config` VALUES ('22', '支付最小金额', 'PAY_MIN', '10', '1', 'num', '', '系统最小金额', '1478345496', '1492001427', '10', '1');
INSERT INTO `ly_admin_config` VALUES ('23', '客服链接', 'SERURL', 'https://kf1.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=588182&configID=64058&jid=4212383372&s=1', '1', 'text', '', '', '1478664998', '1478664998', '255', '1');
INSERT INTO `ly_admin_config` VALUES ('24', '网站域名', 'WEB_SITE_DOMAIN', 'www.3550pay.com', '1', 'text', '', '', '1478664998', '1478664998', '5', '1');
INSERT INTO `ly_admin_config` VALUES ('25', '用户名黑名单', 'disable_user', '123', '1', 'textarea', '', '', '1490626733', '1490626838', '12', '1');
INSERT INTO `ly_admin_config` VALUES ('26', 'IP黑名单', 'disable_ips', '', '1', 'textarea', '', '', '1490626772', '1492001499', '12', '1');
INSERT INTO `ly_admin_config` VALUES ('27', '支付最大金额', 'PAY_MAX', '50000', '1', 'num', '', '0为不限制', '1492001464', '1492001464', '11', '1');
INSERT INTO `ly_admin_config` VALUES ('28', '返回主页网址', 'WEB_SITE_RETHOME', 'http://3550u.com', '1', 'text', '', '', '1478664998', '1478664998', '5', '1');

-- ----------------------------
-- Table structure for ly_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_group`;
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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='部门信息表';

-- ----------------------------
-- Records of ly_admin_group
-- ----------------------------
INSERT INTO `ly_admin_group` VALUES ('1', '0', '超级管理员', '', '', '1426881003', '1427552428', '0', '1');
INSERT INTO `ly_admin_group` VALUES ('2', '0', '推广组长', '', '{\"Admin\":[\"17\",\"18\",\"23\",\"24\",\"25\",\"26\",\"27\",\"54\",\"55\",\"56\",\"57\",\"58\",\"59\"]}', '1561197850', '1561880695', '0', '1');
INSERT INTO `ly_admin_group` VALUES ('3', '2', '推广组组员', '', '{\"Admin\":[\"17\",\"18\",\"54\",\"55\",\"56\",\"57\",\"58\",\"59\"]}', '1561198029', '1561725300', '0', '1');

-- ----------------------------
-- Table structure for ly_admin_hook
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_hook`;
CREATE TABLE `ly_admin_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '钩子ID',
  `name` varchar(32) DEFAULT NULL COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='钩子表';

-- ----------------------------
-- Records of ly_admin_hook
-- ----------------------------
INSERT INTO `ly_admin_hook` VALUES ('1', 'AdminIndex', '后台首页小工具', '后台首页小工具', '1', '1446522155', '1446522155', '1');
INSERT INTO `ly_admin_hook` VALUES ('2', 'FormBuilderExtend', 'FormBuilder类型扩展Builder', '', '1', '1447831268', '1447831268', '1');
INSERT INTO `ly_admin_hook` VALUES ('3', 'UploadFile', '上传文件钩子', '', '1', '1407681961', '1407681961', '1');
INSERT INTO `ly_admin_hook` VALUES ('4', 'PageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '', '1', '1407681961', '1407681961', '1');
INSERT INTO `ly_admin_hook` VALUES ('5', 'PageFooter', '页面footer钩子，一般用于加载插件CSS文件和代码', 'RocketToTop', '1', '1407681961', '1407681961', '1');
INSERT INTO `ly_admin_hook` VALUES ('6', 'CommonHook', '通用钩子，自定义用途，一般用来定制特殊功能', '', '1', '1456147822', '1456147822', '1');

-- ----------------------------
-- Table structure for ly_admin_module
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_module`;
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块功能表';

-- ----------------------------
-- Records of ly_admin_module
-- ----------------------------
INSERT INTO `ly_admin_module` VALUES ('1', 'Admin', '系统', '', 'fa fa-cog', '#3CA6F1', '核心系统', '光谷科技有限公司', '1.0.0', '', '', '{\"1\":{\"pid\":\"0\",\"title\":\"\\u7cfb\\u7edf\",\"icon\":\"fa fa-cog\",\"level\":\"system\",\"id\":\"1\"},\"2\":{\"pid\":\"1\",\"title\":\"\\u7cfb\\u7edf\\u529f\\u80fd\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"2\"},\"3\":{\"pid\":\"2\",\"title\":\"\\u7cfb\\u7edf\\u8bbe\\u7f6e\",\"icon\":\"fa fa-wrench\",\"url\":\"Admin\\/Config\\/group\",\"id\":\"3\"},\"4\":{\"pid\":\"3\",\"title\":\"\\u4fee\\u6539\\u8bbe\\u7f6e\",\"url\":\"Admin\\/Config\\/groupSave\",\"id\":\"4\"},\"5\":{\"pid\":\"2\",\"title\":\"\\u5bfc\\u822a\\u7ba1\\u7406\",\"icon\":\"fa fa-map-signs\",\"url\":\"Admin\\/Nav\\/index\",\"id\":\"5\"},\"6\":{\"pid\":\"5\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Nav\\/add\",\"id\":\"6\"},\"7\":{\"pid\":\"5\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Nav\\/edit\",\"id\":\"7\"},\"8\":{\"pid\":\"5\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Nav\\/setStatus\",\"id\":\"8\"},\"13\":{\"pid\":\"2\",\"title\":\"\\u914d\\u7f6e\\u7ba1\\u7406\",\"icon\":\"fa fa-cogs\",\"url\":\"Admin\\/Config\\/index\",\"id\":\"13\"},\"14\":{\"pid\":\"13\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Config\\/add\",\"id\":\"14\"},\"15\":{\"pid\":\"13\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Config\\/edit\",\"id\":\"15\"},\"16\":{\"pid\":\"13\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Config\\/setStatus\",\"id\":\"16\"},\"17\":{\"pid\":\"2\",\"title\":\"\\u4e0a\\u4f20\\u7ba1\\u7406\",\"icon\":\"fa fa-upload\",\"url\":\"Admin\\/Upload\\/index\",\"id\":\"17\"},\"18\":{\"pid\":\"17\",\"title\":\"\\u4e0a\\u4f20\\u6587\\u4ef6\",\"url\":\"Admin\\/Upload\\/upload\",\"id\":\"18\"},\"19\":{\"pid\":\"17\",\"title\":\"\\u5220\\u9664\\u6587\\u4ef6\",\"url\":\"Admin\\/Upload\\/delete\",\"id\":\"19\"},\"20\":{\"pid\":\"17\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Upload\\/setStatus\",\"id\":\"20\"},\"21\":{\"pid\":\"17\",\"title\":\"\\u4e0b\\u8f7d\\u8fdc\\u7a0b\\u56fe\\u7247\",\"url\":\"Admin\\/Upload\\/downremoteimg\",\"id\":\"21\"},\"22\":{\"pid\":\"17\",\"title\":\"\\u6587\\u4ef6\\u6d4f\\u89c8\",\"url\":\"Admin\\/Upload\\/fileManager\",\"id\":\"22\"},\"23\":{\"pid\":\"1\",\"title\":\"\\u7cfb\\u7edf\\u6743\\u9650\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"23\"},\"24\":{\"pid\":\"23\",\"title\":\"\\u7528\\u6237\\u7ba1\\u7406\",\"icon\":\"fa fa-user\",\"url\":\"Admin\\/User\\/index\",\"id\":\"24\"},\"25\":{\"pid\":\"24\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/User\\/add\",\"id\":\"25\"},\"26\":{\"pid\":\"24\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/User\\/edit\",\"id\":\"26\"},\"27\":{\"pid\":\"24\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/User\\/setStatus\",\"id\":\"27\"},\"28\":{\"pid\":\"23\",\"title\":\"\\u7ba1\\u7406\\u5458\\u7ba1\\u7406\",\"icon\":\"fa fa-lock\",\"url\":\"Admin\\/Access\\/index\",\"id\":\"28\"},\"29\":{\"pid\":\"28\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Access\\/add\",\"id\":\"29\"},\"30\":{\"pid\":\"28\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Access\\/edit\",\"id\":\"30\"},\"31\":{\"pid\":\"28\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Access\\/setStatus\",\"id\":\"31\"},\"32\":{\"pid\":\"23\",\"title\":\"\\u7528\\u6237\\u7ec4\\u7ba1\\u7406\",\"icon\":\"fa fa-sitemap\",\"url\":\"Admin\\/Group\\/index\",\"id\":\"32\"},\"33\":{\"pid\":\"32\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Group\\/add\",\"id\":\"33\"},\"34\":{\"pid\":\"32\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Group\\/edit\",\"id\":\"34\"},\"35\":{\"pid\":\"32\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Group\\/setStatus\",\"id\":\"35\"},\"36\":{\"pid\":\"1\",\"title\":\"\\u5b9a\\u5355\\u7ba1\\u7406\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"36\"},\"37\":{\"pid\":\"36\",\"title\":\"\\u5b9a\\u5355\\u7ba1\\u7406\",\"icon\":\"fa fa-bars\",\"url\":\"Admin\\/Order\\/index\",\"id\":\"37\"},\"38\":{\"pid\":\"37\",\"title\":\"\\u5220\\u9664\",\"url\":\"Admin\\/Order\\/delete\",\"id\":\"38\"},\"39\":{\"pid\":\"37\",\"title\":\"\\u8bbe\\u7f6e\\u7ed3\\u7b97\\u72b6\\u6001\",\"url\":\"Admin\\/Order\\/setStatus\",\"id\":\"39\"},\"40\":{\"pid\":\"37\",\"title\":\"\\u66f4\\u65b0\\u4ed8\\u6b3e\\u4fe1\\u606f\",\"url\":\"Admin\\/Order\\/updateInfo\",\"id\":\"40\"},\"41\":{\"pid\":\"36\",\"title\":\"\\u652f\\u4ed8\\u63a5\\u53e3\",\"icon\":\"fa fa-cogs\",\"url\":\"Admin\\/Payapi\\/index\",\"id\":\"41\"},\"42\":{\"pid\":\"41\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Payapi\\/add\",\"id\":\"42\"},\"43\":{\"pid\":\"41\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Payapi\\/edit\",\"id\":\"43\"},\"44\":{\"pid\":\"41\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/Payapi\\/setStatus\",\"id\":\"44\"},\"45\":{\"pid\":\"36\",\"title\":\"\\u7f51\\u94f6\\u8d26\\u53f7\",\"icon\":\"fa fa-bars\",\"url\":\"Admin\\/Bankpay\\/index\",\"id\":\"45\"},\"46\":{\"pid\":\"45\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/Bankpay\\/add\",\"id\":\"46\"},\"47\":{\"pid\":\"45\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Bankpay\\/edit\",\"id\":\"47\"},\"48\":{\"pid\":\"45\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/Bankpay\\/setStatus\",\"id\":\"48\"},\"49\":{\"pid\":\"36\",\"title\":\"\\u4e94\\u7801\\u5408\\u4e00\",\"icon\":\"fa fa-bars\",\"url\":\"Admin\\/PayType\\/index\",\"id\":\"49\"},\"50\":{\"pid\":\"49\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/PayType\\/add\",\"id\":\"50\"},\"51\":{\"pid\":\"49\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/PayType\\/edit\",\"id\":\"51\"},\"52\":{\"pid\":\"49\",\"title\":\"\\u8bbe\\u7f6e\\u72b6\\u6001\",\"url\":\"Admin\\/PayType\\/setStatus\",\"id\":\"52\"},\"53\":{\"pid\":\"36\",\"title\":\"\\u5b9a\\u5355\\u7ba1\\u7406-\\u4e94\\u7801\",\"icon\":\"fa fa-bars\",\"url\":\"Admin\\/Order\\/indexwm\",\"id\":\"53\"},\"54\":{\"pid\":\"1\",\"title\":\"\\u63a8\\u5e7f\\u8ba2\\u5355\",\"icon\":\"fa fa-folder-open-o\",\"id\":\"54\"},\"55\":{\"pid\":\"54\",\"title\":\"\\u5b9a\\u5355\\u7ba1\\u7406\",\"icon\":\"fa fa-bars\",\"url\":\"Admin\\/TgOrder\\/index\",\"id\":\"55\"},\"56\":{\"pid\":\"55\",\"title\":\"\\u65b0\\u589e\",\"url\":\"Admin\\/TgOrder\\/add\",\"id\":\"56\"},\"57\":{\"pid\":\"55\",\"title\":\"\\u7f16\\u8f91\",\"url\":\"Admin\\/TgOrder\\/edit\",\"id\":\"57\"},\"58\":{\"pid\":\"55\",\"title\":\"\\u5220\\u9664\",\"url\":\"Admin\\/TgOrder\\/setStatus\",\"id\":\"58\"},\"59\":{\"pid\":\"55\",\"title\":\"\\u64cd\\u4f5c\",\"url\":\"Admin\\/TgOrder\\/payTgOrder\",\"id\":\"59\"}}', '1', '1438651748', '1478492426', '0', '1');

-- ----------------------------
-- Table structure for ly_admin_nav
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_nav`;
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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='前台导航表';

-- ----------------------------
-- Records of ly_admin_nav
-- ----------------------------
INSERT INTO `ly_admin_nav` VALUES ('1', 'bottom', '0', '关于', 'link', '', '', '', '1449742225', '1449742255', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('2', 'bottom', '1', '关于我们', 'page', '', '', '', '1449742312', '1449742312', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('4', 'bottom', '1', '服务产品', 'page', '', '', '', '1449742597', '1449742651', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('5', 'bottom', '1', '商务合作', 'page', '', '', '', '1449742664', '1449742664', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('6', 'bottom', '1', '加入我们', 'page', '', '', '', '1449742678', '1449742697', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('7', 'bottom', '0', '帮助', 'page', '', '', '', '1449742688', '1449742688', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('8', 'bottom', '7', '用户协议', 'page', '', '', '', '1449742706', '1449742706', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('9', 'bottom', '7', '意见反馈', 'page', '', '', '', '1449742716', '1449742716', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('10', 'bottom', '7', '常见问题', 'page', '', '', '', '1449742728', '1449742728', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('11', 'bottom', '0', '联系方式', 'page', '', '', '', '1449742742', '1449742742', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('12', 'bottom', '11', '联系我们', 'page', '', '', '', '1449742752', '1449742752', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('13', 'bottom', '11', '新浪微博', 'page', '', '', '', '1449742802', '1449742802', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('14', 'main', '0', '首页', 'link', '', '', '', '1457084559', '1472993801', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('15', 'main', '0', '产品中心', 'page', '', '', '', '1457084559', '1457084559', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('16', 'main', '0', '客户服务', 'page', '', '', '', '1457084572', '1457084572', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('17', 'main', '0', '案例展示', 'page', '', '', '', '1457084583', '1457084583', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('18', 'main', '0', '新闻动态', 'page', '', '', '', '1457084714', '1457084714', '0', '1');
INSERT INTO `ly_admin_nav` VALUES ('19', 'main', '0', '联系我们', 'page', '', '', '', '1457084725', '1457084725', '0', '1');

-- ----------------------------
-- Table structure for ly_admin_post
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_post`;
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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章列表';

-- ----------------------------
-- Records of ly_admin_post
-- ----------------------------

-- ----------------------------
-- Table structure for ly_admin_upload
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_upload`;
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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文件上传表';

-- ----------------------------
-- Records of ly_admin_upload
-- ----------------------------
INSERT INTO `ly_admin_upload` VALUES ('1', '6', 'ss.png', '/Uploads/2019-06-24/5d10a8b20ffab.png', '', 'png', '87819', '74be886a8d6a7a059e3fc10ce15d1a4f', '26bfddc7a46a22fb7e155983d7deb90fd824ca1d', 'Local', '0', '1561372850', '1561372850', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('3', '6', '8.jpg', '/Uploads/2019-06-24/5d10a9493fb7a.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561373001', '1561373001', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('4', '9', 'photo_2019-05-06_16-36-48.jpg', '/Uploads/2019-06-24/5d10a97828574.jpg', '', 'jpg', '23540', '2f2ea190f1fce311737acc5ec157437d', 'e1686b876f674dd78d77742a96696444e76992e6', 'Local', '0', '1561373048', '1561373048', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('6', '9', 'u=3173084480,57421635&fm=26&gp=0.jpg', '/Uploads/2019-06-24/5d10b7a258351.jpg', '', 'jpg', '9011', '8b61f6205fbd3c5820d355762462e933', '438c9dbef3fb226e3d37cd262ae60385d5b1d7a0', 'Local', '0', '1561376674', '1561376674', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('7', '1', 'u=1130103917,939352583&fm=26&gp=0.jpg', '/Uploads/2019-06-24/5d10b9369cd93.jpg', '', 'jpg', '7077', '8a33f792a857a539ee8e2af92e979473', 'ea4819811d4609289cc8f359e88cad6c665debb9', 'Local', '0', '1561377078', '1561377078', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('8', '1', 'taylor.jpg', '/Uploads/2019-06-24/5d10bce52f54a.jpg', '', 'jpg', '14286', '5dc700dd36f6ac1c7d770281e12ba11c', 'b263ecff89b8a5d5bbc08ed2b1dcf8a3fd828d9b', 'Local', '0', '1561378021', '1561378021', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('9', '1', 'u=971968544,1211613436&fm=26&gp=0.jpg', '/Uploads/2019-06-24/5d10bcf580e37.jpg', '', 'jpg', '7115', '78c1c3f75a03c846090f3ef19de3ce97', '9c0fd10152f15041ae4240ac5ebb575ad349f53a', 'Local', '0', '1561378037', '1561378037', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('10', '1', 'u=92076875,749428426&fm=26&gp=0.jpg', '/Uploads/2019-06-24/5d10bda999d43.jpg', '', 'jpg', '4152', '720b5eb4dcf3c5f4a04602ce9535fe3f', 'bdf37c9a9ed038114d88cc9c681b1acc3da3c69c', 'Local', '0', '1561378217', '1561378217', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('11', '1', '8.jpg', '/Uploads/2019-06-24/5d10be91776dd.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561378449', '1561378449', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('12', '1', '安卓.png', '/Uploads/2019-06-24/5d10beaf0f292.png', '', 'png', '24931', 'ad93bf273eaedb8023da50c7cbe496bb', 'f955155955e2d60611193c1bf113bc5aae79a9c5', 'Local', '0', '1561378479', '1561378479', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('13', '1', '5-APP.jpg', '/Uploads/2019-06-24/5d10beea7340f.jpg', '', 'jpg', '74583', 'a8fadd9b3d9479c5e673b7b8e72111b2', '7a6e10a7b6a3db6d5c48363b7a42fb7202e4d1ef', 'Local', '0', '1561378538', '1561378538', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('14', '1', '8.jpg', '/Uploads/2019-06-24/5d10c9ecd3c49.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561381356', '1561381356', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('15', '9', 'taylor.jpg', '/Uploads/2019-06-24/5d10ca319ed48.jpg', '', 'jpg', '14286', '5dc700dd36f6ac1c7d770281e12ba11c', 'b263ecff89b8a5d5bbc08ed2b1dcf8a3fd828d9b', 'Local', '0', '1561381425', '1561381425', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('16', '6', 'ss.png', '/Uploads/2019-06-24/5d10cc9970084.png', '', 'png', '87819', '74be886a8d6a7a059e3fc10ce15d1a4f', '26bfddc7a46a22fb7e155983d7deb90fd824ca1d', 'Local', '0', '1561382041', '1561382041', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('17', '11', 'photo_2019-05-06_16-36-48.jpg', '/Uploads/2019-06-24/5d10ce0632c8f.jpg', '', 'jpg', '23540', '2f2ea190f1fce311737acc5ec157437d', 'e1686b876f674dd78d77742a96696444e76992e6', 'Local', '0', '1561382406', '1561382406', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('18', '6', 'photo_2019-05-06_16-36-48.jpg', '/Uploads/2019-06-24/5d10cfdaabfe6.jpg', '', 'jpg', '23540', '2f2ea190f1fce311737acc5ec157437d', 'e1686b876f674dd78d77742a96696444e76992e6', 'Local', '0', '1561382874', '1561382874', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('19', '11', 'ss.png', '/Uploads/2019-06-25/5d11ae0db50a2.png', '', 'png', '87819', '74be886a8d6a7a059e3fc10ce15d1a4f', '26bfddc7a46a22fb7e155983d7deb90fd824ca1d', 'Local', '0', '1561439757', '1561439757', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('20', '11', '8.jpg', '/Uploads/2019-06-25/5d11aeb63678e.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561439926', '1561439926', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('21', '1', '8.jpg', '/Uploads/2019-06-25/5d11e7aa491c3.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561454506', '1561454506', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('22', '6', 'u=3173084480,57421635&fm=26&gp=0.jpg', '/Uploads/2019-06-25/5d11ecb6999b6.jpg', '', 'jpg', '9011', '8b61f6205fbd3c5820d355762462e933', '438c9dbef3fb226e3d37cd262ae60385d5b1d7a0', 'Local', '0', '1561455798', '1561455798', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('23', '9', '8.jpg', '/Uploads/2019-06-25/5d11ed02d3092.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561455874', '1561455874', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('25', '1', '8.jpg', '/Uploads/2019-06-26/5d132f442aec9.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561538372', '1561538372', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('26', '1', '8.jpg', '/Uploads/2019-06-26/5d132fc558ca7.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561538501', '1561538501', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('27', '1', '8.jpg', '/Uploads/2019-06-26/5d13347213890.jpg', '', 'jpg', '20279', '0f8aa65c9b5a53e5caa1bbae6f39d8fe', 'ae2a9fe42bdfe58441b9dc29b0c391889db01f18', 'Local', '0', '1561539698', '1561539698', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('28', '1', 'photo_2019-05-06_16-36-48.jpg', '/Uploads/2019-06-26/5d1343e828472.jpg', '', 'jpg', '23540', '2f2ea190f1fce311737acc5ec157437d', 'e1686b876f674dd78d77742a96696444e76992e6', 'Local', '0', '1561543656', '1561543656', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('29', '1', 'wyc.png', '/Uploads/2019-06-26/5d135bc6c2cd0.png', '', 'png', '82104', '1891bf66bc4996a210cfbb2e659a71f8', '6ce7fe72218a62d528dae05c14f16fc13b9227d9', 'Local', '0', '1561549766', '1561549766', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('30', '11', 'wyc.png', '/Uploads/2019-06-26/5d136416036e3.png', '', 'png', '82104', '1891bf66bc4996a210cfbb2e659a71f8', '6ce7fe72218a62d528dae05c14f16fc13b9227d9', 'Local', '0', '1561551894', '1561551894', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('31', '1', 'wyc.png', '/Uploads/2019-06-28/5d15cc8208cb1.png', '', 'png', '82104', '1891bf66bc4996a210cfbb2e659a71f8', '6ce7fe72218a62d528dae05c14f16fc13b9227d9', 'Local', '0', '1561709698', '1561709698', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('32', '6', 'wyc.png', '/Uploads/2019-06-28/5d1609e27abee.png', '', 'png', '82104', '1891bf66bc4996a210cfbb2e659a71f8', '6ce7fe72218a62d528dae05c14f16fc13b9227d9', 'Local', '0', '1561725410', '1561725410', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('33', '6', '2X.png', '/Uploads/2019-06-28/5d160a46ecc6b.png', '', 'png', '82613', '4cabf487e0992237d490147bcc4170f1', '68d01831efa931d4701ebac164c56dfeb37eecaf', 'Local', '0', '1561725510', '1561725510', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('34', '9', '7O.png', '/Uploads/2019-06-28/5d160a85685fa.png', '', 'png', '98064', 'a1a75b799feb3d3374b4dc1e62827db0', '1cf4be5de70ba9b7576aacc55d3afae302e4b0f3', 'Local', '0', '1561725573', '1561725573', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('35', '9', '2X.png', '/Uploads/2019-06-28/5d160aa4308d1.png', '', 'png', '82613', '4cabf487e0992237d490147bcc4170f1', '68d01831efa931d4701ebac164c56dfeb37eecaf', 'Local', '0', '1561725604', '1561725604', '0', '1');
INSERT INTO `ly_admin_upload` VALUES ('36', '9', 'photo_2019-05-06_16-36-48.jpg', '/Uploads/2019-06-28/5d160ad261419.jpg', '', 'jpg', '23540', '2f2ea190f1fce311737acc5ec157437d', 'e1686b876f674dd78d77742a96696444e76992e6', 'Local', '0', '1561725650', '1561725650', '0', '1');

-- ----------------------------
-- Table structure for ly_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `ly_admin_user`;
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
  `level` tinyint(4) unsigned NOT NULL COMMENT '权限级别  0-超管 1-推广组长  2-推广组员',
  `group_name` varchar(60) DEFAULT NULL COMMENT '组别',
  `reg_type` varchar(15) NOT NULL DEFAULT '' COMMENT '注册方式',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户账号表';

-- ----------------------------
-- Records of ly_admin_user
-- ----------------------------
INSERT INTO `ly_admin_user` VALUES ('1', '1', '超级管理员', 'admin', '5ea90e22d17f2673649210a83027449c', 'xy35500@qq.com', '0', '13908888777', '0', '0', '0', '0.00', '0', '0', 'admin', '', '1438651748', '1510819892', '1');
INSERT INTO `ly_admin_user` VALUES ('2', '1', 'fhcp11', 'fhcp11', 'df47ab9244054ac4fad9a809221f5509', '', '0', '', '0', '0', '0', '0.00', '2130706433', '1', 'fhcp11', 'admin', '1561889797', '1561890313', '1');
INSERT INTO `ly_admin_user` VALUES ('3', '1', 'fhcp22', 'fhcp22', 'f9a20d4b3e6d2b4ed788975831401daf', '', '0', '', '0', '0', '0', '0.00', '2130706433', '1', 'fhcp22', 'admin', '1561889890', '1561890324', '1');
INSERT INTO `ly_admin_user` VALUES ('4', '1', 'fhcp33', 'fhcp33', '3b413a8aaae4a5683c00b9f015fa84fa', '', '0', '', '0', '0', '0', '0.00', '2130706433', '1', 'fhcp33', 'admin', '1561889944', '1561889944', '1');
INSERT INTO `ly_admin_user` VALUES ('5', '1', 'fhcp55', 'fhcp55', '61fd9e57515b7fabef62d6b7831641f1', '', '0', '', '0', '0', '0', '0.00', '2130706433', '1', 'fhcp55', 'admin', '1561890092', '1561890092', '1');
INSERT INTO `ly_admin_user` VALUES ('6', '1', 'fhcp66', 'fhcp66', '28eb05cfd1328ce8da3961eaee8eb5c2', '', '0', '', '0', '0', '0', '0.00', '2130706433', '1', 'fhcp66', 'admin', '1561890134', '1561890134', '1');
INSERT INTO `ly_admin_user` VALUES ('7', '1', 'fhcp77', 'fhcp77', 'd7df232251450352bb0c489c48f06278', '', '0', '', '0', '0', '0', '0.00', '2130706433', '1', 'fhcp77', 'admin', '1561890181', '1561890181', '1');

-- ----------------------------
-- Table structure for ly_bankpay
-- ----------------------------
DROP TABLE IF EXISTS `ly_bankpay`;
CREATE TABLE `ly_bankpay` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bank` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cardno` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `min` int(5) NOT NULL DEFAULT '0',
  `max` int(5) NOT NULL DEFAULT '50000',
  `payclass` varchar(100) NOT NULL DEFAULT 'bank',
  `gourl` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ly_bankpay
-- ----------------------------
INSERT INTO `ly_bankpay` VALUES ('7', '中国银行', '张三', '461781141111111111111', '1', '0', '0', '50000', 'bank', '');

-- ----------------------------
-- Table structure for ly_category
-- ----------------------------
DROP TABLE IF EXISTS `ly_category`;
CREATE TABLE `ly_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL COMMENT '分类名称',
  `title` text COMMENT '标题描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='通道分类表';

-- ----------------------------
-- Records of ly_category
-- ----------------------------
INSERT INTO `ly_category` VALUES ('1', 'weixin', '微信扫码');
INSERT INTO `ly_category` VALUES ('2', 'bank', '网银支付');
INSERT INTO `ly_category` VALUES ('3', 'alipay', '支付宝扫码');
INSERT INTO `ly_category` VALUES ('4', 'qq', 'QQ钱包');
INSERT INTO `ly_category` VALUES ('6', 'bank', '网银转帐');
INSERT INTO `ly_category` VALUES ('8', 'bank', '银联扫码');
INSERT INTO `ly_category` VALUES ('10', 'jdqb', '京东钱包');
INSERT INTO `ly_category` VALUES ('12', 'wxtx', '微信条形码');
INSERT INTO `ly_category` VALUES ('11', 'wmhy', '五码合一');

-- ----------------------------
-- Table structure for ly_order
-- ----------------------------
DROP TABLE IF EXISTS `ly_order`;
CREATE TABLE `ly_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderno` varchar(250) NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `payid` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0待付1已付',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `payno` varchar(250) NOT NULL DEFAULT '',
  `payzt` int(1) NOT NULL DEFAULT '0' COMMENT '0待结1已结',
  `ip` varchar(20) NOT NULL DEFAULT '0',
  `cid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `orderno` (`orderno`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ly_order
-- ----------------------------
INSERT INTO `ly_order` VALUES ('1', '2019062049995397', 'andy', '100.00', '7', '0', '1561018721', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('2', '2019062010010099', 'andy', '100.00', '7', '0', '1561018957', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('3', '2019062048505753', 'aa', '100.00', '7', '0', '1561019072', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('4', '2019062010055101', 'ss', '100.00', '7', '0', '1561019165', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('5', '2019062054100100', 'f', '100.00', '7', '0', '1561019286', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('6', '2019062054521025', 'f', '100.00', '7', '0', '1561019382', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('7', '2019062010251984', 'andy', '100.00', '7', '0', '1561019551', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('8', '2019062097559951', 'andy', '100.00', '7', '0', '1561019626', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('9', '2019062097971025', 'ff', '100.00', '7', '0', '1561019706', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('10', '2019062150579854', 'andy', '100.00', '7', '0', '1561089538', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('11', '2019062148579910', 'a', '100.00', '7', '0', '1561089776', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('12', '2019062110055975', 'a', '100.00', '7', '0', '1561089837', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('13', '2019062152549710', 'f', '100.00', '7', '0', '1561090036', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('14', '2019062155501005', 'dd', '100.00', '7', '0', '1561090135', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('15', '2019062154102495', 'aa', '100.00', '7', '0', '1561090390', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('16', '2019062149991019', 'aa', '100.00', '7', '0', '1561090465', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('17', '2019062198551004', 'aa', '100.00', '7', '0', '1561090571', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('18', '2019062148101535', 'a', '10.00', '8', '0', '1561090672', '0', '', '0', '127.0.0.1', '1');
INSERT INTO `ly_order` VALUES ('19', '2019062153100974', 'a', '10.00', '7', '0', '1561090693', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('20', '2019062154571011', 'uu', '100.00', '7', '0', '1561091238', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('21', '2019062155501004', 'vv', '100.00', '7', '0', '1561091479', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('22', '2019062149524951', 'ww', '100.00', '7', '0', '1561091553', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('23', '2019062152565055', 'yy', '100.00', '7', '0', '1561091668', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('24', '2019062150499999', 'tt', '100.00', '7', '0', '1561091954', '0', '', '0', '127.0.0.1', '3');
INSERT INTO `ly_order` VALUES ('25', '2019062155101995', 'i', '100.00', '8', '0', '1561092311', '0', '', '0', '127.0.0.1', '1');
INSERT INTO `ly_order` VALUES ('26', '2019062197491025', 'yy', '100.00', '8', '0', '1561092362', '0', '', '0', '127.0.0.1', '1');
INSERT INTO `ly_order` VALUES ('27', '2019062198485397', 'admin', '200.00', '8', '0', '1561092426', '0', '', '0', '127.0.0.1', '1');
INSERT INTO `ly_order` VALUES ('28', '2019062199999810', 'andy', '10.00', '8', '0', '1561093116', '0', '', '0', '127.0.0.1', '1');
INSERT INTO `ly_order` VALUES ('29', '2019062150579753', 'hh', '100.00', '8', '0', '1561093346', '0', '', '0', '127.0.0.1', '1');
INSERT INTO `ly_order` VALUES ('32', '2019061510255534', 'hehe', '200.00', '0', '0', '1560578159', '0', '', '0', '127.0.0.1', '11');
INSERT INTO `ly_order` VALUES ('31', '2019062151101514', 'andy', '100.00', '8', '1', '1561095123', '0', '', '0', '127.0.0.1', '1');

-- ----------------------------
-- Table structure for ly_order2
-- ----------------------------
DROP TABLE IF EXISTS `ly_order2`;
CREATE TABLE `ly_order2` (
  `orderno` varchar(250) NOT NULL,
  `username` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `payid` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0待付1已付',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `payno` varchar(250) NOT NULL DEFAULT '',
  `payzt` int(1) NOT NULL DEFAULT '0' COMMENT '0待结1已结',
  `ip` varchar(20) NOT NULL DEFAULT '0',
  `cid` int(10) NOT NULL DEFAULT '0',
  UNIQUE KEY `orderno` (`orderno`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ly_order2
-- ----------------------------
INSERT INTO `ly_order2` VALUES ('2017112051505598', '916977685', '10.00', '50', '0', '1511161795', '0', '', '0', '222.168.229.203', '1');
INSERT INTO `ly_order2` VALUES ('2017112056100549', '298103', '30.00', '50', '0', '1511161864', '0', '', '0', '120.40.137.252', '1');
INSERT INTO `ly_order2` VALUES ('2017112055541009', '279322', '30.00', '50', '0', '1511161879', '0', '', '0', '112.224.1.49', '1');
INSERT INTO `ly_order2` VALUES ('2017112054101515', '279322', '30.00', '50', '0', '1511161910', '0', '', '0', '112.224.1.49', '1');
INSERT INTO `ly_order2` VALUES ('2017112010251545', '279322', '30.00', '50', '0', '1511161935', '0', '', '0', '112.224.1.49', '1');
INSERT INTO `ly_order2` VALUES ('2017112052509750', '279322', '30.00', '62', '0', '1511161956', '0', '', '0', '112.224.1.49', '3');
INSERT INTO `ly_order2` VALUES ('2017112050499748', '288593', '30.00', '50', '0', '1511162018', '0', '', '0', '103.23.136.29', '1');
INSERT INTO `ly_order2` VALUES ('2017112010157984', 'likkwang', '500.00', '61', '0', '1511162046', '0', '', '0', '61.151.178.176', '1');
INSERT INTO `ly_order2` VALUES ('2017112055981005', 'likkwang', '500.00', '50', '0', '1511162055', '0', '', '0', '61.151.178.176', '1');
INSERT INTO `ly_order2` VALUES ('2017112010256101', 'likkwang', '350.00', '50', '0', '1511162079', '0', '', '0', '61.151.178.176', '1');
INSERT INTO `ly_order2` VALUES ('2017112053515251', 'L10839273182', '10.00', '50', '0', '1511162085', '0', '', '0', '113.143.97.220', '1');
INSERT INTO `ly_order2` VALUES ('2017112052101535', 'likkwang', '100.00', '50', '0', '1511162100', '0', '', '0', '61.151.178.176', '1');
INSERT INTO `ly_order2` VALUES ('2017112098485754', '263925+XB', '100.00', '48', '0', '1511162234', '0', '', '0', '117.136.81.181', '2');
INSERT INTO `ly_order2` VALUES ('2017112010199571', 'sun936479994', '100.00', '50', '0', '1511162270', '0', '', '0', '106.121.0.114', '1');
INSERT INTO `ly_order2` VALUES ('2017112099995399', 'ouyou0758', '350.00', '62', '0', '1511162300', '0', '', '0', '117.136.79.226', '3');
INSERT INTO `ly_order2` VALUES ('2017112056579752', '293796', '30.00', '50', '0', '1511162392', '0', '', '0', '103.23.136.29', '1');

-- ----------------------------
-- Table structure for ly_payapi
-- ----------------------------
DROP TABLE IF EXISTS `ly_payapi`;
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
  `gourl` varchar(200) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ly_payapi
-- ----------------------------
INSERT INTO `ly_payapi` VALUES ('2', '3', 'Xdzfb', '鑫东支付', '200027', '', 'geg2xwaa2venmd98bhg0g9xtkasuym4w', '', '', '0', '0', '');
INSERT INTO `ly_payapi` VALUES ('5', '1', 'Tyuwx', '天宇支付', '10255', '', 'ks4grqp9nl9c8mia5b34c37cgb785rsf', '', '', '0', '0', '');
INSERT INTO `ly_payapi` VALUES ('7', '3', 'Cyizfb', '诚意支付', '800013898', '', '602254730ba307058a63b682dd965964', 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALm2QtisGQPcZyawZq8xGXjBdaKf0UX5bmBXxkvzQWqLPOiTtZ2GFgESxq+e/9GR+4kB1rofaQWzKoBZfdEiHkesUH5FGad0V8YH9AJDBJ6a5a4m58lTMll8XbWa0n8rBkPvgQpGiXEN+hmGHoXimU1btWw5PRuqYFXazyYNZH8JAgMBAAECgYBEbwtW+KQHLjK8qQhNypQOUYvdr3LYjD/GNNIXrr4OWtzI/5VPRNfa8WZR1Q+D1H2SUSElWOnEde9VeKTKaf4p3eQZx+43nJ9mz3nOSqcSF15F3tXJuLS+3UC3r+R9j6ezqf0lBVS1Oosw48/wt2n0/cfmzXuA4H/guioOUO2JuQJBAO2uuDUriRc03Fjc+hN7L8pDuVbeF/3wg9PAEtuwxgnt7Op2gHjdRXaPwzBr41HT5OrtGqnfKa1tZCSkXQsVcEcCQQDIBjSaXx+2NiXijx2tsEPQpmU1K1/678q9gMV9ASSt9hC+AoOAcNgslHgjsotryiyCMzpqTf+ZVuoOwdCWpw4vAkADTN/F7TrUFanRmg/m4VkCh/o02JSgtAxAjnQ0lLnLPYCCqO6Tvw7N3KUrMPRRGI4fLPen0C919wTi9V1NYBjVAkBM2fWECmBIxe0wawRgI4UquYPRNeUeqNsgjEVUjgflvtEX8CdORg0Is9KlBIiE1ZzcOYqMJYO7CqXLHbRi7bSJAkEAqVNFUjlMgLWUN8UeRDXck+5RkFJZ1h1m7jcwti8k7AI3FOAtxdotz6bpxG6E56NL7GV5khstG42r6NoQl3LxsQ==', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJZ1SDIvAPetPR1SJehPUKv4SWh1Jcb24BOyRuiNr3v++09Yon4Mf1QBA7aMlvzsHbPGInVmlh7dtCukzKrforDy4SsID7TtaLfbQcXEPAJaBdLZv18ByCw54Oi7xGrMDCcEuuipB8F9D6M2H6C8Lyl5Z5vV7gLrk9kWyDQc++YQIDAQAB', '0', '1', '');
INSERT INTO `ly_payapi` VALUES ('8', '1', 'Cyiwx', '诚意支付', '800013898', '', '602254730ba307058a63b682dd965964', 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBALm2QtisGQPcZyawZq8xGXjBdaKf0UX5bmBXxkvzQWqLPOiTtZ2GFgESxq+e/9GR+4kB1rofaQWzKoBZfdEiHkesUH5FGad0V8YH9AJDBJ6a5a4m58lTMll8XbWa0n8rBkPvgQpGiXEN+hmGHoXimU1btWw5PRuqYFXazyYNZH8JAgMBAAECgYBEbwtW+KQHLjK8qQhNypQOUYvdr3LYjD/GNNIXrr4OWtzI/5VPRNfa8WZR1Q+D1H2SUSElWOnEde9VeKTKaf4p3eQZx+43nJ9mz3nOSqcSF15F3tXJuLS+3UC3r+R9j6ezqf0lBVS1Oosw48/wt2n0/cfmzXuA4H/guioOUO2JuQJBAO2uuDUriRc03Fjc+hN7L8pDuVbeF/3wg9PAEtuwxgnt7Op2gHjdRXaPwzBr41HT5OrtGqnfKa1tZCSkXQsVcEcCQQDIBjSaXx+2NiXijx2tsEPQpmU1K1/678q9gMV9ASSt9hC+AoOAcNgslHgjsotryiyCMzpqTf+ZVuoOwdCWpw4vAkADTN/F7TrUFanRmg/m4VkCh/o02JSgtAxAjnQ0lLnLPYCCqO6Tvw7N3KUrMPRRGI4fLPen0C919wTi9V1NYBjVAkBM2fWECmBIxe0wawRgI4UquYPRNeUeqNsgjEVUjgflvtEX8CdORg0Is9KlBIiE1ZzcOYqMJYO7CqXLHbRi7bSJAkEAqVNFUjlMgLWUN8UeRDXck+5RkFJZ1h1m7jcwti8k7AI3FOAtxdotz6bpxG6E56NL7GV5khstG42r6NoQl3LxsQ==', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDJZ1SDIvAPetPR1SJehPUKv4SWh1Jcb24BOyRuiNr3v++09Yon4Mf1QBA7aMlvzsHbPGInVmlh7dtCukzKrforDy4SsID7TtaLfbQcXEPAJaBdLZv18ByCw54Oi7xGrMDCcEuuipB8F9D6M2H6C8Lyl5Z5vV7gLrk9kWyDQc++YQIDAQAB', '0', '1', '');
INSERT INTO `ly_payapi` VALUES ('9', '3', 'Zbaozfb', '众宝支付', '89000991', '', '02abd5eea7eb4259b0318a6d8e419d8e', '', '', '0', '0', '');
INSERT INTO `ly_payapi` VALUES ('10', '1', 'Zbaowx', '众宝支付', '89000991', '', '02abd5eea7eb4259b0318a6d8e419d8e', '', '', '0', '0', '');
INSERT INTO `ly_payapi` VALUES ('11', '8', 'Zbaoyl', '众宝支付', '89000991', '', '02abd5eea7eb4259b0318a6d8e419d8e', '', '', '0', '0', '');
INSERT INTO `ly_payapi` VALUES ('12', '10', 'Zbaojd', '众宝支付', '89000991', '', '02abd5eea7eb4259b0318a6d8e419d8e', '', '', '0', '0', '');
INSERT INTO `ly_payapi` VALUES ('13', '3', 'Dxzfb', '达轩支付', '180778972', '', '81soweifbucqcwcby8izjbv4kofongmu', '', '', '0', '0', '');

-- ----------------------------
-- Table structure for ly_payify
-- ----------------------------
DROP TABLE IF EXISTS `ly_payify`;
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `orderNo` (`orderno`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ly_payify
-- ----------------------------

-- ----------------------------
-- Table structure for ly_paywm
-- ----------------------------
DROP TABLE IF EXISTS `ly_paywm`;
CREATE TABLE `ly_paywm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '支付名称',
  `img_src` varchar(100) NOT NULL COMMENT '二维码地址',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `minmoney` int(11) unsigned DEFAULT '0' COMMENT '最小金额',
  `maxmoney` int(11) unsigned DEFAULT '0' COMMENT '最大金额',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ly_paywm
-- ----------------------------
INSERT INTO `ly_paywm` VALUES ('5', '9', '/Uploads/2019-06-26/5d13347213890.jpg', '100', '1', '0', '0');
INSERT INTO `ly_paywm` VALUES ('6', '9', '/Uploads/2019-06-26/5d1343e828472.jpg', '100', '1', '100', '10000');

-- ----------------------------
-- Table structure for ly_tg_order
-- ----------------------------
DROP TABLE IF EXISTS `ly_tg_order`;
CREATE TABLE `ly_tg_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderno` varchar(255) DEFAULT NULL COMMENT '订单号',
  `commit_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '提交类型 0-充值 1-彩金',
  `pay_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '支付类型 0-C/B/R扫码 1-A/D扫码 2-银行卡转账',
  `username` varchar(60) NOT NULL COMMENT '会员账号',
  `amount` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `giv_amount` float(10,2) DEFAULT '0.00' COMMENT '赠送金额',
  `img_src` varchar(255) NOT NULL COMMENT '上传截图',
  `pay_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态 0-未处理 1-充值成功 2-充值失败',
  `collname` varchar(60) DEFAULT NULL COMMENT '收款人',
  `payname` varchar(60) DEFAULT NULL COMMENT '付款人',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `commitname` varchar(60) DEFAULT NULL COMMENT '提交用户(推广)',
  `operaname` varchar(60) DEFAULT NULL COMMENT '操作人(财务)',
  `cmit_time` int(11) NOT NULL COMMENT '提交时间',
  `operatime` int(11) DEFAULT NULL COMMENT '操作时间',
  `groupname` varchar(60) DEFAULT NULL COMMENT '组别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ly_tg_order
-- ----------------------------
