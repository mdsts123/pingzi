<?php
namespace Home\Controller;
class OwnbankController extends HomeController {
  protected function _initialize(){
    $this->iv = "00000000";
    $this->conf = M('Payapi')->cache('Ownbank_conf')->where("payclass='Ownbank'")->find();
  }
  public function pay(){
    $orderno = I('orderno','','trim');             

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->where("orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');
    $order['banks'] = array(
      "1041000"=>"中国银行",
      "1031000"=>"中国农业银行",
      "1021000"=>"中国工商银行",
      "1051000"=>"中国建设银行",
      "3012900"=>"交通银行",
      "3085840"=>"招商银行",
      "3051000"=>"中国民生银行",
      "3093910"=>"兴业银行",
      "3102900"=>"上海浦东发展银行",
      "3065810"=>"广东发展银行",
      "3021000"=>"中信银行",
      "3031000"=>"光大银行",
      "4031000"=>"中国邮政储蓄银行",
      "3071000"=>"平安银行",
      "3131000"=>"北京银行",
      "3133010"=>"南京银行",
      "3133320"=>"宁波银行",
      "3222900"=>"上海农村商业银行",
      "5021000"=>"东亚银行"
    );
    $this->assign('order',$order)->display('Pay/banks');
  }
  public function go() {        
    $orderno = I('orderno','','trim');             

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->where("orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');


    $gateway = 'http://zf.szjhzxxkj.com/ownPay/pay';
    if($order['cid']==3){
      $paytype = '400012';
    }elseif($order['cid']==1){
      $paytype = '9012';
    }elseif($order['cid']==2){
      $paytype = '1002';
    }
    
    $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($this->conf['private_key'], 64, "\n") . "-----END RSA PRIVATE KEY-----";
    //echo($public_key);exit();
    $priKey= openssl_pkey_get_private($private_key); 

    $data = array();
    $data['merchantNo'] = $this->conf['accountId']; //商户号
    $data['requestNo'] =  $order['orderno']; //支付流水
    $data['amount'] = sprintf("%d", $order['amount']*100);//金额（分）
    $data['payCode'] = $paytype;//业务代码
    $data['backUrl'] = U('Ownbank/hrefback','',true,true);   //页面返回URL
    $data['pageUrl'] = U('Ownbank/notify','',true,true);   //服务器返回URL
    $data['payDate'] = time();   //支付时间，必须为时间戳
    $data['agencyCode'] = 0;
    $data['cashier'] = 0;
    $data['remark1'] = '备注1'; 
    $data['remark2'] ='';
    $data['remark3'] = '';
    $data['bankType'] = I('bankid');
    $data['bankAccountType'] = 11;
    //print_r($data);exit;

    $signature=$data['merchantNo']."|".$data['requestNo']."|".$data['amount']."|".$data['pageUrl']."|".$data['backUrl']."|".$data['payDate']."|".$data['agencyCode']."|".$data['remark1']."|".$data['remark2']."|".$data['remark3'];
    openssl_sign($signature,$sign,$priKey);
    openssl_free_key($priKey);
    $sign = base64_encode($sign);
    $data['signature'] = $sign;
    $json = $this->request($data,$gateway,'POST');
    //$json = $this->buildForm($data,$gateway,'POST');
    exit($json);       
    
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