<?php 

unset($listdb);
$rows=15;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid' AND yz='1'";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}house_content $where ORDER BY posttime DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$listHr="<ul class='listHr listHrH'><li class='li1'>��Ϣ����</li>\r\n<li class='li2'>������Ŀ</li>\r\n<li class='li3' style='text-align:center;'>����ʱ��</li>\r\n</ul>\r\n";
while($rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$listHr.="<ul class='listHr listHrC'><li class='li1'><a href='/house/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></li>\r\n<li class='li2'><a href='/house/list.php?fid=$rs[fid]' target='_blank'>$rs[fname]</a></li>\r\n<li class='li3'>$rs[posttime]</li>\r\n</ul>\r\n";
}

$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);

$listHr.="<div class='ShowPage'>$showpage</div>\r\n";

if($showtype=='moreshow'){
	die($listHr);
}

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
			<div class="head"><span class="tag">������Ϣ<em>&nbsp;</em></span></div>
			<div class="cont">
				<div class="ListShops">
$listHr
				</div>				
			</div>
		</div>
	</div>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function showHyNews(url){
   $.get(url+"&"+Math.random(),function(d){
		$('.ListShops').html(d);
	});
}
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php require(style_html("foot"));  ?>