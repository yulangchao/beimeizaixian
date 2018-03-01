<?php
require("global.php");

$pay_code = $_GET['body'];

require("olpay.inc.php");


require(ROOT_PATH.'inc/wapolpay/alipay/notify_url.php');
?>