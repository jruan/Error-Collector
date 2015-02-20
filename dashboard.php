<?php
	require 'db.php';
	session_start();
	$name = "";
	$groupid = "";
	$sent = false;
	$fail = false;
	$already_invited = false;

	if(!(isset($_SESSION['jnjn_username']))){
		header("Location: http://104.131.199.122/mysql_login.php");
	}

	if(getPermission($_SESSION['jnjn_username']) != USER && getPermission($_SESSION['jnjn_username']) == ADMIN){
                header("Location: http://104.131.199.122/admin_dashboard.php");
        }

	else{
		$user = $_SESSION['jnjn_username'];
		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if(!(empty($_POST['invtok']))){
				$token = $_POST['invtok'];
                                $token = cleanInput($token);

                                $groupid = tokentoGroupid($token);
                                $invited_user = $_POST['invite_user'];
                                $invited_user = cleanInput($invited_user);

				$name = $_POST['dashboard_name'];
	                        $name = cleanInput($name);

				$result = invite($user,$invited_user,$groupid);
                         
			        if($result == SUCCESS){
                                       $sent = true;
                                }
				
				else if($result == ALREADY_INVITED){
                                        $already_invited = true;	
				}

				else{
					$fail = true;
				}
			}
			else
				 header("Location:http://104.131.199.122/homepage.php");

		}

		else if(!(empty($_GET['id'])) && !(empty($_GET['name'])) && empty($_GET['user'])){
			$token = $_GET['id'];
			$token = cleanInput($token);
			
			$groupid = tokentoGroupid($token);	
			$name = $_GET['name'];
			$name = cleanInput($name);
			$group_permission = getGroupPermission($groupid, $user);			
			if($group_permission == FAIL || ($group_permission != MANAGER && $group_permission != DEVELOPER)){
				header("Location:http://104.131.199.122/homepage.php");
			}
			else{
				$_SESSION['tok'] = $token;
				$_SESSION['name'] = $name;
			}
		}

		else if(!(empty($_GET['id'])) && !(empty($_GET['user'])) && !(empty($_GET['name']))){
			$token = $_GET['id'];
                        $token = cleanInput($token);

                        $groupid = tokentoGroupid($token);

                        $requester = $_GET['user'];
                        $requester = cleanInput($requester);
			
			$name = $_GET['name'];
                        $name = cleanInput($name);

			$group_permission = getGroupPermission($groupid, $user);
			if($group_permission == FAIL || ($group_permission != MANAGER)){
                                header("Location:http://104.131.199.122/homepage.php");
                        }else{
				confirmRequest($groupid, $requester);
			}
		}
		
		else if(!(empty($_GET['tok'])) && !(empty($_GET['remove'])) && !(empty($_GET['name']))){
			$token = $_GET['tok'];
                        $token = cleanInput($token);

                        $groupid = tokentoGroupid($token);
			$user_to_delete = $_GET['remove'];
	
			$name = $_GET['name'];
                        $name = cleanInput($name);

			$group_permission = getGroupPermission($groupid, $user);

			 if($group_permission == FAIL || ($group_permission != MANAGER)){
                                header("Location:http://104.131.199.122/homepage.php");
                        }else{
				$deleted = deleteUserFromGroup($groupid,$user_to_delete);

				if($deleted != FAIL){
					deleteUsersComment($groupid,$user_to_delete);
				}
			}
		}

		else{
			header("Location:http://104.131.199.122/homepage.php");
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
		<meta  charset = "UTF-8">
		<link href = "http://104.131.199.122/css/dashboard_css.css" rel = "stylesheet" type = "text/css">
		<link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		
		<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!--Import JavaScript-->
		<script src = "http://104.131.199.122/javascript/bootstrap.min.js"></script>
		<script>
			function allError(time){
				var formData =  { 
					'action' : 'search_all_error',
					'time' : time,
					'groupid' : '<?=$groupid; ?>'
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
					$("#all-errors tr:last").after(msg);
				})
				.fail(function(xhr, status, error){
					alert( xhr.responseText );
				})
				return false;
			}
		</script>

		<script type="text/javascript">
      <?php
	$report = errorReport($groupid);
	$var = 'var errors = [[\'errors\',\'percentage error\'],';
	foreach($report as $key => $value){
		$var .=	"['$key', $value],";
	}
	$var = rtrim($var, ',');
	$var .= '];';
	print $var;
      ?>
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(errors);

        var options = {
          title: 'errors'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
    </script>

		<title> Dashboard </title>
		
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
							<li><a href="http://104.131.199.122/homepage.php"> Home </a></li>
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
		
		<div class = "jumbotron">
			<h1 style="color:red;text-align:center;">Dashboard for <?=$name?></h1>	
		</div>
	
		<div class = "container"> 
			<div class = "row">
				<h2 style = "color:#990000;">Getting Started </h2>
				<div class = "col-sm-6">
					<h5> <b>If you have not, please paste the following script into the files of the project that this group is working on</b></h5>

					<div id = "script_container">
                                        <p> &lt;script&gt;var errorFunction = function(errorMessage,url,lineNumber){</p>
                                        <p>if(window.onerror){</p>
                                        <p>function sendRequest(hostname, payload){</p>
                                        <p>var img = new Image();</p>
                                        <p>img.src = hostname + "?" + payload;</p>
                                        <p>}</p>
                                        <p>var payload = "token= <?php echo showToken($groupid);?>" + "&amp;msg=" + errorMessage + "&amp;linenum=" + lineNumber + "&amp;file=" + url;</p>
                                        <p>sendRequest("http://104.131.199.122/error_collector.php", payload);</p>
                                        <p>return true;</p>
                                        <p>}</p>
                                        <p>else{window.onerror = errorFunction;}</p>
                                        <p>}</p>
                                        <p>errorFunction();&lt;/script&gt;</p>
					</div>
                                </div>
				
				 <div class = "col-sm-4">
                                         <div class="table-responsive" style="position:relative;">
                                                <div id = "table-scroll" style = "height:140px;">
                                                        <table class="table table-striped">
                                                                <thead>
                                                                        <tr>
                                                                                <th class = 'header'> Users in This Group </th>
                                                                                <th class = 'header'> Permission </th>
                                                                        </tr>
                                                                </thead>

                                                                <tbody>
                                                                        <?php
                                                                                $users = array();
                                                                                $users = allUsersInGroup($groupid);
                                                                            	$tok = showToken($groupid); 
                                                                                foreach($users as $username => $permission){
                                                                                        echo "<tr>\r\n";
                                                                                        echo "<td>" . $username . "</td>\r\n";
                                                                                        if($permission == MANAGER){
                                                                                                echo "<td>Manager</td>\r\n";
											}
                                                                                        else{
                                                                                                echo "<td>Developer</td>\r\n";
												if($group_permission == MANAGER){
                                                                                                         echo "<td><a href = 'http://104.131.199.122/dashboard.php?tok=$tok&remove=$username&name=$name'>Delete</a></td>\r\n";
                                                                                                }
											}
                                                                                        echo "</tr>\r\n";
                                                                                }
                                                                        ?>
                                                                </tbody>
                                                        </table>
                                                </div>
                                        </div>
					<br>
                                        <div class="table-responsive" style="position:relative;">
                                                <div id = "table-scroll" style = "height:140px;">
                                                        <table class="table table-striped">
                                                                <thead>
                                                                        <tr>
                                                                                <th class ='header'> Pending Requests, Only Manager Can Confirm </th>
                                                                        </tr>
                                                                </thead>

                                                                <tbody>
                                                                        <?php
                                                                                $request = allRequest($groupid);

                                                                                if(count($request) == 0){
                                                                                        echo "<tr>\r\n";
                                                                                        echo "<td>Currently no pending request</td>";
                                                                                        echo "</tr>\r\n";
                                                                                }
										$token = showToken($groupid);
                                                                                foreach($request as $username){
                                                               	                        echo "<tr>\r\n";
                                                                                        echo "<td>$username wishes to join $name</td>";
                                                                                        if($group_permission == MANAGER){
                                                                                                echo "<td><a href ='http://104.131.199.122/dashboard.php?id=$token&user=$username&name=$name'> Confirm </a></td>\r\n";
                                                                                        }
                                                                                        echo "</tr>\r\n";
                                                                                }
                                                                        ?>
                                                                </tbody>
                                                   </table>
                                                </div>
                                        </div>
                                </div>

				<div class = "col-sm-2">
					<h5><b> Invite Other Users to The Group</b></h5><br>
					 <form class = "form-signin" action = "http://104.131.199.122/dashboard.php" method = "POST">
						<?php
							if($sent){
								echo "<p>Invitation Sent</p>";
							}
							if($fail){
								echo "<p> Could not find this user. Try Again.</p>";
							}
							if($already_invited){
								echo "<p> User Already Invited to Group</p>";
							}
						?>
						<input type = "text" name = "invite_user" class = "form-control" placeholder = "The user you want to invite" required><br>
						<input type = "text" name = "invtok" value = "<?=$token?>" style="display:none;">
						<input type = "text" name = "dashboard_name" value = "<?=$name?>" style="display:none;">
						<input type = "submit" class = "btn btn-primary" style="float:right;">
					</form>
				</div>

				
			</div> 


			<div class = "row">
				<br><br><br>
				<h2 style = "color:#990000;">Recent Error Logs </h2>
				<br>
				<div class = "col-sm-8">
					<div class="table-responsive" style="position:relative;">
                                                <div id = "table-scroll" style = "height:300px;">
                                                        <table class="table table-striped" id="all-errors">
								<thead>
									<tr>
										<th>Date of Occurrence</th>	
										<th>Error Type</th>
										<th>Error Info</th>
									</tr>
								</thead>

								<?php
									$requestTime = $_SERVER['REQUEST_TIME'];
							                $time = date('l, F j, Y g:i a', $requestTime);
									$error = array();
									$error = allError($groupid, $time);
									$list_of_errors = '';
									$last_error_time = '';
									if(count($error)==0){
										$list_of_errors .= <<<HTML
											<tr>
												<td>Currently, there are no errors </td>
												<td></td>
												<td></td>
											</tr>
HTML;
									}
									else{
										foreach ($error as $err){
											$errorTime = $err -> getTime();
											$errorType = $err -> getErrorType();
											$errorInfo = $err -> getErrorInfo();
											$errorId = $err -> getErrorid();
	
											$list_of_errors .= <<<HTML
												<tr>
													<td>$errorTime</td>
													<td><a href = 'http://104.131.199.122/error_detail.php?eid=$errorId'>$errorType</a></td>
													<td>$errorInfo</td>
												</tr>					
HTML;
										}
										$last_error_time = $err -> getTime();
									}
									echo $list_of_errors;
									echo <<<HTML
								<tr id="$last_error_time">
									<td></td>
									<td><button type="button" class="btn btn-default" onclick="return allError('$last_error_time')">See More</button></td>
									<td></td>
								</tr>
								</div>
HTML;
     
								?>
					
							</table>
						</div>
					</div>
					
				</div>
				<div class = "col-sm-4">
					<h4> Errors Pie Chart</h4>	
					<div id="piechart" style="width:100%; height: 100%;"></div>
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

