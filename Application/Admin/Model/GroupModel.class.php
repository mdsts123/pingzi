<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Common\Model\ModelModel;
/**
 * 部门模型
 * @author jry <93058680@qq.com>
 */
class GroupModel extends ModelModel {
    /**
     * 数据库表名
     * @author jry <93058680@qq.com>
     */
    protected $tableName = 'admin_group';

    /**
     * 自动验证规则
     * @author jry <93058680@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '部门名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,32', '部门名称长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('title', '', '部门名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('menu_auth', 'require', '权限不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <93058680@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 检查部门功能权限
     * @author jry <93058680@qq.com>
     */
    public function checkMenuAuth() {
        $current_menu = D('Admin/Module')->getCurrentMenu(); // 当前菜单

        $user_group = D('Admin/Access')->getFieldByUid(session('user_auth.uid'), 'group');  // 获得当前登录用户信息

        if ($user_group !== '1') {
            $group_info = $this->find($user_group);
            // 获得当前登录用户所属部门的权限列表
            $group_auth = json_decode($group_info['menu_auth'], true);
            if (in_array($current_menu['id'], $group_auth[MODULE_NAME])) {
                return true;
            }
        } else {
            return true;  // 超级管理员无需验证
        }
        return false;
    }
}
