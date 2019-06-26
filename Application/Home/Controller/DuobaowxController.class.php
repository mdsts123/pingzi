<?php
namespace Home\Controller;
class DuobaowxController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Duobaowx_conf')->where("payclass='Duobaowx'")->find();
    }
    public function pay() {        
        $orderno = I('orderno','','trim');             
        
        if(empty($orderno)){
           $this->error('参数错误!');
       }
        $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');
       $data = array();       
       $data['parter'] = $this->conf['accountId'];
       $data['orderid'] = $order['orderno'];
       $data['type'] = isMobile()?'1007':'1004';
       $data['value'] = sprintf("%0.02f", $order['amount']);
       $data['callbackurl'] = U('Duobaowx/notify','',true,true);//异步通知
       $data['hrefbackurl'] = U('Duobaowx/hrefback','',true,true);//异步通知
       $data['onlyqr'] = 'QR';
       $data['sign'] = $this->prepareSign($data);
       $params = http_build_query($data);
       $res = $this->request($params, "https://gw.169.cc/interface/Autobank/index.aspx");
       if($res<>'' && stripos($res,'error')===false){
        list($codeUrl,$sign) = explode("&", $res);
        $pays['barCode'] = $codeUrl;
        $this->assign('order',$order)->assign($pays)->display('Pay/pay');
       }else{
       	$res = iconv("GB2312","UTF-8//ignore",$res);
        $this->error("$res，请联系管理员！",'/');
       }

    }

    public function notify(){
    	$data = I('request.');
    	$this->log('notify:'.http_build_query($data));
      $versign = $this->versign($data);
      if($versign!==$data['sign']) die('签名错误！');
      if($data['opstate']=='0'){
          $order = array(
            'payzt'=>1,
            'orderno'=>$data['orderid'],
            'payno'=>$data['sysorderid'],
            'paytime'=>NOW_TIME
          );
          $this->OrderChangs($order);
          exit('opstate=0');
      }
    }

    public function hrefback(){
    	$data = I('request.');
      $this->log('hrefback:'.http_build_query($data));
      $versign = $this->versign($data);
      if($versign!==$data['sign']) die('签名错误！');
      if($data['opstate']=='0'){
          $this->success('支付成功！','/');
      }else{
        $this->error("失败，请联系管理员！");
      }

    }

    public function prepareSign($data){

    	$signSource = sprintf("parter=%s&type=%s&value=%s&orderid=%s&callbackurl=%s%s", $data['parter'], $data['type'], $data['value'], $data['orderid'], $data['callbackurl'], $this->conf['accountKey']);
        return md5($signSource);

    }

    public function versign($data){
      $signSource = sprintf("orderid=%s&opstate=%s&ovalue=%s%s", $data['orderid'], $data['opstate'], $data['ovalue'], $this->conf['accountKey']); 
        return md5($signSource);
    }

}
