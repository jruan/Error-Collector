<?php
	class Group{
		private $groupid;
		private $name;
		private $manager;
		private $url;
		private $description;
		private $setupTime;
		private $numOfPages;
		private $token;
/*		
		function __construct($gi,$na,$ma,$u,$de,$st,$nu,$to){
			$this -> groupid = $gi;
			$this -> name = $na;
			$this -> manager = $ma;
			$this -> url = $u;
			$this -> description = $de;
			$this -> setupTime = $st;
			$this -> numOfPages = $nu;
			$this -> token = $token;
		}
*/		
		function __construct($gi,$na,$ma,$u,$de,$st){
			$this -> groupid = $gi;
			$this -> name = $na;
			$this -> manager = $ma;
			$this -> url = $u;
			$this -> description = $de;
			$this -> setupTime = $st;
		}

		public static function createWithFullInfo($gi,$na,$ma,$u,$de,$st,$nu,$to){
			$group = new Group($gi,$na,$ma,$u,$de,$st);
			$group -> numOfPages = $nu;
			$group -> token = $to;
			return $group;
		} 
		
		public function getGroupid(){
			return $this -> groupid;
		}
		
		public function getName(){
			return $this -> name;
		}
		
		public function getManager(){
			return $this -> manager;
		}
		
		public function getUrl(){
			return $this -> url;
		}
		
		public function getDescription(){
			return $this -> description;
		}
		
		public function getSetupTime(){
			return $this -> setupTime;
		}
		
		public function getNumOfPages(){
			return $this -> numOfPages;
		}
		
		public function getToken(){
			return $this -> token;
		}
		
		
	}
?>
