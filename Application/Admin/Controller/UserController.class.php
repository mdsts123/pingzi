<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Util\Think\Page;
/**
 * 用户控制器
 * @author jry <93058680@qq.com>
 */
class UserController extends AdminController {
    /**
     * 用户列表
     * @author jry <93058680@qq.com>
     */
    public function index() {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        // 普通管理员
        if('0' === session('user_auth.level') && '1' !== session('user_auth.uid')){
            $map['id'] = ['gt','1'];
        }
        // 推广组长只能看到自己、自己组下的组员
        if('1' === session('user_auth.level')){
            $map['group_name'] = session('user_auth.group_name');
        }
        $map['id|username|nickname|email|mobile'] = array(
            $condition,
            $condition,
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );

        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object = D('User');
        $data_list = $user_object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id asc')
                   ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('用户列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                ->addTopButton('delete')  // 添加删除按钮
                ->setSearch('请输入ID/用户名／邮箱／手机号', U('index'))
                ->addTableColumn('id', 'UID')
                ->addTableColumn('nickname', '昵称')
                ->addTableColumn('username', '用户名')
                ->addTableColumn('level_name', '权限级别')
                ->addTableColumn('group_name', '组别')
                ->addTableColumn('email', '邮箱')
                ->addTableColumn('mobile', '手机号')
                ->addTableColumn('create_time', '注册时间')
                ->addTableColumn('reg_type', '注册方式')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->addRightButton('edit')          // 添加编辑按钮
                ->addRightButton('forbid')        // 添加禁用/启用按钮
                ->addRightButton('delete')        // 添加删除按钮
                ->display();
    }

    /**
     * 新增用户
     * @author jry <93058680@qq.com>
     */
    public function add() {
        if (IS_POST) {
            if('1' === session('user_auth.level')){
                if($_POST['level'] !== '2'){
                    $this->error('权限级别只能选择：推广组员');
                }
                if(session('user_auth.group_name') !== $_POST['group_name']){
                    $this->error('组别只能是：'.session('user_auth.group_name'));
                }
            }
            $user_object = D('User');
            $data = $user_object->create();
            if ($data) {
                $id = $user_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增用户') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
                    ->addFormItem('nickname', 'text', '昵称', '昵称')
                    ->addFormItem('username', 'text', '用户名', '用户名')
                    ->addFormItem('password', 'password', '密码', '密码')
                    ->addFormItem('level', 'radio', '权限级别', '权限级别', array('0' => '管理员', '1' => '推广组长', '2' => '推广组员'))
                    ->addFormItem('group_name', 'text', '组别', '组别')
                    ->addFormItem('email', 'text', '邮箱', '邮箱')
                    ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                    ->addFormItem('mobile', 'text', '手机号', '手机号')
                    ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                    ->addFormItem('avatar', 'picture', '头像', '头像')
                    ->setFormData(array('reg_type' => session('user_auth.username')))
                    ->display();
        }
    }

    /**
     * 编辑用户
     * @author jry <93058680@qq.com>
     */
    public function edit($id) {
        $map['id'] = $id;
        $user_object = D('User');
        $user = $user_object->where($map)->find();

        if (IS_POST) {
            // 密码为空表示不修改密码
            if ($_POST['password'] === '') {
                unset($_POST['password']);
            }
            // 超管
            if('0' === session('user_auth.level') && session('user_auth.username') == $user['username'] && '1' === session('user_auth.uid')){
                if('0' !== $_POST['level']){
                    $this->error('权限级别只能选择：管理员');
                }
                if(session('user_auth.group_name') !== $_POST['group_name']){
                    $this->error('组别不能为空，且只能是：'.session('user_auth.group_name'));
                }
            }
            // 管理员
            if(session('user_auth.username') == $user['username'] && '0' === session('user_auth.level')){
                if('0' !== $_POST['level']){
                    $this->error('权限级别只能选择：管理员');
                }
                if(session('user_auth.group_name') !== $_POST['group_name']){
                    $this->error('组别不能为空，且只能是：'.session('user_auth.group_name'));
                }
            }
            // 推广组长
            if(session('user_auth.username') == $user['username'] && '1' === session('user_auth.level')){
                if($_POST['level'] !== '1'){
                    $this->error('权限级别只能选择：推广组长');
                }
                if(session('user_auth.group_name') !== $_POST['group_name']){
                    $this->error('组别只能是：'.session('user_auth.group_name'));
                }
            }elseif (session('user_auth.username') !== $user['username'] && '1' === session('user_auth.level')){
                if($_POST['level'] !== '2'){
                    $this->error('权限级别只能选择：推广组员');
                }
                if(session('user_auth.group_name') !== $_POST['group_name']){
                    $this->error('组别只能是：'.session('user_auth.group_name'));
                }
            }

            // 提交数据
            $data = $user_object->create();
            if ($data) {
                $result = $user_object
                        ->field('id,nickname,username,password,level,group_name,email,email_bind,mobile,mobile_bind,gender,avatar,update_time')
                        ->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $user_object->getError());
                }
            } else {
                $this->error($user_object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('User')->find($id);
            unset($info['password']);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑用户')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('nickname', 'text', '昵称', '昵称')
                    ->addFormItem('username', 'text', '用户名', '用户名')
                    ->addFormItem('password', 'password', '密码', '密码')
                    ->addFormItem('level', 'radio', '权限级别', '权限级别', array('0' => '管理员', '1' => '推广组长', '2' => '推广组员'))
                    ->addFormItem('group_name', 'text', '组别', '组别')
                    ->addFormItem('email', 'text', '邮箱', '邮箱')
                    ->addFormItem('email_bind', 'radio', '邮箱绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                    ->addFormItem('mobile', 'text', '手机号', '手机号')
                    ->addFormItem('mobile_bind', 'radio', '手机绑定', '手机绑定', array('1' => '已绑定', '0' => '未绑定'))
                    ->addFormItem('avatar', 'picture', '头像', '头像')
                    ->setFormData($info)
                    ->display();
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author jry <93058680@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME){
        $ids = I('request.ids');

        if (is_array($ids)) {
            if(in_array('1', $ids)) {
                $this->error('超级管理员不允许操作');
            }
            if(in_array(session('user_auth.uid'), $ids)){
                $this->error('系统不允许用户操作自己的账号');
            }
        } else {
            if($ids === '1') {
                $this->error('超级管理员不允许操作');
            }
            if(session('user_auth.uid') === $ids){
                $this->error('系统不允许用户操作自己的账号');
            }
        }
        parent::setStatus($model);
    }
}
