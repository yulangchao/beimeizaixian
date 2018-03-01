<?php
require(dirname(__FILE__)."/global.php");

//导航条
@include(Mpath."data{$webdb[web_dir]}/guide_fid.php");

if($fid){
	$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");
	if(!$fidDB){
		showerr("栏目不存在");
	}elseif($fidDB[jumpurl]){
		header("location:$fidDB[jumpurl]");
		exit;
	}

	/**
	*模型配置文件
	**/
	$field_db = $module_DB[$fidDB[mid]][field];

	//字段筛选
	unset($TempSearch_2,$TempSearch_array);
	foreach($field_db AS $key=>$value){
		if($value[listfilter]){
			$TempSearch_2.="$key=>'{$$key}',";		//分页链接使用
			$TempSearch_array[$key]=$$key;			//其它链接使用
			$search_fieldDB[$key][$$key!=''?$$key:0]=" selected class='ck' style='color:red;'";
		}
	}


	/**
	*栏目配置参数及栏目用户自定义的变量
	*对栏目用户自定义的变量附件路径做处理
	*以下用的比较少,可以删除忽略
	**/
	$fidDB[config]=unserialize($fidDB[config]);
	$CV=$fidDB[config][field_value];
	$_array=array_flip($fidDB[config][is_html]);
	foreach( $fidDB[config][field_db] AS $key=>$rs){
		if(in_array($key,$_array)){
			$CV[$key]=En_TruePath($CV[$key],0);
		}elseif($rs[form_type]=='upfile'){
			$CV[$key]=tempdir($CV[$key]);
		}
	}


	//SEO
	$titleDB[title]	= $fidDB[metatitle]?seo_eval($fidDB[metatitle]):strip_tags("{$city_DB[name][$city_id]} {$zone_DB[name][$zone_id]} {$street_DB[name][$street_id]} $fidDB[name] $seo_tile");
	$titleDB[keywords] = seo_eval($fidDB[metakeywords]);
	$titleDB[description] = seo_eval($fidDB[metadescription]);

	//栏目风格
	//$fidDB[style] && $STYLE=$fidDB[style];


	//个性头部与尾部
	get_list_tpl($head_tpl,$foot_tpl,$main_tpl);

	 
	$rows = $fidDB[maxperpage]>0 ? $fidDB[maxperpage] : 6;	

	//排序方式
	switch($fidDB['listorder']){
		case 1:
			$sql_list="A.posttime";
			$sql_order="DESC";
			break;
		case 2:
			$sql_list="A.posttime";
			$sql_order="ASC";
			break;
		case 3:
			$sql_list="A.hits";
			$sql_order="DESC";
			break;
		case 4:
			$sql_list="A.hits";
			$sql_order="ASC";
			break;
		case 5:
			$sql_list="A.lastview";
			$sql_order="DESC";
			break;
		default:
			$sql_list="A.list";
			$sql_order="DESC";
			break;
	}


	$SQL = build_module_sql();

	//用户自定义筛选字段,过滤数据
	foreach($field_db AS $key=>$value){
		if($value[listfilter]){
			if($_GET[$key]!=''){
				$SQL .= " AND B.`$key`='$_GET[$key]' ";
			}
		}
	}

	if(!$webdb[Info_ShowNoYz]){
		$SQL .= " AND A.yz='1' ";
	}

	if($Fid_db[$fid]){
		$SQL=" LEFT JOIN {$_pre}sort S ON A.fid=S.fid WHERE S.fup='$fid' $SQL ";
	}else{
		$SQL = " WHERE A.fid='$fid' $SQL ";
	}
}else{
	$rows=6;
	$SQL = " WHERE 1 ";
	$sql_list="A.list";
	$sql_order="DESC";
}
if($city_id){
	$SQL .= " AND A.city_id='$city_id' ";
}
if($zone_id){
	$SQL .= " AND A.zone_id='$zone_id' ";
}
if($street_id){
	$SQL .= " AND A.street_id='$street_id' ";
}
if($orders){
	$orders=filtrate($orders);
	$sql_list="A.$orders";
}
$listdb=array();
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;

$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,C.title AS companyname,C.renzheng  FROM {$_pre}company A LEFT JOIN {$pre}hy_company C ON A.uid=C.uid $SQL ORDER BY $sql_list $sql_order LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?fid=$fid&city_id=$city_id&zone_id=$zone_id&street_id=$street_id&orders=$orders",$rows,$totalNum);
$totalpage=@ceil($totalNum/$rows);
while( $rs=$db->fetch_array($query) ){
	$rs[content]=@preg_replace('/<([^<]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
	$rs[content]=get_word($rs[full_content]=$rs[content],100);
	$rs[title]=get_word($rs[full_title]=$rs[title],50);
	if($rs['list']>$timestamp){
		$rs[title]=" <font color='$webdb[Info_TopColor]'>$rs[title]</font> <img src='$webdb[www_url]/images/default/icotop.gif' border=0>";
	}elseif($rs['list']>$rs[posttime]){
		//置顶过期的信息,需要恢复原来发布日期以方便排序,放在后面
		$db->query("UPDATE {$_pre}company SET list='$rs[posttime]' WHERE id='$rs[id]'");
	}
	$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
	if($rs[picurl]){
		$rs[picurl]=tempdir($rs[picurl]);
	}
	$Module_db->showfield($field_db,$rs,'list');
	$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);
	$listdb[]=$rs;
}

if($job=="showmore"){	
	if(!$listdb){
		echo "nodate";
	}else{
		foreach($listdb AS $key=>$rs){
		   $thisPC=ge_pingfen_pc($rs[id]);
		   $rs[arrive_time]||$rs[arrive_time]=0;
		   $str ='';
		   $str .= "<dl class='ListHy'>
			<dt>
				<div class='img'><a href='wapshowhy.php?fid=$rs[fid]&id=$rs[id]'><img src='$rs[picurl]' onError=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></div>
				<div class='state'>营业中</div>
			</dt>
			<dd>
				<div class='title'><a href='wapshowhy.php?fid=$rs[fid]&id=$rs[id]'><em>$rs[title]</em><span>查看商家</span></a></div>
				<div class='xingxing'><span style='width:{$thisPC}%;'></span></div>
				<div class='number'>
					<span>{$rs[price]}元起送</span>
					<span>{$rs[arrive_time]}分钟</span>
					<span>配送费{$rs[sendprice]}元</span>
				</div>
				<div class='phone'><span>电话：{$rs[telphoto]}</span></div>
				<div class='address'>地址：{$rs[address]}</div>
			</dd>
		</dl>";
		   echo $str;	
		}
	}	
	exit;
}

require(getTpl("waplisthy"));


?>