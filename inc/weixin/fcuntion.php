<?php


function write_wx_log(){
	print_r($_GET);
	echo $HTTP_RAW_POST_DATA;
	$content=ob_get_contents();
	ob_end_clean();
	write_file(ROOT_PATH.'cache/wx_err.txt',$content);
}

function check_wx_power(){
	global $webdb;
      $signature = $_GET["signature"];
      $timestamp = $_GET["timestamp"];
      $nonce = $_GET["nonce"];
      $token = $webdb['weixin_Token'];
      $tmpArr = array($token, $timestamp, $nonce);
      sort($tmpArr, SORT_STRING);
      $tmpStr = implode( $tmpArr );
      $tmpStr = sha1( $tmpStr );
      if( $tmpStr == $signature ){
             return true;
      }else{
             return false;
      }
}

?>