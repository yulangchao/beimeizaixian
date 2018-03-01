<?php


function build_module_sql(){
	global $city_id;
	if(!$city_id){
		return ;
	}else{
		return " AND A.city_id IN ('$city_id',0) ";
	}
}

function get_comment_quote($quoteid,&$array){
	global $db,$_pre;
	$query =$db->query("SELECT * FROM `{$_pre}comments` WHERE cid='$quoteid'");
	while($rs =$db->fetch_array($query)){
		if(!$rs[username]){
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].$detail[2].*";
		}
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[content]=str_replace("\n","<br>",$rs[content]);
		$rs[content]=replace_bad_word($rs[content]);
		$rs[username]=replace_bad_word($rs[username]);
	
		$array[$rs[cid]]=$rs;
		if($rs[quoteid]){
			get_comment_quote($rs[quoteid],$array);
		}
	}
}

function getTpl($html,$tplpath=''){
	global $STYLE;
	if($tplpath&&file_exists($tplpath)){
		return $tplpath;
	}elseif($tplpath&&file_exists(Mpath.$tplpath)){
		return Mpath.$tplpath;
	}elseif(file_exists(Mpath."template/$STYLE/$html.htm")){
		return Mpath."template/$STYLE/$html.htm";
	}else{
		return Mpath."template/default/$html.htm";
	}
}

//对用户发表文章与推荐文章的奖罚yz,unyz,com,uncom,del
function Give_News_money($uid,$type='',$rsdb){
	global $webdb,$Mdomain;
	if($type=='yz'){
		$money	=	$webdb[postArticleMoney];
		$msg = '发表文章通过审核奖励';
	}elseif($type=='unyz'){
		$money	=	-$webdb[postArticleMoney];
		$msg = '发表文章取消审核惩罚';
	}elseif($type=='com'){
		$money	=	$webdb[comArticleMoney];
		$msg = '文章被设为精华奖励';
	}elseif($type=='uncom'){
		$money	=	-$webdb[comArticleMoney];
		$msg = '文章被取消精华惩罚';
	}elseif($type=='top'){
		$money	=	$webdb[topArticleMoney];
		$msg = '文章被置顶显示奖励';
	}elseif($type=='front'){
		$money	=	$webdb[frontArticleMoney];
		$msg = '文章被提前显示奖励';
	}
	
	if($type=='del'){
		$money	=	$webdb[deleteArticleMoney];
		$msg = '文章被删除扣分:'.$rsdb[title];
	}else{
		$msg .= " [<A HREF='$Mdomain/bencandy.php?fid=$rsdb[fid]&id=$rsdb[id]' target=_blank>".get_word($rsdb[title],30)."</A>]";
	}
	if(!$money||!$uid){
		return ;
	}
	add_user($uid,$money,$msg);
}


//关键字格式化
function keyword_ck($keyword,$title){
	global $SPword;
	if($title){
		require_once(ROOT_PATH."inc/splitword.php");
		$de=splitword($title);
		$detail=explode(" ",$de);
		foreach( $detail AS $key=>$value){
			//小于3个字符的.不列为关键字
			if(strlen($value)<(WEB_LANG=='utf-8'?4:3)){
				continue;
			}
			$keyword.=" $value";
		}
	}
	if($keyword){
		$keyword=str_replace("　"," ",$keyword);
		$keyword=str_replace(","," ",$keyword);
		$keyword=str_replace("，"," ",$keyword);
		$keyword=str_replace("、"," ",$keyword);
		$detail=explode(' ',str_replace(array('\\',"'",'"'),array('','',''),$keyword)); 
		foreach( $detail AS $key=>$value){
			//大于3个字节的,才列为关键字,一个汉字相当于两个字节
			if(strlen($value)>2){
				 $array[$value]=$value;
			}
		}
		$keyword=implode(" ",$array);
		return $keyword;
	}
}


//添加关键字
function keyword_add($aid,$keyword,$uid=0){
	global $db,$_pre;
	$detail=explode(' ',str_replace(array('\\',"'",'"'),array('','',''),$keyword)); 
	foreach( $detail AS $key=>$value){
		if($value){
			$_rs=$db->get_one("SELECT * FROM `{$_pre}keyword` WHERE keywords='$value'");
			$id=$_rs[id];
			if(!$id){
				$db->query("INSERT INTO `{$_pre}keyword` ( `keywords`,`num`,`ifhide`,`uid` ) VALUES ('$value',1,1,'$uid' )");
				$id=$db->insert_id();
			}else{
				$db->query("UPDATE `{$_pre}keyword` SET num=num+1 WHERE `keywords`='$value'");
			}
			$db->query("INSERT INTO `{$_pre}keywordid` ( `id`,`aid` ) VALUES ('$id','$aid')");
		}
	}
}

?>