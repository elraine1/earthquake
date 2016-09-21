<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--meta http-equiv="Content-Type" content="text/html; charset=euc-kr"-->
<html>
<head>
<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" type="text/css" href="resources/css/mainpage.css">
<script src="//code.jquery.com/jquery.min.js"></script>	
<script language="javascript" src="/jquery/jquery-ui.js"></script>
<script language="javascript" src="/googleMapApi.js"></script>

<script type="text/javascript">
////////////////////////////////////// JQUERY
$(document).ready(function(){
	
//	$("#").load();
//	$("#").load();
	$("#footer_div").load("/template/footer.php");
	
	var newsList = [];
	var url = "news_proxy.php";
	$.ajax({		
		dataType: "xml",
		type: "POST",
		async: false,
		url: url,
		data: {query:'지진', target:'news'},
		success: function(result){
//			alert(result);
			var xml = $(result);
			var items = xml.find("item");
			var newsHtml = "";
					
			var lastBuildDate;
			var newsTitle;
			var newsOriginalLink;
			var newsDescription;
			var newsPubDate;
			
			for(var i=0; i < items.length; i++){
				lastBuildDate = xml.find("lastBuildDate").text();
				newsTitle = $(items[i]).find("title").text();
				newsOriginalLink = $(items[i]).find("originallink").text();
				newsDescription = $(items[i]).find("description").text();
				newsPubDate = $(items[i]).find("pubDate").text();
				
				// html 만들기
				newsHtml += "<tr class='trNewsTitle'><td class='tdNewsTitle'><a href='" + newsOriginalLink + "'><span>" + newsTitle + "</span></a></td>";
				newsHtml += 	"<td class='tdNewsPublishedDate'><span class='newsPublishedDate'>" + newsPubDate + "</span></td></tr>";
				newsHtml += "<tr class='trNewsContent'><td colspan='2'><span class='newsContent'>" + newsDescription + "</span></td></tr>";
				newsHtml += "<tr class='trSpace'></tr>";
			}
			
			var newsTable = $("#newsTable");
			newsTable.empty();
			newsTable.append(newsHtml);
		},
		error: function(xhr){
			alert(xhr.responseText);
		},
		timeout : 3000
	});	
	
	
	$("#showAllMarkers").click(function(){
		clearMarkers();
		clearCircles();
	
		if($("#optShowGradually").is(":checked")){
			$("#stopTimerBtn").attr("disabled", false);
			
			var timeout;
			var index;
			for(var i = 0; i < eqkMapList.length; i++){
				//index = eqkMapList.length - i - 1;
				index = i;
				timeout = i * 500;
				timers.push(window.setTimeout(function(index){
					setMapOnMarker(index);
					setMapOnCircle(index);
				}.bind(this, index), timeout));
			}
			
		}else{
			showMarkers();
			showCircles();
		}
	});
	
	$("#hideAllMarkers").click(function(){
		clearTimeoutAll();
		clearMarkers();
		clearCircles();
	});
	
	$("#eqkListbox").change(function(){
		var index = parseInt($(this).val());		
		setMapOnMarker(index);
		setMapOnCircle(index);
	});
	
	$("#selectYear").change(function(){
		clearTimeoutAll();
		deleteMarkers();
		deleteCircles();
			
		$("#stopTimerBtn").attr("disabled", true);
		
		var fileName = $(this).val() + ".txt";
		
		dataLoad(fileName);
		initListboxItem();	
		makeMarkers();
		makeCircles();
	});
	
	$("#stopTimerBtn").click(function(){
		clearTimeoutAll();
		clearMarkers();
		clearCircles();
		
		$("#stopTimerBtn").attr("disabled", true);
	});
});
		
</script>
		
	</head>
	
	<body>
	<hr class="thick_hr">
	<h2>&nbsp; 대한민국 지진 지도</h2>
	<hr class="thick_hr">
	<h5>&nbsp; &nbsp; ※ 2016년 9월 12일 경주에서 발생한 규모 5.1 지진 이후의 목록은 상세분석 결과이며, 규모 2.0 미만의 지진은 지진목록에서 제외 됨</h5>
	
	<div id="content_wrap">
		
		<div id="map">
			<script async defer
				src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDa8mrOz4m1HWHARilVDc9EKcRYlL5oCf4&callback=initMap">
			</script>
		</div>
		
		<div id="sidebar">
		
			<div id="eqkList">
				<fieldset>
					<legend>지진 목록</legend>
					<select id="eqkListbox" size="42">
						<option disabled>[Num][mgtd] [ co-ordinate ] [yyyy.mm.dd. hh:mm:ss]</option>
					</select>
				</fieldset>
			</div>
			
			<div id="menu">				
				<input type="button" id="hideAllMarkers" value="감추기">
				<input type="button" id="showAllMarkers" value="모두보기">
				<input type="checkbox" id="optShowGradually" >순차보기
				<input type="button" id="stopTimerBtn" value="순차보기 중지" disabled><br>
				
				자료 선택
				<select id="selectYear">
					<option value="2016" selected>2016</option>
					<option value="2015" >2015</option>
					<option value="2014" >2014</option>
					<option value="2013" >2013</option>
				</select><br>
			</div>
			
		</div>
	</div>
	<br>
	<hr class="thin_hr">
	<div id="youtube_wrap">
		
		<div class="youtube_content">
			<fieldset class="youtube_fieldset">
				<legend>한국 지진 실시간 중계</legend>
				<iframe width="100%" height="95%" class="wiki-youtube" src="//www.youtube.com/embed/LqpjAhmhXcQ" frameborder="0" allowfullscreen=""></iframe>
			</fieldset>
		</div>
		
		<div class="youtube_content">
			<fieldset class="youtube_fieldset">
				<legend>일본 지진 실시간 중계</legend>
				<iframe width="100%" height="95%" class="wiki-youtube" src="//www.youtube.com/embed/qmu8zrllUUI" frameborder="0" allowfullscreen=""></iframe>
			</fieldset>
		</div>
	</div>
	<hr class="thin_hr">
	<br>
	<div id="community_wrap">
			
		<div id="news_wrap">
			<h3>&nbsp; 지진 관련 기사</h3><hr>
			<table id="newsTable"></table>
		</div>
		
		<div id="sns_wrap">
			<h3>&nbsp; ABC </h3><hr>
			<table id="snsTable">
				<tr><td><h3>아직은 내용이 없습니다!</h3></td></tr>
			</table>
		</div>
		
	</div>
	
	<div id="footer_div"></div>
	</body>
</html>