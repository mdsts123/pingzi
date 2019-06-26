<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | 使用本产品，应遵守您所在国的相应法律法规，不得将本产品用于非法用途，包括但不限于贩毒、走私、赌博等
// +----------------------------------------------------------------------
namespace Home\Controller;
class IndexController extends HomeController {
    /**
     * 默认方法
     * @author jry <93058680@qq.com>
     */
    public function index() {

        if (isMobile()) $this->redirect('Index/wap');
        $this->paylist = M('Payapi')->alias('p')->join('LEFT JOIN __CATEGORY__ c ON c.id = p.cid')->where('p.status=1')->order("p.sort desc,p.id desc")->field("p.*,c.name,c.title")->select();
        $this->banklist = M('Bankpay')->where('status=1')->order("id desc")->field("*")->select();
        $this->wmhy = M('Paywm')->alias('p')->join('LEFT JOIN __CATEGORY__ c on c.id =p.cid')->where('p.status=1')->order('p.sort desc,p.id desc')->field('p.*,c.name,c.title')->select();
        $this->display();
    }

    public function wap() {
        if (!isMobile()) $this->redirect('Index/index');
        $this->paylist = M('Payapi')->alias('p')->join('LEFT JOIN __CATEGORY__ c ON c.id = p.cid')->where('p.status=1')->order("p.sort desc,p.id desc")->field("p.*,c.name,c.title")->select();
        $this->banklist = M('Bankpay')->where('status=1')->order("id desc")->field("*")->select();
        $this->wmhy = M('Paywm')->alias('p')->join('LEFT JOIN __CATEGORY__ c on c.id =p.cid')->where('p.status=1')->order('p.sort desc,p.id desc')->field('p.*,c.name,c.title')->select();
        $this->display();
    }

    public function chectorder() {
        $orderno = I('post.BillNO');
        if (!$orderno) {
            die();
        }
        $map['orderno'] = array('eq',$orderno);
        $payzt = M('Order')->where($map)->getField('payzt');
        exit($payzt);
    }

    public function payok() {
        $orderno = I('get.BillNO');
        if (!$orderno) {
            die();
        }
        $map['orderno'] = array('eq',$orderno);
        $this->order = M('Order')->where($map)->find();
        $this->display();
    }

    public function qrcode($url='',$level=3,$size=4){

        Vendor('phpqrcode.phpqrcode');

        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        //echo $_SERVER['REQUEST_URI'];
        $object = new \QRcode();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);

    }
}
