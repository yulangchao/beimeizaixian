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
			<div class="head"><span class="tag">��ϵ����<em>Contact Us</em></span></div>
			<div class="cont">
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