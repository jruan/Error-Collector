<?php
require 'db.php';
	if(!(empty($_GET['token'])) && !(empty($_GET['msg'])) && !(empty($_GET['linenum'])) && !(empty($_GET['file']))){
		$token = $_GET['token'];
		$token = cleanInput($token);
		$group_id = tokenToGroupid($token);

		$msg = $_GET['msg'];
		$msg = cleanInput($msg);
 
		$line = $_GET['linenum'];
		$line = cleanInput($line);
		$line = (int)$line;

		$file_path_url = $_GET['file'];
		$file_path_url = cleanInput($file_path_url);
		
		$errorType = substr($msg, 0, strpos($msg, ":"));
		$errorInfo = substr($msg, strpos($msg, ":") + 1, strlen($msg)-1);
		
		postError($group_id, $errorType, $file_path_url, $errorInfo, $line);

/*		$filehandle = fopen("error.txt", "w+");		
		if($filehandle){
			fwrite($filehandle, "$group_id\t $errorInfo\t $line\t $file_path_url\ $errorType\n");
			fclose($filehandle);
		}*/
	}
?>
