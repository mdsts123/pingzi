<?php
namespace Home\Controller;
class LuobofubankController extends HomeController {
	protected function _initialize(){
        $this->conf = M('Payapi')->cache('Luobofubank_conf')->where("payclass='Luobofubank'")->find();
    }
    public function pay(){
    $orderno = I('orderno','','trim');

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->where("orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');
    $order['banks'] = array(
       '101'=>'工商银行',
'102'=>'建设银行',
'103'=>'中国银行',
'104'=>'农业银行',
'105'=>'交通银行',
'106'=>'招商银行',
'107'=>'中信银行',
'108'=>'民生银行',
'109'=>'兴业银行',
'110'=>'浦发银行',
'111'=>'邮政银行',
'112'=>'光大银行',
'113'=>'平安银行',
'114'=>'华夏银行',
'115'=>'北京银行',
'116'=>'广发银行',
'117'=>'上海银行',
'118'=>'浙商银行',
'119'=>'南京银行',
'120'=>'宁波银行',
    );
    $this->assign('order',$order)->display('Pay/banks');
  }

  public function go() {
        $orderno = I('orderno','','trim');
        if(empty($orderno)){
           $this->error('参数错误!');
       }
       $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
       if(!$order) die('定单不存在');

       $gateway = 'http://gt.luobofu.net/chargebank.aspx';
       $data = array(
           'parter'=>$this->conf['accountId'],
           'type'=>I('bankid'),
           'value'=>sprintf("%0.02f", $order['amount']),
           'orderid'=>$order['orderno'],//自定义流水号
           'callbackurl'=>U('Luobofubank/notify','',true,true),
           'hrefbackurl'=>U('Luobofubank/hrefback','',true,true),
           'payerIp'=>$order['ip'],
           'attach'=>''
       );
       $temp='';
       $temp = "parter={$data['parter']}&type={$data['type']}&value={$data['value']}&orderid={$data['orderid']}&callbackurl={$data['callbackurl']}";

       $data['sign'] = md5($temp.$this->conf['accountKey']);

       //print_r($data);exit(md5('parter=99&type=963&value=100.00&orderid=1234567890&callbackurl=http://www.example.com/backAction1234567890abcdef'));

       $ret = $this->buildForm($data,$gateway,'POST',"GBK");
       die($ret);
    }

    public function hrefback(){
      $data = I('get.');
      $this->log('hrefback:'.http_build_query($data));
      $post_data = array();
      foreach ($data as $key=>$value){
        $post_data = array_merge($post_data,array(iconv('GBK//IGNORE','UTF-8',$key)=>iconv('GBK//IGNORE','UTF-8',$value)));
      }

      $temp="orderid={$post_data['orderid']}&opstate={$post_data['opstate']}&ovalue={$post_data['ovalue']}";

      $md5 = md5($temp.$this->conf['accountKey']);
      $this->log('密钥'.$md5);

      if ($md5 == $post_data['sign'] ){
        if($post_data['opstate']==0){
          $this->success("订单支付成功",'/');
        }else{
            $this->error("支付失败，请联系管理员！",'/');
        }
      }else{
        $this->error("支付失败，请联系管理员！",'/');
      }
    }

    public function notify(){
      //$data = file_get_contents('php://input');//接受post原数据
      $data = I('get.');
      $this->log('notify:'.http_build_query($data));
      $post_data = array();
      foreach ($data as $key=>$value){
        $post_data = array_merge($post_data,array(iconv('GBK//IGNORE','UTF-8',$key)=>iconv('GBK//IGNORE','UTF-8',$value)));
      }

      $temp="orderid={$post_data['orderid']}&opstate={$post_data['opstate']}&ovalue={$post_data['ovalue']}";

      $md5 = md5($temp.$this->conf['accountKey']);
      $this->log('密钥'.$md5);

      if ($md5 == $post_data['sign'] ){
        if($post_data['opstate']==0){
          $order = array(
            'payzt'=>1,
            'orderno'=>$post_data['orderid'],
            'payno'=>$post_data['ekaorderid'],
            'paytime'=>NOW_TIME
          );
          $this->OrderChangs($order);
        }
        echo "ok";
      }else{
        echo "fails";
      }
    }
}
