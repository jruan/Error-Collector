<?php
	require 'db.php';
	validateIp();
/*
	if(validate($_POST['input']))
		echo 'validate said:you are welcome<br>';
	else
	//	echo 'validate said:go to hell';
		banIp($_SERVER['REMOTE_ADDR']);
*/

	if(validate($_POST['input']))
		echo 'validate1 said:you are welcome<br>';
	else
		banIp($_SERVER['REMOTE_ADDR']);

?>
