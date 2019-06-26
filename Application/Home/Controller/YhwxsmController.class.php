<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 18-1-6
 * Time: 下午4:54
 * To change this template use File | Settings | File Templates.
 */

namespace Home\Controller;


class YhwxsmController extends HomeController
{
    public $conf;
    public $url;

    /**
     * 查询接口类数据
     */
    public function _initialize()
    {
        $this->conf = M('Payapi')->cache('Yhwxsm_conf')->where('payclass="Yhwxsm"')->find();
        $this->url = 'http://pay.yonghengpay.com/pay/api_pay';
    }
    /**
     * 微信支付页面
     */
    public function pay()
    {
        $orderno = I('orderno','','trim');
        empty($orderno) && $this->redirect('/');
        $order = M('Order')->where('orderno="%s"',$orderno)->find();
        !$order && $this->error('订单不存在','/');
        if(isMobile()){
            $data= array(
                'partner'=>$this->conf['accountId'],
                'money'=>$order['amount'],
                'out_sn'=>$order['orderno'],
                'notify_url'=>U('Yhwxsm/notify', '', false, true),
                'type'=>1,
                'usid'=>'',
            );
            $data['sign'] = $this->getSign($data);
            $res = $this->send_post($this->url,$data);
            $result_array =  json_decode($res,true);
            $result_code = $result_array['code'];
            if($result_code =='3333'){
                $codeUrl =  $result_array['data']['payurl'];
                $pays['barCode'] = $codeUrl;
                $this->assign('order',$order)->assign($pays)->display('Pay/pays');
            } else{
                $this->error($result_array['msg']);
            }
        }else{
            $data= array(
                'partner'=>$this->conf['accountId'],
                'money'=>$order['amount'],
                'out_sn'=>$order['orderno'],
                'notify_url'=>U('Yhwxsm/notify', '', false, true),
                'type'=>1,
                'usid'=>'',
            );
            $data['sign'] = $this->getSign($data);
            $res = $this->send_post($this->url,$data);
            $result_array =  json_decode($res,true);
            $result_code = $result_array['code'];
            if($result_code =='3333'){
                $codeUrl =  $result_array['data']['payurl'];
                $pays['barCode'] = $codeUrl;
                $this->assign('order',$order)->assign($pays)->display('Pay/pays');
            } else{
                $this->error($result_array['msg']);
            }
        }
    }
    /**
     * 异步地址
     */
    public  function notify()
    {
        //商户订单
        $data['p_sn'] = $_REQUEST['p_sn'];
        //订单金额
        $data['money'] = $_REQUEST['money'];
        //订单状态
        $data['status'] = $_REQUEST['status'];
        //支付时间
        $data['paytime'] = $_REQUEST['paytime'];
        //签名
        $sign  = $_REQUEST['sign'];
        $ver_sign = $this->getSign($data,1);
        if($sign==$ver_sign){
            if($data['status']!=0){
				$order_info = M('Order')->where("orderno='%s'",$data['p_sn'])->find();
                $username = $order_info['username'];
                if($order_info['payzt']==1){
                    echo 'success';
                    return true;
                }
                $order = array(
                    'payzt'=>1,
                    'orderno'=>$data['p_sn'],
                    'paytime'=>NOW_TIME
                );
                // $this->OrderChangs($order);
                $re = $this->OrderChangs($order);
                if($re){
                    //$username = M('Order')->where("orderno='%s'",$data['p_sn'])->getField("username");
                    $mes = $this->place_order($data['money'],$username,$data['p_sn']);
                    $mes = json_decode($mes);
                    if($mes['code']==0){
                        $this->error($mes['message']);
                    }
                }
                echo "SUCCESS";
            }else{
                echo "支付失败！";exit;
            }
        }else{
            echo "签名失败！";exit;
        }
    }
    #签名
    function getSign($data,$type='')
    {
        $str = "";
        $key = $this->conf['accountKey'];
        if(empty($type)){
            ksort($data);
        }
        foreach($data as $k=>$v){
            $str .= $v;
        }
        $sign =  md5($str.$key);
        return $sign;
    }
    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    function send_post($url, $data)
    {
        $timeout = 80;//请求超时设置
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}