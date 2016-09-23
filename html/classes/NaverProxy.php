<?php
class NaverProxy {
   
	public function queryNaver($query, $target, $count) {
		
		$query = urlencode($query);
		$client_id = "8Ny6rP3G_PTWygdJV2pO";
		$client_secret = "pIexCDAT0b";

		$url = "https://openapi.naver.com/v1/search/".$target.".xml";		
		$url = sprintf("%s?query=%s&display=10", $url, $query);
		$is_post = false;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, $is_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// 함수 실행 성공 시, string 타입으로 데이터를 읽어오고 실패시 false 를 리턴한다. 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$headers = array();
		$headers[] = "X-Naver-Client-Id: " . $client_id;
		$headers[] = "X-Naver-Client-Secret: " . $client_secret;

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$data = curl_exec ($ch);
		curl_close ($ch);
		return $data;
	}
}

?>