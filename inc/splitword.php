<?php
defined('ROOT_PATH') or die();

function Load_Split_Word_array(){
	global $xingDB,$xing2DB,$CiYuDB;
	$xing="����¹�����ϰǮ��ܳ�������֣�������������������������ʩ�ſ��ϻ���κ�ս���л������ˮ������˸��ɷ�����³Τ������ﻨ������Ԭ��ۺ��ʷ�Ʒ����Ѧ�׺����������ޱϺ�����������ʱ��Ƥ�����ڶ�Ź�����εԽ��¡ʦ��������ť�������ϻ���½��������춻���κ�ӷ����ഢ���������ɾ��θ���������Ԫ������ƽ������Ҧ�ۿ�����ë����ױ���갼Ʒ��ɴ�̸��é���ܼ�������ף������������ϯ����ǿ��·¦Σ��ͯ��÷ʢ�ֵ�����������Ĳ��﷮���������֧�¾̹�¬Ī�������Ѹɽ�Ӧ�������������θ����߾Ӻⲽ�������빴�������������Ǽ��ڵ��������������ʯ�޼�����Ŀܹ�»��������ȫۭ�����������������ﱩ�����������������ղ����Ҷ��˾��۬�輻��ӡ�ް׻���̨�Ӷ����̼���׿�����ɳ����������ܲ�˫��ݷ����̷�����̼������Ƚ��۪Ӻ�S�ɣ���ţ��ͨ�����༽ۣ����ũ�±�ׯ�̲���ֳ�Ľ����°���������Ŀ���ɳ��ᳲ���������������ڽ���";
	for($i=0;$i<strlen($xing);$i++){
		$xingDB[$xing[$i].$xing[$i+1]] = 1;
  		$i++;
  	}
  	$xing2s = explode(",","ŷ��,�Ϲ�,��ľ,�Ϲ�,����,��ԯ,���,����,����,����,����,����,˾ͽ,˾��,����,����,����,����,����,����,Ľ��,˾��,�ĺ�,���,����,����,�ʸ�,ξ��,����");
  	foreach($xing2s AS $name){
		$xing2DB[$name] = 1;
	}

  	$filename = ROOT_PATH."inc/ciyu.dat"; 
  	if($handle=fopen($filename,"rb")){
		flock($handle,LOCK_SH);
		$filedata=fread($handle,filesize($filename));
		fclose($handle);
		$array=explode("\r\n",$filedata);
		$ss="<?php ";
		foreach($array AS $key=>$value){
			$value && $CiYuDB[$value]=1;
		}
	}
}
  
function splitword($str=""){
	if($str!=""){
		if(WEB_LANG=='utf-8'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("UTF8","GB2312",$str,ROOT_PATH."./inc/gbkcode/");
			$str = $cnvert->ConvertIT();
		}elseif(WEB_LANG=='big5'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("BIG5","GB2312",$str,ROOT_PATH."./inc/gbkcode/");
			$str = $cnvert->ConvertIT();
		}
		$Source_String = trim(Split_GetString(trim($str)));
	}
  	if($Source_String==""){
		return "";
	}
  	$Source_String = Split_GetString($Source_String);
  	$spwords = explode(" ",$Source_String);
  	$spLen = count($spwords);
  	$space = ' ';
  	for($i=($spLen-1);$i>=0;$i--){
  		if(trim($spwords[$i])==""){
			continue;
		}
  		if(ord($spwords[$i][0])<0x80){
  			if(ereg("[^0-9\.\+\-]",$spwords[$i])){
				$Result_String = $spwords[$i].$space.$Result_String;
			}else{
  				$nextword = "";
  				@$nextword = substr($Result_String,0,strpos($Result_String," "));
  				if(ereg("^��|��|��|ʱ|��|��|��|Ԫ|��|ǧ|��|��|λ|��|ֻ|ƪ",$nextword)){
  					$Result_String = $spwords[$i].$Result_String;
  				}else{
  					$Result_String = $spwords[$i].$space.$Result_String;
  				}
  			}
  		}else{
			$c = $spwords[$i][0].$spwords[$i][1];
			$n = hexdec(bin2hex($c));
			if($c=="��"){
				$Result_String = $spwords[$i].$space.$Result_String;
			}elseif($n>0xA13F && $n < 0xAA40){ //������
				$Result_String = $spwords[$i].$space.$Result_String;
			}else{ //�����̾�
				if(strlen($spwords[$i]) <= 4){
					if(ereg("��|��|��$",$spwords[$i],$regs)){
						$spwords[$i] = ereg_replace($regs[0]."$","",$spwords[$i]).$space.$regs[0];
					}
  		  			if(!ereg("^��|��|��|ʱ|��|��|��|Ԫ|��|ǧ|��|��|λ|��|ֻ|ƪ",$spwords[$i]) || $i==0){
  		  				$Result_String = $spwords[$i].$space.$Result_String;
  		  			}else{
  		  				$Result_String = $spwords[$i-1].$spwords[$i].$space.$Result_String; 
  		  				$i--;
  		  			}
  		  		}else{
  		  			$Result_String = Split_getwords($spwords[$i]).$space.$Result_String;
  		  		}
			}
		}
	}
	
	if(WEB_LANG=='utf-8'){
		require_once(ROOT_PATH."inc/class.chinese.php");
		$cnvert = new Chinese("GB2312","UTF8",$Result_String,ROOT_PATH."./inc/gbkcode/");
		$Result_String = $cnvert->ConvertIT();
	}elseif(WEB_LANG=='big5'){
		require_once(ROOT_PATH."inc/class.chinese.php");
		$cnvert = new Chinese("GB2312","BIG5",$Result_String,ROOT_PATH."./inc/gbkcode/");
		$Result_String = $cnvert->ConvertIT();
	}
	

	return $Result_String;
}

function Split_getwords($str){
	global $CiYuDB;
  	$space = ' ';
  	$spLen = strlen($str);
  	$WordArray = Array();
  	//���������ֵ�ƥ��
  	for($i=($spLen-1);$i>=0;){
  		//��i�ﵽ��С���ܴʵ�ʱ��,Ҫ�ر���
		if($i<=3){
  			if($i==1){
				$WordArray[] = substr($str,0,2);
			}else{
				$w = substr($str,0,4);
				if(strlen($w)<13&&$CiYuDB[$w]){
					$WordArray[] = $w;   
  			   }else{
  				   $WordArray[] = substr($str,2,2);
  				   $WordArray[] = substr($str,0,2);
  			   }
			}
			$i = -1;
			break;
  		}
  		//��������С������ʱ�����
  		if($i>=13){
			$maxPos = 13;
		}else{
			$maxPos = $i;
		}
  		$isMatch = false;
  		for($j=$maxPos;$j>=0;$j=$j-2){
			$w = substr($str,$i-$j,$j+1);
			if(strlen($w)<13&&$CiYuDB[$w]){
				$WordArray[] = $w;
  			 	$i = $i-$j-1;
  			 	$isMatch = true;
  			 	break;
			}
		}
  		if(!$isMatch){
			if($i>1) {
				$WordArray[] = $str[$i-1].$str[$i];
  				$i = $i-2;
  			}
  		}
  	}
  	$rsStr = Split_duanluo($WordArray);
  	return $rsStr;
}

function Split_duanluo($WordArray){
	global $xingDB,$xing2DB;
	$CnSgNum = "һ|��|��|��|��|��|��|��|��|ʮ|��|ǧ|��|��|��|��";
  	$wlen = count($WordArray)-1;
  	$space = ' ';
  	for($i=$wlen;$i>=0;$i--){
  		if(ereg($CnSgNum,$WordArray[$i])){
  			$rsStr .= $space.$WordArray[$i];
  			if($i>0 && ereg("^��|��|��|ʱ|��|��|��|Ԫ|��|ǧ|��|��|λ|��|ֻ|ƪ",$WordArray[$i-1])){
				$rsStr .= $WordArray[$i-1];
				$i--;
			}else{
				while($i>0 && ereg($CnSgNum,$WordArray[$i-1])){
					$rsStr .= $WordArray[$i-1]; $i--;
				}
  			}
  			continue;
  		}
  		//˫����
  		if(strlen($WordArray[$i])==4 && isset($xing2DB[$WordArray[$i]])){
  			$rsStr .= $space.$WordArray[$i];
  			if($i>0&&strlen($WordArray[$i-1])==2){
  				$rsStr .= $WordArray[$i-1];
				$i--;
  				if($i>0&&strlen($WordArray[$i-1])==2){
					$rsStr .= $WordArray[$i-1];
					$i--;
				}
  			}
  		//������
  		}elseif(strlen($WordArray[$i])==2 && isset($xingDB[$WordArray[$i]])){
			$rsStr .= $space.$WordArray[$i];
  			if($i>0&&strlen($WordArray[$i-1])==2){
				$rsStr .= $WordArray[$i-1];
				$i--;
				if($i>0 && strlen($WordArray[$i-1])==2){
					$rsStr .= $WordArray[$i-1];
					$i--;
				}
  			}
  		//��ͨ�ʻ�
  		}else{
  			$rsStr .= $space.$WordArray[$i];
  		}
	}
  	//���ر��ηִʽ��
  	$rsStr = preg_replace("/^".$space."/","",$rsStr);
  	return $rsStr;
}

function Split_GetString($str){
  	$space = ' ';
    $slen = strlen($str);
    if($slen==0){
		return '';
	}
    $prechar = 0;	// 0-�հ� 1-Ӣ�� 2-���� 3-����
    for($i=0;$i<$slen;$i++){
		if(ord($str[$i]) < 0x81){
			if(ord($str[$i]) < 33){	//Ӣ�ĵĿհ׷���
				if($prechar!=0&&$str[$i]!="\r"&&$str[$i]!="\n"){
					$strings .= $space;
				}
				$prechar=0;
				continue; 
			}elseif(ereg("[^0-9a-zA-Z@\.%#:/\\&_-]",$str[$i])){
				if($prechar==0){
					$strings .= $str[$i]; $prechar=3;
				}else{
					$strings .= $space.$str[$i]; $prechar=3;}
				}else{
					if($prechar==2||$prechar==3){
						$strings .= $space.$str[$i];
						$prechar=1;
					}else{
						if(ereg("@#%:",$str[$i])){
							$strings .= $str[$i];
							$prechar=3;
						}else{
							$strings .= $str[$i];
							$prechar=1;
						}
					}
				}
			}else{
				//�����һ���ַ�Ϊ�����ĺͷǿո����һ���ո�
				if($prechar!=0 && $prechar!=2){
					$strings .= $space;
				}
				//��������ַ�
				if(isset($str[$i+1])){
					$c = $str[$i].$str[$i+1];
					if(ereg("��|��|��|��|��|��|��|��|��|��|��|��|��|��",$c)){
						$strings .= Split_ReplaceNum($c); 
						$prechar = 2; 
						$i++; 
						continue; 
					}
					$n = hexdec(bin2hex($c));
					if($n>0xA13F && $n < 0xAA40){
						if($c=="��"){
							if($prechar!=0){
								$strings .= $space." ��";
						}else{
							$strings .= " ��";
						}
						$prechar = 2;
					}elseif($c=="��"){
						$strings .= "�� ";
						$prechar = 3;
					}else{
						if($prechar!=0){
							$strings .= $space.$c;
						}else{
							$strings .= $c;
						}
						$prechar = 3; 
					}
				}else{
					$strings .= $c;
					$prechar = 2;
				}
				$i++;
			}
		}
	}
	return $strings;
}


function Split_ReplaceNum($fnum){
	$nums = array("��","��","��","��","��","��","��","��","��","��","��","��","��","��");
	$fnums = "0123456789+-%.";
	for($i=0;$i<count($nums);$i++){
		if($nums[$i]==$fnum){
			return $fnums[$i];
		}
	}
	return $fnum;
}


Load_Split_Word_array();
?>