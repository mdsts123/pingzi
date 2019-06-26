<?php
namespace Home\Controller;
class DdbillbankController extends HomeController {
	protected function _initialize(){
    $this->conf = M('Payapi')->cache('Ddbillbank_conf')->where("payclass='Ddbillbank'")->find();
    }
    public function pay(){
    $orderno = I('orderno','','trim');             

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->where("orderno='%s'",$orderno)->find();
	//print_r($order);exit;
    if(!$order) $this->error('定单不存在','/');
    $order['banks'] = array(
      "ABC"=>"农业银行",
      "ICBC"=>"工商银行",
      "CCB"=>"建设银行",
      "BCOM"=>"交通银行",
      "BOC"=>"中国银行",
      "CMB"=>"招商银行",
      "CMBC"=>"民生银行",
      "CEBB"=>"光大银行",
      "BOB"=>"北京银行",
      "SHB"=>"上海银行",
      "NBB"=>"宁波银行",
      "HXB"=>"华夏银行",
      "CIB"=>"兴业银行",
      "PSBC"=>"中国邮政银行",
      "SPABANK"=>"平安银行",
      "SPDB"=>"浦发银行",
      "ECITIC"=>"中信银行",
      "HZB"=>"杭州银行",
      "GDB"=>"广发银行"
    );
    $this->assign('order',$order)->display('Pay/banks');
  }
    public function go() {
      $gateway = 'https://pay.ddbill.com/gateway?input_charset=UTF-8';  
      $orderno = I('orderno','','trim');             
        
      if(empty($orderno)){
           $this->error('参数错误!');
       }
       $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');

       $service_type = 'direct_pay';
       

        $merchant_code = $this->conf['accountId'];//商户号，1118004517是测试商户号，调试时要更换商家自己的商户号

        $notify_url = U('Ddbillbank/notify','',true,true);   

        $interface_version ='V3.0';
        
        $client_ip = get_client_ip();
        
        $sign_type = 'RSA-S';

        $input_charset = "UTF-8";

        $order_no = $order["orderno"];

        $order_time = date("Y-m-d H:i:s");

        $order_amount =sprintf("%.02f", $order['amount']);

        $product_name =$order["orderno"];

        $product_code = "demo";
        
        $product_num = 1;
          
        $product_desc = "demo";

        $extra_return_param =$order["username"];
        
        $extend_param = "";

        $redo_flag = "";

        $show_url = "";

        $bank_code =I('bankid');

        $return_url = U('Ddbillbank/hrefback','',true,true);

        $signStr= "";
        
        if($bank_code != ""){
          $signStr .= "bank_code=".$bank_code."&";
        }
        if($client_ip != ""){
          $signStr .= "client_ip=".$client_ip."&";
        }
        if($extend_param != ""){
          $signStr .= "extend_param=".$extend_param."&";
        }
        if($extra_return_param != ""){
          $signStr .= "extra_return_param=".$extra_return_param."&";
        }
        
        $signStr .= "input_charset=".$input_charset."&";  
        $signStr .= "interface_version=".$interface_version."&";  
        $signStr .= "merchant_code=".$merchant_code."&";  
        $signStr .= "notify_url=".$notify_url."&";    
        $signStr .= "order_amount=".$order_amount."&";    
        $signStr .= "order_no=".$order_no."&";    
        $signStr .= "order_time=".$order_time."&";  

        if($pay_type != ""){
          $signStr .= "pay_type=".$pay_type."&";
        }

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
        if($redo_flag != ""){
          $signStr .= "redo_flag=".$redo_flag."&";
        }
        if($return_url != ""){
          $signStr .= "return_url=".$return_url."&";
        }   
        
        $signStr .= "service_type=".$service_type;

        if($show_url != ""){  
          
          $signStr .= "&show_url=".$show_url;
        }

        $private_key = "-----BEGIN PRIVATE KEY-----\n" . chunk_split($this->conf['private_key'], 64, "\n") . "-----END PRIVATE KEY-----";


        $private_key= openssl_get_privatekey($private_key);
          
        openssl_sign($signStr,$sign_info,$private_key,OPENSSL_ALGO_MD5);
        
        $sign = base64_encode($sign_info);
        
        $param=array('sign'=>$sign,
        'bank_code'=>$bank_code,
        'order_no'=>$order_no,
        'order_amount'=>$order_amount,
        'service_type'=>$service_type,
        'input_charset'=>$input_charset,
        'notify_url'=>$notify_url,
        'interface_version'=>$interface_version,
        'sign_type'=>$sign_type,
        'order_time'=>$order_time,
        'product_name'=>$product_name,
        'client_ip'=>$client_ip,
        'extend_param'=>$extend_param,
        'extra_return_param'=>$extra_return_param,
        'pay_type'=>$pay_type,
        'product_code'=>$product_code,
        'product_desc'=>$product_desc,
        'product_num'=>$product_num,
        'return_url'=>$return_url,
        'show_url'=>$show_url,
        'redo_flag'=>$redo_flag,
        'merchant_code'=>$merchant_code
        );
        //print_r($param);exit;
        $json = $this->buildForm($param,$gateway,'POST');
        exit($json);
    }

    public function notify(){
		$data = I('request.');
    	$this->log('notify:'.http_build_query($data));

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
