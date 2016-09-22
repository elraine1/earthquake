<?php
	// 트위터 API 설정 임포트
	require_once($_SERVER['DOCUMENT_ROOT'] . "/twitter/twitterOAuth.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/twitter/config.php");
	
	// 텍스트에 링크주소를 링크태그 처리해주는 함수
	function makeClickableLinks($text){
		return preg_replace('@(https?://([-\w\.]+)+(:\d|)?(/([w/_\.-]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $text);		
	}

	// 트위터 API APP 인증
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	
	// 트위터 user 정보 불러오기 
	$userinfo = $connection -> get('users/show', array('screen_name' => 'elraine0128'));
	
	// 트위터 타임라인 글 불러오기 // elraine0128 계정의 1페이지에서 5개 가져옴. 		// 아이디 검색 말고 해시태그 검색하면 될듯.. 
	$content = $connection -> get('statuses/user_timeline', array('screen_name' => 'elraine0128', 'count' => 5, 'page' => 1));
	
	// 불러온 트윗의 갯수
	echo "Twitter Count : " . COUNT($content);
	
	
	// 트위터 타임라인 글 출력!
	foreach($content as $feed){
		
		// writer name
		$userName = $feed -> user -> name;
		
		// writer Profile Image url
		$userProfile = $feed -> user -> profile_image_url;
		
		echo "<p><img src=" . $userProfile . "></p>";
		echo "<p><h3> " . $userName . "</h3></p>";
		
		// writer Time Format - 2014-02-08
		$createdDate = date('Y-m-d H:i:s', strtotime($feed->created_at));
		echo "<p>Created Date : " . $createdDate . "</p>";
		
		// Timeline Contents Text
		$text = $feed-text;
		echo "<p>Text : " . makeclickableLinks($text) . "</p>";
		
		// Timeline Media Infomation
		$media = $feed -> entities -> media;
		if ($media){
			
			foreach($media as $media){
				
				$mediaLink = $media->url;
				$mediaImg = $media->media_url;
				
				echo "<p><img src=" . $mediaImg . " width='200'></p>";
				echo "<p><a href=" . $mediaLink . " target='_blank'>" . $mediaLink . "</a></p>";
			}	
		}
	}
?>
