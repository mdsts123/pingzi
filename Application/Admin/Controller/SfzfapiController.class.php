<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 19:23
 */

namespace Admin\Controller;


class SfzfapiController extends \Think\Controller
{
    /**
     * 四方支付订单生成接口
     * @return bool
     */
    public function index()
    {
		echo "";die;

        if(IS_POST) {
            if (empty($_POST['Amount'])) {
                echo '请传入订单金额！';
                return false;
            }
            if (empty($_POST['MemberAccount'])) {
                echo '请传入会员账号！';
                return false;
            }
            if (empty($_POST['MerchantId'])) {
                echo '请传入商户id！';
                return false;
            }
            if (empty($_POST['merchantkey'])) {
                echo '请传入商户密钥！';
                return false;
            }
            //充值金额(必填加入签名)
            $siFang['Amount'] = sprintf("%.2f",$_POST['Amount']);
           // $siFang['Amount'] = $_POST['Amount'];
            //$siFang['Amount'] = 100.20;
            //会员账号(必填加入签名)
            $siFang['MemberAccount'] = trim($_POST['MemberAccount']);
            //游戏平台客服提供(必填加入签名)
            $siFang['MerchantId'] = trim($_POST['MerchantId']);
            //游戏平台后台可自行设置(必填加入签名)
            $siFang['merchantkey'] = trim($_POST['merchantkey']);
            //生成签名
            $siFangSign = $this->prepareOrderSign($siFang);
            //快捷支付平台单号，最大长度20。可空，方便核对订单(非必填非加入签名)
            $siFang['MerchantOrderNumber'] = trim($_POST['MerchantOrderNumber']);
            //签名(必填非加入签名)
            $siFang['OrderSign'] = $siFangSign;
           //第二种方法
           unset($siFang['merchantkey']);
           $url = 'http://pay.ls1.lxwaf.com/FourthPay/AddOrders';
           $resultData = $this->buildForm($siFang, $url,"POST");
           var_dump($resultData);die;
        }else {
            $this->display();
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
    /**
     * 四方支付生成签名
     * @param $data
     * @return string
     */
    function prepareOrderSign($data)
    {
        ksort($data);
        $arg  = "";
        while (list ($key, $val) = each ($data)) {
            if($key!='merchantkey' && $val<>''){
                $arg .=$key."=".$val."&";
            }
        }
        $signature = strtolower(trim(($arg.'merchantkey='.$data['merchantkey'])));
        $signature = MD5($signature);
//        echo "<pre>";
//        var_dump($signature);die;
        return $signature;
    }
    function request($data, $gateway,$method='POST')
    {
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

}