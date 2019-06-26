<?php
namespace Home\Controller;
class HaolianwxController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Haolianwx_conf')->where("payclass='Haolianwx'")->find();
    }
    public function pay() {
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
       $data['notifyurl'] = U('Haolianwx/notify','',true,true);//异步通知
       $data['returnurl'] = U('Haolianwx/hrefback','',true,true);//异步通知
       $data['paytype'] = 'weixin';
       $data['remark'] = '';
       $data['get_code'] = '';
       $data['bankcode'] = '';
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
