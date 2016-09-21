<?php
class earthQuake {
   
	public function queryEQ($fromTmFc, $toTmFc, $numOfRows, $pageNo) {
	
		$is_post = false;
		$key = "";
		
		$ch = curl_init();
		$url = 'http://newsky2.kma.go.kr/service/ErthqkInfoService/EarthquakeReport'; /*URL*/
		$queryParams = '?' . urlencode('ServiceKey') . '=' . $key; /*Service Key*/
		$queryParams .= '&' . urlencode('fromTmFc') . '=' . urlencode($fromTmFc); /*발표시각(From)*/
		$queryParams .= '&' . urlencode('toTmFc') . '=' . urlencode($toTmFc); /*발표시각(To)*/
		$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode($numOfRows); /*검색건수*/
		$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($pageNo); /*페이지 번호*/

		curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
//		curl_setopt($ch, CURLOPT_POST, $is_post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec($ch);
		curl_close($ch);

		var_dump($response);
		
	}
}

?>