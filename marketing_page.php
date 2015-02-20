<!DOCTYPE html>
<?php
require 'db.php';
	session_start();
	$missing_field = false;
	$connection_err = false;
	$taken_username = false;
        $jnjn_username = "";
        $jnjn_password = "";
	$jnjn_email = "";
        $salt = "200ok@powell135";
        if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"])){
			$missing_field = true;
		}
		  else{
			$jnjn_username = $_POST["username"];
			$jnjn_password = $_POST["password"];
			$jnjn_email = $_POST["email"];
			$jnjn_username = cleanInput($jnjn_username);
			$jnjn_email = cleanInput($jnjn_email);
			$jnjn_password = cleanInput($jnjn_password);
			$hash_password = encrypt($jnjn_password, $salt);
			$register_result = register($jnjn_username, $jnjn_email, $hash_password);
			if($register_result == CONNECTION_FAIL){
				$connection_err = true;
			}
			else if($register_result == USERNAME_EXIST){
				$taken_username = true;
			}
			else if($register_result == FAIL){
				$connection_err = true;
			}
			else{
				$_SESSION['jnjn_username'] = $jnjn_username;
				header("Content-Type: text/html");
				header("Location: http://104.131.199.122/homepage.php");
			}

		  }
	}	
?>
<html><head><meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0"><meta charset = "UTF-8"><link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">	<link href = "http://104.131.199.122/css/marketpage_css.css" rel = "stylesheet" type = "text/css"><title> Marketing Page </title><style>.jumbotron{background-image: url('http://104.131.199.122/images/jumbo_background.jpg');height:580px;background-size:100%;}footer ul{list-style-type:none;}footer ul li{cursor:pointer;display:inline;}.dropdown-menu li{cursor:pointer;	border-bottom:1px solid white;}</style></head><body><div class = "navbar navbar-inverse navbar-fixed-top" role = "navigation"><div class ="container"><button class = "navbar-toggle" data-toggle = "collapse" data-target = ".collapseNavHeader"><span class = "icon-bar"></span><span class = "icon-bar"></span><span class = "icon-bar"></span></button><div class = "collapse navbar-collapse collapseNavHeader"><ul class = "nav navbar-nav navbar-right"><li class = "active"><a href="#"> Features </a></li><li><a href = "http://104.131.199.122/index"> Contact </a></li><li><a href = "http://104.131.199.122/mysql_login.php">Sign In</a></li></ul></div></div></div><div class="jumbotron"><div class = "container"><br><br><h1 class="slide-in" style="text-align:center;"> Welcome to Error Log</h1><form class = "form-signin" action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "POST"><h3 class="form-signin-heading">Sign Up</h3><?php	if($missing_field == true){echo "<h4 style='color:white;'> Missing Input Fields </h4>";session_destroy();}if($taken_username == true){echo "<h4 style = 'color:white;'> Username Has Been Taken </h4>";session_destroy();}?><div id = "form-signin-container"><input type="email" class="form-control" placeholder="Email Address" name = "email"><br><input type ="text" class="form-control" placeholder = "Username" name ="username"><br><input type="password" class="form-control" placeholder="Password" name="password"><br><br><input type="submit" class="btn btn-primary" value="Sign up"></div></form></div></div><div class = "container"><h1 style = "text-align:center; color:#00928A;font-size:40pt;">Features</h1><br><br><div class="row"><div class = "col-sm-4"><h3> Dashboard Page</h3><br><p> Provides users with the date, error type, and a description of the most recent errors that occured during the past month.</p><a href = "http://104.131.199.122/dashboard_example"> View a Demo</a><br><img src = "http://104.131.199.122/images/dashboard.jpg" alt = "dashboard_demo" style="cursor:pointer;" onclick = "window.location= 'http://104.131.199.122/dashboard_example';"></div><div class = "col-sm-4"><h3> Error Detail Page</h3><br><p> Allows users to rate the severity of the error, comment on the error, and even upload screen shots of what they capture</p><a href= "http://104.131.199.122/error_detail"> View A Demo</a><br><img src="http://104.131.199.122/images/error_detail.jpg" alt="error_detail_demo"  style="cursor:pointer;" onclick = "window.location= 'http://104.131.199.122/error_detail.html';"></div><div class = "col-sm-4"><h3> User Management </h3><br><p> Allows the editing, adding, and deleting of users</p><ul><li> Admin Management includes adding, deleting, and editing any developer accounts</li>	<li> Developer Manager includes adding, deleting, and editing any developer that got invited into the account group</li><li> Developers are only allowed to comment and view error dashboard </li></ul>	</div></div><br><br></div><div class = "col-sm-12" style="background-color:#202020;"><footer><br><ul><li><a href = "http://104.131.199.122/index">About Us</a></li></ul></footer></div>	<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script></body></html>

