<?php
namespace Home\Controller;
class OrderController extends HomeController {
	public function index(){

	}
    public function pay() {

        $data = array();
        $data['orderno'] = order_sn();
        $data['username'] = I('username','','trim');
        $data['amount'] = I('coin','','intval');
        $data['addtime'] = I('P_Time',NOW_TIME,'strtotime');
        $data['payid'] = I('channel_id','','trim');
        $wm1=$data['payid'];
        $wm=str_replace('wm','',$data['payid']);
        $wm=intval($wm);
        $count_wm=substr_count($wm1,'wm');

        $data['ip'] = get_client_ip();
        if(substr_count($data['payid'],'bank')){
            $data['payid'] = str_replace('bank', '', $data['payid']);
            $data['cid'] = 6;
            $pay = M('Bankpay')->where("id='%s'",$data['payid'])->find();
        }else{
            $data['payid'] = I('channel_id','','intval');
            if(!$count_wm && empty($data['payid'])){
                $this->error('请选择支付通道！');
            }else{
                if($count_wm >=1){
                    $pay = M('Paywm')->where("cid='%s'",$wm)->find();
                }else{
                    $pay = M('Payapi')->where("id='%s'",$data['payid'])->find();
                }
                $data['cid'] = $count_wm < 1 ? $pay['cid'] : $wm;
                $data['desc'] = $pay['desc'];
            }
        }

        if(empty($data['username'])){
            $this->error('会员名不能为空！');
        }
        if(in_array($data['username'], explode('|', C('disable_user')))){
            $this->error('会员名已被禁用！');
        }
        if(!$data['amount']){
            $this->error('金额不能为空');
        }

        if ($data['username']!=I('rusername','','trim')) {
            $this->error('两次会员名称不一致，请核对！');
        }

        if($pay['minmoney'] && $pay['maxmoney']){
            if($data['amount']<$pay['minmoney']){
                $this->error("充值金额不能小于".$pay['minmoney']."元！");
            }
            if($data['amount']>$pay['maxmoney']){
                $this->error("充值金额不能大于".$pay['maxmoney']."元！");
            }
        }else{
            if($data['amount']<C("PAY_MIN")){
                $this->error("充值金额不能小于".C("PAY_MIN")."元！");
            }
            if($data['amount']>C("PAY_MAX") && C("PAY_MAX")<>0){
                $this->error("充值金额不能大于".C("PAY_MAX")."元！");
            }
        }


        if (M('Order')->data($data)->add()) {
            $payclass = $pay['payclass'];
            if($pay['gourl']){
                $url= $pay['gourl'] . U($payclass . '/pay', array('orderno' => $data['orderno']));
                header("Location: $url");
            }else{
                $count_wm > 0 && $wm > 0 ? $this->redirect('QrPay/index',array('cid' => $wm,'orderno'=>$data['orderno']))
                    : $this->redirect($payclass . '/pay', array('orderno' => $data['orderno']));
            }
        }else{
            $this->error('提交失败！');
        }

    }

    public function checkstatus(){

    }

}
