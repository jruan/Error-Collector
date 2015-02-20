<?php



	define('SEARCH_GROUP_BY_NAME','search_group_by_name');
	define('JOIN_GROUP','join_group');
	define('SEARCH_ALL_USER','search_all_user');
	define('SEARCH_ALL_USER_WITH_LINK','search_all_user_with_link');
	define('SEARCH_ALL_ERROR', 'search_all_error');
	define('SEARCH_ALL_COMMENT', 'search_all_comment');
	require 'db.php';
	$action = $_POST['action'];
	session_start();
	if($action == SEARCH_GROUP_BY_NAME){
		$username = $_POST['username'];
		$username = cleanInput($username);
		$groupName = $_POST['group_name'];
		$groupName = cleanInput($groupName);
		$result = searchGroupsByName($groupName,$username);
		$reply = '';
		if(count($result) > 0){
			$reply .= <<<HTML
					 <div class='table-responsive' style='position:relative;' width=100%>
					<div id = 'table-scroll' style = 'height:150px;'>
                                        	<table class='table table-striped'>
						<h4>Result</h4>
						<thead>
							 <th width=20%><span class = 'header'> Name </span></th>
							<th width=20%><span class = 'header'> Manager </span></th>
							<th width=50%><span class = 'header'> Description </span></th>
							<th width=10%><span class = 'header'></span></th>
			                            </thead><tbody>
HTML;
			foreach($result as $group){
				$groupid = $group -> getGroupid();
				$name = $group -> getName();
				$manager = $group -> getManager();
				$description = $group -> getDescription();
				$token = showToken($groupid);
				$reply .= <<<HTML
					<tr>
						<td>$name</td>
						<td>$manager</td>
						<td>$description</td>
						<td><a href = "http://104.131.199.122/homepage.php?id=$token"> Request </a></td>
					</tr>
HTML;
			}
			$reply .= '</tbody></div></div>';
		}
		echo $reply;
	}else if($action == SEARCH_ALL_USER){
		if(!isset($_SESSION['gen_per'])){
			header('HTTP/1.1 500 Internal Server Error');
			print "Gone to the hell";
		}else{
			$permission = $_SESSION['gen_per'];
			if($permission == ADMIN){
				$date = $_POST['time'];
				$date = cleanInput($date);
				$users = allUser($date,false);
				$reply = '';
				$lastLogin= $date;
				if(count($users) != 0){
					foreach($users as $user){
						$email = $user -> getEmail();
						$username = $user -> getUsername();
						$lastLogin = $user -> getLastLogin();
						$reply .= <<<HTML
						<tr>
							<td>$email</td>
							<td>$username</td>
							<td>$lastLogin</td>
						</tr>
HTML;
					}
					$reply .= <<<HTML
								<tr id="$lastLogin">
									<td></td>
									<td><button type="button" class="btn btn-default" onclick="return allUser('$lastLogin')">See More</button></td>
									<td></td>
								</div>
HTML;
					echo $reply;
				}else{
					header('HTTP/1.1 500 Internal Server Error');
					print 'No more users';	
				}
					
			}else{
				header('HTTP/1.1 500 Internal Server Error');
				print "Gone to the beach";
			}
		}
			
	}else if($action == SEARCH_ALL_USER_WITH_LINK){
		if(!isset($_SESSION['gen_per'])){
			header('HTTP/1.1 500 Internal Server Error');
			print "Gone to the hell";
		}else{
			$permission = $_SESSION['gen_per'];
			if($permission == ADMIN){
				$date = $_POST['time'];
				$date = cleanInput($date);
				$users = allUser($date,false);
				$reply = '';
				$lastLogin= $date;
				if(count($users) != 0){
					foreach($users as $user){
						$email = $user -> getEmail();
						$username = $user -> getUsername();
						$lastLogin = $user -> getLastLogin();
						$reply .= <<<HTML
						<tr>
							<td>$email</td>
							<td>$username</td>
							<td>$lastLogin</td>
							<td><a href = 'http://104.131.199.122/admin_user_management.php?user="$username"'> Delete </a></td>
						</tr>
HTML;
					}
					$reply .= <<<HTML
								<tr id="$lastLogin">
									<td></td>
									<td><button type="button" class="btn btn-default" onclick="return allUser('$lastLogin')">See More</button></td>
									<td></td>
								</div>
HTML;
					echo $reply;
				}else{
					header('HTTP/1.1 500 Internal Server Error');
					print 'No more users';	
				}
					
			}else{
				header('HTTP/1.1 500 Internal Server Error');
				print "Gone to the beach";
			}
		}
	}else if($action == SEARCH_ALL_ERROR){
			if(!(isset($_SESSION['jnjn_username']))){
				header('HTTP/1.1 500 Internal Server Error');
				print "Gone to the hell";
			}else{
				$groupid = $_POST['groupid'];
				$time = $_POST['time'];
				$groupid = cleanInput($groupid);
				$time = cleanInput($time);
				$error = allError($groupid, $time);
				if(count($error) == 0){
					header('HTTP/1.1 500 Internal Server Error');
					print 'No more errors';
				}else{
				$reply = '';
				$last_error_time=$time;
				foreach ($error as $err){
					$errorTime = $err -> getTime();
					$errorType = $err -> getErrorType();
					$errorInfo = $err -> getErrorInfo();
					$errorId = $err -> getErrorid();
					$reply .= <<<HTML
								<tr>
									<td>$errorTime</td>
									<td><a href = 'http://104.131.199.122/error_detail.php?eid=$errorId'>$errorType</a></td>
									<td>$errorInfo</td>
								</tr>					
HTML;
					$last_error_time = $errorTime;
				}
				$reply .= <<<HTML
								<tr id="$last_error_time">
									<td></td>
									<td><button type="button" class="btn btn-default" onclick="return allError('$last_error_time')">See More</button></td>
									<td></td>
								</tr>
								</div>
HTML;
				
				echo $reply;
				}
		}
	}else if($action == SEARCH_ALL_COMMENT){
		if(!(isset($_SESSION['jnjn_username']))){
				header('HTTP/1.1 500 Internal Server Error');
				print "Gone to the hell";
		}else{
			$errorid = $_POST['errorid'];
			$time = $_POST['time'];
			$errorid = cleanInput($errorid);
			$time = cleanInput($time);
			$result = allComment($errorid, $time);
			if(count($result) == 0){
				header('HTTP/1.1 500 Internal Server Error');
				print 'No more comments';
			}else{
				$last_comment_time = '';
				$reply = '';

				foreach($result as $comment){
					$username = $comment -> getUsername();
					$c = $comment -> getComment();
					$screenshot = $comment -> getScreenShot();
					$t = $comment -> getTime();
					$reply .= <<<HTML
						 <li><strong>$username</strong>: $c</li>
                                                 <li style = "font-size:10pt;">Date Posted: $t </li>

HTML;
					if($screenshot != ''){
						$reply .= <<<HTML
							<li><img src = "/images/icon.png" style="cursor: pointer" onclick="showImage('/upload/$screenshot')"></li>
HTML;
					}
					$reply .= '<hr>';
					$last_comment_time = $t;
				}
				$reply .= <<<HTML
					<li id="$last_comment_time"><button type="button" class="btn btn-default" onclick="return allComment('$last_comment_time')">See More</button></li>
HTML;
				echo $reply;
				
			}
		}
	}else{

		header('HTTP/1.1 500 Internal Server Error');
		print $action;
		print 'I don\'t even know what is going on';
	}
	
	//	echo $reply;



?>


