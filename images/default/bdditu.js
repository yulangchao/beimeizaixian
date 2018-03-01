var markerA = new Array();
function initialize(){	
	var lng=position_array[0],lat=position_array[1];
	map = new BMap.Map("map_canvas"); 
	if (lng!="" && lat!=""){
		map.centerAndZoom(new BMap.Point(lng, lat), 11);	//��̨��ע����Ӧ���е�λ��
	}else{
		map.centerAndZoom(querycity,11);	//�����̨�����ע�Ļ����Ͷ�λ������
	}
	for(var i=0;i<LatLngArray.length;i++){
		creatMark(i);
	}
	map.enableScrollWheelZoom();    //���ù��ַŴ���С��Ĭ�Ͻ���
	map.enableContinuousZoom();    //���õ�ͼ������ק��Ĭ�Ͻ���
	map.addControl(new BMap.NavigationControl());  //���Ĭ������ƽ�ƿؼ�
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //���Ͻǣ�������ƽ�ƺ����Ű�ť
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //���½ǣ�������ƽ�ư�ť
	map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //���½ǣ����������Ű�ť
}

function creatMark(i){
	ar = LatLngArray[i].split(",");
	var icon = new BMap.Icon("http://www.google.cn/mapfiles/marker"+String.fromCharCode("A".charCodeAt(0) + i)+".png", new BMap.Size(20, 32), {anchor: new BMap.Size(10, 30)});
	markerA[i] = new BMap.Marker(new BMap.Point(parseFloat(ar[0]),parseFloat(ar[1])), {icon: icon});
	map.addOverlay(markerA[i]);
	markerA[i].addEventListener("click", function(){this.openInfoWindow(new BMap.InfoWindow(pintMsg[i]));});
}

function showShop(i){//������Ӧ��������ʾ��Ӧ������
	markerA[i].openInfoWindow(new BMap.InfoWindow(pintMsg[i]));
}