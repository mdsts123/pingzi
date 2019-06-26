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
use Common\Controller\Sfzfdd;
/**
 * 推广订单
 * @author jry <93058680@qq.com>
 */
class TgOrderController extends AdminController {
    public function index(){
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['orderno|username|collname|payname|commitname'] = array(
            $condition,
            $condition,
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );

        if(2 == session('user_auth.level')){
            $map['groupname'] = session('user_auth.group_name');
        }elseif (3 == session('user_auth.level')){
            $map['commitname'] = session('user_auth.username');
        }

        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;

        $order_object = D('TgOrder');
        $data_list = $order_object
            ->page($p , C('ADMIN_PAGE_ROWS'))
            ->where($map)
            ->order('id desc')
            ->select();
        $page = new Page(
            $order_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('订单列表') // 设置页面标题
        ->addTopButton('addnew')  // 添加新增按钮
        ->addTopButton('delete')  // 添加删除按钮
        ->setSearch('请输入订单号/会员账号/收款人/付款人/提交用户', U('index'))
            ->addTableColumn('id', 'ID')
            ->addTableColumn('orderno', '订单号')
            ->addTableColumn('img_src', '凭证', 'picture', null, true)
            ->addTableColumn('cmit_time', '提交时间')
            ->addTableColumn('pay_type_name', '支付类型')
            ->addTableColumn('username', '会员账号')
            ->addTableColumn('pay_status_name', '状态')
            ->addTableColumn('collname', '收款人')
            ->addTableColumn('payname', '付款人')
            ->addTableColumn('amount', '充值金额')
            ->addTableColumn('giv_amount', '赠送金额')
            ->addTableColumn('desc', '备注')
            ->addTableColumn('commitname', '提交用户')
            ->addTableColumn('right_button', '操作', 'btn')
            ->addTableColumn('operaname', '操作人')
            ->addTableColumn('operatime', '操作时间')
            ->setTableDataList($data_list)    // 数据列表
            ->setTableDataPage($page->show()) // 数据列表分页
            ->addRightButton('edit')          // 添加编辑按钮
            ->addRightButton('operation')        // 自动下单
            ->addRightButton('delete')        // 添加删除按钮
            ->display();
    }

    /**
     * 添加订单
     * @author jry <93058680@qq.com>
     */
    public function add() {
        if (IS_POST) {
            $order_object = D('TgOrder');
            $data = $order_object->create();
            if ($data) {
                $data['img_src']=isset($_SESSION['imginfo']) ? $_SESSION['imginfo'] : '';
                $id = $order_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index'));
                    $_SESSION['erweima']='';
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($order_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') //设置页面标题
            ->setPostUrl(U('add'))    //设置表单提交地址
            ->addFormItem('orderno', 'hidden', '订单号', '订单号')
            ->addFormItem('commitname', 'hidden', '提交用户', '提交用户')
            ->addFormItem('img_src', 'picture', '上传凭证', '上传凭证')
            ->addFormItem('cmit_time', 'hidden', '提交时间', '提交时间')
            ->addFormItem('groupname', 'hidden', '组别', '组别')
                ->addFormItem('commit_type', 'radio', '提交类型', '提交类型', array('1' => '充值', '2' => '彩金'))
                ->addFormItem('pay_type', 'radio', '付款类型', '付款类型', array('1' => 'C/B/R扫码', '2' => 'A/D扫码', '3' => '银行卡转账'))
                ->addFormItem('username', 'text', '会员账号', '会员账号')
                ->addFormItem('reusername', 'text', '确认账号', '确认账号')
                ->addFormItem('amount', 'text', '充值金额', '充值金额')
                ->addFormItem('desc', 'text', '备注', '备注')
                ->setFormData(array('orderno' => order_sn(), 'commitname' => session('user_auth.username'), 'cmit_time' => time() ,'groupname'=> session('user_auth.group_name')))
                ->display();
        }
    }

    public function edit($id){
        // 获取推广订单信息
        $info = D('TgOrder')->find($id);

        if (IS_POST) {
            // 提交数据
            $api_obj = D('TgOrder');
            $data = $api_obj->create();
            if ($data) {
                $data['img_src']=empty($_SESSION['imginfo']) && isset($info['img_src']) && !empty($info['img_src']) ? $info['img_src'] : $_SESSION['imginfo'];
                $result = $api_obj->save($data);
                if ($result) {
                    $_SESSION['imginfo']='';
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $api_obj->getError());
                }
            } else {
                $this->error($api_obj->getError());
            }
        } else {

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();

            $builder->setMetaTitle('编辑推广订单') //设置页面标题
            ->setPostUrl(U('edit'))    //设置表单提交地址
            ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('cmit_time', 'hidden', '提交时间', '提交时间')
                ->addFormItem('img_src', 'picture', '上传凭证', '上传凭证')
                ->addFormItem('commit_type', 'radio', '提交类型', '提交类型', array('1' => '充值', '2' => '彩金'))
                ->addFormItem('pay_type', 'radio', '付款类型', '付款类型', array('1' => 'C/B/R扫码', '2' => 'A/D扫码', '3' => '银行卡转账'))
                ->addFormItem('username', 'text', '会员账号', '会员账号')
                ->addFormItem('amount', 'text', '充值金额', '充值金额')
                ->addFormItem('desc', 'text', '备注', '备注')
                ->setFormData(array('cmit_time' => time()))
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 操作
     * @param $id
     * http://www.lingyun.com/admin.php?s=/Admin/TgOrder/payTgOrder/id/11.html
     */
    public function payTgOrder($id) {
        // 获取推广订单信息
        $tg_order = D('TgOrder');
        $info = $tg_order->find($id);
        $info['pays'] = $this->payStatus();
        return json_encode($info);
    }

    public function toTgOrder($pay_status,$username,$amount,$order){
        $pay_status = I('post.pay_status');
        $username = I('post.username');
        $amount = I('post.amount');
        $order = I('post.orderno');
        $tg_order = D('TgOrder');
        if(1 == $pay_status){
            $mes = $this->place_order($username,$amount,$order);
            $mes = json_decode($mes);
            if($mes['code']==0){
                $this->error($mes['message']);
            }else{
                $condition['pay_status'] = $pay_status;
                if($tg_order->create($condition)){
                    $tg_order->save();
                }
            }
        }else{
            $condition['pay_status'] = $pay_status;
            if($tg_order->create($condition)){
                $tg_order->save();
            }
        }
    }

    public function payStatus(){
        $arr = array(
            '0' => array(
                'pay_status'        => 0,
                'pay_status_name'   => '未处理',
            ),
            '1' => array(
                'pay_status'        => 1,
                'pay_status_name'   => '自动下单',
            ),
            '2' => array(
                'pay_status'        => 2,
                'pay_status_name'   => '充值失败',
            )
        );
        return $arr;
    }

    /**
     * 自动下单
     * @param string $amount        下单金额
     * @param string $account       会员账号
     * @param string $orderid       订单号
     * @param string $merchantid    商户id
     * @param string $merchantkey   商户密钥
     * @param string $url           下单地址
     * @return mixed
     */
    protected function place_order($amount='',$account='',$orderid='',$merchantid='',$merchantkey='',$url='')
    {
        $siFang_order['Amount'] = $amount;
        $siFang_order['MemberAccount'] = $account;
        $siFang_order['MerchantOrderNumber'] = $orderid;
        $siFang_order['MerchantId'] = $merchantid;
        $siFang_order['merchantkey'] = $merchantkey;
        $siFang_order['url'] = $url;
        $re = new Sfzfdd();
        $mes = $re->sfzfapi($siFang_order);
        $res = json_decode($mes,true);
        return $res;
    }

}
