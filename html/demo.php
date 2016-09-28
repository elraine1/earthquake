<?php
	session_start();
	 
	include_once("twitter.lib/config.php");
	include_once("twitter.lib/twitteroauth.php");
	if(isset($_SESSION['status']) && $_SESSION['status']=='verified') {
	 
	// 인증 중일 때 배열에 값이 들어가 있다.
	echo "<pre>";
	print_r($_SESSION['request_vars']);
	}else{
	// 인증 정보가 없을 때
	echo "<a href='/twitter.php'>Twitter Login</a>";
	 
	}
?>