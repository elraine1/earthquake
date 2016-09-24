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
			document.getElementById("optTimeSequence")
		}else{
			$("#optTimeSequence").attr("disabled", true);
		}
	});
	
	
	//// 댓글작성 미완성
	$("#commentWriteBtn").click(function(){
		if(commentContentsVerify()){
			var url = $("#comment_write_form").attr('action');
			var writer = $("#txtWriter").val();
			var cmt = $("#txtComment").val();
			
			$.ajax({
				async: false,
				url: url,
				type: "POST",
				data: {writer: writer, comment: cmt, count: 30},
				dataType: 'json',
				success: function(data){
					fillCommentTable(data);						
				},				
				error: function(xhr){
					alert(xhr.requestText);
				},
				timeout: 3000			
			});			
		}
		
	});
	
	
	var getCommentUrl = "board/comment_get.php";
	$.ajax({
		async:false,
		url: getCommentUrl,
		type: "POST",
		data: {count: 30},
		dataType: 'json',
		success:function(data){
			fillCommentTable(data);			
		},
		error: function(xhr){
			alert(xhr.requestText);
		}
	})
	
	
	/*
	$("").click(function(){
		
	});
	*/
	
});

function commentContentsVerify(){
	var writer = $("#txtWriter").val();
	var txt = $("#txtComment").val();
	// 적합성 검사 추가 할 것. 09.24.
	if (writer == 'admin'){	
		alert("작성자명은 'admin'으로 만들 수 없습니다!"); return false;
	}else if(writer.length < 3){
		alert("작성자명은 3자 이상 작성해주세요!"); return false;
	}else if(txt.length < 5){
		alert("내용은 5자 이상 작성해주세요!"); return false;
	}else if (txt.length > 400){	
		alert("글자수 제한은 400자 입니다!");	return false;
	}else{
		return true;
	}
}



function fillCommentTable(comments){
	var comment_table = $("#comment_table");
	var tableHtml="";
	$.each(comments, function(key, val){
		tableHtml += "<tr><td class='tdWriter2'><b>" + val.comment_writer + "</b><br></td>";
		tableHtml += "<td class='tdComment2'><span>" + val.comment_contents + "</span></td>";
		tableHtml += "<td class='tdButton'><input type='button' class='commentBtn' value='버튼1' onclick='commentBtnClick()'><br>";
		tableHtml += "					   <input type='button' class='commentBtn' value='버튼2' onclick='commentBtnClick()'></td>";
		tableHtml += "<td class='tdCommentDate'><span class='spanCommentDate'>" + val.comment_date + "</span></td></tr>";
	});
	comment_table.empty();
	comment_table.append(tableHtml);
}

function commentBtnClick(){
	alert('아직은 기능이 없어요!');
}

</script>
</head>

<body>
	<?php
		$mylib_path = $_SERVER['DOCUMENT_ROOT'] . '/../includes/mylib.php';
		require_once($mylib_path);
	?>

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
							<?php 
								for($i=0; $i<10; $i++){
									printf("<option value='%d'>%d</option>", date("Y")-$i, date("Y")-$i);
								}
							?>
							</select>
						</td>
						<td>
							<input type="checkbox" id="optTimeSequence" disabled>시간순(1월->12월)
						</td>
					</tr>
				</table>
				<br>
				
			</div>
		</div>
	</div>
	<br>
	<hr class="thin_hr">
	<div id="wrapper_row1">
		<div id="row1_contents_wrap">
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
	</div>
	<hr class="thin_hr">	
	<div id="wrapper_row2">
		<div id="row2_contents_wrap">
					
			<div id="news_wrap">
				<h3>&nbsp; 지진 관련 기사</h3><hr>
				<table id="newsTable"></table>
			</div>
			
			<div id="sns_wrap">
				<h3>&nbsp; ABCDEFG </h3><hr>
				<table id="snsTable">
					<tr><td><h3>공사 중..</h3></td></tr>
				</table>
			</div>
			
		</div>
	</div>
	
	<hr class="thin_hr">
	<div id="wrapper_row3">
		<div id="row3_contents_wrap">
			
			<div id="comment_wrap">
				<h3>&nbsp; 방명록<span style='font-size: 12px'>(아무 말이나 편하게 써주세요! 단, 비속어는 자제바랍니다! [최소 5자, 400자 제한])<span></h3>
				<hr>
					<div id="comment_table_wrap">
						<table id="comment_table"></table>
					</div>
				<hr>
				<div id="comment_write_div">
					<form action="board/comment_write.php" id="comment_write_form" method="POST">
						<table>
							<tr>
								<td align="center" class="tdWriter" align='center'>
									<b>Writer<span style='font-size: 12px'>[최소 3자]</span></b><br>
									<input type="text" id="txtWriter" size='14' value="" maxlength='20' placeholder="아이디 입력">
								</td>
								<td class="tdComment">
									<div class="textwrapper"><textarea id="txtComment" rows='3' placeholder="내용 입력"></textarea></div>
								</td>
								<td align="center" class="tdButton">
									<input type='button' class='commentBtn' id="commentWriteBtn" value="작성완료" ><br>
									<input type='reset' class='commentBtn' value='취소'>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			
			<div id="site_link_wrap">
				<h3>&nbsp; 관련 사이트 링크</h3>
				<hr>
				<div id="banner_wrap">
				<table>
					<tr>
						<td><a href="http://www.kma.go.kr/weather/earthquake_volcano/report.jsp">
							<img src='/resources/images/banner/banner_earthquake_kma.gif' width='100px' height='40px'></a></td>
						<td><a href="https://twitter.com/kma_earthquake"> 
							<img src='/resources/images/banner/banner_earthquake_twitter.png' width='100px' height='40px'></a></td>
					</tr>
					<tr>
						<td><a href="http://www.safekorea.go.kr/idsiSFK/index.jsp"> 
							<img src='/resources/images/banner/banner_earthquake_NDIC.png' width='100px' height='40px'></a></td>
						<td><a href="https://quake.kigam.re.kr/"> 
							<img src='/resources/images/banner/banner_earthquake_KERC.png' width='100px' height='40px'></a></td>
					</tr>
					<tr>
						<td><a href="http://gall.dcinside.com/board/lists/?id=jijinhee">
							<img src='/resources/images/banner/banner_earthquake_jijinhee.png' width='100px' height='40px'></a></td>
						<td><a href="http://www.jma.go.jp/jp/quake/">
							<img src='/resources/images/banner/banner_earthquake_japan.png' width='100px' height='40px'></a></td>
					</tr>
				</table>	
				<div>
			</div>
		</div>
	</div>
	
	<div id="footer_div"></div>
	</body>
</html>










