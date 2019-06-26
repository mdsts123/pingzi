<?php
namespace Home\Controller;
class Ak47payController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Ak47pay_conf')->where("payclass='Ak47pay'")->find();
    }
    public function pay(){
        $orderno = I('orderno','','trim');

        if(empty($orderno)){
          $this->redirect('/');
        }

        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');
       if($order['cid']==3){//支付宝
         $type = 'ALIPAY_QRCODE_PAY';//1
       }elseif($order['cid']==1){//微信
         $type = 'WECHAT_QRCODE_PAY';//1
       }elseif($order['cid']==2){
         $type = 'H5';//1
       }

       vendor("easypay.sdk");

       $parameters = array(
               "merchantNo" => $this->conf['accountId'],//86350120170628026
               "outTradeNo" => $order['orderno'],
               "currency" => "CNY",
               "amount" => sprintf("%d", $order['amount']*100),//单位分
               "payType" => $type,//
               "content" => $order['orderno'],
               "callbackURL" => U('Ak47pay/notify','',true,true)
               );

       $response = request("com.opentech.cloud.easypay.trade.create", "0.0.1", $parameters);
       if ($response['errorCode'] == 'SUCCEED') {
          $response['data'] = json_decode($response['data'],TRUE);
           if ($order['cid']==2) {
            $url = $response['data']['paymentInfo'];
              header("Location: $url");
           } else {

              $pays['barCode'] = $response['data']['paymentInfo'];
              $this->assign('order',$order)->assign($pays)->display('Pay/pay');
           }

       } else {
          die($response['msg']);
       }
    }

    public function notify(){
      vendor("easypay.sdk");
      function callback4pay($data) {
        $this->log('notify:'.$data);
        if($data['status']=='PAYED' || $data['status']=='SETTLED'){
          $order = array(
            'payzt'=>1,
            'orderno'=>$data['outTradeNo'],
            'payno'=>$data['status'].'_'.($data['amount']/100),
            'paytime'=>NOW_TIME
          );
          $this->OrderChangs($order);
        }

      }

      //
      process_callback4pay("callback4trade");


    	$data = I('post.data');

      $resultJson=json_decode($data);
      if ($resultJson->resultCode == '00' && $resultJson->resCode == '00') {

          $resultToSign = array();

          foreach ($resultJson as $key => $value) {
              if ($key != 'sign') {
                  $resultToSign[$key] = $value;
              }
          }

          $resultSign = $this->prepareSign($resultToSign);

          if ($resultSign != $resultJson->sign) {
              echo '签名验证失败';
          } else {

            echo '订单通知支付成功';
          }
      } else {
          echo $resultJson->resDesc;
      }
    }

}
