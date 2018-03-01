<?php
!function_exists('html') && exit('ERR');

if($job=="make"&&$Apower[baidu_sitemap])
{
	hack_admin_tpl('make');
}
elseif($action=="make"&&$Apower[baidu_sitemap])
{
	$M_array = array('news_',
	'wei_',
	'hy_',
	'fenlei_',
	'shop_',
	'tuangou_',
	'shoptg_',
	'hr_',
	'gift_',
	'coupon_',
	'house_',
	'2shou_',
	'dianping_',
	'zhuangxiu_',
	);
	
	$cotent='<?xml version="1.0" encoding="utf-8"?><urlset>';
	
	foreach($M_array AS $value){
		if( is_array($ModuleDB[$value]) ){
			$query =$db->query("SELECT * FROM {$pre}{$value}sort");
			while($rs =$db->fetch_array($query)){
				$cotent.="\r\n<url><loc>$webdb[www_url]/{$ModuleDB[$value][dirname]}/list.php?fid=$rs[fid]</loc></url>";
			}
		}
	}
	
	$cotent.="\r\n</urlset>";
	
	write_file(ROOT_PATH.'baidu_sitemap.xml',$cotent);
	 
	jump("百度Sitemap生成完毕，请前往百度提交地址","index.php?lfj=$lfj&job=make",3);	

}
?>