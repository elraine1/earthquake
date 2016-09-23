<?php
	class Comment{
		public $comment_id;
		public $comment_writer;
		public $comment_password;
		public $comment_contents;
		public $comment_date;
		
		function __construct($id, $writer, $password, $contents, $date){
			$this->comment_id = $id;
			$this->comment_writer = $writer;
			$this->comment_password = $password;
			$this->comment_contents = $contents;
			$this->comment_date = $date;
		}	
	}

?>