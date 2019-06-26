<?php
namespace Home\Controller;
class QrPayController extends HomeController
{
    public function index(){
        $orderno = I('orderno','','trim');
        $order=D('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c on c.id=o.cid LEFT JOIN ly_paywm p on o.cid=p.cid')->field('o.*,c.name,c.title,p.img_src')->where('orderno=%s',$orderno)->find();
        $this->assign('order',$order)->display(isMobile()?'wap':null);
    }

}