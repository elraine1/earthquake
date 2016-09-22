<?php 
	require_once("mysql_config.php");
	
	function get_sqlserver_conn(){
		
		$conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DBNAME);
		mysqli_query($conn, "SET NAMES 'utf8'");
		if (!($conn)) {
			die('Mysql connection failed: '.mysqli_connect_error());
		} 
		return $conn;
	}

	
?>
