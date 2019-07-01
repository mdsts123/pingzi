<?php
// +----------------------------------------------------------------------
// | 推广订单
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: andy <3297123230@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Util\Think\Page;
use Common\Controller\Sfzfdd;
/**
 * 推广订单
 * @author jry <93058680@qq.com>
 */
class TgOrderController extends AdminController {
    public function index($group=''){
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
        // 判断用户权限级别
        if(1 == session('user_auth.level')){
            $map['groupname'] = session('user_auth.group_name');
        }elseif (2 == session('user_auth.level')){
            $map['commitname'] = session('user_auth.username');
        }
        switch ($group) {
            case '1':
                $map['pay_status'] = array('eq','0');
                break;
            case '2':
                $map['pay_status'] = array('eq','1');
                break;
            case '3':
                $map['pay_status'] = array('eq','2');
                break;
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

        $tab_list[0]['title'] = '全部订单';
        $tab_list[0]['href']  = U('index');
        $tab_list[1]['title'] = '未处理';
        $tab_list[1]['href']  = U('index', array('group' => '1'));
        $tab_list[2]['title'] = '充值成功';
        $tab_list[2]['href']  = U('index', array('group' => '2'));
        $tab_list[3]['title'] = '充值失败';
        $tab_list[3]['href']  = U('index', array('group' => '3'));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('订单列表') // 设置页面标题
        ->addTopButton('addnew')  // 添加新增按钮
        ->addTopButton('delete')  // 添加删除按钮
        ->setTabNav($tab_list, $group)
            ->setSearch('请输入订单号/会员账号/收款人/付款人/提交用户', U('index'))
            //->addTableColumn('id', 'ID')
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
                ->addFormItem('commit_type', 'radio', '提交类型', '提交类型', array('0' => '充值', '1' => '彩金'))
                ->addFormItem('pay_type', 'radio', '付款类型', '付款类型', array('0' => 'A扫码', '1' => 'B扫码','2'=> 'C扫码', '3'=>'D扫码', '4' => '银行卡转账', '5' => '第三方','6'=>'快充')) //凤凰
                //    ->addFormItem('pay_type', 'radio', '付款类型', '付款类型', array('0' => 'A扫码', '1' => 'B扫码','2'=> 'C扫码', '3'=>'D扫码','4'=>'E扫码', '5' => '银行卡转账', '6' => '第三方','7'=>'快充'))
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
                ->addFormItem('commit_type', 'radio', '提交类型', '提交类型', array('0' => '充值', '1' => '彩金'))
                ->addFormItem('pay_type', 'radio', '付款类型', '付款类型', array('0' => 'A扫码', '1' => 'B扫码','2'=> 'C扫码', '3'=>'D扫码', '4' => '银行卡转账', '5' => '第三方','6'=>'快充')) //凤凰
            //    ->addFormItem('pay_type', 'radio', '付款类型', '付款类型', array('0' => 'A扫码', '1' => 'B扫码','2'=> 'C扫码', '3'=>'D扫码','4'=>'E扫码', '5' => '银行卡转账', '6' => '第三方','7'=>'快充'))
                ->addFormItem('username', 'text', '会员账号', '会员账号')
                ->addFormItem('amount', 'text', '充值金额', '充值金额')
                ->addFormItem('desc', 'text', '备注', '备注')
                ->setFormData(array('cmit_time' => time()))
                ->setFormData($info)
                ->display();
        }
    }

    /**
     * 订单详情
     * @param $id
     * http://www.lingyun.com/admin.php?s=/Admin/TgOrder/payTgOrder/id/11.html
     */
    public function payTgOrder($id) {
        // 获取推广订单信息
        $tg_order = D('TgOrder');
        $info = $tg_order->find($id);

        if(0 == $info['commit_type']){
            $info['commit_type_name'] = "充值";
        }elseif (1 == $info['commit_type']){
            $info['commit_type_name'] = "彩金";
        }
        // 凤凰
        if(0 == $info['pay_type']){
            $info['pay_type_name'] = "A扫码";
        }elseif (1 == $info['pay_type']){
            $info['pay_type_name'] = "B扫码";
        }elseif (2 == $info['pay_type']){
            $info['pay_type_name'] = "C扫码";
        }elseif (3 == $info['pay_type']){
            $info['pay_type_name'] = "D扫码";
        }elseif (4 == $info['pay_type']){
            $info['pay_type_name'] = "银行卡转账";
        }elseif (5 == $info['pay_type']){
            $info['pay_type_name'] = "第三方";
        }elseif (6 == $info['pay_type']){
            $info['pay_type_name'] = "快充";
        }

        // 天天
//        if(0 == $info['pay_type']){
//            $info['pay_type_name'] = "A扫码";
//        }elseif (1 == $info['pay_type']){
//            $info['pay_type_name'] = "B扫码";
//        }elseif (2 == $info['pay_type']){
//            $info['pay_type_name'] = "C扫码";
//        }elseif (3 == $info['pay_type']){
//            $info['pay_type_name'] = "D扫码";
//        }elseif (4 == $info['pay_type']){
//            $info['pay_type_name'] = "E扫码";
//        }elseif (5 == $info['pay_type']){
//            $info['pay_type_name'] = "银行卡转账";
//        }elseif (6 == $info['pay_type']){
//            $info['pay_type_name'] = "第三方";
//        }elseif (7 == $info['pay_type']){
//            $info['pay_type_name'] = "快充";
//        }

        if(0 == $info['pay_status']){
            $info['pay_status_name'] = "未处理";
        }elseif (1 == $info['pay_status']){
            $info['pay_status_name'] = "充值成功";
        }elseif (2 == $info['pay_status']){
            $info['pay_status_name'] = "充值失败";
        }
        $info['cmit_time'] = date( "Y-m-d H:i:s",$info['cmit_time']);
        $info['pays'] = $this->payStatus();
        $data = array(
            'code'      => 200,
            'message'   => "成功",
            'data'      => $info,
        );
        exit(json_encode($data));
    }

    /**
     * 操作处理方法
     */
    public function toTgOrder(){
        // 获取下单数据
        $pay_status = I('post.pay_status');
        $username = I('post.username');
        $amount = I('post.amount');
        $orderno = I('post.orderno');
        // 获取操作数据
        $id = I('post.id');
        $condition['operatime'] = time();
        $condition['operaname'] = session('user_auth.username');
        $tg_order = D('TgOrder');
        if(1 == $pay_status){
            $mes = $this->place_order($amount,$username,$orderno);
            //$mes = json_decode($mes);
            if($mes['code']==0){
                $data = array(
                    'code'  => 201,
                    'message'   => $mes['message'],
                );
                exit(json_encode($data));
            }else{
                $condition['pay_status'] = $pay_status;
                $tg_order->where(['id'=>$id])->save($condition);
                $data = array(
                    'code'  => 200,
                    'message'   => "操作成功",
                );
                exit(json_encode($data));
            }
        }else if(2 == $pay_status){
            $condition['pay_status'] = $pay_status;
            $tg_order->where(['id'=>$id])->save($condition);
            $data = array(
                'code'  => 200,
                'message'   => "操作成功",
            );
            exit(json_encode($data));
        }
    }

    /**
     * 状态
     * @return array
     */
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
