<?php
	session_start();
	include_once("twitter.lib/config.php");
	include_once("twitter.lib/twitteroauth.php");
	 
	echo "1. " . $_SESSION['token'] . "<br>";
	echo "2. " . $_REQUEST['oauth_token'] . "<br>";
	
	echo "3. " . CONSUMER_KEY . "<br>";
	echo "4. " . CONSUMER_SECRET  . "<br>";
	 
	if (isset($_REQUEST['oauth_token']) && $_SESSION['token'] != $_REQUEST['oauth_token']) {
	 
	// 토큰이 있지만 유효하지 않으면 토큰을 파기하고 원페이지로 돌린다.
		session_destroy();
		header('Location: ./demo.php');
		 
	}elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {
		 
		// 문제가 없다. 정상인 경우 토큰 및 시크릿, 사용자 정보를 세션에 넣는다.
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		 
		if($connection->http_code=='200'){
			// 정상
			$_SESSION['status'] = 'verified';
			$_SESSION['request_vars'] = $access_token;
		 
			// unset no longer needed request tokens
			unset($_SESSION['token']);
			unset($_SESSION['token_secret']);
			header('Location: ./demo.php');
			
		} else {
		// 오류
			die("Error, try again later!");
		}
		 
	}else{
		 
		if(isset($_GET["denied"])){
			header('Location: ./demo.php');
			die();
		}
		
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
		$_SESSION['token'] = $request_token['oauth_token'];
		$_SESSION['token_secret'] = $request_token['oauth_token_secret'];
		 
		echo "2.1 " . $connection -> url;
		echo "2.2 " . $request_token . "<br>";
		echo "2.3 " . $_SESSION['token'] . "<br>";
		echo "2.4 " . $_SESSION['token_secret'] . "<br>";
		 
		 
		 
		// HTTP OK 이면(200) 처리 아니면 실패합니다
		if($connection->http_code=='200') {
			// 트위터 로그인을 요청합니다
			$twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
			header('Location: ' . $twitter_url);
		 
		} else {
			die("Error connecting to twitter! try again later!");
		}
	}
?>