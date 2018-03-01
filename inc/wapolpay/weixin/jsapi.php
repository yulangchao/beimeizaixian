<?php
if(!$IsMob){
	showerr('����Ҫ���ֻ�΢���ϲ��ܷ��ʴ�ҳ�����΢��֧����');
}
//ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once dirname(__FILE__).'/'."lib/WxPay.Api.php";
require_once dirname(__FILE__).'/'."WxPay.JsApiPay.php";
require_once dirname(__FILE__).'/'.'log.php';

//��ʼ����־
$logHandler= new CLogFileHandler(ROOT_PATH."cache{$webdb[web_dir]}/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//��ӡ���������Ϣ
function printf_info($data)
{
	
    foreach($data as $key=>$value){
        if($data['return_code']=='FAIL'){
			if(WEB_LANG!='utf-8')$value=utf82gbk($value);
			echo "<font color='#00ff55;'>$key</font> : $value <br/>";
		}
		if($data['err_code_des']!=''){
			if(WEB_LANG!='utf-8')$value=utf82gbk($value);
			echo "<font color='#00ff55;'>$key</font> : $value <br/>";
		}
    }
	
	if(strstr($data[return_msg],'time_expire')){
		echo "<hr>��ǰ������ʱ����������һ�£���ǰ����������ʾ��ʱ���ǣ�<br>".date('Y-m-d H:i:s');
	}
}

//�١���ȡ�û�openid
$tools = new JsApiPay();
//$openId = $tools->GetOpenid();
//$openId = get_cookie('WeiXin_OpenId');
$openId = $lfjdb['weixin_api'];
if(!$openId){
	//$openId = set_weixin_openid();
	$openId = $tools->GetOpenid();
}
//�ڡ�ͳһ�µ�
$input = new WxPayUnifiedOrder();
$input->SetBody($array['title']);
$input->SetAttach($array['other']);
$input->SetOut_trade_no( $array['numcode'] );
$input->SetTotal_fee($array['money']*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($array['title']);
$input->SetNotify_url($array['wx_notify_url']);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>��ǰϵͳ���Խ׶Σ���ֻ��Ҫ֧��1��Ǯ������������֧�����̣�</b></font><br/>';
//echo '<font color="#f00"><b>��ǰϵͳ���Խ׶Σ���ֻ��Ҫ֧��1��Ǯ������������֧�����̣�</b></font><br/>';
printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//��ȡ�����ջ���ַjs��������
$editAddress = $tools->GetEditAddressParameters();

//�ۡ���֧�ֳɹ��ص�֪ͨ�д���ɹ�֮������ˣ��� notify.php
/**
 * ע�⣺
 * 1������Ļص���ַ���ɷ��ʵ�ʱ�򣬻ص�֪ͨ��ʧ�ܣ�����ͨ����ѯ������ȷ��֧���Ƿ�ɹ�
 * 2��jsapi֧��ʱ��Ҫ�����û�openid��WxPay.JsApiPay.php���л�ȡopenid���� ���ĵ����Բο�΢�Ź���ƽ̨����ҳ��Ȩ�ӿڡ���
 * �ο�http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html��
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=gbk"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>΢��֧��</title>
    <script type="text/javascript">
	//����΢��JS api ֧��
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
				if(res.err_msg=='get_brand_wcpay_request:ok')window.location.href="<?php echo "$array[wx_return_url]&ispay=ok"; ?>";
				if(res.err_msg=='get_brand_wcpay_request:cancel')window.location.href="<?php echo "$array[wx_return_url]&ispay=0"; ?>";
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	//��ȡ�����ַ
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				
				alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}
	/*
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}
	};*/
	
	</script>
</head>
<body>
    <br/>
    <font color="#9ACD32"><b>�ñʶ���֧�����Ϊ<span style="color:#f00;font-size:50px"><?php echo $array['money']; ?></span>ԪǮ</b></font><br/><br/>
	<div align="center">
		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >����֧��</button>
	</div>
</body>
</html>