<?php
if(!function_exists('html')){
	die('F');
}
require_once(dirname(__FILE__)."/googlemap.inc.php");
explain_url($city_id);

$title = filtrate($title);
$cityname || $cityname='北京';
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
<title>百度地图位置显示 $titleDB[title]</title>
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

// 百度地图API功能 113.220243, 28.181133
var map = new BMap.Map("allmap");            // 创建Map实例

var lng="$position_x",lat="$position_y";
//var lng="$position_x",lat="$position_y";
var point = new BMap.Point(lng, lat);
map.centerAndZoom(point, 12);


map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
//map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
//map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮

map.addControl(new BMap.MapTypeControl({mapTypes: [BMAP_NORMAL_MAP,BMAP_HYBRID_MAP]}));     //2D图，卫星图

map.addControl(new BMap.MapTypeControl({anchor: BMAP_ANCHOR_TOP_LEFT}));    //左上角，默认地图控件



var marker1 = new BMap.Marker(point);  // 创建标注
map.addOverlay(marker1);              // 将标注添加到地图中

//创建信息窗口
var infoWindow1 = new BMap.InfoWindow("$title");
marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
marker1.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

 

</script>


</body>
</html>

EOT;

?>