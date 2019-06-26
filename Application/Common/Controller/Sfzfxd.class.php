<?php
/**
 * 四方支付下单生成订单
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/18
 * Time: 19:32
 */
namespace Common\Controller;

class Sfzfxd extends \Think\Controller
{
    public $MerchantId;//商户id
    public $merchantkey;//商户密钥
    public $url;//提交url

    protected function _initialize()
    {
        $this->MerchantId = 'c081';
        $this->merchantkey = '6393c62e4aa547e14a2e8ca2e180f183';
        $this->url = 'http://pay.ls1.lxwaf.com/FourthPay/AddOrders';
    }
    /**
     * 四方支付订单生成接口
     * @param array $data
     * @return bool
     */
    public function sfzfapi($data= array())
    {
        if(empty($data)){
            return json_encode(array('code'=>0,'message'=>'生成下单数据不能为空!'));
        }
        if (empty($data['Amount'])) {
            return json_encode(array('code'=>0,'message'=>'生成下单缺少必填参数!'));
        }
        if (empty($data['MemberAccount'])) {
            return json_encode(array('code'=>0,'message'=>'生成下单缺少必填参数!'));
        }
        $MerchantId=$data['MerchantId']?$data['MerchantId']:$this->MerchantId;
        $merchantkey = $data['merchantkey']?$data['merchantkey']:$this->merchantkey;
        //充值金额(必填加入签名)
        $siFang['Amount'] = sprintf("%.2f",$data['Amount']);
        //会员账号(必填加入签名)
        $siFang['MemberAccount'] = trim($data['MemberAccount']);
        //游戏平台客服提供(必填加入签名)
        $siFang['MerchantId'] = trim($MerchantId);
        //游戏平台后台可自行设置(必填加入签名)
        $siFang['merchantkey'] = trim($merchantkey);
        //生成签名
        $siFangSign = $this->prepareOrderSign($siFang);
        //快捷支付平台单号，最大长度20。可空，方便核对订单(非必填非加入签名)
        $siFang['MerchantOrderNumber'] = trim($data['MerchantOrderNumber']);

        unset($siFang['merchantkey']);
        ksort($siFang);
        $arg  = "";
        while (list ($key, $val) = each ($siFang)) {
            if($key!='merchantkey' && $val<>''){
                $arg .=$key."=".$val."&";
            }
        }
        //签名(必填非加入签名)
        $siFang['OrderSign'] = $siFangSign;
        $arg = $arg.'OrderSign='.$siFangSign;
        $url = $data['url']?$data['url']:$this->url;
        $resultData = $this->request($arg, $url, 'POST');
        $result_array = json_decode($resultData,true);
        if(empty($result_array)){
            return json_encode(array('code'=>0,'message'=>'下单接口维护中,请联系客服!'));
        }
        $Status = $result_array['Status'];
        if ($Status == true) {
            return json_encode(array('code'=>1,'message'=>'下单成功!'));
        } else {
            return json_encode(array('code'=>0,'message'=>$result_array['Info']));
        }
    }
    /**
     * 提交下单数据
     * @param $data
     * @param $gateway
     * @param string $method
     * @return string
     */
    private function buildForm($data, $gateway,$method='POST')
    {
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
    private function prepareOrderSign($data)
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
        return $signature;
    }
    public  function request($data, $gateway,$method='POST')
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
            $header [] = 'Content-Type:application/x-www-form-urlencoded';
            curl_setopt_array($curl, $curlData);
            curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
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
        return $result;
    }
}