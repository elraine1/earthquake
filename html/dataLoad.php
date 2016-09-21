<?php

	$directoryPath = "data/";
	$fileName = $_POST['fileName'];
	$filePath = $directoryPath . $fileName;
	$fhandle = fopen($filePath, "r") or die("unable to open file!");
	
	$data = array();
	
	while(!(($line=fgets($fhandle))===false)){
/*
		// 번호, 진원시, 규모, 위도, 경도
		$tmp = explode(" ", $line);
//		echo explode(" ", $line) ;
		$data[]['num'] = $tmp[0];
		$data[]['eqkTime'] = $tmp[1];
		$data[]['mt'] = $tmp[2];
		$data[]['lat'] = $tmp[3];
		$data[]['lon'] = $tmp[4];
*/
		echo $line;
	}
	
	fclose($fhandle);