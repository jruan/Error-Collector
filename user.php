<?php
class User{
	private $username;
	private $email;
	private $lastLogin;
	private $suspended;
	private $lastUpdate;
	
	public function User($uname, $e, $llogin, $susp, $lupdate){
		$this -> username = $uname;
		$this -> email = $e;
		$this -> lastLogin = $llogin;
		$this -> suspended = $susp;
		$this -> lastUpdate = $lupdate;
	}
	
	public function __construct($uname, $e, $llogin, $susp, $lupdate){
		$this -> username = $uname;
		$this -> email = $e;
		$this -> lastLogin = $llogin;
		$this -> suspended = $susp;
		$this -> lastUpdate = $lupdate;
	}
	
	public function getUsername(){
		return $this -> username;
	}
	
	public function getEmail(){
		return $this -> email;
	}
	
	public function getLastLogin(){
		return $this -> lastLogin;
	}
	
	public function getSuspended(){
		return $this -> suspended;
	}
	
	public function getLastUpdate(){
		return $this -> lastUpdate;
	}
	function _toString(){
		return 'wtf';
	}
}
?>
