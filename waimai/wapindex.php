<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");



web_cache($webdb['shop_cache_time']);	//��ȡ���棬ֱ����ʾ������Ĵ��벻��ִ��

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);




	$rows=6;
	$SQL = " WHERE 1 ";
	$sql_list="A.list";
	$sql_order="DESC";


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
	$rs[content]=@preg_replace('/<([^<]*)>/is',"",$rs[content]);	//��HTML������˵�
	$rs[content]=get_word($rs[full_content]=$rs[content],100);
	$rs[title]=get_word($rs[full_title]=$rs[title],50);
	if($rs['list']>$timestamp){
		$rs[title]=" <font color='$webdb[Info_TopColor]'>$rs[title]</font> <img src='$webdb[www_url]/images/default/icotop.gif' border=0>";
	}elseif($rs['list']>$rs[posttime]){
		//�ö����ڵ���Ϣ,��Ҫ�ָ�ԭ�����������Է�������,���ں���
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
				<div class='state'>Ӫҵ��</div>
			</dt>
			<dd>
				<div class='title'><a href='wapshowhy.php?fid=$rs[fid]&id=$rs[id]'><em>$rs[title]</em><span>�鿴�̼�</span></a></div>
				<div class='xingxing'><span style='width:{$thisPC}%;'></span></div>
				<div class='number'>
					<span>{$rs[price]}Ԫ����</span>
					<span>{$rs[arrive_time]}����</span>
					<span>���ͷ�{$rs[sendprice]}Ԫ</span>
				</div>
				<div class='phone'><span>�绰��{$rs[telphoto]}</span></div>
				<div class='address'>��ַ��{$rs[address]}</div>
			</dd>
		</dl>";
		   echo $str;	
		}
	}	
	exit;
}


//��ҳ����ͷ����β��
get_index_tpl($head_tpl,$foot_tpl);


//��ȡ��ǩ����
$template_file=getTpl("wapindex");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));


//�Ƽ�����Ŀ����ҳ��ʾ
//$listdb_moresort=Info_ListMoreSort($webdb[InfoIndexCSRow],$webdb[InfoIndexCSLeng],$city_id);

//ÿ����Ŀ����Ϣ��
//$InfoNum=get_infonum($city_id);

//require(ROOT_PATH."inc/waphead.php");
require($template_file);
//require(ROOT_PATH."inc/wapfoot.php");


?>