<?php
namespace Home\Controller;
class Pay095bankController extends HomeController {
  /**
   * 金阳支付入款
   * @author xufudesign <93058680@qq.com> 2017-11-06T18:37:19+0800
   * @return [type] [description]
   */
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Pay095bank_conf')->where("payclass='Pay095bank'")->find();
    }

  public function pay(){
    $orderno = I('orderno','','trim');

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->where("orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');
    $order['banks'] = array(
        'ICBC'=>'工商银行',
        'ABC'=>'农业银行',
        'CCB'=>'建设银行',
        'BOC'=>'中国银行',
        'CMB'=>'招商银行',
        'BCCB'=>'北京银行',
        'BOCO'=>'交通银行',
        'CIB'=>'兴业银行',
        'NJCB'=>'南京银行',
        'CMBC'=>'民生银行',
        'CEB'=>'光大银行',
        'PINGANBANK'=>'平安银行',
        'CBHB'=>'渤海银行',
        'HKBEA'=>'东亚银行',
        'NBCB'=>'宁波银行',
        'CTTIC'=>'中信银行',
        'GDB'=>'广发银行',
        'SHB'=>'上海银行',
        'SPDB'=>'上海浦东发展银行',
        'PSBS'=>'中国邮政',
        'HXB'=>'华夏银行',
        'BJRCB'=>'北京农村商业银行',
        'SRCB'=>'上海农商银行',
        'SDB'=>'深圳发展银行',
        'CZB'=>'浙江稠州商业银行',
        'SRCB'=>'上海农商行'
    );
    $this->assign('order',$order)->display('Pay/banks');
  }

  public function go() {
       $orderno = I('orderno','','trim');

        if(empty($orderno)){
           $this->error('参数错误!');
       }
       $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');

       $gateway = 'http://pay.095pay.com/zfapi/order/pay';
       /* 排序并组装签名字符串 */
       $data = array();
       $data["p1_mchtid"] = $this->conf['accountId'];
       $data["p2_paytype"] = I('bankid');
       $data["p3_paymoney"] = sprintf("%0.02f", $order['amount']);
       $data["p4_orderno"] = $order['orderno'];
       $data["p5_callbackurl"] = U('Pay095bank/notify','',true,true);//异步通知
       $data["p6_notifyurl"] = U('Pay095bank/hrefback','',true,true);//同步通知
       $data["p7_version"] = "v2.8";
       $data["p8_signtype"] = "1";
       $data["p9_attach"] = "";
       $data["p10_appname"] = "";
       $data["p11_isshow"] = "1";//是否显示PC收银台
       $data["p12_orderip"] = $order['ip'];
       $data["sign"] = $this->cteateSign($data);
       $res = $this->buildForm($data, $gateway);
       echo $res;

    }

    public function notify(){
        $data = I('request.');
        $this->log('notify:'.http_build_query($data));
        $sign = $this->prepareSign($data);

        if ($data['sign'] == $sign){
          if($data['orderstatus']=='1'){
              $order = array(
                'payzt'=>1,
                'orderno'=>$data['ordernumber'],
                'payno'=>$data['sysnumber'],
                'paytime'=>NOW_TIME
              );
              $this->OrderChangs($order);
          }
          exit('ok');
        }
    }

    public function hrefback(){
      $data = I('request.');
        $this->log('hrefback:'.http_build_query($data));
        $sign = $this->prepareSign($data);

        if ($data['sign'] == $sign){
          if($data['orderstatus']=='1'){
              $order = array(
                'payzt'=>1,
                'orderno'=>$data['ordernumber'],
                'payno'=>$data['sysnumber'],
                'paytime'=>NOW_TIME
              );
              $this->OrderChangs($order);
          }
          $this->success('支付成功！','/');
        }else{
          $this->error("支付失败，如有疑问请联系客服人员",'/');
        }
    }

  protected function prepareSign($data){
        $signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s",$data['partner'], $data['ordernumber'], $data['orderstatus'], $data['paymoney'], $this->conf['accountKey']);
        return md5($signSource);
    }

  protected function cteateSign($param) {
    $string = '';
    //ksort($param);
    foreach($param as $k => $value) {
      $string .= $k . '=' . $value . '&';
    }
    $string = rtrim($string, '&');

    return md5($string . $this->conf['accountKey']);
  }

  /**
     * 发送curl-post请求
     * @author Ben
     * @date 2017-10-27
     * @param $aPostData
     * @param $respondType 1 xml 2 json
     * @param $timeout
     * @return array
     */
    protected function curlPost($aPostData, $sUrl, $timeout = 5) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, (int)$timeout);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11');// 添加浏览器内核信息，解决403问题 add by ben 2017/10/25

        $response = curl_exec($ch);

        $res = json_decode($response, true);
        // 如果没有decode成功，也许是因为三方用的是GB2312
        if (is_null($res)) {
            $res = json_decode(iconv('GB2312', 'UTF-8', $response), true);
        }
        curl_close($ch);

        return $res;
    }
}
