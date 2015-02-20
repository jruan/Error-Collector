<?php
	session_start();
	session_unset();
	session_destroy();
	header("Location:http://104.131.199.122/mysql_login.php");
?>
