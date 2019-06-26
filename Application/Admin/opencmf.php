<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return array(
    // 模块信息
    'info' => array(
        'name'        => 'Admin',
        'title'       => '系统',
        'icon'        => 'fa fa-cog',
        'icon_color'  => '#3CA6F1',
        'description' => '核心系统',
        'developer'   => '光谷科技有限公司',
        'website'     => 'http://www.22cloud.com',
        'version'     => '1.0.0',
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'pid'   => '0',
            'title' => '系统',
            'icon'  => 'fa fa-cog',
            'level' => 'system',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '系统功能',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => '系统设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Admin/Config/group',
        ),
        '4' => array(
            'pid'   => '3',
            'title' => '修改设置',
            'url'   => 'Admin/Config/groupSave',
        ),
        '5' => array(
            'pid'   => '2',
            'title' => '导航管理',
            'icon'  => 'fa fa-map-signs',
            'url'   => 'Admin/Nav/index',
        ),
        '6' => array(
            'pid'   => '5',
            'title' => '新增',
            'url'   => 'Admin/Nav/add',
        ),
        '7' => array(
            'pid'   => '5',
            'title' => '编辑',
            'url'   => 'Admin/Nav/edit',
        ),
        '8' => array(
            'pid'   => '5',
            'title' => '设置状态',
            'url'   => 'Admin/Nav/setStatus',
        ),
        '13' => array(
            'pid'   => '2',
            'title' => '配置管理',
            'icon'  => 'fa fa-cogs',
            'url'   => 'Admin/Config/index',
        ),
        '14' => array(
            'pid'   => '13',
            'title' => '新增',
            'url'   => 'Admin/Config/add',
        ),
        '15' => array(
            'pid'   => '13',
            'title' => '编辑',
            'url'   => 'Admin/Config/edit',
        ),
        '16' => array(
            'pid'   => '13',
            'title' => '设置状态',
            'url'   => 'Admin/Config/setStatus',
        ),
        '17' => array(
            'pid'   => '2',
            'title' => '上传管理',
            'icon'  => 'fa fa-upload',
            'url'   => 'Admin/Upload/index',
        ),
        '18' => array(
            'pid'   => '17',
            'title' => '上传文件',
            'url'   => 'Admin/Upload/upload',
        ),
        '19' => array(
            'pid'   => '17',
            'title' => '删除文件',
            'url'   => 'Admin/Upload/delete',
        ),
        '20' => array(
            'pid'   => '17',
            'title' => '设置状态',
            'url'   => 'Admin/Upload/setStatus',
        ),
        '21' => array(
            'pid'   => '17',
            'title' => '下载远程图片',
            'url'   => 'Admin/Upload/downremoteimg',
        ),
        '22' => array(
            'pid'   => '17',
            'title' => '文件浏览',
            'url'   => 'Admin/Upload/fileManager',
        ),
        '23' => array(
            'pid'   => '1',
            'title' => '系统权限',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '24' => array(
            'pid'   => '23',
            'title' => '用户管理',
            'icon'  => 'fa fa-user',
            'url'   => 'Admin/User/index',
        ),
        '25' => array(
            'pid'   => '24',
            'title' => '新增',
            'url'   => 'Admin/User/add',
        ),
        '26' => array(
            'pid'   => '24',
            'title' => '编辑',
            'url'   => 'Admin/User/edit',
        ),
        '27' => array(
            'pid'   => '24',
            'title' => '设置状态',
            'url'   => 'Admin/User/setStatus',
        ),
        '28' => array(
            'pid'   => '23',
            'title' => '管理员管理',
            'icon'  => 'fa fa-lock',
            'url'   => 'Admin/Access/index',
        ),
        '29' => array(
            'pid'   => '28',
            'title' => '新增',
            'url'   => 'Admin/Access/add',
        ),
        '30' => array(
            'pid'   => '28',
            'title' => '编辑',
            'url'   => 'Admin/Access/edit',
        ),
        '31' => array(
            'pid'   => '28',
            'title' => '设置状态',
            'url'   => 'Admin/Access/setStatus',
        ),
        '32' => array(
            'pid'   => '23',
            'title' => '用户组管理',
            'icon'  => 'fa fa-sitemap',
            'url'   => 'Admin/Group/index',
        ),
        '33' => array(
            'pid'   => '32',
            'title' => '新增',
            'url'   => 'Admin/Group/add',
        ),
        '34' => array(
            'pid'   => '32',
            'title' => '编辑',
            'url'   => 'Admin/Group/edit',
        ),
        '35' => array(
            'pid'   => '32',
            'title' => '设置状态',
            'url'   => 'Admin/Group/setStatus',
        ),
        '36' => array(
            'pid'   => '1',
            'title' => '定单管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '44' => array(
            'pid'   => '36',
            'title' => '定单管理',
            'icon'  => 'fa fa-bars',
            'url'   => 'Admin/Order/index',
        ),
        '45' => array(
            'pid'   => '44',
            'title' => '删除',
            'url'   => 'Admin/Order/delete',
        ),
        '46' => array(
            'pid'   => '44',
            'title' => '设置结算状态',
            'url'   => 'Admin/Order/setStatus',
        ),
        '47' => array(
            'pid'   => '44',
            'title' => '更新付款信息',
            'url'   => 'Admin/Order/updateInfo',
        ),
        '48' => array(
            'pid'   => '36',
            'title' => '支付接口',
            'icon'  => 'fa fa-cogs',
            'url'   => 'Admin/Payapi/index',
        ),
        '49' => array(
            'pid'   => '36',
            'title' => '代付管理',
            'icon'  => 'fa fa-th',
            'url'   => 'Admin/Payify/index',
        ),
        '50' => array(
            'pid'   => '49',
            'title' => '添加',
            'url'   => 'Admin/Payify/add',
        ),
        '58' => array(
            'pid'   => '48',
            'title' => '新增',
            'url'   => 'Admin/Payapi/add',
        ),
        '59' => array(
            'pid'   => '48',
            'title' => '编辑',
            'url'   => 'Admin/Payapi/edit',
        ),
        '60' => array(
            'pid'   => '48',
            'title' => '设置状态',
            'url'   => 'Admin/Payapi/setStatus',
        ),
    )
);
