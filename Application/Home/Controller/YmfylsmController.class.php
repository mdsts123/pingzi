<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 19:23
 */

namespace Home\Controller;
class YmfylsmController extends HomeController {
    protected function _initialize(){
        $this->conf = M('Payapi')->where("payclass='Ymfylsm'")->find();//->cache('Ymfylsm_conf')
    }

    public function pay()
	{
		$orderno = I('orderno', '', 'trim');
		if (empty($orderno)) {
			$this->error('参数错误!');
		}
		$order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'", $orderno)->find();
		if (!$order) die('定单不存在');
		if (isMobile()) {
			$data = array(
					'apiName' => 'WAP_PAY_B2C',
					'apiVersion' => '1.0.0.1',
					'platformID' => $this->conf['accountId'],////////////////////////////////
					'merchNo' => $this->conf['accountId'],///////////////////////////////
					'orderNo' => $order['orderno'],
					'tradeDate' => date("Ymd"),
					'amt' => $order['amount'],
					'merchUrl' => U('Ymfylsm/notify', '', false, true),
					'merchParam' => 'abcd',
					'tradeSummary' => 'kuaichong',
					'customerIP' => $this->getIP(),
			);
		} else {
			$data = array(
					'apiName' => 'WEB_PAY_B2C',
					'apiVersion' => '1.0.0.1',
					'platformID' => $this->conf['accountId'],////////////////////////////////
					'merchNo' => $this->conf['accountId'],///////////////////////////////
					'orderNo' => $order['orderno'],
					'tradeDate' => date("Ymd"),
					'amt' => $order['amount'],
					'merchUrl' => U('Ymfylsm/notify', '', false, true),
					'merchParam' => 'abcd',
					'tradeSummary' => 'kuaichong',
					'customerIP' => $this->getIP(),
			);
		}

		foreach ($data as $k => $a) {
			if ($tmp_str <> '') {
				$tmp_str = $tmp_str . '&' . $k . '=' . $a;
			} else
				$tmp_str = $k . '=' . $a;
		}

		$key = trim($this->conf['accountKey']);

		$sign = md5($tmp_str . $key);

		$data['signMsg'] = $sign;
		if (isMobile()) {
			$data['choosePayType'] = '17';//支付类型
		}else
			$data['choosePayType'] = '17';//支付类型


		reset($data);
		// 生成表单数据
		$res = $this->buildForm($data, "http://cashier.youmifu.com/cgi-bin/netpayment/pay_gate.cgi","POST");
		echo $res;
    }
    public function notify(){
        $data = I('post.');

        $Sign = trim($data['Sign']);
        unset($data['Sign']);

		if($data['orderStatus']=="1"){
			//ksort($data);

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
				return true;
	     

			}
			else
				echo '验签出错';
		}
		else
			return false;
    }

	 // 定义一个函数获取客户端IP地址
	public function getIP(){
		global $ip;
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else $ip = "127.0.0.11";
		return $ip;
	}

}