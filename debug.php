<?php
	require 'db.php';
	echo 'POST DATA:<br>';
	foreach($_POST as $key => $value){
		echo <<<HTML
			$key:  $value<br>
HTML;
	}

	echo 'GET DATA:<br>';
	foreach($_GET as $key => $value){
		echo <<<GET
			$key: $value<br>
GET;
	}
	$report = errorReport(35);
	foreach($report as $key => $value){
		echo <<<HTML
			$key: $value<br>
HTML;
	}
?>
