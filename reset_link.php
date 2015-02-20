<?php
	if(!isset($_GET['q'])){
		header('location:/login.php');
		die();
	}
	$token = $_GET['q'];
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
                                <form class="form-signin" method = "POST" role="form" action ="/reset_password_final.php">
                                        <h3 class="form-signin-heading" style ="font-size:20pt;">Reset Your Password</h3>
                                        <div id = "form-signin-container">
										<?php
                                                echo "<p style='color:white;'> $message </p>";
                                        ?>

						<br>
                                                <input type="password" class = "form-control" placeholder = "Enter a new password 
" name = "password"><br>
<?php
						echo "<input type = \"hidden\" name = \"token\" value=\"$token\">";
?>
						<br><br>
                                                <input type="submit" class="btn btn-primary" value="Send">
                                        </div>
                                </form>
                        </div>


	</body>
</html>
