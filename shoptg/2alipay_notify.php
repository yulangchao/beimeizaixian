<?php
require("global.php");

$pay_code = $_GET['body'];

require("2olpay.inc.php");


require(ROOT_PATH.'inc/olpay/alipay/notify_url.php');
?>