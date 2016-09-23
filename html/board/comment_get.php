<?php
	$mylib_path = $_SERVER['DOCUMENT_ROOT'] . '/../includes/mylib.php';
	require_once($mylib_path);	
		
	$comments = get_comments($_POST['count']);
	echo json_encode($comments);	
	
?>