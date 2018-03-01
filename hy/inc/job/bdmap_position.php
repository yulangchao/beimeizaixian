<?php
if(!function_exists('html')){
	die('F');
}

$position || $position=$city_DB['maps'][$cityid];
list($position_x,$position_y)=explode(',',filtrate($position));
$cityname=$city_DB['name'][$cityid];

$cityname || $cityname='����';
$webdb[baidu_map_key] || $webdb[baidu_map_key]='MGdbmO6pP5Eg1hiPhpYB0IVd';

$java_show='';
if($getlocal==1){
	$cityname='����';
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
<title>�ٶȵ�ͼλ�ö�λ $titleDB[title]</title>
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
<!--
if('$showDomain'=='1'){
	if('$webdb[cookieDomain]'!='')document.domain = '$webdb[cookieDomain]';
}else{
	window.onerror=function (sMessage,sUrl,sLine){
		if(sLine==0||sLine==1){return true;}//������������ʱ�ȸ���������ش������1�������������0
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
<div style="margin:10px;width:100%;">����˵��:��굥��ѡ�е�λ��,�����һ��Сͼ��,�ٵ��Сͼ�꼴��ѡ��λ��(Ҳ�����϶�Сͼ�궨�����׼��λ��)!
<!--
EOT;
if($getlocal!=1){print<<<EOT
-->
<br>
ע�⣺�����ͼ��һƬ��ɫ��<a href="$WEBURL&getlocal=1&$timestamp">�������ص���ǰ����
<!--
EOT;
}
print <<<EOT
-->
</a>
</div>
<div id="allmap"></div>

<script type="text/javascript">

// �ٶȵ�ͼAPI���� 113.220243, 28.181133
var map = new BMap.Map("allmap");            // ����Mapʵ��
var point,marker;
var lng="$position_x",lat="$position_y";
if (lng!="" && lat!=""){
	map.centerAndZoom(new BMap.Point(lng, lat), 12);
	set_marker();
}else{
	map.centerAndZoom("$cityname",12);                   // ��ʼ����ͼ,���ó��к͵�ͼ����
}

var gc = new BMap.Geocoder();

map.enableScrollWheelZoom();    //���ù��ַŴ���С��Ĭ�Ͻ���
map.enableContinuousZoom();    //���õ�ͼ������ק��Ĭ�Ͻ���
map.addControl(new BMap.NavigationControl());  //���Ĭ������ƽ�ƿؼ�
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //���Ͻǣ�������ƽ�ƺ����Ű�ť
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //���½ǣ�������ƽ�ư�ť
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //���½ǣ����������Ű�ť



map.addEventListener("click",function(e){

	//check_error_point(e);	//���λ���Ƿ�����

	map.removeOverlay(marker);	//�Ƴ�������
	lng=e.point.lng;
	lat=e.point.lat;
	set_marker();
});


function postpoint(){
	 if(confirm("��ȷ��ѡ��ǰλ����?")){
		 window.opener.document.getElementById('mapid').value=lng+','+lat;
		 window.close();
	 }else{
	 }
}

function set_marker(){
	marker = new BMap.Marker( new BMap.Point(lng, lat) );  // ������ע
	marker.addEventListener("click", function(){    
		postpoint();	//�ύ�õ�������
	});
	marker.enableDragging();    
	marker.addEventListener("dragend", function(e){
		lng=e.point.lng;
		lat=e.point.lat;
		postpoint();	//�ύ�õ�������
	})
	map.addOverlay(marker);              // ����ע��ӵ���ͼ��
	//marker.setAnimation(BMAP_ANIMATION_BOUNCE); //�����Ķ���
}

function check_error_point(e){
    gc.getLocation(e.point, function(rs){
        var addComp = rs.addressComponents;
        //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
		if(addComp.province==""){
			if( confirm("��ǰλ�������Ƿ����¶��������ڵ�����") ){
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