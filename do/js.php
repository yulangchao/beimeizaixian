<?php
error_reporting(0);
require(dirname(__FILE__)."/../data/config.php");
if(!eregi("^([0-9]+)$",$_GET['id'])){
	die("document.write('ID������');");
}
unset($_GET['FileName'],$_POST['FileName'],$_COOKIE['FileName'],$_FILES['FileName']);
$FileName=dirname(__FILE__)."/../cache/js/";

$FileName.="{$_GET[id]}.txt";
//Ĭ�ϻ���3����.
if(!$webdb["cache_time_js"]){
	$webdb["cache_time_js"]=3;
}
if( (time()-filemtime($FileName))<($webdb["cache_time_js"]*60) ){
	$show = file_get_contents($FileName);
	repalce_js_code($show);
	//$show=str_replace(array("\n","\r","'"),array("","","\'"),stripslashes($show));
	if($_GET['iframeID']){	//��ܷ�ʽ����������ҳ����ٶ�,�Ƽ�
		if(!eregi("^([_a-z0-9]+)$",$_GET['id'])){
			die("document.write('iframeID����');");
		}
		//�����������
		if($webdb['cookieDomain']){
			echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
		}
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		parent.document.getElementById('$_GET[iframeID]').innerHTML='$show';
		</SCRIPT>";
	}else{			//JSʽ��������ҳ����ٶ�,���Ƽ�
		echo "document.write('$show');";
	}
	exit;
}

require(dirname(__FILE__)."/"."global.php");
require_once(ROOT_PATH."inc/label_funcation.php");


	$query=$db->query(" SELECT * FROM {$pre}label WHERE lid='$id' ");
	while( $rs=$db->fetch_array($query) ){
		//�����ݿ�ı�ǩ
		if( $rs[typesystem] )
		{
			$_array=unserialize($rs[code]);

			if($_array[SYS]=='artcile'){
				$_array[sql]=preg_replace("/ORDER BY A.aid/is","ORDER BY A.list",$_array[sql]);
				//�ٶ��Ż�����,��ʹ�û���,�ͺ����ٶ�
				//$webdb[label_cache_time]=='-1' || $_array[sql]=preg_replace("/AND R.topic=1/is","",$_array[sql]);				
			}

			$value=($rs[type]=='special')?Get_sp($_array):Get_Title($_array);
			if(strstr($value,"(/mv)")){
				$value=get_label_mv($value);
			}
			if($_array[c_rolltype])
			{
				$value="<marquee direction='$_array[c_rolltype]' scrolldelay='1' scrollamount='1' onmouseout='if(document.all!=null){this.start()}' onmouseover='if(document.all!=null){this.stop()}' height='$_array[roll_height]'>$value</marquee>";
			}
		}
		//�����ǩ
		elseif( $rs[type]=='code' )
		{
			$value=stripslashes($rs[code]);
			//����һ�²�������javascript����,������Ȩ���ж�,��ͨ�û�Ҳ��ɾ��
			if(eregi("<SCRIPT",$value)&&!eregi("<\/SCRIPT",$value)){
				if($delerror){
					$db->query("UPDATE `{$pre}label` SET code='' WHERE lid='$rs[lid]'");
				}else{
					die("<A HREF='$WEBURL?&delerror=1'>�ˡ�{$rs[tag]}����ǩ����,���ɾ��֮!</A><br>$value");
				}			
			}
			//��ʵ��ַ��ԭ
			$value=En_TruePath($value,0);
		}
		//����ͼƬ
		elseif( $rs[type]=='pic' )
		{	
			unset($width,$height);
			$picdb=unserialize($rs[code]);
			
			$picdb[width] && $width=" width='$picdb[width]'";
			$picdb[height] && $height=" height='$picdb[height]'";
			$picdb[imgurl]=En_TruePath($picdb[imgurl],0);
			$picdb[imglink]=En_TruePath($picdb[imglink],0);
			$picdb[imgurl]=tempdir($picdb[imgurl]);
			if($picdb['imglink'])
			{
				$value="<a href='$picdb[imglink]' target=_blank><img src='$picdb[imgurl]' $width $height border='0' /></a>";
			}
			else
			{
				$value="<img src='$picdb[imgurl]' $width $height  border='0' />";
			}
		}
		//����FLASH
		elseif( $rs[type]=='swf' )
		{
			$flashdb=unserialize($rs[code]);
			$flashdb[flashurl]=En_TruePath($flashdb[flashurl],0);
			$flashdb[flashurl]=tempdir($flashdb[flashurl]);
			$flashdb[width] && $width=" width='$flashdb[width]'";
			$flashdb[height] && $height=" height='$flashdb[height]'";
			$value="<object type='application/x-shockwave-flash' data='$flashdb[flashurl]' $width $height wmode='transparent'><param name='movie' value='$flashdb[flashurl]' /><param name='wmode' value='transparent' /></object>";
		}
		//��ͨ�õ�Ƭ
		elseif( $rs[type]=='rollpic' )
		{	
			$detail=unserialize($rs[code]);
			foreach($detail[picurl] AS $key=>$value){
				$detail[picurl][$key]=En_TruePath($value,0);
			}
			foreach($detail[piclink] AS $key=>$value){
				$detail[piclink][$key]=En_TruePath($value,0);
			}
			if($detail[rolltype]==2){	//�Զ�����ʽ
				unset($_listdb);
				foreach($detail[picurl] AS $key=>$value){
					$_listdb[]=array(
						'picurl'=>tempdir($value),
						'link'=>$detail[piclink][$key],
						'url'=>$detail[piclink][$key],
						'title'=>$detail[picalt][$key],
					  );
				}
				$_listdb[0][tpl_1code]=En_TruePath($detail[tplpart_1code],0);
				$value=run_label_php($_listdb);
			}elseif($detail[picalt][1]==''){	//û�б�������
				$value=rollPic_no_title_js($detail);
			}else{
				if($detail[rolltype]==1){
					$value=rollPic_flash($detail);
				}else{
					$value=rollpic_2js($detail);
				}
			}
			
		}
		//������ʽ��
		else
		{
			$value=stripslashes($rs[code]);
			//��ʵ��ַ��ԭ
			$value=En_TruePath($value,0);
		}
	}

$show=stripslashes($value);

if(!is_dir(dirname($FileName))){
	makepath(dirname($FileName));
}
if( (time()-filemtime($FileName))>($webdb["cache_time_js"]*60) ){
	if($webdb["cache_time_js"]!=-1){
		write_file($FileName,$show);
	}
}

repalce_js_code($show);

if($iframeID){	//��ܷ�ʽ����������ҳ����ٶ�,�Ƽ�
	//�����������
	if($webdb[cookieDomain]){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
	parent.document.getElementById('$iframeID').innerHTML='$show';
	</SCRIPT>";
}else{			//JSʽ��������ҳ����ٶ�,���Ƽ�
	echo "document.write('$show');";
}

function repalce_js_code(&$show){
	//��javascript�����ر���
	if(eregi("<SCRIPT",$show)){
		preg_match_all("/<SCRIPT([^>]*)>(.*?)<\/SCRIPT>/is",$show,$array);
		foreach($array[1] AS $key=>$value){
			//һ�����˹�������������
			if(eregi("src=",$value)){
				$value=str_replace("'","\'",$value);
				echo "document.write('<SCRIPT$value><\/SCRIPT>');";
			}else{
				echo $array[2][$key];
			}
		}
		$show=preg_replace("/(.*?)<SCRIPT([^>]*)>(.*?)<\/SCRIPT>(.*?)/is","\\1\\4",$show);
	}
	
	$show=str_replace(array("\r","\n","'"),array("","","\'"),$show);
}

?>