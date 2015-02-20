<!DOCTYPE html>
<?php
	require 'db.php';
	session_start();
	$user = "";
	$update_user = false;
	$update_password = false;
	$update_email = false;
	$wrong_email = false;
	$wrong_password = false;
	if(!(isset($_SESSION['jnjn_username']))){
                header("Location: http://104.131.199.122/logout.php");
        }
	else{
		$user = $_SESSION['jnjn_username'];
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if(!(empty($_POST['new_username']))){
				$new_username = $_POST['new_username'];
				$new_username = cleanInput($new_username);
			  	if(updateUser($user, $new_username, '', '') == SUCCESS){
					$_SESSION['jnjn_username'] = $new_username;	
					$user = $new_username;
					$update_user = true;
				}
			}
			else if(!(empty($_POST['old_email'])) && !(empty($_POST['new_email']))){
				$old_email = $_POST['old_email'];
				$old_email = cleanInput($old_email);
				$new_email = $_POST['new_email'];
				$new_email = cleanInput($new_email);
				if(verifyInput($user,$old_email, '') == SUCCESS){
					if(updateUser($user,'', $new_email, '') == SUCCESS){
						$update_email = true;
					}					
				}
				else{
					$wrong_email = true;
				}
			}
			else if(!(empty($_POST['old_password'])) && !(empty($_POST['new_password']))){
				$old_password = $_POST['old_password'];
				$old_password = cleanInput($old_password);
				$old_password = encrypt($old_password, SALT);
				$new_password = $_POST['new_password'];
				$new_password = cleanInput($new_password);
				$new_password = encrypt($new_password, SALT);
				
				if(verifyInput($user, '', $old_password) == SUCCESS){
					if(updateUser($user,'','', $new_password) == SUCCESS){
						$update_password = true;
					}
				}
				else{
					$wrong_password = true;
				}
			}else{}
		}
	}
	
?>

<html>
	<head>
		 <meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
                        <meta charset = "UTF-8">
                        <link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">	
			<link href = "http://104.131.199.122/css/admin_user_management_css.css" rel = "stylesheet" type="text/css">
			<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        	        <!--Import JavaScript-->
	                <script src = "http://104.131.199.122/javascript/bootstrap.min.js"></script>

                        <title> Account Settings </title>	
	</head>

	<body>

			<div class = "navbar navbar-inverse navbar-fixed-top" role = "navigation">
                                 <div class ="container">
                                        <button class = "navbar-toggle" data-toggle = "collapse" data-target = ".collapseNavHeader">


                                                <span class = "icon-bar"></span>
                                                <span class = "icon-bar"></span>
                                                <span class = "icon-bar"></span>

                                        </button>

                                        <div class = "collapse navbar-collapse collapseNavHeader">
                        			 <ul class = "nav navbar-nav navbar-right">
							<?php
								if(getPermission($user) == ADMIN){
									echo "<li><a href='http://104.131.199.122/admin_dashboard.php'> Home </a></li>";
								}else
									echo "<li><a href='http://104.131.199.122/homepage.php'> Home </a></li>";
							?>
                                                        <li class = "dropdown">
                                                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "#">
                                                                        <?=$user?>
                                                                        <!-- "caret" for down arrow -->
                                                                        <b class = "caret"></b>
                                                                </a>

                                                                <ul class = "dropdown-menu">
                                                                         <li><a href="http://104.131.199.122/account_management.php"> Account Setting </a></li>
                                                                        <li> <a href="http://104.131.199.122/logout.php"> Logout </a></li>
                                                                </ul>
                                                       </li>
                                                </ul>                
					</div>
                                </div>
                        </div>
			
			<div class = "jumbotron"></div>

			<div class = "container">
				<h1> Account Setting </h1>
					<div class="col-sm-4">
						<h4> Username : <?=$user?></h4>
						<?php
							if($update_user){
								echo "<p> Username Successfully Updated</p>";
							}
						?>
						<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "POST">
							<input type= "text" class = "form-control"name = "new_username" placeholder = "New Username" style="border:1px solid black;"><br>
							<input type ="submit" class = "btn btn-primary" value = "Update" style="float:right;">
						</form>
					</div>
				
					<div class = "col-sm-4">
						<h4> Update Email </h4>
						<?php 
							if($wrong_email){
								echo "<p>Wrong Old Email</p>";
							}
							if($update_email){
								echo "<p>Email Successfully Updated</p>";
							}
							
						?>
						<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "POST">
							<input type = "email" class ="form-control" name ="old_email" placeholder = "Enter Old Email" style = "border:1px solid black;"><br>
							<input type = "email" class = "form-control" name = "new_email" placeholder = "Enter New Email" style = "border:1px solid black;"><br>
							<input type = "submit" class = "btn btn-primary" value = "update" style = "float:right;">
						</form>
					</div>
				
					<div class = "col-sm-4">
						<h4>Update Password </h4>
						<?php
							if($wrong_password){
								echo "<p> Wrong Old Password</p>";
							}
							if($update_password){
								echo "<p> Password Successfully Updated</p>";
							}
						?>
						 <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "POST">
                                                        <input type = "password" class ="form-control" name ="old_password" placeholder = "Enter Old Password" style = "border:1px solid black;"><br>
                                                        <input type = "password" class = "form-control" name = "new_password" placeholder = "Enter New Password" style = "border:1px solid black;"><br>
                                                        <input type = "submit" class = "btn btn-primary" value = "update" style = "float:right;">
                                                </form>

					</div>
				</div>
			<br><br>
		 <div class="footer">
                                <div class="container">
                                        <p class = "text-muted"><a href = "http://104.131.199.122/index">About Us</a></p>
                                </div>
                 </div>
	</body>
</html>


