<?php
namespace Home\Controller;
class OwnpayController extends HomeController {
  protected function _initialize(){
    $this->iv = "00000000";
    $this->conf = M('Payapi')->cache('Ownpay_conf')->where("payclass='Ownpay'")->find();
  }
  public function pay() {        
    $orderno = I('orderno','','trim');             

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');

    $gateway = 'http://zf.szjhzxxkj.com/ownPay/pay';
    if($order['cid']==3){
      $paytype = '400012';
    }elseif($order['cid']==1){
      $paytype = '9012';
    }elseif($order['cid']==2){
      $paytype = '1005';
    }

    $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($this->conf['private_key'], 64, "\n") . "-----END RSA PRIVATE KEY-----";
    //echo($public_key);exit();
    $priKey= openssl_pkey_get_private($private_key);


    $data = array();
    $data['merchantNo'] = $this->conf['accountId']; //商户号
    $data['requestNo'] =  $order['orderno']; //支付流水
    $data['amount'] = sprintf("%d", $order['amount']*100);//金额（分）
    $data['payCode'] = $paytype;//业务代码
    $data['backUrl'] = U('Ownpay/hrefback','',true,true);   //页面返回URL
    $data['pageUrl'] = U('Ownpay/notify','',true,true);   //服务器返回URL
    $data['payDate'] = time();   //支付时间，必须为时间戳
    $data['agencyCode'] = 0;
    $data['cashier'] = 0;
    $data['remark1'] = '备注1'; 
    $data['remark2'] ='';
    $data['remark3'] = '';

    $signature=$data['merchantNo']."|".$data['requestNo']."|".$data['amount']."|".$data['pageUrl']."|".$data['backUrl']."|".$data['payDate']."|".$data['agencyCode']."|".$data['remark1']."|".$data['remark2']."|".$data['remark3'];
    openssl_sign($signature,$sign,$priKey);
    openssl_free_key($priKey);
    $sign = base64_encode($sign);
    $data['signature'] = $sign;
    $json = $this->request($data,$gateway,'POST');
    $pay = json_decode($json,true);
    if(!isset($pay['backQrCodeUrl'])){
      $this->log('提交:'.'出错了!'.$pay['code'] . '错误描述：' . $pay['msg']);
      die("接口维护中");
    }
    $pay['barCode'] = $pay['backQrCodeUrl'];
    $this->assign('order',$order)->assign($pay)->display('Pay/pay'); 
  }

  public function notify(){
    $public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($this->conf['public_key'], 64, "\n") . "-----END PUBLIC KEY-----";
    $pubkey= openssl_pkey_get_public($public_key);
    $data = I('request.');
    $this->log('notify:'.http_build_query($data));     
    $signature=$data['ret']."|".$data['msg'];
    openssl_sign($signature,$sign,$pubkey);
    openssl_free_key($pubkey);
    $versign = base64_encode($sign);
    $this->log('notify:生成后的签名'.$versign);
    if($versign!==$data['sign']) die('ERROR');
    $ret = json_decode($data['ret'],true);
    $msg = json_decode($data['msg'],true);
    if($ret['code']=='00'){
      $order = array(
        'payzt'=>1,
        'orderno'=>$msg['no'],
        'payno'=>$msg['payNo'],
        'paytime'=>NOW_TIME
      );
      $this->OrderChangs($order);   
      exit("SUCCESS");         
    }
  }

  public function hrefback(){
    $data['ret'] = I('request.ret');
    $data['msg'] = I('request.msg');
    $data['sign'] = I('request.sign');
    $this->log('hrefback:'.http_build_query($data));
    $signature=$data['ret']."|".$data['msg'];
    openssl_sign($signature,$sign,$pubkey);
    openssl_free_key($pubkey);
    $versign = base64_encode($sign);
    $this->log('hrefback:生成后的签名'.$versign);
    if($versign!==$data['sign']) $this->error('签名错误！');
    $ret = json_decode($data['ret'],true);
    $msg = json_decode($data['msg'],true);
    if($ret['code']=='00'){
      $this->success('支付成功！','/');
    }else{
      $this->error('支付失败！'.$ret['msg'],'/');
    }
  }

}