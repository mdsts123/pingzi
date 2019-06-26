<?php
namespace Home\Controller;
class HaolianbankController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Haolianbank_conf')->where("payclass='Haolianbank'")->find();
    }
    public function pay(){
    $orderno = I('orderno','','trim');

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->where("orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');
    $order['banks'] = array(
       'ICBC'=>'中国工商银行',
       'ABC'=>'中国农业银行',
       'BOCSH'=>'中国银行',
       'CCB'=>'建设银行',
       'CMB'=>'招商银行',
       'SPDB'=>'浦发银行',
       'GDB'=>'广发银行',
       'BOCOM'=>'交通银行',
       'PSBC'=>'邮政储蓄银行',
       'CNCB'=>'中信银行',
       'CMBC'=>'民生银行',
       'CEB'=>'光大银行',
       'HXB'=>'华夏银行',
       'CIB'=>'兴业银行',
       'BOS'=>'上海银行',
       'SRCB'=>'上海农商',
       'PAB'=>'平安银行',
       'BCCB'=>'北京银行'
    );
    $this->assign('order',$order)->display('Pay/banks');
  }

  public function go() {
        $type = I('type','','trim');
        $orderno = I('orderno','','trim');

        if(empty($orderno)){
           $this->error('参数错误!');
       }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');
       $data = array();

       $data['version'] = '1.0';
       $data['customerid'] = $this->conf['accountId'];
       $data['sdorderno'] = $order['orderno'];
       $data['total_fee'] = sprintf("%0.02f", $order['amount']);
       $data['payip'] = get_client_ip();
       $data['notifyurl'] = U('Haolianbank/notify','',true,true);//异步通知
       $data['returnurl'] = U('Haolianbank/hrefback','',true,true);//异步通知
       $data['paytype'] = 'bank';
       $data['remark'] = '';
       $data['get_code'] = '';
       $data['bankcode'] = I('bankid');
       $data['sign'] = md5('version='.$data['version'].'&customerid='.$data['customerid'].'&total_fee='.$data['total_fee'].'&sdorderno='.$data['sdorderno'].'&notifyurl='.$data['notifyurl'].'&returnurl='.$data['returnurl'].'&'.$this->conf['accountKey']);
       $code = $this->buildForm($data,"https://www.haolianpay.com/apisubmit");
       exit($code);

    }

    public function notify(){
    	 $data = I('post.');
        $this->log('notify:'.http_build_query($data));
        $versign=md5('customerid='.$data['customerid'].'&status='.$data['status'].'&sdpayno='.$data['sdpayno'].'&sdorderno='.$data['sdorderno'].'&total_fee='.$data['total_fee'].'&paytype='.$data['paytype'].'&'.$this->conf['accountKey']);
        if($versign!==$data['sign']) die('签名错误！');
        if($data['status']=='1'){
            $order = array(
              'payzt'=>1,
              'orderno'=>$data['sdorderno'],
              'payno'=>$data['sdpayno'],
              'paytime'=>NOW_TIME
            );
            $this->OrderChangs($order);
            exit('success');
        }


    }

    public function hrefback(){
    	$data = I('get.');
    	$this->log('hrefback:'.http_build_query($data));
      $versign=md5('customerid='.$data['customerid'].'&status='.$data['status'].'&sdpayno='.$data['sdpayno'].'&sdorderno='.$data['sdorderno'].'&total_fee='.$data['total_fee'].'&paytype='.$data['paytype'].'&'.$this->conf['accountKey']);

        if($versign!==$data['sign']) $this->error('签名错误！');
        if($data['status']=='1'){
            $this->success('支付成功！','/');
        }else{
          $this->error('error','/');
        }

    }
}
