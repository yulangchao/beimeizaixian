<?php 
$thiswxurl = urlencode("$webdb[www_url]/hy/waphomepage.php?uid=$uid");
require(style_html("head"));
?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/style1/contact.css">
<div class='MainContainer'>
	<div class='hyOtherInfo'>
		<div onclick="AddFavorite(window.location,document.title)" class='Favorite'><span>�ղر�����</span></div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>����վ����Ϣ</span></a></div>
		<ul class='tjInfo'>
			<li>�ÿ����Թ�:<span>{$guestbookNUM}</span>��</li>
			<li>ҳ������:<em>{$companydb[hits]}</em>��</li>
		</ul>
	</div>
	<div class='Hydianping'>
		<div class="head"><span class="tag">��ϵ����<em>Contact Us</em></span></div>
		<dl class="aboutUs">
			<dt>
				<table width="100%" class='contactTable'>
				  <tr>
					<td class='tdL'>��λ���ƣ�</td>
					<td colspan="3">$companydb[title]</td>
				  </tr>
				  <tr>
					<td class='tdL'>�� ϵ �ˣ�</td>
					<td class='tdR1'>$companydb[qy_contact]</td>
					<td class='tdL'>�绰���룺</td>
					<td class='tdR1'>$companydb[qy_contact_tel]</td>
				  </tr>
				  <tr>
					<td class='tdL'>������룺</td>
					<td class='tdR1'>$companydb[qy_contact_fax]</td>
					<td class='tdL'>�ƶ����룺</td>
					<td class='tdR1'>$companydb[qy_contact_mobile]</td>
				  </tr>
				  <tr>
					<td class='tdL'>���ڵ�����</td>
					<td class='tdR1'>{$area_DB[name][$companydb[province_id]]} {$city_DB[name][$companydb[city_id]]} {$zone_DB[name][$companydb[zone_id]]} {$street_DB[name][$companydb[street_id]]}</td>
					<td class='tdL'>�����ַ��</td>
					<td class='tdR1'>$companydb[qy_contact_email]</td>
				  </tr>
				  <tr>
					<td class='tdL'>��ϸ��ַ��</td>
					<td colspan="3">$companydb[qy_address]</td>
				  </tr>
				  <tr>
					<td class='tdL'>Q Q��</td>
					<td class='tdR1'>$companydb[show_qq]</td>
					<td class='tdL'>����������</td>
					<td class='tdR1'>$companydb[show_ww]</td>
				  </tr>
				 </table>
			</dt>
			<dd>
				<ul>
					<ol>��ɨһɨ �˽���ࡿ</ol>
					<li><img src="$webdb[www_url]/do/2codeimg.php?url=$thiswxurl"/></li>
				</ul>
			</dd>
		</dl>
<!--
EOT;
if($companydb[gg_maps]){
print <<<EOT
-->
		<div class="RightMainBox">
			<div class="head"><span class="tag">��ͼλ��<em>Map location</em></span></div>
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