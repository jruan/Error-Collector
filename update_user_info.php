<?php
	require 'db.php';
	session_start();
	$new_username = "";
        $new_password = "";
        $new_email = "";
	$old_user = "";

	if(!(isset($_SESSION['jnjn_username']))){
		header("Location: http://104.131.199.122/logout.php");
	}
	else{
		if(empty($_POST['modify_user'])){
			header("Location:http://104.131.199.122/admin_user_management.php");
		}
		else{
			$old_user = '';
			$new_email = '';
			$new_password = '';
			$new_username = '';
			if(!empty($_POST['modify_user']))
				$old_user = cleanInput($_POST['modify_user']);
			else
				header("Location:http://104.131.199.122/admin_user_management.php");
			if(!empty($_POST['new_email']))
				$new_email = cleanInput($_POST['new_email']);
			if(!empty($_POST['new_password']))
				$new_password = encrypt(cleanInput($_POST['new_password']),SALT);
			if(!empty($_POST['new_username']))
				$new_username = cleanInput($_POST['new_username']);
				
			$result = updateUser($old_user, $new_username, $new_email, $new_password);
			
			echo $result;
			
			header("Location:http://104.131.199.122/admin_user_management.php");
			
		}
	}
?>
