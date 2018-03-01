<?php
if(!function_exists('html')){
	die('F');
}

$position || $position=$city_DB['maps'][$cityid];
list($position_x,$position_y)=explode(',',filtrate($position));
$cityname=$city_DB['name'][$cityid];

$cityname || $cityname='北京';
$webdb[baidu_map_key] || $webdb[baidu_map_key]='MGdbmO6pP5Eg1hiPhpYB0IVd';

$java_show='';
if($getlocal==1){
	$cityname='北京';
	unset($position_x,$position_y);
	$java_show = "gotomycity();";
}
print<<<EOT

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=$webdb[baidu_map_key]"></script>
<title>百度地图位置定位 $titleDB[title]</title>
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
<!--
if('$showDomain'=='1'){
	if('$webdb[cookieDomain]'!='')document.domain = '$webdb[cookieDomain]';
}else{
	window.onerror=function (sMessage,sUrl,sLine){
		if(sLine==0||sLine==1){return true;}//当地名不存在时谷歌浏览器返回错误代码1，其它浏览器是0
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
<div style="margin:10px;width:100%;">帮助说明:鼠标单击选中的位置,会产生一个小图标,再点击小图标即可选中位置(也可以拖动小图标定义更精准的位置)!
<!--
EOT;
if($getlocal!=1){print<<<EOT
-->
<br>
注意：如果地图是一片灰色，<a href="$WEBURL&getlocal=1&$timestamp">请点击返回到当前城市
<!--
EOT;
}
print <<<EOT
-->
</a>
</div>
<div id="allmap"></div>

<script type="text/javascript">

// 百度地图API功能 113.220243, 28.181133
var map = new BMap.Map("allmap");            // 创建Map实例
var point,marker;
var lng="$position_x",lat="$position_y";
if (lng!="" && lat!=""){
	map.centerAndZoom(new BMap.Point(lng, lat), 12);
	set_marker();
}else{
	map.centerAndZoom("$cityname",12);                   // 初始化地图,设置城市和地图级别。
}

var gc = new BMap.Geocoder();

map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮



map.addEventListener("click",function(e){

	//check_error_point(e);	//检查位置是否有误！

	map.removeOverlay(marker);	//移除旧坐标
	lng=e.point.lng;
	lat=e.point.lat;
	set_marker();
});


function postpoint(){
	 if(confirm("你确认选择当前位置吗?")){
		 window.opener.document.getElementById('mapid').value=lng+','+lat;
		 window.close();
	 }else{
	 }
}

function set_marker(){
	marker = new BMap.Marker( new BMap.Point(lng, lat) );  // 创建标注
	marker.addEventListener("click", function(){    
		postpoint();	//提交得到的坐标
	});
	marker.enableDragging();    
	marker.addEventListener("dragend", function(e){
		lng=e.point.lng;
		lat=e.point.lat;
		postpoint();	//提交得到的坐标
	})
	map.addOverlay(marker);              // 将标注添加到地图中
	//marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
}

function check_error_point(e){
    gc.getLocation(e.point, function(rs){
        var addComp = rs.addressComponents;
        //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
		if(addComp.province==""){
			if( confirm("当前位置有误，是否重新定向到你所在地区！") ){
				var myCity = new BMap.LocalCity();
				myCity.get(function(result){
					var cityName = result.name;
					map.centerAndZoom(cityName,12);
				});
			}			
		}
    });
}


function myFun(result){
		var mycityName = result.name;
		map.setCenter(mycityName);
}
function gotomycity(){
		var my_City = new BMap.LocalCity();
		my_City.get(myFun);
}
$java_show;

</script>


 
</body>
</html>

EOT;



?>