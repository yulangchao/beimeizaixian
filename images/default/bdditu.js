var markerA = new Array();
function initialize(){	
	var lng=position_array[0],lat=position_array[1];
	map = new BMap.Map("map_canvas"); 
	if (lng!="" && lat!=""){
		map.centerAndZoom(new BMap.Point(lng, lat), 11);	//后台标注了相应城市的位置
	}else{
		map.centerAndZoom(querycity,11);	//如果后台那里标注的话，就定位到城市
	}
	for(var i=0;i<LatLngArray.length;i++){
		creatMark(i);
	}
	map.enableScrollWheelZoom();    //启用滚轮放大缩小，默认禁用
	map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
	map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
}

function creatMark(i){
	ar = LatLngArray[i].split(",");
	var icon = new BMap.Icon("http://www.google.cn/mapfiles/marker"+String.fromCharCode("A".charCodeAt(0) + i)+".png", new BMap.Size(20, 32), {anchor: new BMap.Size(10, 30)});
	markerA[i] = new BMap.Marker(new BMap.Point(parseFloat(ar[0]),parseFloat(ar[1])), {icon: icon});
	map.addOverlay(markerA[i]);
	markerA[i].addEventListener("click", function(){this.openInfoWindow(new BMap.InfoWindow(pintMsg[i]));});
}

function showShop(i){//点周相应的链接显示对应的内容
	markerA[i].openInfoWindow(new BMap.InfoWindow(pintMsg[i]));
}