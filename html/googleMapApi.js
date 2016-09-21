var map;
var markers = [];
var circles = [];
var labels = '1234567890';
//var labels = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz~!@#$%^&*';
var labelIndex = 0;
var timers = [];
//var eqkMapList = [];

// api로부터 데이터를 가져온 뒤, 성공시 마커를 만든다.
var eqkMapList = [
	{
		"num": 189,
		"tmEqk": "20160921115354",
		"lat": 35.75,
		"lon": 129.18,
		"mt": 3.5,
	}];
	
function convertTimeFormat(str){
	var result = "";
	result += str.substr(0, 4) + ".";	// year
	result += str.substr(4, 2) + ".";	// month
	result += str.substr(6, 2) + ". ";	// date
	result += str.substr(8, 2) + ":";	// hour
	result += str.substr(10, 2) + ":";	// minute
	result += str.substr(12, 2);		// second
	return result;
}

function leftPad(num){
	switch(num.toString().length){
		case 3: break;
		case 2: num = '0' + num; break;
		case 1: num = '00' + num; break;
		default: break;
	}
	return num;
}

function initListboxItem(){
	var listbox = document.getElementById("eqkListbox");
	var index;
	var latlng;
	var mt;
	var tmEqk;
	
	$("#eqkListbox").empty();
	$("#eqkListbox").append("<option disabled>[Num][mgtd] [ co-ordinate ] [yyyy.mm.dd. hh:mm:ss]</option>");
	
	
	for(var i=0; i < eqkMapList.length; i++){
		index = "[" + leftPad(eqkMapList[i]['num']) + "]";
		latlng = "[" + eqkMapList[i]['lat'].toFixed(2) + ", " + eqkMapList[i]['lon'].toFixed(2) + "]";
		mt = "[M" + eqkMapList[i]['mt'].toFixed(1) + "]";
		tmEqk = "[" + convertTimeFormat(eqkMapList[i]['tmEqk']) + "]";
		$("#eqkListbox").append("<option value='" + i + "'>" + index + " " +  mt + " " + latlng + " " + tmEqk + "</option>");
	}	
}

////////////////////////////////////////////// Google Map API
function initMap() {
	
	var myLatlng = new google.maps.LatLng(eqkMapList[0]['lat'], eqkMapList[0]['lon']);
	map = new google.maps.Map(document.getElementById('map'), {
		center: myLatlng,
		zoom: 8
	});
	
	var fileName = "2016.txt";
	dataLoad(fileName);
	initListboxItem();	
	makeMarkers();
	makeCircles();
	
}

function dataLoad(fileName){
	
	var url = 'dataLoad.php';
	
	$.ajax({
		async: false,
		type: "POST",
		url: url,
		data: {fileName: fileName},
		dataType: "text",
		success: function(data){
			initEqkMapList(data);
		},
		error: function(xhr){
			alert(xhr.requestText);
		},
		timeout: 3000
	});
}

function initEqkMapList(data){

	eqkMapList = [];
	var lines = data.split("\n");
	var eqkInfo;
	var tmp;
	
	for(var i=0; i < lines.length; i++){
		tmp = lines[i].split(" ");
		eqkInfo = {
			"num": parseInt(tmp[0]),
			"tmEqk": tmp[1],
			"mt": parseFloat(tmp[2]),	
			"lat": parseFloat(tmp[3]),
			"lon": parseFloat(tmp[4]),		
		};
		eqkMapList.push(eqkInfo);
	}
}


// 지진 기록(좌표)에 대한 마커 목록 생성
function makeMarkers(){
	for(var i=0; i < eqkMapList.length; i++){
		addMarker(i);
	}
}

// 하나의 마커를 생성
function addMarker(index){
	
	index = parseInt(index);
	
	var myLatlng = new google.maps.LatLng(eqkMapList[index]['lat'], eqkMapList[index]['lon']);
	var tmEqk = convertTimeFormat(eqkMapList[index]['tmEqk']);
	var mt = eqkMapList[index]['mt'].toString();
	
	var myTitle = "- 지진 발생 일시 -\n"; 
		myTitle += tmEqk + "\n";
		myTitle += "[No." + eqkMapList[index]['num'] + "]" + "[M" + mt + "]";
	var myLabel = (eqkMapList[index]['num'] % 10).toString();
//	var myLabel = labels.substr((index % (labels.length)),1);
	
	var marker = new google.maps.Marker({
		position: myLatlng,
		label: myLabel,
		title: myTitle
	});
	markers.push(marker);
}

function makeCircles(){
	for(var i=0; i < eqkMapList.length; i++){
		addCircle(i);
	}
} 

function addCircle(index){
	
	var myLatlng = new google.maps.LatLng(eqkMapList[index]['lat'], eqkMapList[index]['lon']);
	
//	alert(myLatlng.coord.lat);
	var magnitudeCircle = new google.maps.Circle({		
		strokeColor: '#FF0000',
		strokeOpacity: 0.8,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		center: myLatlng,
		radius: Math.pow(eqkMapList[index]['mt'], 3) * 500
    });
	circles.push(magnitudeCircle);
}
	
	

// Sets the map on all markers in the array.
function setMapOnAll(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	}
}

// Shows any markers currently in the array.
function showMarkers() {
	setMapOnAll(map);
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
	setMapOnAll(null);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
	clearMarkers();
	markers = [];
}

function setMapOnMarker(index){
	
	clearMarkers();
	markers[index].setMap(map);
}


//////// Circles
// Sets the map on all circles in the array.
function setMapOnAllCircles(map){
	for(var i=0; i < circles.length; i++){
		circles[i].setMap(map);
	}
}

function showCirclesWithTimeout(){
	setMapOnAllCircles(map);
}

function showCircles(){
	setMapOnAllCircles(map);
}

function clearCircles(){
	setMapOnAllCircles(null);
}

function deleteCircles(){
	clearCircles();
	circles = [];
}

function setMapOnCircle(index){
	clearCircles();
	circles[index].setMap(map);
}


function clearTimeoutAll(){
	for (var i=0; i<timers.length; i++) {
		clearTimeout(timers[i]);
	}
}
