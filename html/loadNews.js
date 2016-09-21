
function loadNews(){
	
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
}