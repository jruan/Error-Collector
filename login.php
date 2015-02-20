<?php 
	session_start();
	$wrong_password = "false";
	if(isset($_SESSION['wrong'])){
		$wrong_password = "true";
	}
?>

	
<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
		<meta charset = "UTF-8">
		<link href = "http://104.131.199.122/css/bootstrap.css" rel = "stylesheet" type = "text/css">
		<link href = "http://104.131.199.122/css/login_css.css" rel = "stylesheet" type = "text/css">
		
		<title> Login </title>
		
	</head>
	
	<body style="background-color:gray;">
		<div class = "navbar navbar-inverse navbar-fixed-top" role = "navigation">
			<div class ="container">
				<button class = "navbar-toggle" data-toggle = "collapse" data-target = ".collapseNavHeader"> 

						<!-- Number of lines to show in dropdown bar when screen is resized -->
						<span class = "icon-bar"></span>
						<span class = "icon-bar"></span>
						<span class = "icon-bar"></span>

				</button>
			
				<div class = "collapse navbar-collapse collapseNavHeader">
						<ul class = "nav navbar-nav navbar-right">
							<li><a href="http://104.131.199.122/marketing_page"> Features </a></li>
							<li> <!-- Indent home using "active" --> 
								<a href = "http://104.131.199.122/index"> Contact </a>
							</li>
						</ul>
				</div>
			</div>
		</div>

		<div class = "container">
			 <form class="form-signin" method = "POST" role="form" action ="http://104.131.199.122/dashboard.php">
				<h2 class="form-signin-heading">Please Sign In</h2>
				<?php
					if($wrong_password == "true"){
						echo "<h4 style='color:white;'> Wrong Password </h4>";
						unset($_SESSION['wrong']);
	
					}
				?>
				<div id = "form-signin-container">
					<input type="text" class="form-control" placeholder="Username" name = "username"><br>
					<input type="password" class="form-control" placeholder="Password" name="password">
			
					<label>
						<br>
						<a href="#" style = "color:#3333CC;">Forgot Password</a>
					</label>
					<br><br>
					<input type="submit" class="btn btn-primary" value="Sign In">
					<button type="button" class="btn btn-primary">Sign Up</button>
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

