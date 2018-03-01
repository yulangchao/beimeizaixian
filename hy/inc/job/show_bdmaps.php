<?php
if(!function_exists('html')){
	die('F');
}
require_once(dirname(__FILE__)."/googlemap.inc.php");
explain_url($city_id);

$title = filtrate($title);
$cityname || $cityname='����';
$webdb[baidu_map_key] || $webdb[baidu_map_key]='MGdbmO6pP5Eg1hiPhpYB0IVd';
print<<<EOT

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style type="text/css">
body, html,#allmap {width: 100%;height: 100%;overflow: hidden;hidden;margin:0;}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=$webdb[baidu_map_key]"></script>
<title>�ٶȵ�ͼλ����ʾ $titleDB[title]</title>
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
<!--
if('$showDomain'=='1'){
	if('$webdb[cookieDomain]'!='')document.domain = '$webdb[cookieDomain]';
}else{
	window.onerror=function (){
		url = '$WEBURL';
		url +=url.indexOf('?')>0?'&':'?';
		if('$webdb[cookieDomain]'!='')window.location.href=url+'showDomain=1';
		return true;
	};
	obj = (self==top) ? window.opener : window.parent ;
	obj.document.body;
}
//-->
</SCRIPT>

<div id="allmap"></div>

<script type="text/javascript">

// �ٶȵ�ͼAPI���� 113.220243, 28.181133
var map = new BMap.Map("allmap");            // ����Mapʵ��

var lng="$position_x",lat="$position_y";
//var lng="$position_x",lat="$position_y";
var point = new BMap.Point(lng, lat);
map.centerAndZoom(point, 12);


map.enableScrollWheelZoom();    //���ù��ַŴ���С��Ĭ�Ͻ���
map.enableContinuousZoom();    //���õ�ͼ������ק��Ĭ�Ͻ���
//map.addControl(new BMap.NavigationControl());  //���Ĭ������ƽ�ƿؼ�
//map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //���Ͻǣ�������ƽ�ƺ����Ű�ť
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //���½ǣ�������ƽ�ư�ť
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //���½ǣ����������Ű�ť

map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]}));     //2Dͼ������ͼ

map.addControl(new BMap.MapTypeControl({anchor: BMAP_ANCHOR_TOP_LEFT}));    //���Ͻǣ�Ĭ�ϵ�ͼ�ؼ�



var marker1 = new BMap.Marker(point);  // ������ע
map.addOverlay(marker1);              // ����ע��ӵ���ͼ��

//������Ϣ����
var infoWindow1 = new BMap.InfoWindow("$title");
marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
marker1.setAnimation(BMAP_ANIMATION_BOUNCE); //�����Ķ���

 

</script>


</body>
</html>

EOT;

?>