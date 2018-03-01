<?php 

$defaultstyle0[banner][img]="$WebStyleurl/images/default/banner.png";
$defaultstyle0[banner][title]='头部背景图片';
$defaultstyle0[banner][url]="#";

$defaultstyle0[menus]=$defaultmenu;

$defaultconfig0 = addslashes(serialize($defaultstyle0));

$checkstyle=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='default'");
if(!$checkstyle){
	$db->query("INSERT INTO  `{$_pre}style` (`uid`,`type`,`stylename`,`config`) VALUES ('0','1','default','$defaultconfig0')");
}elseif($defaultconfig0!=$checkstyle[config]){
	$db->query("UPDATE `{$_pre}style` SET config='$defaultconfig0' WHERE uid='0' AND type='1' AND stylename='default'");
}

$quit_setstyle=($action=='setstyle')?"<div class='quit_setstyle'><a href='$WebStyleurl/index.php?uid=$uid'>退出风格设置</a></div>":"";

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
			<div class="head">店铺档案</div>
			<div class="cont">
				<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
				<div class="title">$companydb[title]</div>
				<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
				<dl class="other">
					<dt>通行证：$companydb[username]</dt>
					<dd>登记时间：$companydb[posttime]</dd>
				</dl>
			</div>
		</div>
		<div class="AddFavorite">
			<span onclick="AddFavorite(window.location,document.title)"><em>收藏本店</em></span>
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
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//-->
</SCRIPT>
		</div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>发送站内信息</span></a></div>
		<div class="tjInfo">
			<div class="head">统计信息</div>
			<ul>
				<li>访客留言共:<span>{$guestbookNUM}</span>条</li>
				<li>页面点击量:<span>{$companydb[hits]}</span>次</li>
			</ul>
		</div>
	</div>
	<div class="RightContainers">
		<div class="RightMainBox">
			<div class="head"><span class="tag">商家简介<em>Business profile</em></span></div>
			<div class="cont">
				<div class="abouttitle">{$companydb[title]}概况</div>
				<div class="aboutcontent">
					$companydb[content]
					<div class="showmore"><a href='about.php?uid=$uid'>【查看更多】</a></div>
				</div>
			</div>
		</div>
		<div class="RightMainBox">
			<div class="head"><span class="tag">关于我们<em>About us</em></span></div>
			<div class="cont">
				<dl class="aboutUs">
					<dt>
						<ul>
							<li class="sort">主营分类：$companydb[fname]</li>
							<li class="ser">主营产品或服务：$companydb[qy_pro_ser]</li>
							<li class="qq">QQ：$companydb[qq]</li>
							<li class="address">联系地址：$companydb[qy_address]</li>
						</ul>
					</dt>
					<dd>
						<ul>
							<ol>【扫一扫 了解更多】</ol>
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
			<div class="head"><span class="tag">地图位置<em>Map location</em></span></div>
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