<?php
require_once(dirname(__FILE__)."/global.php");

if(!table_field("{$_pre}company",'zone_id')){
	$db->query("ALTER TABLE `{$_pre}company` ADD `zone_id` mediumint(7) NOT NULL default '0'");
}
if(!table_field("{$_pre}company",'street_id')){
	$db->query("ALTER TABLE `{$_pre}company` ADD `street_id` mediumint(7) NOT NULL default '0'");
}
if(!table_field("{$_pre}company",'telphoto')){
	$db->query("ALTER TABLE `{$_pre}company` ADD `telphoto` varchar(50) NOT NULL default ''");
}

if(!$lfjid){
	showerr('请先登录!');
}
//elseif($webdb['postShopNeedQy']&&!$web_admin&&$lfjdb['grouptype']!=1){
	//showerr('只有通过审核的企业用户才能发布商品!');
//}

/**
*获取栏目配置文件

$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid'");

if(!$fidDB){
	showerr('栏目有误!');
}

$rs=$db->get_one("SELECT admin FROM {$pre}city WHERE fid='$_COOKIE[admin_cityid]'");
$detail=explode(',',$rs[admin]);
if($lfjid && in_array($lfjid,$detail)){
	$web_admin=1;
}
**/
/**
*模型参数配置文件

$field_db = $module_DB[$fidDB[mid]][field];
$ifdp = $module_DB[$fidDB[mid]][ifdp];
$m_config[moduleSet][useMap] = $module_DB[$fidDB[mid]][config][moduleSet][useMap];

if($fidDB[type]){
	showerr("大分类,不允许发表内容");
}elseif( $fidDB[allowpost] && $action!="del" && in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
	showerr("你所在用户组,无权在本栏目发布");
}
**/

/*模板
$FidTpl=unserialize($fidDB[template]);

$lfjdb[money]=$lfjdb[_money]=intval(get_money($lfjuid));
*/



if($action=="postnew"||$action=="edit"){
	
	$titledb = $_POST['titledb'];
	$postdb['title']=filtrate($postdb['title']);
	$postdb['price']=filtrate($postdb['price']);
	$postdb['keywords']=filtrate($postdb['keywords']);
	if(!$postdb[title]){
		showerr("商家名称不能为空");
	}elseif(strlen($postdb[title])>80){
		showerr("商家名称不能大于40个汉字.");
	}
	
	if($postdb[money]){
		if($postdb[money]<0){
			showerr("赠送{$webdb[MoneyName]}不能小于0");
		}elseif(!is_numeric($postdb[money]) ){
			showerr("赠送{$webdb[MoneyName]}只能是整数");
		}elseif(!$webdb[giveMoneyFromSystem] && $postdb[money]>get_money($lfjuid)){
			showerr("赠送{$webdb[MoneyName]}不能大于你自身的");
		}
	}
	
	if( count($city_DB[name])==1 )$postdb[city_id]=$city_id;
}

if($job=='set'){

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.uid='$lfjuid' ORDER BY id DESC LIMIT 1");

	if(!$rsdb){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=post_company.php'>";
		exit;
	}else{
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=edit&fid=$rsdb[fid]&id=$rsdb[id]'>";
		exit;
	}

}
elseif($action=="postnew")
{
	$ts = $db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
	if(!$web_admin && $ts){	//非管理员不能创建多个
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=edit&fid=$ts[fid]&id=$ts[id]'>";
		exit;
	}

	/*验证码处理*/
	if(!$web_admin&&$groupdb[postShopYzImg]){
		if(!check_imgnum($yzimg)){		
			showerr("验证码不符合,发布失败");
		}
	}

	if(!$web_admin){
		if($groupdb[post_shop_num]<1){
			showerr('你所在用户组不允许发布商品,请升级用户组吧');
		}
		$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}company` WHERE uid='$lfjuid'");
		if($_rs[NUM]>$groupdb[post_shop_num]){
			showerr("你所在用户组发布的商品信息不能超过{$groupdb[post_shop_num]}个,请升级用户组吧");
		}
	}

	if($postdb['storage']<1){
		//showerr("库存量必须要大于 1");
	}

	$postdb['list']=$timestamp;
	if($iftop){		//推荐置顶
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}company` WHERE list>'$timestamp' AND fid='$fid' AND city_id='$postdb[city_id]'"));
		if($webdb[Info_TopNum]&&$NUM>=$webdb[Info_TopNum]){
			showerr("当前栏目置顶信息已达到上限!");
		}
		$postdb['list']+=3600*24*$webdb[Info_TopDay];
		if($lfjdb[money]<$webdb[Info_TopMoney]){
			showerr("你的{$webdb[MoneyName]}不足:$webdb[Info_TopMoney],不能选择置顶");
		}
		$lfjdb[money]=$lfjdb[money]-$webdb[Info_TopMoney];	//为下面做判断积分是否足够
	}



	
	if(eregi("[a-z0-9]{15,}",$postdb[title])){
		showerr("请认真写好标题!");
	}
	if(eregi("[a-z0-9]{25,}",$postdb[content])){
		//showerr("请认真填写内容!");
	}
	
	//自定义字段进行校正检查是否合法
	//$Module_db->checkpost($field_db,$postdb,'');

	//上传本地图片
	post_info_photo();

	unset($num,$postdb[picurl]);
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		if($titledb[$key]>100){
			showerr("标题不能大于50个汉字");
		}
		$num++;
		if(!$postdb[picurl]){
			if(is_file(ROOT_PATH."$webdb[updir]/$value.gif")){
				$postdb[picurl]="$value.gif";
			}else{
				$postdb[picurl]=$value;
			}
		}
	}

	$postdb[ispic]=$postdb[picurl]?1:0;

	//是否自动通过审核
	$web_admin && $groupdb[shop_postauto_yz]=1;
	$postdb[yz] = $groupdb[shop_postauto_yz];

	if($postdb[yz]==1){
		//add_user($lfjdb[uid],$webdb[PostInfoMoney],'发布商品奖励积分');
	}

	//置顶扣分
	if($iftop){
		add_user($lfjuid,-intval($webdb[Info_TopMoney]),'发布商品置顶扣积分');
	}
	

	
	$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$postdb[fid]'");
	/*往标题表插入内容*/
	$db->query("INSERT INTO `{$_pre}company` (`title` , `mid` ,`fid` , `fname` ,  `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` , `keywords` , `ip` , `money` , `city_id`, `zone_id`, `street_id`,`picnum`,`price`,`sendprice`,`arrive_time`,`address`,`ranges`,`maps`,`content`,`telphoto`) 
	VALUES (
	'$postdb[title]','$fidDB[mid]','$postdb[fid]','$fidDB[name]','$timestamp','$postdb[list]','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','$postdb[yz]','$postdb[keywords]','$onlineip','$postdb[money]','$postdb[city_id]','$postdb[zone_id]','$postdb[street_id]','$num','$postdb[price]','$postdb[sendprice]','$postdb[arrive_time]','$postdb[address]','$postdb[ranges]','$postdb[maps]','$postdb[content]','$postdb[telphoto]')");

	$id=$db->insert_id();

	//插入图片
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=addslashes(filtrate($titledb[$key]));
		$value=filtrate($value);
		$db->query("INSERT INTO `{$_pre}companypic` ( `id` , `fid` , `mid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$mid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
	}

	unset($sqldb);
	$sqldb[]="id='$id'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	/*检查判断辅信息表要插入哪些字段的内容*/
	foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}

	$sql=implode(",",$sqldb);

	/*往辅信息表插入内容*/
	//$db->query("INSERT INTO `{$_pre}company_$fidDB[mid]` SET $sql");
	
	set_user_log(4);	//用户访问日志

	refreshto("?job=edit&fid=$fid&id=$id","<a target='_blank' href='../listhy.php?fid=$fid&id=$id'>返回列表</a> <a target='_blank' href='../showhy.php?city_id=$rsdb[city_id]&fid=$fid&id=$id'>查看效果</a>",600);

}

/*删除内容,直接删除,不保留*/
elseif($action=="del")
{

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid])
	{
		showerr("栏目有问题");
	}
	
	elseif($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin])))
	{
		showerr("你没权限!");
	}

	del_company_info($id,$rsdb);

	if($rsdb[yz]){
	//	add_user($lfjdb[uid],-$webdb[PostInfoMoney],'删除商城信息扣分');
	}
	
	refreshto($FROMURL,"删除成功");
}

/*编辑内容*/
elseif($job=="edit")
{

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin]))){	
		showerr('你没权限!');
	}
	
	/*表单默认变量作处理*/
	//$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";



	$rsdb['list']>$timestamp?($ifTop[1]=' checked '):($ifTop[0]=' checked ');

	$rsdb[price]==0 && $rsdb[price]='';
	
	$listdb = array();
	$query = $db->query("SELECT * FROM {$_pre}companypic WHERE id='$id'");
	while($rs = $db->fetch_array($query)){
		$listdb[$rs[pid]]=$rs;
	}

	$city_id=$rsdb[city_id];
	$zone_id=$rsdb[zone_id];
	$street_id=$rsdb[street_id];

	$city_fid=select_where("{$pre}city","'postdb[city_id]'  onChange=\"choose_where('getzone',this.options[this.selectedIndex].value,'','1','')\"",$city_id);
	
	require(ROOT_PATH."member/head.php");
	require(getTpl("post_company"));
	require(ROOT_PATH."member/foot.php");
}

/*处理提交的内容做修改*/
elseif($action=="edit")
{

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin]))){
		showerr("你无权修改");
	}


	if($rsdb['yz']==2 && $postdb['storage']>1){
		$rsdb['yz'] = 1;
	}


	if($iftop&&$rsdb['list']<$timestamp){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}company` WHERE list>'$timestamp' AND fid='$fid' AND city_id='$postdb[city_id]'"));
		if($webdb[Info_TopNum]&&$NUM>=$webdb[Info_TopNum]){
			showerr("当前栏目置顶信息已达到上限!");
		}
		if($lfjdb[money]<$webdb[Info_TopMoney]){
			showerr("你的积分不足:$webdb[Info_TopMoney],不能选择置顶");
		}
	}
	
	//自定义字段进行校正检查是否合法
	//$Module_db->checkpost($field_db,$postdb,$rsdb);

	//上传本地图片
	post_info_photo();

	unset($num,$postdb[picurl]);
	foreach( $photodb AS $key=>$value ){

		if(!$value&&$piddb[$key]){
			$db->query("DELETE FROM `{$_pre}companypic` WHERE pid='{$piddb[$key]}' AND id='$id'");
		}

		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=addslashes(filtrate($titledb[$key]));
		$value=filtrate($value);
		if($titledb[$key]>100){
			showerr("标题不能大于50个汉字");
		}
		$num++;
		if($piddb[$key]){		
			$db->query("UPDATE `{$_pre}companypic` SET name='{$titledb[$key]}',imgurl='$value' WHERE pid='{$piddb[$key]}' AND id='$id'");
		}else{
			$db->query("INSERT INTO `{$_pre}companypic` ( `id` , `fid` , `mid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$mid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
		}
		if(!$postdb[picurl]){
			if(is_file(ROOT_PATH."$webdb[updir]/$value.gif")){
				$postdb[picurl]="$value.gif";
			}else{
				$postdb[picurl]=$value;
			}
		}
	}

	/*判断是否为图片主题*/
	$postdb[ispic]=$postdb[picurl]?1:0;


	if($iftop){
		if($rsdb['list']<$timestamp){
			$list=$timestamp+3600*24*$webdb[Info_TopDay];
			$SQL=",list='$list'";
			add_user($lfjuid,-intval($webdb[Info_TopMoney]),'置顶商城信息扣分');
		}	
	}else{
		if($rsdb['list']>$timestamp){
			$SQL=",list='$rsdb[posttime]'";
		}
	}
	$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$postdb[fid]'");
	/*更新主信息表内容*/
	$db->query("UPDATE `{$_pre}company` SET fid='$postdb[fid]',fname='$fidDB[name]',title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',picnum='$num',price='$postdb[price]',city_id='$postdb[city_id]',zone_id='$postdb[zone_id]',street_id='$postdb[street_id]',yz='$rsdb[yz]',sendprice='$postdb[sendprice]',arrive_time='$postdb[arrive_time]',ranges='$postdb[ranges]',address='$postdb[address]',maps='$postdb[maps]',content='$postdb[content]',telphoto='$postdb[telphoto]' WHERE id='$id'");

	/*检查判断辅信息表要插入哪些字段的内容*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*更新辅信息表*/
	//$db->query("UPDATE `{$_pre}company_$fidDB[mid]` SET $sql WHERE id='$id'");

	set_user_log(5);	//用户访问日志

	refreshto("?job=edit&fid=$fid&id=$id","<a target='_blank' href='../listhy.php?fid=$fid&id=$id'>返回列表</a> <a target='_blank' href='../showhy.php?city_id=$rsdb[city_id]&fid=$fid&id=$id'>查看效果</a>",600);
}
else
{
	$checkdb=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
	if(!$web_admin && $checkdb){	//非管理员不能创建多个
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=edit&fid=$checkdb[fid]&id=$checkdb[id]'>";
		exit;
	}

	/*模块设置时,有些字段有默认值*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]!=''){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*表单默认变量作处理*/
	//$Module_db->formGetVale($field_db,$rsdb);


	$atc="postnew";

	$ifTop[0]=' checked ';

	$listdb=array('');

	if(!$city_id){
		$city_id=$_COOKIE[city_id];
	}
	$city_fid=select_where("{$pre}city","'postdb[city_id]'  onChange=\"choose_where('getzone',this.options[this.selectedIndex].value,'','1','')\" ",$city_id);
	
	//$select_mysort = select_mysort($lfjuid,'postdb[myfid]');

	require(ROOT_PATH."member/head.php");
	require(getTpl("post_company"));
	require(ROOT_PATH."member/foot.php");
}

?>