<?php 
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
			<div class="head"><span class="tag">联系我们<em>Contact Us</em></span></div>
			<div class="cont">
				<dl class="aboutUs">
					<dt>
						<table width="100%" class='contactTable'>
						  <tr>
							<td class='tdL'>单位名称：</td>
							<td colspan="3">$companydb[title]</td>
						  </tr>
						  <tr>
							<td class='tdL'>联 系 人：</td>
							<td class='tdR1'>$companydb[qy_contact]</td>
							<td class='tdL'>电话号码：</td>
							<td class='tdR1'>$companydb[qy_contact_tel]</td>
						  </tr>
						  <tr>
							<td class='tdL'>传真号码：</td>
							<td class='tdR1'>$companydb[qy_contact_fax]</td>
							<td class='tdL'>移动号码：</td>
							<td class='tdR1'>$companydb[qy_contact_mobile]</td>
						  </tr>
						  <tr>
							<td class='tdL'>所在地区：</td>
							<td class='tdR1'>{$area_DB[name][$companydb[province_id]]} {$city_DB[name][$companydb[city_id]]} {$zone_DB[name][$companydb[zone_id]]} {$street_DB[name][$companydb[street_id]]}</td>
							<td class='tdL'>邮箱地址：</td>
							<td class='tdR1'>$companydb[qy_contact_email]</td>
						  </tr>
						  <tr>
							<td class='tdL'>详细地址：</td>
							<td colspan="3">$companydb[qy_address]</td>
						  </tr>
						  <tr>
							<td class='tdL'>Q Q：</td>
							<td class='tdR1'>$companydb[show_qq]</td>
							<td class='tdL'>阿里旺旺：</td>
							<td class='tdR1'>$companydb[show_ww]</td>
						  </tr>
						 </table>
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
              	<div class="ShowMaps ShowMaps1">
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
<!--
EOT;
?>
-->
<?php require(style_html("foot")); ?>