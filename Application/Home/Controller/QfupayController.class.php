<?php
namespace Home\Controller;
class QfupayController extends HomeController {
  protected function _initialize(){
    $this->conf = M('Payapi')->cache('Qfupay_conf')->where("payclass='Qfupay'")->find();
    $this->iv = "00000000";
  }
  public function pay() {        
    $orderno = I('orderno','','trim');             

    if(empty($orderno)){
      $this->redirect('/');
    }
    $order = M('Order')->alias('o')->join('LEFT JOIN __CATEGORY__ c ON c.id = o.cid')->field("o.*,c.name as payname,c.title as paytitle")->where("o.orderno='%s'",$orderno)->find();
    if(!$order) $this->error('定单不存在','/');

    if($order['cid']==3){
      if(isMobile()){
        $paytype = 'ZFB_WAP';
        $gateway = 'http://zfbwap.985pay.com/api/pay.action';
      }else{
        $paytype = 'ZFB';
        $gateway = 'http://nw.985pay.com/api/pay.action';
      }
    }elseif($order['cid']==1){
      $paytype = 'WX';
      $gateway = 'http://nw.985pay.com/api/pay.action';
    }elseif($order['cid']==2){
      $paytype = 'bank';
      $gateway = '';
      die('出错了，接口维护中!');
    }    

    $pay = array();
    $pay['merNo'] = $this->conf['accountId']; #商户号
    $pay['netway'] = $paytype;
    $pay['random'] = (string) rand(1000,9999);  #4位随机数    必须是文本型
    $pay['orderNum'] = $order['orderno'];
    $pay['amount'] = sprintf("%d", $order['amount']*100);  #默认分为单位 转换成元需要 * 100   必须是文本型
    $pay['goodsName'] = '在线充值';  #商品名称
    $pay['callBackUrl'] = U('Qfupay/notify','',true,true);  #通知地址 可以写成固定
    $pay['callBackViewUrl'] = U('Qfupay/hrefback','',true,true);  #前台跳转 可以写成固定
    ksort($pay); #排列数组 将数组已a-z排序
    $sign = md5($this->util_json_encode($pay) . $this->conf['accountKey']); #生成签名
    $pay['sign'] = strtoupper($sign); #设置签名
    //print_r($pay);exit();
    $data = $this->util_json_encode($pay); #将数组转换为JSON格式
    $post = array('data'=>$data);       
    $json = $this->request($post,$gateway,'POST');
    $pays = json_decode($json,true);
    if($pays['stateCode'] !== '00'){
      $this->log('提交出错了!'.http_build_query($pays));
      die("接口维护中");
    }else{
      $pays['barCode'] = $pays['qrcodeUrl'];
      $this->assign('order',$order)->assign($pays)->display('Pay/pay');
    }
  }

  public function notify(){
    $data = I('post.data');
    $data = str_replace('""', '","', $data);
    $this->log('notify:'.$data);
    $arr = json_decode($data,320);
    $this->log('notify:'.http_build_query($arr));     
    $versign = $this->prepareSign($arr);
    $this->log('notify:生成后的签名'.$versign);
    if($versign!==$arr['sign']) die('ERROR');
    if($arr['payResult']=='00'){
      $order = array(
        'payzt'=>1,
        'orderno'=>$arr['orderNum'],
        'payno'=>NOW_TIME,
        'paytime'=>NOW_TIME
      );
      $this->OrderChangs($order);   
      exit("0");         
    }
  }

  public function hrefback(){
    $data = I('request.data');
    $data = str_replace('""', '","', $data);
    $arr = json_decode($data,320);
    $this->log('hrefback:'.http_build_query($arr));     
    $versign = $this->prepareSign($arr);
    $this->log('notify:生成后的签名'.$versign);
    if($versign!==$arr['sign']) $this->error('签名错误！');
    if($arr['payResult']=='00'){
      $this->success('支付成功！','/');
    }else{
      $this->error('支付失败！','/');
    }
  }

  public function prepareSign($data){
    unset($data['sign']);
    ksort($data);
    return strtoupper(md5($this->util_json_encode($data) . $this->conf['accountKey']));      
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

  public function encrypt($input){
    $size = mcrypt_get_block_size(MCRYPT_3DES,MCRYPT_MODE_ECB);
    $input = $this->pkcs5_pad($input, $size);
    $key = str_pad($this->conf['accountName'],24,'0');
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
  public function decrypt($encrypted){
    $encrypted = base64_decode($encrypted);
    $key = str_pad($this->conf['accountName'],24,'0');
    $td = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
    if( $this->iv == '' )
    {
      $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    }
    else
    {
      $iv = $this->iv;
    }
    $ks = mcrypt_enc_get_key_size($td);
    @mcrypt_generic_init($td, $key, $iv);
    $decrypted = mdecrypt_generic($td, $encrypted);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $y=$this->pkcs5_unpad($decrypted);
    return $y;
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

}