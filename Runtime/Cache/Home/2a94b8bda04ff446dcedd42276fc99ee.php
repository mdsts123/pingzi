<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="renderer" content="webkit" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo C('WEB_SITE_TITLE');?> - <?php echo C('WEB_SITE_SLOGAN');?></title>
    <meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>" />
    <meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>" />
	<script type="text/javascript" src="/Public/js/jquery-1.8.js"></script>
    <link href="/Public/css/pc.css"  rel="stylesheet"  type="text/css"/>
</head>
  <body>
  <form id="dinpayRedirect" action="<?php echo U('order/pay');?>" method="post" name="dinpayRedirect">
    <div class="header">
      <div class="logo">
        <img src="/Public/images/logo.png" align="" />
      </div>
      <div class="online-service">
        <img src="/Public/images/header_04.png" align="" />
      </div>
    </div>
    <div class="content">
       <a href="javascript:void(0);" onclick="javascript:window.open('<?php echo C('SERURL');?>','_blank','menubar=0,location=0,scrollbars=auto,resizable=1,status=0,width=1024,height=728')" class="service">在线客服</a>
       
      <h2>
      支持【<span style="color: #ff0000;">手机端</span>、<span style="color:#000cff;">电脑端</span>】<span style="color:#05a11f">【微信扫码】</span>、<span style="color:#fa00d4">【支付宝】</span>、【<span style="color: #ff0000;">手机网银、信用卡</span>】在线支付最高单笔<?php echo C('PAY_MAX');?>元！
      </h2>
      <p>
      	扫一扫支付，手机也能支付，输入支付网址： <span style="color:#000cff;"><?php echo C('WEB_SITE_DOMAIN');?></span>一键入款，立即到账!<br>
        支付流程：输入并确认<?php echo C('WEB_SITE_TITLE');?>正确的会员账号→输入存款额度→点击确认支付→付款成功后<span style="color: #ff0000;">1~10秒</span>自动到账；<br />
       支付宝 /网银 /信用卡存款金额范围为（<span style="color: #ff0000;"><?php echo C('PAY_MIN');?>~<?php echo C('PAY_MAX');?>元</span>）, 需要大额入款可分多次存入或使用其它方式存款；<br />
      </p>
      <table class="content-table">
        <tr>
          <td class="title">会员账号：</td>
          <td class="inputtd">
            <input name="client_type" id="client_type" type="hidden" value="1"/>
            <input name="username" id="username" type="text" value="" class="table-input"  placeholder="请填写<?php echo C('WEB_SITE_TITLE');?>会员账户"/> 
          </td>
          <td align="center" style="color: #e60012;">*必填</td>
        </tr>
        <tr>
          <td class="title">确认账号：</td>
          <td>
            <input name="rusername" id="rusername" type="text" value="" class="table-input" placeholder="请确认会员账户是否正确，否则无法充值"/>
          </td>
          <td align="center" style="color: #e60012;">*必填</td>
        </tr>
		<?php if($banklist): ?><tr>
          <td class="title">微信、支付宝转账</td>
          <td style="padding: 0px 10px;">
          <?php if(is_array($banklist)): $i = 0; $__LIST__ = $banklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="pay-label">
                                <input type="radio" class="regular-radio" name="channel_id" data-host="<?php echo ($vo["host"]); ?>" value="bank<?php echo ($vo["id"]); ?>" <?php if(($key) == "0"): ?>checked<?php endif; ?> />
                                <label></label>
                                <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;"><?php echo ($vo["bank"]); ?></span>
                            </label><?php endforeach; endif; else: echo "" ;endif; ?>
          </td>
          <td align="center" style="color: #e60012;">*必选</td>
        </tr><?php endif; ?>

        <tr>
          <td class="title">支付类型：</td>
          <td style="padding: 0px 10px;">
          <?php if(is_array($paylist)): $i = 0; $__LIST__ = $paylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="pay-label">
            <input type="radio" name="channel_id" class="regular-radio" data-host="<?php echo ($vo["host"]); ?>" value="<?php echo ($vo["id"]); ?>"  <?php if(($key) == "0"): ?>checked<?php endif; ?> />
            <label ></label>  
            <img src="/Public/images/<?php echo ($vo["name"]); ?>.jpg" alt="" style="height: 40px; width:auto; display: inline-block; position: relative;"/>
            <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;"><?php echo ($vo["title"]); ?></span>
          </label><?php endforeach; endif; else: echo "" ;endif; ?>
          </td>
          <td align="center" style="color: #e60012;">*必选</td>
        </tr>
          <?php if($wmhy): ?><tr>
                  <td class="title">五码合一：</td>
                  <td style="padding: 0px 10px;">
                      <?php if(is_array($wmhy)): $i = 0; $__LIST__ = $wmhy;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="pay-label">
                              <input type="radio" name="channel_id" class="regular-radio" data-host="<?php echo ($vo["host"]); ?>" value="wm<?php echo ($vo["cid"]); ?>"  <?php if(($key) == "0"): endif; ?> />
                              <label ></label>
                              <img src="/Public/images/<?php echo ($vo["name"]); ?>.jpg" alt="" style="height: 40px; width:auto; display: inline-block; position: relative;"/>
                              <span style="font-size: 20px; height: 36px; display: inline-block; position: relative;"><?php echo ($vo["title"]); ?></span>
                          </label><?php endforeach; endif; else: echo "" ;endif; ?>
                  </td>
                  <td align="center" style="color: #e60012;">*必选</td>
              </tr><?php endif; ?>
        <tr>
          <td class="title">确认额度：</td>
          <td>
            <input type="text" name="coin" id="coin" class="table-input" placeholder="微信、支付宝、网银和信用卡<?php echo C('PAY_MIN');?>-<?php echo C('PAY_MAX');?>元" />
          </td>
          <td align="center" style="color: #e60012;">*必填</td>
        </tr>
        <tr>
          <td class="title">存款时间：</td>
          <td>
            <input name="P_Time" id="P_Time" type="text" value="" class="table-input" disabled />
          </td>
          <td align="center">无需填写</td>
        </tr>
      </table>
      <div class="form-btn">
        <a href="javascript:btnOK_zf_onclick();">确认支付</a>
      </div>
      <p class="tips">
        <span style="color:#f00;">温馨提示：</span>为了避免掉单情况的发生，请您在支付完成后，需等"支付成功"页面跳转出来, 再关闭页面，以免掉单！感谢配合！！！
 <br>支付成功后，若3分钟内未能及时到达您的会员账号请联系
 
 <a href="javascript:void(0);" onclick="javascript:window.open('<?php echo C('SERURL');?>','_blank','menubar=0,location=0,scrollbars=auto,resizable=1,status=0,width=1024,height=728')" style="color:#f00">【在线客服】</a>
 
 咨询；<br><?php echo C('WEB_SITE_TITLE');?>祝您生活愉快，盈利多多！O(∩_∩)O　　     </p>
 
    </div>
    <div class="copyright">
      Copyright &copy; <?php echo C('WEB_SITE_TITLE');?> Reserved
    </div>
  </form>  
  </body>

</html>

<script type="text/javascript">

	function getNowFormatDate() {
	    var date = new Date();
	    var seperator1 = "-";
	    var seperator2 = ":";
	    var year = date.getFullYear();
	    var month = date.getMonth() + 1;
	    var strDate = date.getDate();
	    if (month >= 1 && month <= 9) {
	        month = "0" + month;
	    }
	    if (strDate >= 0 && strDate <= 9) {
	        strDate = "0" + strDate;
	    }
	    var currentdate = year + seperator1 + month + seperator1 + strDate
	            + " " + date.getHours() + seperator2 + date.getMinutes()
	            + seperator2 + date.getSeconds();
	    return currentdate;
	}
  $(function(){
	  var time = getNowFormatDate();
	  $('#P_Time').val(time);
	  
	  $(".pay-label label").click(function(){
	  	 $(this).siblings('input').attr('checked', true);
	  })
  })
  function btnOK_zf_onclick(){        
		  var username = $("#username").val();
		  var rusername = $("#rusername").val();
		  var coin = $("#coin").val();
		  if(username==null ||username==""){
		    alert("[提示]游戏账户不能为空！");
	   	  return false;
	   	}
		  if(username != rusername){
		   alert("[提示]两次输入的游戏账户不一致！");
	   	   return false;
	   	}
	  	if(isNaN(coin)){
	   		alert("[提示]存款额度非有效数字！");
	   		return false;
	   	}
		
	   	if(coin<1){
		     alert("[提示]<?php echo C('PAY_MIN');?>元以上才能存款！");
	   	   return false;
	   	}
      if(coin><?php echo C('PAY_MAX');?>){
         alert("[提示]不能超过<?php echo C('PAY_MAX');?>元！");
         return false;
      }
	  	$("#dinpayRedirect").submit(); 
	  	return true;   
  }
</script>