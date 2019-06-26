<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 19:23
 */

namespace Home\Controller;

class CxzfzfbController extends HomeController {
    protected function _initialize(){
        $this->conf = M('Payapi')->where("payclass='Cxzfzfb'")->find();//->cache('Cxzfzfb_conf')
    }

    public function pay() {
        $orderno = I('orderno','','trim');
        if(empty($orderno)){
            $this->error('参数错误!');
        }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
        if(!$order) die('定单不存在');


		$appkey= trim($this->conf['accountKey']);
		$appid = $this->conf['accountId'];


		$out_trade_no 	= $order['orderno'];//商户订单号，商户网站订单系统中唯一订单号，必填
		$type	= '1';//支付方式 1支付宝、3微信、2Q财付通
		$sbname= '天天快充';//商品名称
		$total_fee= $order['amount'];//付款金额
		$webname= '百特云支付';//站点名称
		$signc	= md5($appid.$appkey.$out_trade_no.$total_fee);//校验码
		$state	= '';
		$msg	= '';
		if($appid && $out_trade_no && $type  && $total_fee  && $signc){
			$msg =
				'<form name="myform" id="myform" action="http://www.chengxinzhifu8.com/pay/codepay.php" method="post">
				<input type="hidden" name="bty_appid" value="'.$appid.'" />
				<input type="hidden" name="bty_out_trade_no" value="'.$out_trade_no.'" />
				<input type="hidden" name="bty_type" value="'.$type .'" />
				<input type="hidden" name="bty_subject" value="'.$sbname .'" />
				<input type="hidden" name="bty_total_fee" value="'.$total_fee .'" />
				<input type="hidden" name="bty_webname"  value="'.$webname .'" />
				<input type="hidden" name="sign" value="'.$signc.'" />
				<div style=" display:none;">
				<input type="submit" name="Submit" value="正在支付" /></div>
				</form>
				<div style=" width:100%; text-align:center; color:#FF0000;">正在支付，请稍后！</div>
				<script>document.forms["myform"].submit();</script>';
		}
		else {
			$state = 'ERR_PARAM';
			$msg = '缺少参数';
		}
		echo $msg;


    }
    public function notify(){
    	
		/*异步通知*/
		header("Content-type: text/html; charset=utf-8");
		date_default_timezone_set('Asia/Shanghai');
		$data = I('request.');
		$appkey= trim($this->conf['accountKey']);
		$appid = $this->conf['accountId'];

		/*商户需要自行处理该订单号*/
		$out_trade_no 	= $data['out_trade_no'];

		/* 返回的商户ID 判断该id是否等于商户的id */
		$id = $data['id'];
		/* 返回的金额*/
		$total_fee= $data['money'];

		/*诚信支付生成的校验码 */
		$signc	= $data['sign'];

		/*商户生成校验码 并且需要判断$signc是否等于$signs*/
		$signs	= md5($appid.$appkey.$out_trade_no.$total_fee);//

		$msg = '';
		$stau = '';
		$leibie= '';
		if($appid == $id && $out_trade_no && $total_fee && $signc && $signc == $signs){
			$order_info = M('Order')->where("orderno='%s'",$out_trade_no)->find();
            $username = $order_info['username'];
            if($order_info['payzt']==1){
                echo 'success';
                return true;
            }
			$order = array(
                    'payzt'=>1,
                    'orderno'=>$data['out_trade_no'],
                    'paytime'=>NOW_TIME
                );
			$re = $this->OrderChangs($order);
			if($re){
				//$username = M('Order')->where("orderno='%s'",$out_trade_no)->getField("username");
				$mes = $this->place_order($total_fee,$username,$out_trade_no);
				$mes = json_decode($mes);
				if($mes['code']==0){
					$this->error($mes['message']);
				}
				echo 'success';
				return true;
			}
			$stau = "ok";
		}
		else {
			$msg = '参数有误';
			$stau = 'no';
		}
		echo $stau;
		
    }

    public function hrefback(){
    	
       $this->success('对支付结果有疑问，请联系在线客服！','/');

    }

}