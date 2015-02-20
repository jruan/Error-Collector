<?php
class Comment{
	private $comment;
	private $time;
	private $screenShot;
	private $username;
	private $commentid;
	
	function __construct($co, $ti, $ss, $un, $id){
		$this -> comment = $co;
		$this -> time = $ti;
		$this -> screenShot = $ss;
		$this -> username = $un;
		$this -> commentid = $id;
	}
	
	public function getComment(){
		return $this -> comment;
	}
	
	public function getTime(){
		return $this -> time;
	}
	
	public function getScreenShot(){
		return $this -> screenShot;
	}
	
	public function getUsername(){
		return $this -> username;
	}

	public function getCommentId(){
		return $this -> commentid;
	}
}
?>
