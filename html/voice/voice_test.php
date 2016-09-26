<?php
//<-- 네이버 오픈API 음성합성 API  사용
	
	header("Content-Type:text/html; charset=utf-8");
	header("Content-Encoding:utf-8");
	//ini_set('memory_limit', '256M');
		
	//<-- 음성합성API를 사용하는 어플리케이션 등록후 발급받은 ID와 KEY 값을 입력	
	$clientID  = "8Ny6rP3G_PTWygdJV2pO";
	$secretKey = "pIexCDAT0b";
		
	$ttsURL  = "https://openapi.naver.com/v1/voice/tts.bin";
	$text = "안녕하세요. 네이버 TTS 샘플 입니다.";

	//<-- 음성합성 파라미터 설정 (남성,여성 보이스 선택,  말하기 속도 설정)
	$fields = array('speaker'=>'mijin','speed'=>'0', 'text'=>$text);
	$postvars = '';
	foreach($fields as $key=>$value) {
		$postvars .= $key . "=" . $value . "&";
	}
	  
	  
	$is_post = true;
	$ch = curl_init();
	  

	curl_setopt($ch,CURLOPT_URL,$ttsURL);
	curl_setopt($ch,CURLOPT_POST, 1);              
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,10);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	  
	$headers = array();
	$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
	$headers[] = 'X-Naver-Client-Id: '.$clientID.'';
	$headers[] = 'X-Naver-Client-Secret: '.$secretKey.'';
	$headers[] = 'Cache-Control: no-cache';
	$headers[] = 'User-Agent: curl/7.43.0';

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	  
	$response = curl_exec($ch);  //<-- 네이버 음성합성 API 요청하여 결과데이타를 mp3로 받는다.
	curl_close ($ch);
	
	$root_path = $_SERVER['DOCUMENT_ROOT'];
	$destination = $root_path . "/voice/voice_data/sample1.mp3"; //<--받은 파일을 쓰기 가능한 경로의 파일명으로 저장한다.. 
	$file = fopen($destination, "w+");
	fputs($file, $response);
	fclose($file);  

	echo "OK!";
	
?>

