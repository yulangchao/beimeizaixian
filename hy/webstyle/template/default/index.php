<?php 

$defaultstyle0[banner][img]="$WebStyleurl/images/default/banner.png";
$defaultstyle0[banner][title]='ͷ������ͼƬ';
$defaultstyle0[banner][url]="#";

$defaultstyle0[menus]=$defaultmenu;

$defaultconfig0 = addslashes(serialize($defaultstyle0));

$checkstyle=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='default'");
if(!$checkstyle){
	$db->query("INSERT INTO  `{$_pre}style` (`uid`,`type`,`stylename`,`config`) VALUES ('0','1','default','$defaultconfig0')");
}elseif($defaultconfig0!=$checkstyle[config]){
	$db->query("UPDATE `{$_pre}style` SET config='$defaultconfig0' WHERE uid='0' AND type='1' AND stylename='default'");
}

$quit_setstyle=($action=='setstyle')?"<div class='quit_setstyle'><a href='$WebStyleurl/index.php?uid=$uid'>�˳��������</a></div>":"";

$companydb[picurl]=$companydb[picurl]?tempdir($companydb[picurl]):"$webdb[www_url]/images/default/nopic.jpg";
$companydb[posttime]=date("Y-m-d",$companydb[posttime]);

$companydb[content] = En_TruePath($companydb[content],0,1);
$companydb[content]=@preg_replace('/<([^>]*)>/is',"",$companydb[content]);
$companydb[content]=get_word($companydb[content],200);

$thiswxurl = urlencode("$webdb[www_url]/hy/waphomepage.php?uid=$uid");

require(style_html("head")); 

?>
<!--
<?php
print <<<EOT
-->
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/base.css">
<div class="MainContainers">
	<div class="LeftContainers">
		<div class="companyBase">
			<div class="head">���̵���</div>
			<div class="cont">
				<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
				<div class="title">$companydb[title]</div>
				<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
				<dl class="other">
					<dt>ͨ��֤��$companydb[username]</dt>
					<dd>�Ǽ�ʱ�䣺$companydb[posttime]</dd>
				</dl>
			</div>
		</div>
		<div class="AddFavorite">
			<span onclick="AddFavorite(window.location,document.title)"><em>�ղر���</em></span>
<SCRIPT LANGUAGE="JavaScript">
<!--
function AddFavorite(sURL, sTitle){
    try{
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e){
        try{
            window.sidebar.addPanel(sTitle, sURL, "");
        }
        catch (e){
            alert("�����ղ�ʧ�ܣ���ʹ��Ctrl+D�������");
        }
    }
}
//-->
</SCRIPT>
		</div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>����վ����Ϣ</span></a></div>
		<div class="tjInfo">
			<div class="head">ͳ����Ϣ</div>
			<ul>
				<li>�ÿ����Թ�:<span>{$guestbookNUM}</span>��</li>
				<li>ҳ������:<span>{$companydb[hits]}</span>��</li>
			</ul>
		</div>
	</div>
	<div class="RightContainers">
		<div class="RightMainBox">
			<div class="head"><span class="tag">�̼Ҽ��<em>Business profile</em></span></div>
			<div class="cont">
				<div class="abouttitle">{$companydb[title]}�ſ�</div>
				<div class="aboutcontent">
					$companydb[content]
					<div class="showmore"><a href='about.php?uid=$uid'>���鿴���ࡿ</a></div>
				</div>
			</div>
		</div>
		<div class="RightMainBox">
			<div class="head"><span class="tag">��������<em>About us</em></span></div>
			<div class="cont">
				<dl class="aboutUs">
					<dt>
						<ul>
							<li class="sort">��Ӫ���ࣺ$companydb[fname]</li>
							<li class="ser">��Ӫ��Ʒ�����$companydb[qy_pro_ser]</li>
							<li class="qq">QQ��$companydb[qq]</li>
							<li class="address">��ϵ��ַ��$companydb[qy_address]</li>
						</ul>
					</dt>
					<dd>
						<ul>
							<ol>��ɨһɨ �˽���ࡿ</ol>
							<li><img src="$webdb[www_url]/do/2codeimg.php?url=$thiswxurl"/></li>
						</ul>
					</dd>
				</dl>	
			</div>
		</div>
<!--
EOT;
if($companydb[gg_maps]){
print <<<EOT
-->
		<div class="RightMainBox">
			<div class="head"><span class="tag">��ͼλ��<em>Map location</em></span></div>
			<div class="cont">
              	<div class="ShowMaps">
					<iframe src="$Mdomain/job.php?job=show_ggmaps&position=$companydb[gg_maps]&title=$companydb[title]" scrolling="no" frameborder="0" ></iframe>
				</div>
			</div>
		</div>
<!--
EOT;
}
print <<<EOT
-->	
	</div>
</div>
$quit_setstyle
<!--
EOT;
?>
-->
<?php require(style_html("foot")); ?>