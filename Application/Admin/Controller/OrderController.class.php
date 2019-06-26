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
class OrderController extends AdminController {
    /**
     * 用户列表
     * @author jry <93058680@qq.com>
     */
    public function index($group='') {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['orderno|username|payno'] = array(
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );
        switch ($group) {
            case '1':
                $map['payzt'] = array('eq','0');
                break;

            case '2':
                $map['payzt'] = array('eq','1');
                break;
        }

        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object = D('Order');
        $data_list = $user_object
            ->page($p , C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        $tab_list[0]['title'] = '全部订单';
        $tab_list[0]['href']  = U('index');
        $tab_list[1]['title'] = '待付款';
        $tab_list[1]['href']  = U('index', array('group' => '1'));
        $tab_list[2]['title'] = '已付款';
        $tab_list[2]['href']  = U('index', array('group' => '2'));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('订单列表') // 设置页面标题
        ->addTopButton('resume',array('title' =>'结算'))  // 添加启用按钮
        ->addTopButton('delete')  // 添加禁用按钮
        ->setTabNav($tab_list, $group)
            ->setSearch('请输入订单号/用户名／付款号码', U('index'))
            ->addTableColumn('orderno', '订单号')
            ->addTableColumn('amount', '金额')
            ->addTableColumn('username', '用户名')
            ->addTableColumn('payid', '接口','payname')
            ->addTableColumn('cid', '类型','paytype')
            ->addTableColumn('ip', 'IP')
            ->addTableColumn('addtime', '下单时间', 'time')
            ->addTableColumn('paytime', '付款时间', 'time')
            ->addTableColumn('payno', '付款号码')
            ->addTableColumn('status', '结算状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list)    // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid')        // 添加禁用/启用按钮
            ->display();
    }
    public function indexwm($group='') {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['orderno|username|payno'] = array(
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );
        $map['payid'] = array('eq','0');
        switch ($group) {
            case '1':
                $map['payzt'] = array('eq','0');
                break;

            case '2':
                $map['payzt'] = array('eq','1');
                break;
        }
        // 获取所有用户
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object = D('Order');
        $data_list = $user_object
            ->page($p , C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        $tab_list[0]['title'] = '全部订单';
        $tab_list[0]['href']  = U('indexwm');
        $tab_list[1]['title'] = '待付款';
        $tab_list[1]['href']  = U('indexwm', array('group' => '1'));
        $tab_list[2]['title'] = '已付款';
        $tab_list[2]['href']  = U('indexwm', array('group' => '2'));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('订单列表') // 设置页面标题
        ->addTopButton('resume',array('title' =>'结算'))  // 添加启用按钮
        ->addTopButton('delete')  // 添加禁用按钮
        ->setTabNav($tab_list, $group)
            ->setSearch('请输入订单号/用户名／付款号码', U('indexwm'))
            ->addTableColumn('orderno', '订单号')
            ->addTableColumn('amount', '金额')
            ->addTableColumn('username', '用户名')
            ->addTableColumn('payid', '接口','payname')
            ->addTableColumn('cid', '类型','paytype')
            ->addTableColumn('ip', 'IP')
            ->addTableColumn('addtime', '下单时间', 'time')
            ->addTableColumn('paytime', '付款时间', 'time')
            ->addTableColumn('payno', '付款号码')
            ->addTableColumn('status', '结算状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list)    // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('forbid')        // 添加禁用/启用按钮
            ->addRightButton('recycle')        // 添加已付/未付按钮
            ->display();
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
        } else {
            if($ids === '1') {
                $this->error('超级管理员不允许操作');
            }
        }
        parent::setStatus($model);
    }

    public function countnow(){
        $count = M('Order')->where('payzt=1 AND status=0')->count('*');
        exit($count);
    }

}
