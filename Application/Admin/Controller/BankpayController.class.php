<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | 使用本产品，应遵守您所在国的相应法律法规，不得将本产品用于非法用途，包括但不限于贩毒、走私、赌博等
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Util\Think\Page;
/**
 * 代付控制器
 * @author jry <93058680@qq.com>
 */
class BankpayController extends AdminController {
    public function index($group='') {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['cardno'] = $condition;

        // 获取所有银行帐号
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $Bankpay_object = D('Bankpay');
        $data_list = $Bankpay_object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
        $page = new Page(
            $Bankpay_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('网银列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                ->addTopButton('delete')  // 添加删除按钮
                ->setSearch('请输入卡号查询', U('index'))
                ->addTableColumn('bank', '银行')
                ->addTableColumn('name', '姓名')
                ->addTableColumn('cardno', '卡号')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('addtime', '操作时间', 'time')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->addRightButton('edit')          // 添加编辑按钮
                ->addRightButton('forbid')        // 添加禁用/启用按钮
                ->addRightButton('recycle')        // 添加删除按钮
                ->display();
    }

    public function add() {
        if (IS_POST) {
            $Bankpay_object = D('Bankpay');
            $data = $Bankpay_object->create();
            if ($data) {
                $id = $Bankpay_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Bankpay_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增银行帐号') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('bank', 'text', '银行名称', '开户行名称')
                    ->addFormItem('name', 'text', '姓名', '收款人姓名')
                    ->addFormItem('cardno', 'text', '卡号', '收款人卡号')
                    ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用', '0' => '禁用'))
                    ->display();
        }
    }

    /**
     * 编辑银行帐号
     * @author jry <93058680@qq.com>
     */
    public function edit($id) {
        if (IS_POST) {
            // 提交数据
            $Bankpay_object = D('Bankpay');
            $data = $Bankpay_object->create();
            if ($data) {
                $result = $Bankpay_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $Bankpay_object->getError());
                }
            } else {
                $this->error($Bankpay_object->getError());
            }
        } else {
            // 获取账号信息
            $info = D('Bankpay')->find($id);
            unset($info['password']);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑银行帐号')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('bank', 'text', '银行名称', '开户行名称')
                    ->addFormItem('name', 'text', '姓名', '收款人姓名')
                    ->addFormItem('cardno', 'text', '卡号', '收款人卡号')
                    ->addFormItem('status', 'radio', '状态', '状态', array('1' => '启用', '0' => '禁用'))
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
        parent::setStatus($model);
    }
}