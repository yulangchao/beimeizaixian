<?php
//�ϴ�ͼ��
$r=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}pic` WHERE uid='$uid'");
$pics=$db->query("SELECT * FROM {$_pre}pic WHERE uid='$uid' AND psid='$psid' ORDER BY pid DESC");
	while($img=$db->fetch_array($pics)){
		$picdb[]=$img;
	}
if(!$web_admin&&$r[NUM]>$groupdb[post_photo_num]){
	if(!$groupdb[post_photo_num]){
		showerr("�������û��鲻������ͼƬ,�㻹��Ҫ�����Ļ�,������");
	}else{
		showerr("�������û������������{$groupdb[post_sell_num]}��ͼƬ,�㻹��Ҫ�����Ļ�,������,������ɾ��һЩ�ɵ�.");
	}
}

if($step==2){
	if(!$psid){
		showerr('��ѡ��һ��ͼ��!');
	}
	//print_r($piclistdb); exit;
	$t=1;
	foreach($piclistdb[title] AS $key=>$rs){
		$title = is_array($rs[title][$t])?filtrate($rs[title][$t]):'';
		$title = get_word($title,32);
		$db->query("UPDATE `{$_pre}pic` SET `title`='$title'");
		$t++;
	}
	foreach($photodb AS $key=>$value){
	
		if( !eregi("\.(gif|jpg|jpeg|png|bmp)$",$value) || !getimagesize(ROOT_PATH."$webdb[updir]/$value") ){
			delete_attachment($uid,tempdir($value));
			continue;
		}
		$picpath = "homepage/pic/".ceil($uid/1000)."/";
		$picurl = $picpath.basename($value);
		move_attachment($uid,tempdir($value),$picpath);	//ͼƬת��Ŀ¼���ˮӡ
		if(!is_file(ROOT_PATH."$webdb[updir]/$picurl")){
			$picurl=$value;
		}
		
		$Newpicpath=ROOT_PATH."$webdb[updir]/{$picurl}.gif";
		gdpic(ROOT_PATH."$webdb[updir]/$picurl",$Newpicpath,200,200);
		if(!is_file($Newpicpath)){
			copy(ROOT_PATH."$webdb[updir]/{$picurl}",$Newpicpath);
		}

		$title = is_array($photodb[title])?filtrate($photodb[title]):'';
		$title = get_word($title,32);
		$picurl = filtrate( $picurl );
		
		$db->query("INSERT INTO `{$_pre}pic` (`psid` , `uid` , `username` ,  `title` , `url` , `level` , `yz` , `posttime` , `isfm` , `orderlist`  ) VALUES ('$psid', '$uid', '$lfjid', '$title', '$picurl', '0', '1', '$timestamp', '0', '0')");
	}
	
	refreshto("?atn=piclist&uid=$uid&psid=$psid","�޸ĳɹ�",1);
}else{
	$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC");
	while($rs=$db->fetch_array($query)){
		$listdb[]=$rs;
	}
	$webdb[company_upload_max]=$webdb[company_upload_max]?$webdb[company_upload_max]:8;
	$webdb[company_uploadnum_max]=$webdb[company_uploadnum_max]?$webdb[company_uploadnum_max]:100;	
}


?>