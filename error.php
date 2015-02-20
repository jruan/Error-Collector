<?php
class Error{
	private $errorType;
	private $time;
	private $url;
	private $errorInfo;
	private $errorStatus;
	private $priority;
	private $username;
	private $rating;
	private $numOfVote;
	private $groupid;
	private $line;
	private $id;
	function __construct($et, $ti, $u, $ei, $es, $pr, $un, $ra, $nu, $gi, $li, $id){
		$this -> errorType = $et;
		$this -> time = $ti;
		$this -> url = $u;
		$this -> errorInfo = $ei;
		$this -> errorStatus =$es;
		$this -> priority = $pr;
		$this -> username = $un;
		$this -> rating = $ra;
		$this -> numOfVote = $nu;
		$this -> groupid = $gi;
		$this -> line = $li;
		$this -> id = $id;
	}

	public function getErrorid(){
		return $this -> id;
	}
	
	public function getErrorType(){
		return $this -> errorType;
	}
	
	public function getTime(){
		return $this -> time;
	}
	
	public function getUrl(){
		return $this -> url;
	}
	
	public function getErrorInfo(){
		return $this -> errorInfo;
	}
	
	public function getErrorStatus(){
		return $this -> errorStatus;
	}
	
	public function getPriority(){
		return $this -> priority;
	}
	
	public function getUsername(){
		return $this -> username;
	}
	
	public function getRating(){
		return $this -> rating;
	}
	
	public function getNumOfVote(){
		return $this -> numOfVote;
	}
	
	public function getGroupid(){
		return $this -> groupid;
	}
	
	public function getLine(){
		return $this -> line;
	}
}
?>
