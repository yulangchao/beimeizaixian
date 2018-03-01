<?php
function_exists('html') OR exit('ERR');

define('Mdirname', preg_replace("/(.*)\/([^\/]+)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );
define('Mpath', preg_replace("/(.*)\/([^\/]+)\/([^\/]+)/is","\\1/\\2/",str_replace("\\","/",dirname(__FILE__))) );

define('Madmindir', preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );
define('SYS_TYPE','shop');
$Mpath = Mpath;
define('Adminpath',dirname(__FILE__).'/');

require(Mpath."data{$webdb[web_dir]}/config.php");
require(Mpath."inc/function.php");
require(Mpath."data{$webdb[web_dir]}/all_fid.php");
require_once(Mpath."inc/module.class.php");

$Murl=$webdb[www_url].'/'.Mdirname;
$_pre="{$pre}{$webdb[module_pre]}";					//数据表前缀
$Module_db=new Module_Field(Mpath);						//自定义模型相关

?>