<?php
/**
 * 四方支付订单生成订单
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/05/06
 * Time: 12:59
 */
namespace Common\Controller;
use Think\Log;
class Sfzfdd extends \Think\Controller
{
    public $MerchantId;//站长id
    public $url;//接入地址
    public $Memberkey;//接入地址
    public $MemberId;

    protected function _initialize()
    {
        $this->MerchantId = 'a11-1274325248';//站长id
        $this->Memberkey = '6f5251c24b354569b6df6a8349378c45';//密钥
        $this->url = 'http://payback.2448880.com/foxpay/forth/pay/index';//接入地址
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
        //订单金额
        if (empty($data['Amount'])) {
            return json_encode(array('code'=>0,'message'=>'生成下单缺少必填参数!'));
        }
        //会员账户
        if (empty($data['MemberAccount'])) {
            return json_encode(array('code'=>0,'message'=>'生成下单缺少必填参数!'));
        }
        //会员订单号
        if (empty($data['MerchantOrderNumber'])) {
            return json_encode(array('code'=>0,'message'=>'生成下单缺少必填参数!'));
        }
        $MerchantId = $data['MerchantId']?$data['MerchantId']:$this->MerchantId;//站长id
        //站长id(必填加入签名)
        $siFang['MerchantId'] = trim($MerchantId);
        //会员账号(必填加入签名)
        $siFang['MemberId'] = trim($data['MemberAccount']);
        //充值金额(必填加入签名)
        $siFang['Amount'] = sprintf("%.2f",$data['Amount']);
        //站长系统订单号，该值需在商户系统内唯一，SRC接口会校验该值是否唯一(必填非加入签名)
        $siFang['OrderId'] = trim($data['MerchantOrderNumber']);
        //站长系统程序的服务器ip地址(必填非加入签名)
        $siFang['ClientIP'] = '175.41.18.34';
        //终端：PC、Mobile、APP
        $SourceName = 'PC';
        if(isMobile()){
            $SourceName = 'Mobile';
        }
        $siFang['SourceName'] = trim($SourceName);
        //生成签名
        $siFangSign = $this->prepareOrderSign($siFang);
        $arg  = "";
        while (list ($key, $val) = each ($siFang)) {
            if($val<>''){
                $arg .=$key."=".$val."&";
            }
        }
        //签名(必填非加入签名)
        $siFang['Sign'] = $siFangSign;

        $arg = $arg.'Sign='.$siFangSign;
        $url = $data['url']?$data['url']:$this->url;
        $resultData = $this->request($arg, $url, 'POST');
		//$test = new Log();
		//$test::record($data['MemberAccount']);
        $result_array = json_decode($resultData,true);

        if(empty($result_array)){
            return json_encode(array('code'=>0,'message'=>'下单接口维护中,请联系客服!'));
        }
        $Status = $result_array['RespCode'];
        if ($Status == 'SUCCESS') {
            return json_encode(array('code'=>1,'message'=>'下单成功!'));
        } else {
            return json_encode(array('code'=>0,'message'=>$result_array['ResultDesc']));
        }
    }
    /**
     * 四方支付生成签名
     * @param $data
     * @return string
     */
    private function prepareOrderSign($data)
    {
        $arg  = "{";
        while (list ($key, $val) = each ($data)) {
            if($val<>''){
                $arg .=$key."=".$val."&";
            }
        }
		$arg =substr($arg,0,strlen($arg)-1);
        $signature = trim(($arg.'}'.$this->Memberkey));
        $signature = MD5($signature);
        return strtolower($signature);
    }

    /**
     * 发起请求
     * @param $data
     * @param $gateway
     * @param string $method
     * @return bool|string
     */
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
        //echo $result;
        return $result;
    }
}