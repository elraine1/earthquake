$(document).ready(function(){
	
//	$("#").load();
	$("#menu").load("/template/menu.php");
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