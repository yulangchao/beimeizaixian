<?php 
//ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once dirname(__FILE__).'/'."lib/WxPay.Api.php";
require_once dirname(__FILE__).'/'."WxPay.JsApiPay.php";
require_once dirname(__FILE__).'/'.'log.php';

//初始化日志
$logHandler= new CLogFileHandler(ROOT_PATH."cache{$webdb[web_dir]}/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);


$tools = new JsApiPay();

$openId = $lfjdb['weixin_api'];

//$tools->data=array("access_token"=> get_cookie('WeiXin_AccessToken') );

$tools->data=array("access_token"=> wx_getAccessToken() );

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();


?>
<!--
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=gbk"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>
    <script type="text/javascript">

	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var msg = res.err_msg;
				var provice = res.proviceFirstStageName;
				var city = res.addressCitySecondStageName;
				var zone = res.addressCountiesThirdStageName;
				var address = res.addressDetailInfo;
				var tel = res.telNumber;
				var postcode = res.addressPostalCode;
				var uname = res.userName;
				if(msg.indexOf("ok")==-1){
					alert("fail");
				}else{
					alert(msg+ ":" + provice+ ":" + city + ":" +  zone + ":" +  address + ":" + tel+ ":" + postcode+ ":" + uname);
				}
				
				
			}
		);
	}
	 
	function call_address(){
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
	}; 
	
	</script>
</head>
<body>
    <br/>
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $array['money']; ?></span>元钱</b></font><br/><br/>
	<div align="center">
		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="call_address()" >立即支付</button>
	</div>
</body>
</html>

-->