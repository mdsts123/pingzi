<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 19:23
 */

namespace Home\Controller;
class EyxbankController extends HomeController {
    protected function _initialize(){
        $this->conf = M('Payapi')->where("payclass='Eyxbank'")->find();//->cache('Bsqqsm_conf')
    }

    public function pay()
    {
        $orderno = I('orderno', '', 'trim');
        if (empty($orderno)) {
            $this->error('参数错误!');
        }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'", $orderno)->find();
        if (!$order) die('定单不存在');

        $order['banks'] = array(
            'CITIC' => '中信银行',
            'BOC' => '中国银行',
            'ABC' => '中国农业银行',
            'CCB' => '中国建设银行',
            'ICBC' => '中国工商银行',
            'CMB' => '招商银行',
            'PSBC' => '邮政储蓄',
            'CIB' => '兴业银行',
            '974' => '深圳发展银行',
            'SPDB' => '浦东发展银行',
            'PAYH' => '平安银行',
            'CMBC' => '民生银行',
            'BOCM' => '交通银行',
            'HXB' => '华夏银行',
            'CGB' => '广东发展银行',
            'CEB' => '光大银行',
            'SHBANK' => '上海银行',
            'BCCB' => '北京银行',
        );
        $this->assign('order', $order)->display('Pay/banks');

    }
    public function go() {
        $gateway = 'https://gateway.shangyizhifu.com/chargebank.aspx';
        $orderno = I('orderno','','trim');

        if(empty($orderno)){
            $this->error('参数错误!');
        }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
        if(!$order) die('定单不存在');

        $data = array(
            "service" => 'online_pay',
            "paymentType" => '1',
            "merchantId" => $this->conf['accountId'],
            "returnUrl" => U('Eyxbank/hrefback', '',false,true),
            "notifyUrl" => U('Eyxbank/notify', '',false,true),
            "orderNo" => $orderno,
            "title" => '快充',
            "body" => '快充-网银',
            "totalFee" => sprintf("%.02f", $order['amount']),
//            "totalFee" => 0.01,
            "paymethod" => 'directPay',
            "defaultbank" =>I('bankid'),
            "isApp" => 'web',
            "charset" => "UTF-8"
        );

        $key = $this->conf['accountKey'];
        $sign = $this->sign($data,$key);

        $data['signType'] = 'SHA';
        $data['sign'] = $sign;
        $res = $this->buildForm($data,'https://ebank.ztpo.cn/payment/v1/order/'.$this->conf['accountId'].'-'.$orderno);//https://ebank.xxx.xxx/payment/v1/order/100000000008888-XXX12345611s513593361107

        echo $res;

    }
    public function notify(){
        $data = I('post.');
//        var_dump($data);

        $sign = $data['sign'];
        unset($data['sign']);
        $signType = $data['signType'];
        unset($data['signType']);

        $key = $this->conf['accountKey'];
        $ori_sign = $this->sign($data,$key);


        if($ori_sign == $sign){
            if($data['is_success'] == 'T'){
                $order = array(
                    'payzt'=>1,
                    'orderno'=>$data['order_no'],
//                'payno'=>$PaymentNo,
                    'paytime'=>NOW_TIME
                );
                $this->OrderChangs($order);
                echo 'success';
            }
            else
                echo $_GET['msg'];
        }
        else
            echo '验签有误！';


    }

    public function hrefback(){
        $data = I('post.');
//        var_dump($data);

        $sign = $data['sign'];
        unset($data['sign']);
        $signType = $data['signType'];
        unset($data['signType']);

        $key = $this->conf['accountKey'];
        $ori_sign = $this->sign($data,$key);


        if($ori_sign == $sign){
            if($data['is_success'] == 'T'){
                $this->success('支付成功！','/');
            }
            else
                $this->error('支付可能失败！请联系客服确认支付结果','/');
        }
        else
            $this->error('返回的数据解密失败！请联系客服确认支付结果','/');

    }

    public function sign($params,$key){
        ksort($params);
        $string = "";
        foreach ($params as $name => $value) {
            $string .= $name . '=' . $value . '&';
        }
        $string = substr($string, 0, strlen($string) -1 );
        $string .= $key;
        return strtoupper(sha1($string));
    }

}