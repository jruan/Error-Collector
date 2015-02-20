<!DOCTYPE html>

<?php
	require 'db.php';
	session_start();
	$user = "";
	$request = false;

	if(!(isset($_SESSION['jnjn_username']))){
		header("Location: http://104.131.199.122/mysql_login.php");
	}

	if(getPermission($_SESSION['jnjn_username']) != USER && getPermission($_SESSION['jnjn_username']) == ADMIN){
                header("Location: http://104.131.199.122/admin_dashboard.php");
        }


	else{
		$user = $_SESSION['jnjn_username'];
		$requestTime = $_SERVER['REQUEST_TIME'];
                $time = date('l, F j, Y g:i a', $requestTime);		
		$missing_field = false;
		$request_sent = false;
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if(!(empty($_POST['create_group_name'])) && !(empty($_POST['description'])) && !(empty($_POST['numofpages'])) && !(empty($_POST['url_or_path'])) && empty($_POST['search_group_name'])){
				$group_name = $_POST['create_group_name'];
				$group_name = cleanInput($group_name);
					
				$url = $_POST['url_or_path'];
				$url = cleanInput($url);
				
				$description = $_POST['description'];
				$description = cleanInput($description);
				
				$numofpages = $_POST['numofpages'];
				$numofpages = cleanInput($numofpages);
				
				$group_id = createGroup($user, $url, $description, $numofpages, $group_name);
				if($group_id != FAIL){
					header("Content-Type: text/html");
					header("Location: http://104.131.199.122/dashboard.php?id=$group_id&name=$group_name");
				}
					
			}	

			else if(!(empty($_POST['search_group_name'])) && empty($_POST['create_group_name']) && empty($_POST['description']) && empty($_POST['numofpages']) && empty($_POST['url_or_path'])){
				$request_group = $_POST['search_group_name'];
				$request_group = cleanInput($request_group);
				$request = true;
			}
			else{
				$missing_field = true;
			}
			
		}
	
		else if(!(empty($_GET['id'])) && empty($_GET['tok']) && empty($_GET['user']) && empty($_GET['confTok'])){
			$token = $_GET['id'];
			$token = cleanInput($token);

			$id = tokentoGroupid($token);
		  	
	
			if(request($user, $id) != FAIL){
				$request_sent = true;	
			}
			else{
				header("Content-Type: text/html");
				header("Locaiton: http://104.131.199.122/homepage.php");
			}
		}

	        else if(!(empty($_GET['tok'])) && empty($_GET['id']) && empty($_GET['user']) && empty($_GET['confTok'])){
			$token = $_GET['tok'];
			$token = cleanInput($token);
			
                        $id = tokentoGroupid($token);		
			
			$group_permission = getGroupPermission($id, $user);
                        if($group_permission == FAIL || ($group_permission != MANAGER)){
                                header("Location:http://104.131.199.122/homepage.php");
                        }
			else
				deleteGroup($id);
		}
	
		else if(!(empty($_GET['user'])) && !(empty($_GET['confTok'])) && empty($_GET['tok']) && empty($_GET['id'])){
			$token = $_GET['confTok'];
			$token = cleanInput($token);

                        $id = tokentoGroupid($token);
			$confirm_username = $_GET['user'];
			$confirm_username = cleanInput($confirm_username);
			
			$group_permission = getGroupPermission($groupid, $user);
                        if($group_permission == FAIL || ($group_permission != MANAGER && $group_permission != DEVELOPER)){
                                header("Location:http://104.131.199.122/homepage.php");
                        }

			confirmRequest($id, $confirm_username);
		}	

		else{
			header("Locaiton: http://104.131.199.122/homepage.php");
		}
	
	}
?>


<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
		<meta  charset = "UTF-8">
		<link href = "http://104.131.199.122/css/bootstrap.min.css" rel = "stylesheet" type = "text/css">
		<link href = "http://104.131.199.122/css/homepage_css.css" rel = "stylesheet" type = "text/css">
			
		<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src = "http://104.131.199.122/javascript/bootstrap.min.js"></script>
		
		<script>
			
			function searchGroupsByName(username){
				var formData =  { 
					'group_name' : $('input[name=search_group_name]').val(), 
					'action' : 'search_group_by_name',
					'username' : username
				};
				
				var request = $.ajax({
					url:"/search.php",
					type: "POST",
					data: formData, 
					dataType: "html"
				})
				.done(function( msg ){
					$("#search-results").html(msg);
				})
				.fail(function(xhr, status, error){
					$("#search-results" ).html( xhr.responseText );
					alert("FAILURE");
				})

				return false;
			}
		
		</script>

		<title> Home </title>
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
                                                        <li class = "active"><a href="http://104.131.199.122/homepage.php"> Home </a></li>
                                                                               <li class = "dropdown">
                                                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "#">
                                                                        <?=$user?>
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
                         <div class="container">
                              <h1> Welcome to Error Log, <strong id ="user"><?=$user?></strong> </h1>
                              <h4 style="margin-left:3px;color:white;"><?=$time?> </h4>
                        </div>
                </div>
		<div class="container">	
			<div class = "col-sm-4">
				<h3> Request to Join a Group </h3><br>
				<form role="form"  class = "form-signin" method = "POST" onsubmit="return searchGroupsByName('<?=$user;?>')">
					<div class = "form-signin-container">
						<input type = "text" name = "search_group_name" class = "form-control" placeholder = "Search for a Group" style="border:1px solid black;" required>
						<br><input type = "submit" class = "btn btn-primary" value = "Search" style = "float:right;"><br><br><br>
						<div id='search-results'></div>
						<br>
					</div>
				</form>
			</div>
			<div class = "col-sm-4">
				<h3> Create a Group </h3><br>
				<form class = "form-signin" action = "http://104.131.199.122/homepage.php" method = "POST">
					<div class = "form-signin-container">
						<input type = "text" name = "create_group_name" class = "form-control" placeholder = "Enter Name of Group You Want to Create" style = "border:1px solid black;" required><br>
						<input type = "text" name = "url_or_path" class = "form-control" placeholder = "Enter in the url or path to the location of your files" style = "border:1px solid black;" required><br>
						<textarea name = "description" rows = "3" col = "20" placeholder = "Enter in a small description" class = "form-control" style = "border:1px solid black;" required></textarea><br>
						<strong> Expected Num of Files: </strong><br><input type = "text" name = "numofpages" class = "form-control" placeholder = "Num of Files" style = "border:1px solid black;width:50%;float:left;" required>	
						<input type = "submit" class = "btn btn-primary" value = "Create" style = "float:right;">
					</div>
				</form>
			</div>

			<div class = "col-sm-4">
				<h3> Pending Invitations </h3>	
				<div class="table-responsive" style="position:relative;">
                                                <div id = "table-scroll" style = "height:200px;border-left:1px solid black;border-bottom:1px solid black;">
                                                        <table class="table table-striped">
                                                                <thead>
                                                                        <tr>
                                                                                <th class = 'header'>Accept Invitations</th>
                                                                        </tr>
                                                                </thead>
				
								<tbody>
									<?php
										$invitaion = array();
										$invitation = allInvite($user);
										$list_of_invitation = '';
		
										if(count($invitation) == 0){
											$list_of_invitation .= <<<HTML
											<tr>
											<td> No Pending Invitation </td>
											</tr>
HTML;
											echo $list_of_invitation;
										}
										else{
											foreach($invitation as $groupname => $id){
												$token = showToken($id);
												echo "<tr>\r\n";
												echo "<td> Invitation to join $groupname</td>\r\n";
												echo "<td><a href='http://104.131.199.122/homepage.php?user=$user&confTok=$token'>Accept</a></td>\r\n";
												echo "</tr>\r\n";
											}
										}
									?>
								</tbody>
							</table>
						</div>
				</div>	
			</div>

			<div class ="col-sm-10">
				<br><br>
				<h2> My Groups </h2>
				<br>
				<?php
					$groups_manager = array();
					$groups_manager = allGroupsIn($user,MANAGER);
					
					$groups_developer = array();
					$groups_developer = allGroupsIn($user, DEVELOPER);

					$length_manager = count($groups_manager);		
					$length_developer = count($groups_developer);
				
					if($length_manager == 0 && $length_developer == 0){	
						echo "<p> You are currently not in any groups. Request to join a group or create your own and begin tracking errors on your own project or help others in their projects </p>";

					}
					else{
						echo "<div class='table-responsive' style='position:relative;'>\r\n";
						echo "<div id = 'table-scroll' style = 'height:280px;border-left:1px solid black;border-bottom:1px solid black;'>\r\n";
						echo "<table class='table table-striped'>\r\n";
						echo "<thead>\r\n";
						echo "<th><span class = 'header'>Group Name </span></th>\r\n";						
						echo "<th><span class = 'header'> Permission</span></th>\r\n";
						echo "</thead>\r\n";
						echo "<tbody>\r\n";
						foreach($groups_manager as $name => $id){
							$token = showToken($id);
							echo "<tr>\r\n";
							echo "<td><a href = 'http://104.131.199.122/dashboard.php?id=$token&name=$name'>" . $name . "</a></td>\r\n";
							echo "<td> Manager </td>\r\n";
							if(getGroupPermission($id, $user) == MANAGER){
								echo "<td><a href = 'http://104.131.199.122/homepage.php?tok=$token'>Delete</a></td>\r\n";
							}
							echo "</tr>\r\n";
						}
					
						foreach($groups_developer as $name => $id){
							$token = showToken($id);
							echo "<tr>\r\n";
                                                        echo "<td><a href = 'http://104.131.199.122/dashboard.php?id=$token&name=$name'>" . $name . "</a></td>\r\n";	
							if(getGroupPermission($id, $user) == MANAGER){
                                                                echo "<td><a href = 'http://104.131.199.122/homepage.php?tok=$token'>Delete</a></td>\r\n";
                                                        }

                                                        echo "<td> Developer </td>\r\n";
                                                        echo "</tr>\r\n";

						}
	
						echo "</tbody>\r\n";
						echo "</table>\r\n";
						echo "</div>\r\n";
						echo "</div>\r\n";
					}
				?>
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

