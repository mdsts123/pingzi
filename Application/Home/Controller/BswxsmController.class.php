<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 19:23
 */

namespace Home\Controller;
class BswxsmController extends HomeController {
    protected function _initialize(){
        $this->conf = M('Payapi')->where("payclass='Bswxsm'")->find();//->cache('Bswxsm_conf')
    }

    public function pay() {
        $orderno = I('orderno','','trim');
        if(empty($orderno)){
            $this->error('参数错误!');
        }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
        if(!$order) die('定单不存在');

        $data = array(
            'MerchantId' => $this->conf['accountId'],
			'Timestamp' => date("Y-m-d H:m:s"),
			'PaymentTypeCode' => 'WECHAT_QRCODE_PAY',
			'OutPaymentNo' => $order['orderno'],
			'PaymentAmount' => sprintf('%s',$order['amount']*100),
			'NotifyUrl' => U('Bswxsm/notify', '',false,true),
			//'NotifyUrl' => 'https://www.baidu.com',
			'PassbackParams' => $order['orderno'],
        );

		ksort($data);

		foreach($data as $k=>$a){
            if($tmp_str <> ''){
                $tmp_str = $tmp_str.'&'.$k.'='.$a;
            }
            else
                $tmp_str = $k.'='.$a;
        }
		
		$key = trim($this->conf['accountKey']);

		$sign = md5($tmp_str.$key);

		$data['Sign'] = $sign;

		reset($data);		

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://ebank.baishengpay.com/Payment/Gateway");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response=curl_exec($ch);

		$return_data = json_decode($response,TRUE);
		

		if($return_data['Code'] == '200'){
			$pays['barCode'] = $return_data['QrCodeUrl'];
            $this->assign('order',$order)->assign($pays)->display('Pay/pays');
		}
		else
			$this->error('出现错误，请重试');



    }
    public function notify(){
        $data = I('post.');

        $Sign = trim($data['Sign']);
        unset($data['Sign']);

		if($data['Code']=="200"){
			ksort($data);

			foreach($data as $k=>$a){
				if($tmp_str <> ''){
					$tmp_str = $tmp_str.'&'.$k.'='.$a;
				}
				else
					$tmp_str = $k.'='.$a;
			}

			$key = trim($this->conf['accountKey']);
			$self_sign = md5($tmp_str.$key);

			if($self_sign == strtolower($Sign)){
       			$order = array(
                    'payzt'=>1,
                    'orderno'=>$data['OutPaymentNo'],
                    'paytime'=>NOW_TIME
                );
                $this->OrderChangs($order);
                echo 'success';
	     

			}
			else
				echo '验签出错';
		}	


        
    }

}