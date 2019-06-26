<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------

/**
 * Content-type设置
 */
header("Content-type: text/html; charset=utf-8");

/**
 * PHP版本检查
 */
if (version_compare(PHP_VERSION,'5.3.0','<')) {
    die('require PHP > 5.4.0 !');
}

/**
 * PHP报错设置
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING);

/**
 * 开发模式环境变量前缀
 */
define('ENV_PRE', 'LY_');

/**
 * 根目录绝对路径
 */
define('ROOT_PATH', dirname(dirname(dirname(dirname(__DIR__)))));

/**
 * 定义前台标记
 */
define('MODULE_MARK', 'Home');

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define('APP_PATH', './Application/');

/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define('RUNTIME_PATH', './Runtime/');

/**
 * 静态缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define('HTML_PATH', RUNTIME_PATH.'Html/');

define('APP_DEBUG', true);

/**
 * 系统安装及开发模式检测
 */
if (is_file('./Data/install.lock') === false && @$_SERVER[ENV_PRE.'DEV_MODE'] !== 'true') {
    define('BIND_MODULE','Install');
}else{
	define('BIND_MODULE','Home');
}

/**
 * Composer
 */
//require './vendor/autoload.php';

/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require './Framework/LingYun.php';
