<?php
namespace Home\Controller;
class EboopayController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Eboopay_conf')->where("payclass='Eboopay'")->find();
    }
    public function pay() {
        $orderno = I('orderno','','trim');

        if(empty($orderno)){
           $this->error('参数错误!');
       }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');

       $jsapi = array(
           "pay_memberid" => $this->conf['accountId'],
           "pay_orderid" => $order['orderno'].$this->conf['accountId'],
           "pay_amount" => sprintf("%0.02f", $order['amount']),
           "pay_applydate" => date("Y-m-d H:i:s"),
           "pay_bankcode" => "ALIPAY",
           "pay_notifyurl" => U('Eboopay/notify','',true,true),
       );

       ksort($jsapi);
       $md5str = "";
       foreach ($jsapi as $key => $val) {
           $md5str = $md5str . $key . "=" . $val . "&";
       }
       //die($md5str . "key=" . $this->conf['accountKey']."<br>");
       $sign = strtoupper(md5($md5str . "key=" . $this->conf['accountKey']));
       $jsapi["pay_md5sign"] = $sign;
       //print_r($jsapi);
       $ret = $this->buildForm($jsapi,"http://sapi.eboopay.com/Pay_Index.html",'POST');
       die($ret);

    }

    public function notify(){
    	$data = array( // 返回字段
            "memberid" => I('param.memberid'), // 商户ID
            "orderid" =>  I('param.orderid'), // 订单号
            "amount" =>  I('param.amount'), // 交易金额
            "sign" =>  I('param.sign'), //
            "datetime" =>  I('param.datetime'), // 交易时间
            "returncode" => I('param.returncode')//响应码
        );

    	$this->log('notify:'.http_build_query($data));

        $signSource = $this->verSign($data);

        if($signSource!==$data['sign']) die('签名错误！');
        if($data['returncode']=='00000'){
            $order = array(
              'payzt'=>1,
              'orderno'=>$data['orderid'],
              'payno'=>$data['datetime'],
              'paytime'=>NOW_TIME
            );
            $this->OrderChangs($order);
            exit('success');
        }


    }

    protected function verSign($data){
        unset($data['sign']);
        ksort($data);
        reset($data);
        $md5str = "";
        foreach ($data as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        return strtoupper(md5($md5str . "key=" . $this->conf['accountKey']));
    }

}
