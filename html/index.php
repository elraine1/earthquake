<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--meta http-equiv="Content-Type" content="text/html; charset=euc-kr"-->
<html>
<head>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" type="text/css" href="resources/css/mainpage.css">
<script src="//code.jquery.com/jquery.min.js"></script>	
<script language="javascript" src="/jquery/jquery-ui.js"></script>
<script language="javascript" src="/googleMapApi.js"></script>
<script language="javascript" src="/loadNews.js"></script>

<script>
$(document).ready(function(){
	
	loadNews();
	$("#footer_div").load("/template/footer.php");
	
	$("#showAllMarkers").click(function(){
		clearMarkers();
		clearCircles();
	
		if($("#optShowGradually").is(":checked")){
			$("#stopTimerBtn").attr("disabled", false);
			
			var timeout;
			var index;
			for(var i = 0; i < eqkMapList.length; i++){
				
				if($("#optTimeSequence").is(":checked") == false){ 	// 시간 역순(현재 -> 과거)
					index = i;
				}else{												// 시간순(과거->현재)
					index = eqkMapList.length - i - 1;
				}
				
				timeout = i * 500;
				timers.push(window.setTimeout(function(index){
//					$("#eqkListbox option:eq(" + (index+1) + ")").attr("selected", "selected");
					$("#eqkListbox").val(index.toString()).attr("selected", "selected");
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
		
		$("#optShowGradually").attr("checked", false);
		$("#stopTimerBtn").attr("disabled", true);
	});
	
	$("#optShowGradually").change(function(){
		if($(this).is(":checked") == true){
			$("#optTimeSequence").attr("disabled", false);
		}else{
			$("#optTimeSequence").attr("disabled", true);
		}
	});
	
	
});
</script>
</head>

<body>
	<hr class="thick_hr">
	<div id="site_title">
		<h2>&nbsp; &nbsp; &nbsp; 대한민국 지진 지도</h2>
	</div>
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
					<select id="eqkListbox" size="35">
						<option disabled>[Num][mgtd] [ co-ordinate ] [yyyy.mm.dd. hh:mm:ss]</option>
					</select>
				</fieldset>
			</div>
			<div id="menu">
				<table id="btnTable">
					<tr>
						<td>
							<input type="button" id="hideAllMarkers" value="감추기">
							<input type="button" id="showAllMarkers" value="모두보기">
						</td>
						<td>
							<input type="checkbox" id="optShowGradually">순차보기
							<input type="button" id="stopTimerBtn" value="순차보기 중지" disabled>
						</td>
					</tr>
					<tr>
						<td>
							자료 선택
							<select id="selectYear">
								<option value="2016" selected>2016</option>
								<option value="2015" >2015</option>
								<option value="2014" >2014</option>
								<option value="2013" >2013</option>
							</select>
						</td>
						<td>
							<input type="checkbox" id="optTimeSequence" disabled>시간순(1월 -> 12월)
						</td>
					</tr>
				</table>
				<br>
				
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
			<h3>&nbsp; ABCDEFG </h3><hr>
			<table id="snsTable">
				<tr><td><h3>컨텐츠 부족!</h3></td></tr>
			</table>
		</div>
		
	</div>
	
	<div id="footer_div"></div>
	</body>
</html>