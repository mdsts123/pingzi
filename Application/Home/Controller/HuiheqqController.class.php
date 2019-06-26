<?php
namespace Home\Controller;
class HuiheqqController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Huiheqq_conf')->where("payclass='Huiheqq'")->find();
    }
    public function pay() {
       $orderno = I('orderno','','trim');

        if(empty($orderno)){
           $this->error('参数错误!');
       }
       $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');

       $gateway = "https://pay.huihepay.com/Gateway";
       /* 排序并组装签名字符串 */
       $data = array();
       $data["AppId"] = $this->conf['accountId'];
       $data["Method"] = "trade.page.pay";
       $data["Version"] = "v1.0";
       $data["Format"] = "JSON";
       $data["Charset"] = "UTF-8";
       $data["Timestamp"] = date("Y-m-d H:i:s");
       $data["SignType"] = "MD5";
       $data["OutTradeNo"] = $order['orderno'];
       $data["TotalAmount"] = sprintf("%0.02f", $order['amount']);
       $data["PassbackParams"] = $order['username'];
       $data["PayType"] = '3';//H5
       $data["Subject"] = "UserPAY";//H5
       $data["Body"] = "UserPAY:{$order['amount']}";//H5
       $data["NotifyUrl"] = U('Huiheqq/notify','',true,true);//异步通知
       $data["Sign"] = $this->cteateSign($data,$this->conf['accountKey']);
       $response = $this->request($data, $gateway);
       $res = json_decode($response,true);

       if($res['QrCode']<>'' && $res['Code']==0){
        $pays['barCode'] = $res['QrCode'];
        $this->assign('order',$order)->assign($pays)->display('Pay/pay');
       }else{
          die("接口维护中");
       }
    }

    public function notify(){
        $data = I('request.');
        $this->log('notify:'.http_build_query($data));
        $sign = $this->prepareSign($data,$this->conf['accountKey']);


        if ($data['Sign'] == $sign){
          if($data['Code']=='0'){
              $order = array(
                'payzt'=>1,
                'orderno'=>$data['OutTradeNo'],
                'payno'=>$data['TradeNo'],
                'paytime'=>NOW_TIME
              );
              $this->OrderChangs($order);
          }
          exit('SUCCESS');
        }
    }

    protected function cteateSign($data,$merchant_key){
        ksort($data);
        $arg  = "";
        while (list ($key, $val) = each ($data)) {
            if($key<>'Sign' && $key<>'SignType' && $val<>''){
                $arg .=$key."=".$val."&";
            }
        }
        $arg = substr($arg,0,count($arg)-2);
        return strtoupper(md5($arg.$merchant_key));
    }
}
