<?php
namespace Home\Controller;
class LfbpaybankController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Lfbpaybank_conf')->where("payclass='Lfbpaybank'")->find();
        //print_r($this->conf);
    }
      public function pay(){
      $orderno = I('orderno','','trim');

      if(empty($orderno)){
        $this->redirect('/');
      }
      $order = M('Order')->where("orderno='%s'",$orderno)->find();
      if(!$order) $this->error('定单不存在','/');
      $order['banks'] = array(
'ABC'=>'中国农业银行',
'BOC'=>'中国银行',
'CBHB'=>'渤海银行',
'CCB'=>'中国建设银行',
'CEB'=>'光大银行',
'CIB'=>'兴业银行',
'CMB'=>'招商银行',
'CMBC'=>'民生银行',
'CNCB'=>'中信银行',
'COMM'=>'交通银行',
'GDB'=>'广发银行',
'HXB'=>'华夏银行',
'ICBC'=>'中国工商银行',
'PAB'=>'平安银行',
'PSBC'=>'中国邮政储蓄银行',
'SPDB'=>'浦发银行',
      );
      $this->assign('order',$order)->display('Pay/banks');
    }

    public function go() {
      $gateway = 'http://gate.lfbpay.com/cooperate/gateway.cgi';
      $orderno = I('orderno','','trim');

      if(empty($orderno)){
           $this->error('参数错误!');
      }
      $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
      if(!$order) die('定单不存在');

      // 请求数据赋值
      $data = "";
      // 商户APINMAE，扫码支付
      $data['service'] = "TRADE.B2C";
      // 商户API版本
      $data['version'] = "1.0.0.0";
      // 商户在支付平台的的平台号
      $data['merId'] = $this->conf['accountId'];
      //商户订单号
      $data['tradeNo'] = $order["orderno"];
      // 商户订单日期
      $data['tradeDate'] = date("Ymd");
      // 商户交易金额
      $data['amount'] = sprintf("%.02f", $order['amount']);
      // 商户通知地址
      $data['notifyUrl'] = U('Lfbpaybank/notify','',true,true);
      // 商户扩展字段
      $data['extra'] = $order["orderno"];
      // 商户交易摘要
      $data['summary'] = $order["orderno"];
      //超时时间
      $data['expireTime'] = 180;
      //客户端ip
      $data['clientIp'] = $order["ip"];

      // 接收银行代码
      $data['bankId'] = I('bankid');

      $str_to_sign = $this->prepareSign($data);
      // 数据签名
      $data['sign'] = $this->sign($str_to_sign);

      echo $resultData = $this->buildForm($data,$gateway,'POST');//$this->buildForm($to_requset, $gateway);
    }

    public function notify(){
      $data = "";
      $data['service'] = $_REQUEST["service"];
      // 通知时间
      $data['merId'] = $_REQUEST["merId"];
      // 支付金额(单位元，显示用)
      $data['tradeNo'] = $_REQUEST["tradeNo"];
      // 商户号
      $data['tradeDate'] = $_REQUEST["tradeDate"];
      // 商户参数，支付平台返回商户上传的参数，可以为空
      $data['opeNo'] = $_REQUEST["opeNo"];
      // 订单号
      $data['opeDate'] = $_REQUEST["opeDate"];
      // 订单日期
      $data['amount'] = $_REQUEST["amount"];
      // 支付订单号
      $data['status'] = $_REQUEST["status"];
      // 支付账务日期
      $data['extra'] = $_REQUEST["extra"];
      // 订单状态，0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
      $data['payTime'] = $_REQUEST["payTime"];
      // 签名数据
      $data['sign'] = $_REQUEST["sign"];
      $data['notifyType'] = $_REQUEST["notifyType"];
      $str_to_sign = $this->prepareSign($data);
      // 验证签名
      $resultVerify = $this->verify($str_to_sign, $data['sign']);
      if($resultVerify){
        if($data['status'] == 1){
          $order = array(
            'payzt'=>1,
            'orderno'=>$order_no,
            'payno'=>$trade_no,
            'paytime'=>NOW_TIME
          );
          $this->OrderChangs($order);
        }
        exit("SUCCESS");

      }else{
        echo "Signature error";
      }
    }

    public function hrefback(){

    }
/**
   * @name  准备签名/验签字符串
   */
  //merchParam   expireTime   tradeSummary  expireTime  clientIp
  /**
   * @param $data
   * @return string
     */

  public function prepareSign($data) {
    //1网银支付
    if($data['service'] == 'TRADE.B2C') {
      $result = sprintf(
        "service=%s&version=%s&merId=%s&tradeNo=%s&tradeDate=%s&amount=%s&notifyUrl=%s&extra=%s&summary=%s&expireTime=%s&clientIp=%s&bankId=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['amount'],
          $data['notifyUrl'],
          $data['extra'],
          $data['summary'],
          $data['expireTime'],
          $data['clientIp'],
          $data['bankId']
      );


      return $result;
      //2扫码支付
      }else if($data['service'] == 'TRADE.SCANPAY'){
      $result = sprintf(
          "service=%s&version=%s&merId=%s&typeId=%s&tradeNo=%s&tradeDate=%s&amount=%s&notifyUrl=%s&extra=%s&summary=%s&expireTime=%s&clientIp=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['typeId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['amount'],
          $data['notifyUrl'],
          $data['extra'],
          $data['summary'],
          $data['expireTime'],
          $data['clientIp']


      );

      return $result;

      //3支付订单查询
    }else if($data['service'] == 'TRADE.QUERY'){
      $result = sprintf(
          "service=%s&version=%s&merId=%s&tradeNo=%s&tradeDate=%s&amount=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['amount']
      );

      return $result;
      //4退款申请
    }else if($data['service'] == 'TRADE.REFUND'){
      $result = sprintf(
          "service=%s&version=%s&merId=%s&tradeNo=%s&tradeDate=%s&amount=%s&summary=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['amount'],
            $data['summary']
      );
      return $result;
      //5单笔委托结算
    }else if($data['service'] == 'TRADE.SETTLE'){

           $result = sprintf(
          "service=%s&version=%s&merId=%s&tradeNo=%s&tradeDate=%s&amount=%s&notifyUrl=%s&extra=%s&summary=%s&bankCardNo=%s&bankCardName=%s&bankId=%s&bankName=%s&purpose=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['amount'],
          $data['notifyUrl'],
          $data['extra'],
          $data['summary'],
          $data['bankCardNo'],
          $data['bankCardName'],
          $data['bankId'],
          $data['bankName'],
          $data['purpose']

      );

      return $result;

      //6单笔委托结算查询
    }else if($data['service'] == 'TRADE.SETTLE.QUERY'){
      $result = sprintf(
          "service=%s&version=%s&merId=%s&tradeNo=%s&tradeDate=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['tradeNo'],
          $data['tradeDate']
      );
      return $result;
      //7回调
    }else if($data['service'] == 'TRADE.NOTIFY'){
      $result = sprintf(
          "service=%s&merId=%s&tradeNo=%s&tradeDate=%s&opeNo=%s&opeDate=%s&amount=%s&status=%s&extra=%s&payTime=%s",
          $data['service'],
          $data['merId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['opeNo'],
          $data['opeDate'],
          $data['amount'],
          $data['status'],
          $data['extra'],
          $data['payTime']
      );
      return $result;
      //h5支付
    }else if($data['service'] == 'TRADE.H5PAY'){
      $result = sprintf(
          "service=%s&version=%s&merId=%s&typeId=%s&tradeNo=%s&tradeDate=%s&amount=%s&notifyUrl=%s&extra=%s&summary=%s&expireTime=%s&clientIp=%s",
          $data['service'],
          $data['version'],
          $data['merId'],
          $data['typeId'],
          $data['tradeNo'],
          $data['tradeDate'],
          $data['amount'],
          $data['notifyUrl'],
          $data['extra'],
          $data['summary'],
          $data['expireTime'],
          $data['clientIp']


      );
           return $result;
        }


  }

  /**
   * @name  生成签名
   * @param sourceData
   * @return  签名数据
   */
  public function sign($data) {

    $signature=md5($data.$this->conf['accountKey']);

    return $signature;
  }
/*
   * @name  准备带有签名的request字符串
   * @desc  merge signature and request data
   * @param request字符串
   * @param 签名数据
   * @return
   */
  public function prepareRequest($string, $signature) {
    return $string.'&sign='.$signature;
  }
  /*
   * @name  准备获取验签数据
   * @desc  extract signature and string to verify from response result
   */
  public function prepareVerify($result) {

    preg_match('{<detail>(.*?)</detail>}', $result, $match);
    $srcData = $match[0];
    preg_match('{<sign>(.*?)</sign>}', $result, $match);

    $signature = $match[1];
    $signature = str_replace('%2B', '+', $signature);
        return array($srcData, $signature);
  }

  /*
   * @name  验证签名
   * @param signData 签名数据
   * @param sourceData 原数据
   * @return
   */
  public function verify($data, $signature) {
    $mySign = $this->sign($data);
    if (strcasecmp($mySign, $signature) == 0) {
      return true;
    } else {
      return false;
    }

  }

}
