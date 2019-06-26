<?php
namespace Home\Controller;
use Common\Controller\ControllerController;
use Common\Controller\Sfzfdd;
class HomeController extends ControllerController {
    protected function _initialize() {
        // 系统开关
        if (!C('TOGGLE_WEB_SITE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        // 记录当前url
        if (MODULE_NAME !== 'User' && IS_GET === true) {
            cookie('forward', (is_ssl()?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
        }
    }

    protected function buildForm($data, $gateway,$method='POST') {                 
      $sHtml = "<form id='paysubmit' name='paysubmit' action='".$gateway."' method='$method'>";
      foreach ($data as $key => $val) {
          $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
      }
      $sHtml.= "</form><script>document.forms['paysubmit'].submit();</script>";
                
      return $sHtml;
    }

    protected function request($data, $gateway,$method='POST') {
        if($method=='POST'){
            $curl = curl_init();
            $curlData = array();
            $curlData[CURLOPT_POST] = true;
            $curlData[CURLOPT_URL] = $gateway;
            $curlData[CURLOPT_RETURNTRANSFER] = true;
            $curlData[CURLOPT_TIMEOUT] = 120;
            #CURLOPT_FOLLOWLOCATION
            $curlData[CURLOPT_POSTFIELDS] = $data;
            curl_setopt_array($curl, $curlData);
			//curl_setopt ($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt ($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $result = curl_exec($curl);
        }else{
            $curl = curl_init();
            $curlData = array();
            $curlData[CURLOPT_URL] = $gateway.'?'.http_build_query($data);
            $curlData[CURLOPT_RETURNTRANSFER] = true;
            $curlData[CURLOPT_TIMEOUT] = 120;
            #CURLOPT_FOLLOWLOCATION
            //curl_setopt_array($curl, $curlData);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $result = curl_exec($curl);
        }        
        
        if (!$result)
        {
            var_dump(curl_error($curl));
        }
        curl_close($curl);
        //echo $result;
        return $result;
    }

    protected function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }    

    protected function createLinkstring($para) {
        $para = $this->argSort($para);
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }

    protected function OrderChangs($order){
        $res = M('Order')->where("orderno='%s'",$order['orderno'])->save($order);
		return $res;
    }

    protected function log($log_content=''){
          $log_file = RUNTIME_PATH . date('Ymd').'_pay.log';
          $fp = fopen($log_file, 'a+');
          fwrite($fp, $log_content." [".date('Ymd H:i:s')."]\n");
          fclose($fp);
    }
	 /**
     * @param string $amount     下单金额
     * @param string $account    用户账户
     * @param string $merchantid 商户id
     * @param string $merchantkey 商户密钥
     * @param string $orderid     订单号码
     * @param string $url          下单地址
     */
    protected function place_order($amount='',$account='',$orderid='',$merchantid='',$merchantkey='',$url='')
    {
        $siFang_order['Amount'] = $amount;
        $siFang_order['MemberAccount'] = $account;
        $siFang_order['MerchantOrderNumber'] = $orderid;
        $siFang_order['MerchantId'] = $merchantid;
        $siFang_order['merchantkey'] = $merchantkey;
        $siFang_order['url'] = $url;
        $re = new Sfzfdd();
        $mes = $re->sfzfapi($siFang_order);
        $res = json_decode($mes,true);
        return $res;
    }
}
