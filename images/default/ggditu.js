var infowindow;

function initialize() {
	
	map = new google.maps.Map(document.getElementById("map_canvas"), {scaleControl: true,scrollwheel:true});
	map.setZoom(11);
	map.setMapTypeId(google.maps.MapTypeId.ROADMAP);	
	
	var mapLat=parseFloat(position_array[0]),mapLng=parseFloat(position_array[1]);
	if( isNaN(mapLat) || isNaN(mapLng) ){
		codeAddress();
	}else{
		map.setCenter(new google.maps.LatLng(mapLat,mapLng));
	}

	infowindow = new google.maps.InfoWindow();
	for(i=0;i<LatLngArray.length;i++){
		creatMark(i);
	}
}

function creatMark(i){
	ar = LatLngArray[i].split(",");
	var mapLatLng = new google.maps.LatLng(parseFloat(ar[0]),parseFloat(ar[1]));	
	var newMarker = new google.maps.Marker({map: map,position:mapLatLng});
	newMarker.setIcon("http://www.google.cn/mapfiles/marker"+String.fromCharCode("A".charCodeAt(0) + i)+".png");
	//var infowindow = new google.maps.InfoWindow();	
	google.maps.event.addListener(newMarker, 'click', function() {
		infowindow.setContent(pintMsg[i]);
		infowindow.open(map, this);
	});
}

function codeAddress() {
	var address = querycity;
	var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			//new google.maps.Marker({map: map, position: results[0].geometry.location});
		} else {
			map.setZoom(5);
			map.setCenter(new google.maps.LatLng(39.89979413273051,116.35774612426758));
		}
	});
}

function showShop(i){
	ar=LatLngArray[i].split(",");
	var point = new google.maps.LatLng(parseFloat(ar[0]),parseFloat(ar[1]));
	map.setCenter(point);
	//creatMark(i);
	infowindow.setPosition(point);
	infowindow.setContent(pintMsg[i]);	
	infowindow.open(map);
}