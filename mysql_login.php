
<?php
require 'db.php';
	session_start();
	$wrong_password = false;
	$connection_err = false;
	$jnjn_username = "";
	$jnjn_password = "";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(empty($_POST["username"]) || empty($_POST["password"])){	
			$wrong_password = true;
		}
		
		else{ 
			$jnjn_username = $_POST['username'];
			$jnjn_password = $_POST['password'];
			$jnjn_username = cleanInput($jnjn_username);
			$jnjn_password = cleanInput($jnjn_password);
			$hash_password = encrypt($jnjn_password, SALT);

			$login_result = login($jnjn_username, $hash_password);

			if($login_result == CONNECTION_FAIL){
				$connection_err = true;
			}

			else if($login_result == FAIL){
				$wrong_password = true;
			}

			else if($login_result == SUCCESS){
				if($_SESSION['gen_per'] == ADMIN){
					 header("Content-Type: text/html");
                                         header("Location: http://104.131.199.122/admin_dashboard.php");								}
				else{
					if($_SESSION['gen_per'] == USER){
						header("Content-Type: text/html");
						header("Location: http://104.131.199.122/homepage.php");
					}
				}			
			}
			else{
			}
		}	
	}
?>
	<html>
		<head>
			<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
			<meta charset = "UTF-8">
			<link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">
	                <link href = "http://104.131.199.122/css/login_css.css" rel = "stylesheet" type = "text/css">

        	        <title> Login </title>
			
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
							<li class="active"><a href = "http://104.131.199.122/mysql_login.php"> Sign In </a></li>
                                                </ul>
                                	</div>
                        	</div>
                	</div>


			<div class = "container">
                         	<form class="form-signin" method = "POST" role="form" action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
                                	<h2 class="form-signin-heading">Please Sign In</h2>
                                	<?php
                                        	if($wrong_password == true){
                                        		session_destroy();
					        	echo "<h4 style='color:white;'>Wrong Username or Wrong Password</h4>";
                                       		 }	
						else if($connection_err == true){							
							session_destroy();
							echo "<h4 style='color:white;'>So Sorry, Connection Error Occur. Try Again. </h4>";
						}
                                	?>
                                	<div id = "form-signin-container">
                                        	<input type="text" class="form-control" placeholder="Username" name = "username"><br>
                                        	<input type="password" class="form-control" placeholder="Password" name="password">

                                        	<label>
                                                	<br>
                                                	<a href="http://104.131.199.122/password_reset.php" style = "color:#3333CC;">Forgot Password</a>
                                        	</label>
                                        	<br><br>
                                        	<input type="submit" class="btn btn-primary" value="Sign In">
                                        	<button type="button" class="btn btn-primary" onclick = "window.location = 'http://104.131.199.122/marketing_page.php';">Sign Up</button>
                                	</div>
                        	</form>
                	</div>

                	<div class="footer">
                        	<div class="container">
                                        <p class = "text-muted"><a href = "http://104.131.199.122/index">About Us</a></p>
                        	</div>
                	</div>
		</body>
	</html>

