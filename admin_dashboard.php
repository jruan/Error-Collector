<!DOCTYPE html>
<?php
	require 'db.php';
	session_start();
	$user = "";
	if((!(isset($_SESSION['jnjn_username'])))) {
		session_destroy();
		header("Content-Type: text/html");
		header("Location: http://104.131.199.122/mysql_login.php");
	}

	if(getPermission($_SESSION['jnjn_username']) != ADMIN && getPermission($_SESSION['jnjn_username']) == USER){
                header("Location: http://104.131.199.122/homepage.php");
        }

	else{
		$requestTime = $_SERVER['REQUEST_TIME'];
	        $time = date('l, F j, Y g:i a', $requestTime);
		$user = $_SESSION['jnjn_username'];
	}

?>


<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
                <meta  charset = "UTF-8">
                <link href = "http://104.131.199.122/css/bootstrap.css" rel = "stylesheet" type = "text/css">
                <link href = "http://104.131.199.122/css/dashboard_css.css" rel = "stylesheet" type = "text/css">
                <link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">

                <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                <!--Import JavaScript-->
                <script src = "http://104.131.199.122/javascript/bootstrap.js"></script>
				
		<script>
			function allUser(time){
				var formData =  { 
					'action' : 'search_all_user',
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

                <title> Administrator </title>

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
                                                        <li class = "active"><a href="#"> Home </a></li>
                                                                               <li class = "dropdown">
                                                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "#">
                                                                        <?=$user?>
                                                                        <b class = "caret"></b>
                                                                </a>

                                                                <ul class = "dropdown-menu">
                                                                        <li> <a href="http://104.131.199.122/logout.php"> Logout </a></li>
                                                                </ul>
                                                       </li>
                                                </ul>
                                </div>
                        </div>
                </div>
	
		<div class = "jumbotron">
               		 <div class="container">
	                      <h1> Welcome to Error Log, <strong id ="user"><?=$user?></strong> </h1>
        	              <h4 style="margin-left:3px;color:white;"><?=$time?> </h4>
                        </div>
                </div>
	
		<div class = "container">
	
			<div class = "col-sm-12">
				<h1> Administrators</h1>
				<div class="table-responsive" style="position:relative;">
					 <div id = "table-scroll" style = "height:200px;">
                                                <table class="table table-striped">
							<thead>
								<th><span class="header">Email</span></th>
								<th><span class="header">Username</span></th>
								<th><span class="header">Last Login</span></th>
							</thead>
							<tbody>
								<?php
									$con = new mysqli("localhost", "heng", "@powell135", "200ok");
									$query = "SELECT * FROM jnjn_user WHERE permission = '-135'";
									$result = $con -> query($query);
									while($row = mysqli_fetch_array($result)){
										echo "<tr>\r\n";
										echo "<td>" . $row['email'] . "</td>\r\n";
										echo "<td>" . $row['username'] . "</td>\r\n";
										echo "<td>" . $row['lastLogin'] . "</td>\r\n";
										echo "</tr>\r\n";
									}
									$result ->close();	
								?>
							</tbody>
						</table>
					</div>
				</div>
				<h1> Developer/Users </h1>
				<a style = "font-size:12pt;" href = "http://104.131.199.122/admin_user_management.php"> Manage Users </a>
				<br><br>
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
								$date = date('Y-m-d H:i:s');
								$users = allUser($date,false);
								$lastLogin = 0;
								foreach($users as $u){
									$email = $u -> getEmail();
									$username = $u -> getUsername();
									$lastLogin = $u -> getLastLogin();
									echo <<<HTML
										<tr>
											<td>$email</td>
											<td>$username</td>
											<td>$lastLogin</td>
										</tr>
HTML;
								}
								echo <<<HTML
								<tr id="$lastLogin">
									<td></td>
									<td><button type="button" class="btn btn-default" onclick="return allUser('$lastLogin')">See More</button></td>
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
		
		<br><br>
		
		<div class="footer">
                        <div class="container">
                                        <p class = "text-muted"><a href = "http://104.131.199.122/index">About Us</a></p>
                        </div>
                </div>
	
	</body>
</html>

