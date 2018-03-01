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




//���û������������Ƽ����µĽ���yz,unyz,com,uncom,del
function Give_News_money($uid,$type='',$rsdb){
	global $webdb,$Mdomain;
	if($type=='yz'){
		$money	=	$webdb[postArticleMoney];
		$msg = '��������ͨ����˽���';
	}elseif($type=='unyz'){
		$money	=	-$webdb[postArticleMoney];
		$msg = '��������ȡ����˳ͷ�';
	}elseif($type=='com'){
		$money	=	$webdb[comArticleMoney];
		$msg = '���±���Ϊ��������';
	}elseif($type=='uncom'){
		$money	=	-$webdb[comArticleMoney];
		$msg = '���±�ȡ�������ͷ�';
	}elseif($type=='top'){
		$money	=	$webdb[topArticleMoney];
		$msg = '���±��ö���ʾ����';
	}elseif($type=='front'){
		$money	=	$webdb[frontArticleMoney];
		$msg = '���±���ǰ��ʾ����';
	}
	
	if($type=='del'){
		$money	=	$webdb[deleteArticleMoney];
		$msg = '���±�ɾ���۷�:'.$rsdb[title];
	}else{
		$msg .= " [<A HREF='$Mdomain/bencandy.php?fid=$rsdb[fid]&id=$rsdb[id]' target=_blank>".get_word($rsdb[title],30)."</A>]";
	}
	if(!$money||!$uid){
		return ;
	}
	add_user($uid,$money,$msg);
}


//�ؼ��ָ�ʽ��
function keyword_ck($keyword,$title){
	global $SPword;
	if($title){
		require_once(ROOT_PATH."inc/splitword.php");
		$de=splitword($title);
		$detail=explode(" ",$de);
		foreach( $detail AS $key=>$value){
			//С��3���ַ���.����Ϊ�ؼ���
			if(strlen($value)<(WEB_LANG=='utf-8'?4:3)){
				continue;
			}
			$keyword.=" $value";
		}
	}
	if($keyword){
		$keyword=str_replace("��"," ",$keyword);
		$keyword=str_replace(","," ",$keyword);
		$keyword=str_replace("��"," ",$keyword);
		$keyword=str_replace("��"," ",$keyword);
		$detail=explode(' ',str_replace(array('\\',"'",'"'),array('','',''),$keyword)); 
		foreach( $detail AS $key=>$value){
			//����3���ֽڵ�,����Ϊ�ؼ���,һ�������൱�������ֽ�
			if(strlen($value)>2){
				 $array[$value]=$value;
			}
		}
		$keyword=implode(" ",$array);
		return $keyword;
	}
}


//��ӹؼ���
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