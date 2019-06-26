<?php
// +----------------------------------------------------------------------
// | 爱云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.22cloud.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <93058680@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Util\Think\Page;
/**
 * 代付控制器
 * @author jry <93058680@qq.com>
 */
class PayifyController extends AdminController {
    /**
     * 代付列表
     * @author jry <93058680@qq.com>
     */
    protected function _initialize(){
        $this->iv = "00000000";
    }  
    public function index($group='') {
        // 搜索
        $keyword   = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['accountname|accountno|orderno'] = array(
            $condition,
            $condition,
            $condition,
            '_multi'=>true
        );
        switch ($group) {
            case '1':
                $map['status'] = array('eq','0');
                break;
            
            case '2':
                $map['status'] = array('eq','1');
                break;
        }

        // 获取所有代付
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object = D('Payify');
        $data_list = $user_object
                   ->page($p , C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
        $page = new Page(
            $user_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );
        $tab_list[0]['title'] = '全部代付';
        $tab_list[0]['href']  = U('index');
        $tab_list[1]['title'] = '待付款';
        $tab_list[1]['href']  = U('index', array('group' => '1'));
        $tab_list[2]['title'] = '已付款';
        $tab_list[2]['href']  = U('index', array('group' => '2'));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('代付列表') // 设置页面标题
        		->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('delete')  // 添加禁用按钮
                ->setTabNav($tab_list, $group)
                ->setSearch('请输入定单号/代付名／付款号码', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('orderno', '定单号')
                ->addTableColumn('accountname', '开户名')
                ->addTableColumn('accountno', '银行卡号')
                ->addTableColumn('bankcode', '银行代码')
                ->addTableColumn('amount', '金额')
                ->addTableColumn('paytime', '付款时间', 'time')
                ->addTableColumn('addtime', '提交时间', 'time')
                ->addTableColumn('status', '结算状态', 'status')
                ->setTableDataList($data_list)    // 数据列表
                ->setTableDataPage($page->show()) // 数据列表分页
                ->display();
    }

    /**
     * 新增代付
     * @author jry <93058680@qq.com>
     */
    public function add() {
        if (IS_POST) {
        	$data['orderno'] = date('ymdHis') . rand(1000,9999);
            $data['accountname'] = I('post.accountname','','trim');
            $data['accountno'] = I('post.accountno','','trim');
            $data['amount'] = I('post.amount','','intval');
            $data['bankcode'] = I('post.bankcode','','trim');
            
            if($data['accountname']=='') $this->error('开户姓名不能为空');
            if($data['accountno']=='') $this->error('银行卡号不能为空');
            if($data['amount']<1) $this->error('代付金额不能小于1元');
            if($data['bankcode']=='') $this->error('开户行不能为空');
            if(M("Payify")->data($data)->add()){
            	$config = D('Payapi')->where("payclass=''")->find();
    			$pay = array();
    			$pay['merNo'] = $config['accountId']; #商户号
    			$pay['orderNum'] = $data['orderno'];  #商户订单号
    			$pay['amount'] = $this->encrypt("100",$config['accountName']);  #默认分为单位 转换成元需要 * 100 并且进行3DES加密
    			$pay['bankCode'] = $data['bankcode'];  #银行名称代码 比如 爱存不存的ICBC
    			$pay['bankAccountName'] = $this->encrypt($data['accountname'],$config['accountName']);  #结算姓名3DES加密
    			$pay['bankAccountNo'] = $this->encrypt($data['accountno'],$config['accountName']);  #结算卡号3DES加密
    			$pay['callBackUrl'] = 'http://'. C('WEB_SITE_DOMAIN') . '/payify/notify';  #通知地址 可以写成固定
    			
    			ksort($pay); #排列数组 将数组已a-z排序
    			$sign = md5($this->util_json_encode($pay) . $config['accountKey']); #生成签名
    			$pay['sign'] = strtoupper($sign);
    			$data = $this->util_json_encode($pay);
    			$post = array('data'=>$data);
    			$return = $this->wx_post("http://nw.985pay.com/api/remit.action",$post); #提交订单数据
    			$row = json_decode($return,true); #将返回json数据转换为数组
    			if ($row['stateCode'] !== '00'){
    				$this->error('系统错误,错误号：' . $row['stateCode'] . '错误描述：' . $row['msg']);
    			}else{
					if ($row['stateCode'] == '00'){
						$stateCode = $row['stateCode'];
	 					$msg = $row['msg'];
	 					$orderNum = $row['orderNum'];
	 					$amount = $row['amount'];
	 					$amount = $amount / 100;
	 					$string = '创建代付成功!订单号：' . $orderNum . ' 系统消息：' . $msg . ' 代付金额：' . $amount;
						$this->success($string);
					}else{
						$stateCode = $row['stateCode'];
	 					$msg = $row['msg'];
	 					
	 					$string = '创建代付失败!系统消息：' . $msg . ' 错误编号：' . $stateCode;
						$this->error($string);
					}    				
    			}
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增代付') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('accountname', 'text', '开户名', '开户姓名')
                    ->addFormItem('accountno', 'text', '银行卡号', '银行卡号')
                    ->addFormItem('amount', 'text', '金额', '代付金额')
                    ->addFormItem('bankcode', 'select', '开户行', '选择收款人开户银行', array("BOC"=>"中国银行","ABC"=>"中国农业银行","ICBC"=>"中国工商银行","CCB"=>"中国建设银行","BCM"=>"交通银行","CMB"=>"中国招商银行","CEB"=>"中国光大银行","CMBC"=>"中国民生银行","HXB"=>"华夏银行","CIB"=>"兴业银行","CNCB"=>"中信银行","SPDB"=>"上海浦东发展银行","PSBC"=>"中国邮政储蓄银行"))                 

                    ->display();
        }
    }

    public function encrypt($input,$accountName){
        $size = mcrypt_get_block_size(MCRYPT_3DES,MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $key = str_pad($accountName,24,'0');
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        if( $this->iv == '' )
        {
            $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        }
        else
        {
            $iv = $this->iv;
        }
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    public function util_json_encode($input){
      if(is_string($input)){
        $text = $input;
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace(
          array("\r", "\n", "\t", "\""),
          array('\r', '\n', '\t', '\\"'),
          $text);
        return '"' . $text . '"';
      }else if(is_array($input) || is_object($input)){
        $arr = array();
        $is_obj = is_object($input) || (array_keys($input) !== range(0, count($input) - 1));
        foreach($input as $k=>$v){
          if($is_obj){
            $arr[] = $this->util_json_encode($k) . ':' . $this->util_json_encode($v);
          }else{
            $arr[] = $this->util_json_encode($v);
          }
        }
        if($is_obj){
          return '{' . join(',', $arr) . '}';
        }else{
          return '[' . join(',', $arr) . ']';
        }
      }else{
        return $input . '';
      }
    }

	Public function wx_post($url,$data){ #POST访问
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        return $tmpInfo;
    }

    public function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    public function pkcs5_unpad($text){
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
    public function PaddingPKCS7($data) {
        $block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char),$padding_char);
        return $data;
    } 

    public function demo(){
    	$data = M('AdminModule')->where('id=1')->getField('admin_menu');
    	$data = json_decode($data,true);
    	$data[49]['title'] = "代付管理";
    	$data[49]['url'] = "Admin/Payify/index";
    	$data[50]['title'] = "添加代付";
    	$data[50]['url'] = "Admin/Payify/add";
    	$data[51]['title'] = "删除";
    	$data[51]['url'] = "Admin/Payify/del";
    	$data[52]['title'] = "设置状态";
    	$data[52]['url'] = "Admin/Payify/setStatus";
    	unset($data[53]);
    	unset($data[54]);
    	unset($data[55]);
    	unset($data[56]);unset($data[57]);


    	dump($data);

    	$data = json_encode($data);
    	M('AdminModule')->where('id=1')->setField('admin_menu',$data);
    }
}
