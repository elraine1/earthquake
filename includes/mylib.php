<?php 
	require_once("mysql_config.php");
	
	function get_mysql_server_conn(){
		
		$conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DBNAME);
		mysqli_query($conn, "SET NAMES 'utf8'");
		if (!($conn)) {
			die('Mysql connection failed: '.mysqli_connect_error());
		} 
		return $conn;
	}

	function get_connection_check(){
		$conn = get_mysql_server_conn();
		
		if($conn){
			return "YEA!!!!";
		}else{
			return "FAILED.. T-T";
		}
	}
	
	
?>
