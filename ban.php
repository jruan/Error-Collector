<?php
	function isInBlackList($ip){
		$myfile = fopen('./jnjn/blacklist','r');
		$list = array();
		while($line = fgets($myfile)){
			$line = trim(preg_replace('/\s\s+/', ' ', $line));
			if($line != '')
				$list[] = $line;
		}
		fclose($myfile);
		return in_array($ip, $list);
	}

	function addToBlackList($ip){
		$myfile = fopen('./jnjn/blacklist','a') or die('cannot open file');
		fwrite($myfile, $ip."\n");
		fclose($myfile);	
	}

	function validateIp(){
		if(isInBlackList($_SERVER['REMOTE_ADDR'])){
			header('location: https://www.youtube.com/watch?v=PzFXfvZuLK0');
			die();
		}
	}

	function banIp($ip){
		addToBlackList($ip);
		header('location: https://www.youtube.com/watch?v=PzFXfvZuLK0');
		die();	
	}
?>
