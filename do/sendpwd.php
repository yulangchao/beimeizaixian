<?php
require_once("global.php");

if($action=='send')
{
	$rs = $userDB->get_allInfo($atc_username,'name');
	if(!$rs){
		showerr("�ʺ�����,������");
	}elseif(!$rs[email]){
		showerr("��ǰ�ʺ�û����������,����ϵͳ����Ա�����޸�����!");
	}elseif($rs[groupid]==3){
		showerr('Ϊ��ȫ���������Ա���벻��ͨ�������ȡ������ϵվ���޸ģ�');
	}
	if(!$webdb[mymd5])
	{
		$webdb[mymd5]=rands(10);
		$db->query("REPLACE INTO {$pre}config (`c_key`,`c_value`) VALUES ('mymd5','$webdb[mymd5]')");
		write_file(ROOT_PATH."data{$webdb[web_dir]}/config.php","\$webdb['mymd5']='$webdb[mymd5]';",'a');
	}
	$newpwd=strtolower(rands(8));
	$md5_id=str_replace('+','%2B',mymd5("{$rs[username]}\t{$rs[password]}\t$newpwd",'EN',md5(ROOT_PATH)));
	$Title="���ԡ�{$webdb[webname]}�����ʼ�,ȡ������!!";
	$Content="���ڡ�{$webdb[webname]}�����ʺ��ǡ�{$rs[$TB[username]]}��,����������ǣ���{$newpwd}��,������������ַ,����������,��������,�ſ�����Ч��<br><br><A HREF='$webdb[www_url]/do/sendpwd.php?job=getpwd&md5_id=$md5_id' target='_blank'>$webdb[www_url]/do/sendpwd.php?job=getpwd&md5_id=$md5_id</A>";


	$succeeNUM = send_mail($rs[email],$Title,$Content,$ifcheck=1);
	if($succeeNUM)
	{
		refreshto("../","�������Ѿ��ɹ����͵��������:��{$rs[email]}������ע�����!",5);
	}
	else
	{
		showerr("�ʼ�����ʧ�ܣ����������������,�����Ƿ����������ʼ����������⣡��");
	}
}
elseif($job=='getpwd')
{
	if(strstr($FROMURL,"$webdb[www_url]/")){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/'>";
		exit;
	}
	list($username,$password,$newpassword)=explode("\t",mymd5($md5_id,'DE',md5(ROOT_PATH)));
	$username = filtrate($username);
	$newpassword = filtrate($newpassword);
	$rs = $userDB->get_allInfo($username,'name');
	if($rs[groupid]==3){
		showerr('Ϊ��ȫ���������Ա���벻��ͨ�������ȡ������ϵվ���޸ģ�');
	}
	if($rs && $rs[password]==$password)
	{
		$userDB->edit_user( array('password'=>$newpassword,'username'=>$username) );
		refreshto("login.php","��ϲ�㣬�����뼤��ɹ����뾡���¼�޸�����!",10);
	}
	else
	{
		if($lfjid){
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=../'>";
			exit;
		}
		refreshto("$webdb[www_url]/","�����뼤��ʧ��!",1);
	}
}

require(ROOT_PATH."inc/head.php");
require(html("sendpwd"));
require(ROOT_PATH."inc/foot.php");
?>