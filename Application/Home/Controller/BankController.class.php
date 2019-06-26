<?php
namespace Home\Controller;
class BankController extends HomeController {
  public function pay(){
    $orderno = I('orderno','','trim');
    if(empty($orderno)){
      $this->redirect('/');
    }

    $order = M('Order')->where("orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');

    $pay = M('Bankpay')->where("id='%s'",$order['payid'])->find();
    if($pay['status']==0){
      die('接口已关闭');
    }
    $this->assign('pay',$pay);
    $this->assign('order',$order)->display('Index/bank');
  }
}
