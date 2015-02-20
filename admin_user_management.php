<!DOCTYPE html>
<?php
require 'db.php';
	session_start();
	$user = "";
	$missing_field = false;
        $taken_username = false;
	$connection_err = false;	
        $jnjn_username = "";
        $jnjn_password = "";
        $jnjn_email = "";

	if(!(isset($_SESSION['jnjn_username']))){
		header("Location: http://104.131.199.122/logout.php");		
	}
	
	if(getPermission($_SESSION['jnjn_username']) != ADMIN && getPermission($_SESSION['jnjn_username']) == USER){
		header("Location: http://104.131.199.122/homepage.php");
	}

	else{
		$user = $_SESSION['jnjn_username'];
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])){
				$missing_field = true;
			}
			else{
				$jnjn_username = $_POST["username"];
	                        $jnjn_password = $_POST["password"];
        	                $jnjn_email = $_POST["email"];
                	        $jnjn_username = cleanInput($jnjn_username);
                       	 	$jnjn_email = cleanInput($jnjn_email);
				$jnjn_password = cleanInput($jnjn_password);
                        	$hash_password = encrypt($jnjn_password, SALT);
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
                       		else{}	
			}			
		}

		if(!(empty($_GET['user']))){
			$user_to_delete = $_GET['user'];
			$user_to_delete = trim($user_to_delete);
			$user_to_delete = stripslashes($user_to_delete);
			$user_to_delete = htmlspecialchars($user_to_delete);
			if(delete($user_to_delete, ADMIN) == SUCCESS){
				header("Location:http://104.131.199.122/admin_user_management.php");
			}
			else{
				header("Location:http://104.131.199.122/admin_user_mananagement.php");
			}
		}
	}
?>

<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
                <meta  charset = "UTF-8">
		<link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">
		<link href = "http://104.131.199.122/css/admin_user_management_css.css" rel = "stylesheet" type="text/css">
                <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                <!--Import JavaScript-->
                <script src = "http://104.131.199.122/javascript/bootstrap.min.js"></script>
				<script>
			function allUser(time){
				var formData =  { 
					'action' : 'search_all_user_with_link',
					'time' : time
				};
				
				var request = $.ajax({
					url:"/search.php",
					type: "POST",
					data: formData, 
					dataType: "html"
				})
				.done(function( msg ){
					var row = document.getElementById(""+time);
   					 row.style.display = 'none';
					$(""+time).hide();
					$("#all-users tr:last").after(msg);
				})
				.fail(function(xhr, status, error){
					alert( xhr.responseText );
				})
				return false;
			}
		</script>

                <title> User Management </title>
	</head>

	<body>
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
                                                        <li><a href="http://104.131.199.122/admin_dashboard.php"> Home </a></li>
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
			
		<div class="row" style="margin-left:15px;">
			<div class=col-sm-12">
				<h1> Manage Users </h1>
			</div>

		</div>

		<div class="row">
			<div class = "col-sm-2" style = "margin-left:15px;">
				<form class = "form-signin" action ="http://104.131.199.122/admin_user_management.php" method = "POST">

					<h4>Add User</h4>
					<?php
						if($missing_field){
							echo"<h5> Missing Input Fields</h5>";
						}
						if($taken_username){
							echo"<h5> Username Has Been Taken</h5>";
						}
					?>
					<div class = "form-signin-container">
						<input type="email" class="form-control" placeholder="Email Address" name = "email" style="border:1px solid black;"><br>
						<input type ="text" class="form-control" placeholder = "Username" name ="username" style = "border:1px solid black;"><br>
						<input type="password" class="form-control" placeholder="Password" name="password" style = "border:1px solid black;">
			
						<br>
						<input type="submit" class="btn btn-primary" value="Add">
						<button type="button" class="btn btn-primary" onclick = "window.location = 'http://104.131.199.122/admin_user_management.php';">Cancel</button>
						<br><br>
					</div>
				 </form>

				 <form class = "form-signin" action ="/update_user_info.php" method = "POST">
					<h4> Modify User Info </h4>
					<div class = "form-signin-container">
						<input type = "text" class = "form-control" placeholder = "User to Modify" name = "modify_user" style="border:1px solid black;"><br>
						<input type = "email" class = "form-control" placeholder = "New Email/Blank If No Change" name = "new_email" style = "border:1px solid black;"><br>
						<input type = "text" class = "form-control" placeholder = "New Username/Blank If No Change" name ="new_username" style = "border:1px solid black;"><br>
						<input type = "password" class = "form-control" placeholder = "New Password/Blank If No Change" name = "new_password" style = "border:1px solid black;"><br>
						 <input type="submit" class="btn btn-primary" value="Save">
                                                <button type="button" class="btn btn-primary" onclick = "window.location = 'http://104.131.199.122/admin_user_management.php';">Cancel</button>
                                                <br><br>

					</div>
				</form>
			</div>	


			<div class="container">
				<div class = "col-sm-10">
					<h4 style="margin-left:20px;"> Users/ Developers </h4>
					<div class="table-responsive" style = "position:relative;">
                	                        <div id = "table-scroll-2">
                        	                        <table class="table table-striped" id="all-users">
                                	                        <thead>
                                        	                        <th><span class="header">Email</span></th>
                                                	                <th><span class="header">Username</span></th>
                                                        	        <th><span class="header">Last Login</span></th>
															
                                                        	</thead>
                                                  	 
			      					<tbody>
                                                                	<?php
										 $requestTime = $_SERVER['REQUEST_TIME'];
		                                                                 $time = date('l, F j, Y g:i a', $requestTime);																	
								/*		$date = date('Y-m-d H:i:s');*/
										$users = allUser($time, false);
	                                                                        foreach($users as $u){
                                	                                                echo "<tr>\r\n";
                	                                                                echo "<td>" . $u -> getEmail() . "</td>\r\n";
                        	                                                        echo "<td>" . $u -> getUsername() . "</td>\r\n";
                                        	                                        echo "<td>" . $u -> getLastLogin() . "</td>\r\n";
											echo "<td><a href = 'http://104.131.199.122/admin_user_management.php?user=".$u->getUsername() . "'> Delete </a></td>\r\n";
                                                	                                echo "</tr>\r\n";
                                                        	                        $last_login_time = $u -> getLastLogin();
                                                                        	}
																			echo <<<HTML
								<tr id="$last_login_time">
									<td></td>
									<td><button type="button" class="btn btn-default" onclick="return allUser('$last_login_time')">See More</button></td>
									<td></td>
								</tr>
								</div>
HTML;
                                                                	?>
                                                        	</tbody>
                                                	</table>
                                        	</div>
                                	</div>
				</div>
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


