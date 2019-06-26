<?php
namespace Home\Controller;
class DdbillpayController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Ddbillpay_conf')->where("payclass='Ddbillpay'")->find();
        //print_r($this->conf);
    }
    public function pay() {
      $gateway = 'https://api.ddbill.com/gateway/api/scanpay';  
      $orderno = I('orderno','','trim');             
        
      if(empty($orderno)){
           $this->error('参数错误!');
       }
       $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');
       if($order['cid']==3){
         $service_type = 'alipay_scan';
       }elseif($order['cid']==1){//微信
         $service_type = 'weixin_scan';
       }elseif($order['cid']==2){
         die("");
       }

        $merchant_code = $this->conf['accountId'];//商户号，1118004517是测试商户号，调试时要更换商家自己的商户号

        $notify_url = U('Ddbillpay/notify','',true,true);   

        $interface_version ='V3.1';
        
        $client_ip = get_client_ip();
        
        $sign_type = 'RSA-S';

        $order_no = $order["orderno"];

        $order_time = date("Y-m-d H:i:s");

        $order_amount =sprintf("%.02f", $order['amount']);

        $product_name =$order["orderno"];

        $product_code = "demo";
        
        $product_num = 1;
          
        $product_desc = "demo";

        $extra_return_param =$order["username"];
        
        $extend_param = "";

        $signStr = "";
        
        $signStr .= "client_ip=".$client_ip."&";  
        
        if($extend_param != ""){
          $signStr .= "extend_param=".$extend_param."&";
        }
        
        if($extra_return_param != ""){
          $signStr .= "extra_return_param=".$extra_return_param."&";
        }
        
        $signStr .= "interface_version=".$interface_version."&";  
        
        $signStr .= "merchant_code=".$merchant_code."&";  
        
        $signStr .= "notify_url=".$notify_url."&";    
        
        $signStr .= "order_amount=".$order_amount."&";    
        
        $signStr .= "order_no=".$order_no."&";    
        
        $signStr .= "order_time=".$order_time."&";  

        if($product_code != ""){
          $signStr .= "product_code=".$product_code."&";
        } 
        
        if($product_desc != ""){
          $signStr .= "product_desc=".$product_desc."&";
        }
        
        $signStr .= "product_name=".$product_name."&";

        if($product_num != ""){
          $signStr .= "product_num=".$product_num."&";
        } 
        
        $signStr .= "service_type=".$service_type;

        $private_key = "-----BEGIN PRIVATE KEY-----\n" . chunk_split($this->conf['private_key'], 64, "\n") . "-----END PRIVATE KEY-----";


        $private_key= openssl_get_privatekey($private_key);
          
        openssl_sign($signStr,$sign_info,$private_key,OPENSSL_ALGO_MD5);
        
        $sign = base64_encode($sign_info);
        
        $postdata=array('extend_param'=>$extend_param,
        'extra_return_param'=>$extra_return_param,
        'product_code'=>$product_code,
        'product_desc'=>$product_desc,
        'product_num'=>$product_num,
        'merchant_code'=>$merchant_code,
        'service_type'=>$service_type,
        'notify_url'=>$notify_url,
        'interface_version'=>$interface_version,
        'sign_type'=>$sign_type,
        'order_no'=>$order_no,
        'client_ip'=>$client_ip,
        'sign'=>$sign,
        'order_time'=>$order_time,
        'order_amount'=>$order_amount,
        'product_name'=>$product_name);
        //print_r($postdata);exit;
        $postdata = http_build_query($postdata);
       $response = $this->request($postdata, $gateway);

       $res=simplexml_load_string($response);
       //dump($res);exit;
       if($res->response->resp_code!='SUCCESS'){
        $this->error('出错了!'.$res->response->resp_desc);
       }else{
        $pays['barCode'] = $res->response->qrcode;
        $this->assign('order',$order)->assign($pays)->display('Pay/pay');
       }
    }

    public function notify(){
      $merchant_code  = I("merchant_code");  

      $notify_type = I("notify_type");

      $notify_id = I("notify_id");

      $interface_version = I("interface_version");

      $sign_type = I("sign_type");

      $DD4Sign = base64_decode(I("sign"));

      $order_no = I("order_no");

      $order_time = I("order_time"); 

      $order_amount = I("order_amount");

      $extra_return_param = I("extra_return_param");

      $trade_no = I("trade_no");

      $trade_time = I("trade_time");

      $trade_status = I("trade_status");

      $bank_seq_no = I("bank_seq_no");

      $signStr = "";

      if($bank_seq_no != ""){
        $signStr .= "bank_seq_no=".$bank_seq_no."&";
      }

      if($extra_return_param != ""){
        $signStr .= "extra_return_param=".$extra_return_param."&";
      } 

      $signStr .= "interface_version=".$interface_version."&";  

      $signStr .= "merchant_code=".$merchant_code."&";

      $signStr .= "notify_id=".$notify_id."&";

      $signStr .= "notify_type=".$notify_type."&";

      $signStr .= "order_amount=".$order_amount."&";  

      $signStr .= "order_no=".$order_no."&";  

      $signStr .= "order_time=".$order_time."&";  

      $signStr .= "trade_no=".$trade_no."&";  


      $signStr .= "trade_status=".$trade_status."&";

      $signStr .= "trade_time=".$trade_time;

      $public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($this->conf['public_key'], 64, "\n") . "-----END PUBLIC KEY-----";


      $public_key = openssl_get_publickey($public_key); 

      $flag = openssl_verify($signStr,$DD4Sign,$public_key,OPENSSL_ALGO_MD5);

      if($flag){            
        if($trade_status == "SUCCESS"){
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
}
