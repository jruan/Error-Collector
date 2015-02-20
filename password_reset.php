<!DOCTYPE html>
<?php
	require 'db.php';
	session_start();
	$message=' We will mail you a link to reset your password ';
	if(isset($_SESSION['passwordReset'])){
		if($_SESSION['passwordReset']==CONFLICT)
			$message = ' We could not find your username or email in our system! ';
		else
			$message = ' We were not able to send you an email, please try again. ';
	}
?>

<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
                <meta charset = "UTF-8">
                <link href = "http://104.131.199.122/css/bootstrap.css" rel = "stylesheet" type = "text/css">
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
                                <form class="form-signin" method = "POST" role="form" action ="/password_reset_handle.php">
                                        <h3 class="form-signin-heading" style ="font-size:20pt;">Reset Your Password</h3>
                                        <div id = "form-signin-container">
										<?php
                                                echo "<p style='color:white;'> $message </p>";
                                        ?>

						<br>
                                                <input type="email" class = "form-control" placeholder = "Enter the Email That You Signed 
Up With" name = "email"><br>
						<input type = "text" class = "form-control" placeholder = "Enter in Your Username" name = "username">
						<br><br>
                                                <input type="submit" class="btn btn-primary" value="Send">
                                        </div>
                                </form>
                        </div>


	</body>
</html>

