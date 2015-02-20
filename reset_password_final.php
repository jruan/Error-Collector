<?php
	require 'db.php';
	$token = $_POST['token'];
	$password = $_POST['password'];
	if(!isset($token) || !isset($password) || $token == '' || $password == ''){
		header('location:/login.php');
		die();
	}
	
	$token = cleanInput($token);
	$password = cleanInput($password);
	$password = encrypt($password,SALT);
	
	$result = resetPassword($token,$password);
	
?>

<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
                <meta charset = "UTF-8">
                <link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">
                <link href = "http://104.131.199.122/css/login_css.css" rel = "stylesheet" type = "text/css">

                <title> Password Reset </title>

	</head>

	<body style = "background-color:gray;">
                         <div class = "navbar navbar-inverse navbar-fixed-top" role = "navigation">
                                 <div class ="container">
                                        <button class = "navbar-toggle" data-toggle = "collapse" data-target = ".collapseNavHeader">


                                                <span class = "icon-bar"></span>
                                                <span class = "icon-bar"></span>
                                                <span class = "icon-bar"></span>

                                        </button>

                                        <div class = "collapse navbar-collapse collapseNavHeader">
                                                <ul class = "nav navbar-nav navbar-right">
                                                        <li><a href="http://104.131.199.122/marketing_page.php"> Features </a></li>
                                                        <li>
                                                                <a href = "http://104.131.199.122/index"> Contact </a>
                                                        </li>
                                                        <li><a href = "http://104.131.199.122/mysql_login.php"> Sign In </a></li>
                                                </ul>
                                        </div>
                                </div>
                        </div>

			<div class = "container">
			 <div class="alert alert-success" role="alert">
								<?php
								if($result == SUCCESS)
									echo 'Your password has been successfully reset.
                                <a href="/mysql_login.php" class="alert-link">Click here to login</a>';
								else{
									header('location:/404.html');
									die();
								}
								?>
                                
                        </div>
			</div>


	</body>
</html>
