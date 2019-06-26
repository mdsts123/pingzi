<?php
namespace Home\Controller;
class XinmawxController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Xinmawx_conf')->where("payclass='Xinmawx'")->find();
    }
    public function pay(){
        $orderno = I('orderno','','trim');

        if(empty($orderno)){
          $this->redirect('/');
        }

        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');

       $reqData = array(
           'messageid'         => '200001',
           'out_trade_no'      => $order['orderno'],
           'back_notify_url'   => U('Xinmawx/notify','',true,true),
           'branch_id'         => $this->conf['accountId'],
           'prod_name'         => '测试支付',
           'prod_desc'         => '测试支付描述',
           'pay_type'          => '10',
           'total_fee'         => sprintf("%d", $order['amount']*100),
           'nonce_str'         => rand_string(32)
       );
       ksort($reqData);
       $reqData['sign'] = $this->prepareSign($reqData);
       $data_string = $this->zh_json_encode($reqData);
       $result = $this->httpPost($data_string, "https://www.xinmapay.com:7301/jhpayment");
       $resJson=json_decode($result);
       if ($resJson->resultCode == '00' && $resJson->resCode == '00') {

           $resultToSign = array();

           foreach ($resJson as $key => $value) {
               if ($key != 'sign') {
                   $resultToSign[$key] = $value;
               }
           }

           $resultSign = $this->prepareSign($resultToSign);

           if ($resultSign != $resJson->sign) {
               echo '签名验证失败';
           } else {
              $pays = (array)$resJson;
              $pays['barCode'] = $resJson->payUrl;
              $this->assign('order',$order)->assign($pays)->display('Pay/pay');
           }
       } else {
           echo $resJson->resDesc;
       }
    }

    public function notify(){
    	$data = I('param.');
    	$this->log('notify.post:'.$this->formatBizQueryParaMap($_POST));
    	$this->log('notify:'.$this->formatBizQueryParaMap($data));
      $resultJson=json_decode($data['data']);
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
            if($resultJson->status=='02'){
              $order = array(
                'payzt'=>1,
                'orderno'=>$resultJson->outTradeNo,
                'payno'=>$resultJson->orderNo,
                'paytime'=>NOW_TIME
              );
              $this->OrderChangs($order);
            }
            echo '订单通知支付成功';
          }
      } else {
          echo $resultJson->resDesc;
      }
    }
protected function httpPost($data_string,$url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );
    $result = curl_exec($ch);
    if (!$result)
    {
        var_dump(curl_error($curl));
    }
    curl_close($curl);
    return $result;

}
protected function formatBizQueryParaMap($map, $urlencode = false) {

    ksort($map);
    $result = array();
    foreach ($map as $key => $value) {

        if(!$value) {

            continue;

        }
        if($urlencode) {

            $value = urlencode($value);

        }
        $result[$key] = $value;

    }
    return urldecode(http_build_query($result));

}
protected function zh_json_encode($array) {
    $array = $this->urlencode_array($array);
    return urldecode(json_encode($array));
}

/**
 * 递归多维数组，进行urlencode
 * @param $array
 * @return mixed
 */
protected function urlencode_array($array) {
    foreach($array as $k => $v) {
        if(is_array($v)) {
            $array[$k] = $this->urlencode_array($v);
        } else {
            $array[$k] = urlencode($v);
        }
    }
    return $array;
}

    protected function prepareSign($data){
    	$signSource = $this->formatBizQueryParaMap($data)."&key=".$this->conf['accountKey'];
      return strtoupper(md5($signSource));

    }

}
