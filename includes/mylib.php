<?php 
	require_once("mysql_config.php");
	
	$comment_path = $_SERVER['DOCUMENT_ROOT'] . '//classes/Comment.php';
	require_once($comment_path);	
	
	function get_mysql_conn(){
		
		$conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DBNAME);
		mysqli_query($conn, "SET NAMES 'utf8'");
		if (!($conn)) {
			die('Mysql connection failed: '.mysqli_connect_error());
		} 
		return $conn;
	}

	// 연결 
	function get_connection_check(){
		$conn = get_mysql_server_conn();
		
		if($conn){
			return "YEA!!!!";
		}else{
			return "FAILED.. T-T";
		}
	}
	

	// 댓글 작성 함수.
	function comment_write($writer, $comment){
		$conn = get_mysql_conn();
		$stmt = mysqli_prepare($conn, "INSERT INTO comment(comment_writer, comment_contents) VALUES(?, ?)");
		mysqli_stmt_bind_param($stmt, "ss", $writer, $comment);	
		
		if (mysqli_stmt_execute($stmt) === false) {
			die(mysqli_error($conn));
		}
	}
		
	// 방명록을 count만큼 가져오는 함수. 
	function get_comments($count){	

		$conn = get_mysql_conn();
		$stmt = mysqli_prepare($conn, "SELECT * FROM comment ORDER BY comment_date DESC LIMIT ?");						
		mysqli_stmt_bind_param($stmt, "d", $count);
		mysqli_stmt_execute($stmt);
				
		$result = mysqli_stmt_get_result($stmt);
		if($result === false){
			die(mysqli_error($conn));
		}

		$comments = array();
		while($comment = mysqli_fetch_assoc($result)){
			$comments[] = new Comment($comment['comment_id'], $comment['comment_writer'], $comment['comment_password'],
										$comment['comment_contents'], $comment['comment_date']);
		}		
		mysqli_free_result($result);
		mysqli_close($conn);
		return $comments;
	}

	
	
	
?>
