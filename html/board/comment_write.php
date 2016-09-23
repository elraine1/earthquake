<?php
	$mylib_path = $_SERVER['DOCUMENT_ROOT'] . '/../includes/mylib.php';
	require_once($mylib_path);	
		
	comment_write($_POST['writer'], $_POST['comment']);
	$comments = get_comments($_POST['count']);
	echo json_encode($comments);	
?>