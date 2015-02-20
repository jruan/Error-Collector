<?php
include 'user.php';
include 'group.php';
include 'comment.php';
include 'error.php';
include 'ban.php';

define('SALT','200ok@powell135');
define('CONNECTION_FAIL',-200);
define('USERNAME_EXIST',-100);
define('FAIL',-300);
define('SUCCESS',-123);
define('NOT_ALLOW', -2);
define('SUSPENDED', -3);

//permission code in general
define('ADMIN', -135);
define('USER', -319);

//permision code for group
define('MANAGER', 'ma');
define('DEVELOPER', 'de');

define('ALREADY_INVITED', -303);
//status code for user group relationship
define('REQUEST', 're');
define('CONFIRMED', 'co');
define('INVITED', 'iv'); // just in case, we need invite functionality

//error status code for error
define('NEW', 'ne');
define('IN_PROCESS','ip');
define('SOLVED','so');

//error priority code
define('P1','p1');
define('P2','p2');
define('P3','p3');

//password recovery code
define('CONFLICT','conflict');
define('EMAIL_FAIL','ef');

function cleanInput($input){
	$cleanInput = trim($input);
	$cleanInput = stripslashes($cleanInput);
	$cleanInput = htmlspecialchars($cleanInput, ENT_QUOTES);
	return $cleanInput;
}

function encrypt($input, $salt){
	return crypt($input, $salt);
}


function validate($input){
	return $input == cleanInput($input);
}


// return: CONNECTION_FAIL, FAIL, SUCCESS, SUSPENDED
function login($username, $password){
        $con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
        	return CONNECTION_FAIL;
	}

        $query = "SELECT * FROM jnjn_user WHERE username='$username' and password = '$password'";
        $result = $con -> query($query);

	if($result->num_rows == 1){
		$row = mysqli_fetch_array($result);
		if($row['suspended'])
			return SUSPENDED;

		session_start();
		$_SESSION['jnjn_username'] = $username;  // store the general permission into session cookie
		$_SESSION['gen_per'] = $row['permission'];
		$date = date('Y-m-d H:i:s');
		$query = "update jnjn_user set lastLogin='$date', lastUpdate='$date' where username='$username'";
		$con -> query($query);
		$result -> close();
		return SUCCESS;
	}else{
		$result -> close();
		return FAIL;	
	}
}

// this function is used for password recovery. Before we recover user's password, we need to suspend user's account

function suspendUser($username, $email, $recovery){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
	if ($con -> connect_errno){
        	return CONNECTION_FAIL;
	}
	
	$query = "UPDATE jnjn_user SET suspended='1', recovery='$recovery' WHERE username='$username' and email='$email'";
	$con -> query($query);
	if($con -> affected_rows == 1)
		return SUCCESS;
	else
		return FAIL;
}

function resetPassword($token,$password){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
	if ($con -> connect_errno){
		return CONNECTION_FAIL;
	}
	
	$query = "update jnjn_user set recovery=NULL, password='$password', suspended='0' where recovery='$token'";
	$con -> query($query);
	if($con -> affected_rows == 1)
		return SUCCESS;
	else
		return FAIL;
}

// update the last update time for the user
function writeLastUpdate($username){
	$con = new mysqli('localhost','heng','@powell135','200ok');
	if ($con -> connect_errno){
		return CONNECTION_FAIL;
	}
	$date = date('Y-m-d H:i:s');
	$query = "update jnjn_user set lastUpdate='$date' where username='$username'";
	if($con -> query($query))
		return SUCCESS;
	else
		return FAIL;
}

// This function register user. All user's permissions are set to be o---ordinary user by default. and suspended is false by default.
// return: CONNECTION_FAIL, FAIL, SUCCESS, USERNAME_EXIST

function register($username, $email, $password){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
	if ($con -> connect_errno){
		return CONNECTION_FAIL;
	}
	
	$query = "SELECT * FROM jnjn_user WHERE username='$username'";
	$result = $con -> query($query);
	if($result->num_rows != 0){
		$result -> close();
		return USERNAME_EXIST;
	}else{
		$date = date("Y-m-d H:i:s");
		$permission = USER;
		$query = "insert into jnjn_user (email,password,permission,suspended,username,lastLogin,lastUpdate) values('$email','$password','$permission','false','$username','$date','$date')";
		if($con -> query($query))
			return SUCCESS;
		else
			return FAIL;
	}
}

function updateUser($oldUsername, $username, $email, $password){
	$set = 'SET ';
	$comma = false;
	if($username != ''){
		$set = $set."username='$username' ";
		$comma = true;
	}
	if($email != ''){
		if($comma)
			$set .= ',';
		$set = $set."email='$email' ";
		$comma = true;
	}
	if($password != ''){
		if($comma)
			$set .= ',';
		$set = $set."password='$password' ";
	}
	if($set == 'SET ')
		return FAIL;
	$con = new  mysqli('localhost','heng','@powell135','200ok');
	if ($con -> connect_errno){
		return CONNECTION_FAIL;
	}
	$query = "UPDATE jnjn_user $set WHERE username='$oldUsername'";
	if($con -> query($query))
			return SUCCESS;
		else
			return FAIL;
}

function verifyInput($user, $old_email, $old_password){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	
	$query = "SELECT * FROM jnjn_user WHERE username = '$user'";
	$result = $con -> query($query);
	
	$row = mysqli_fetch_array($result);
	
	if($old_email != '' && $old_password ==''){
		if($row['email'] == $old_email)
			return SUCCESS;

		else
			return FAIL;
	}

	else if($old_email =='' && $old_password != ''){
		if($row['password'] == $old_password)
			return SUCCESS;

		else 
			return FAIL;
	}
	else
		return FAIL;
}

function getPermission($user){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }

	$query = "SELECT * FROM jnjn_user WHERE username = '$user'";

        $result = $con -> query($query);
	if($result -> num_rows == 1){
	        $row = mysqli_fetch_array($result);
		return $row['permission'];
	}
	else
		return FAIL;
}

 function getGroupPermission($groupid, $username){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	$status = CONFIRMED;
        $query = "SELECT * FROM jnjn_user_group WHERE username='$username' and groupid='$groupid' and status='$status'";
        $result = $con -> query($query);
        if($result -> num_rows == 1){
                $row = mysqli_fetch_array($result);
                return $row['permission'];
        }
        else
                return FAIL;
 }
// This function is used when user wants to send a request to join a group
// return: CONNECTION_FAIL, FAIL, SUCCESS

 function request($username, $groupid){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
	if ($con -> connect_errno){
		return CONNECTION_FAIL;
	}
	$status = REQUEST;
	$requester_permission = getGroupPermission($groupid,$username);
	
	if($requester_permission == MANAGER)
		return FAIL;

	$permission = DEVELOPER;
	$query = "insert into jnjn_user_group (groupid, username, status, permission) values('$groupid', '$username', '$status', '$permission')";
	if($con -> query($query))
		return SUCCESS;
	else
		return FAIL;
 }
 
function invite($inviter,$recipient,$groupid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
        $status = INVITED;
        $inviter_permission = getGroupPermission($groupid,$inviter);

        if($inviter_permission != MANAGER)
                return FAIL;
	
	$query = "select * from jnjn_user_group where groupid='$groupid' and username='$recipient'";
	$result = $con -> query($query);

	if($result -> num_rows > 0)
		return ALREADY_INVITED;

        $permission = DEVELOPER;
        $query = "insert into jnjn_user_group (groupid, username, status, permission) values('$groupid', '$recipient', '$status', '$permission')";
        if($con -> query($query))
                return SUCCESS;
        else
                return FAIL;
} 
 
 // This function delete user from table jnjn_user. All records in other tables which are related to this user should be deleted too.
 // Since the CASCADE DELETE is set up for the tables, related records should be deleted by database.
 // return: CONNECTION_FAIL, FAIL, SUCCESS
 function delete($username, $permission){
 	if($permission != ADMIN)
 		return NOT_ALLOW;
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	
 	$query = "delete from jnjn_user where username='$username'";
 	if($con -> query($query)){
 		$query = "delete from jnjn_user_group where username = '$username'";
		if($con -> query($query)){
			$query = "delete from jnjn_comment where username = '$username'";
			if($con -> query($query)){
				return SUCCESS;
			}
			else
				return FAIL;
		}
		else
			return FAIL;
	}
	else
 		return FAIL;
 }
 
 function deleteGroup($groupid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }

	
		$query = "delete from jnjn_group where groupid='$groupid'";
		if($con -> query($query)){
			return SUCCESS;
		}
		else
			return FAIL;
		
 }

 function deleteUserFromGroup($groupid, $user){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	$query = "delete from jnjn_user_group where groupid='$groupid' and username='$user'";	
/*	$query = "delete from jnjn_comment inner join jnjn_error on jnjn_comment.errorId=jnjn_error.id where groupid='$groupid' and username='$user'";*/
	if($con -> query($query)){
			return SUCCESS;
	}
	else
		return FAIL;
 }

 function deleteUsersComment($groupid,$user){
	 $con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	 $query = "delete jnjn_comment from jnjn_comment inner join jnjn_error on jnjn_comment.errorId=jnjn_error.id where groupid='35' and jnjn_comment.username='jruan'";
                if($con -> query($query)){
                        return SUCCESS;
                }
        else
                return FAIL;

 }

 function deleteComment($error_id){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	$query = "delete from jnjn_comment where id='$error_id'";
	if($con -> query($query)){
		return SUCCESS;
	}
	else
		return FAIL;

 } 
 // This function return first 20 user info after/before a given lastLogin time
 // In order to make paging work, $time should be the last user's lastLogin time
 // return array of User objects 
 function allUser($time = 0, $isAscending = true){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$orderSign = '<';
 	$order = 'DESC';
 	if($isAscending){
 		$orderSign = '>';
 		$order = 'ASC';
 	}
 	$permission = USER;
 	$query = "select lastLogin, username, email, suspended, lastUpdate from jnjn_user where permission = '$permission' and lastLogin $orderSign '$time' order by lastLogin $order limit 10";
	$result = $con -> query($query);
	$users = array();
	while ($row = mysqli_fetch_array($result)) {
		$users[] = new User($row['username'],$row['email'],$row['lastLogin'],$row['suspended'],$row['lastUpdate']);
	}
	$result -> close();
	return $users; 
 }

 //This function returns all admins 
 // return an array that contains key value pair. The keys are lastLogin, username, email, suspended
 function allAdmin(){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
	$permission = ADMIN;
 	$query = "select lastLogin, username, email, suspended, lastUpdate from jnjn_user where permission = '$permission'";
 	$result = $con -> query($query);
 	$users = array();
 	while($row = mysqli_fetch_array($result)){
		$users[] = new User($row['username'],$row['email'],$row['lastLogin'],$row['suspended'],$row['lastUpdate']);
 	}
 	$result -> close();
 	return $users;
 }
 
 //This function returns groups that you created
 // return an array of group name and group ids pairs
 function allGroupsIn($username, $permission){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
//	$permission = MANAGER;
	$status = CONFIRMED;
 	$query = "select jnjn_group.groupid,name from jnjn_group inner join jnjn_user_group on jnjn_group.groupid=jnjn_user_group.groupid where username='$username' and status='$status' and permission='$permission'";
 	$result = $con -> query($query);
 	$groupids = array();
 	while($row = mysqli_fetch_array($result)){
 		$groupids[$row['name']] =  $row['groupid'];
 	}
 	$result -> close();
 	return $groupids;
 }

 function allUsersInGroup($groupid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	$status = CONFIRMED;

	$query = "SELECT permission,username FROM jnjn_user_group WHERE groupid='$groupid' and status='$status'";
	$result = $con-> query($query);
	$info = array();

	while($row = mysqli_fetch_array($result)){
		$info[$row['username']] = $row['permission'];
	}

	return $info;
 }
 
 function searchGroupsByName($name,$username){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$permission = MANAGER;
	$query = "select jnjn_group.groupid,name,url,description,setupTime,username from jnjn_group inner join jnjn_user_group on jnjn_group.groupid=jnjn_user_group.groupid where name like '%$name%' and permission='$permission' and jnjn_group.groupid not in (select groupid from jnjn_user_group where username='$username');";
 	$result = $con -> query($query);
 	$groups = array();
 	while($row = mysqli_fetch_array($result)){
 		$groups[] = new Group($row['groupid'],$row['name'],$row['username'],$row['url'],$row['description'],$row['setupTime']);
 	}
 	return $groups;
 }

 //This function returns all requests for the group
 //return an array of usernames
 function allRequest($groupid){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
	$status = REQUEST;
 	$query = "select username from jnjn_user_group where groupid='$groupid' and status='$status'";
 	$result = $con -> query($query);
 	$usernames = array();
 	while($row = mysqli_fetch_array($result)){
 		$usernames[] =  $row['username'];
 	}
 	$result -> close();
 	return $usernames;
 }

 function allInvite($username){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }

	$status = INVITED;
/*	$query = "select groupid from jnjn_user_group where username='$user' and status='$status'";*/
	$query = "select jnjn_group.groupid,name from jnjn_group inner join jnjn_user_group on jnjn_group.groupid=jnjn_user_group.groupid where username='$username' and status='$status'";
        $result = $con -> query($query);
        $groupid = array();
        while($row = mysqli_fetch_array($result)){
                $groupid[$row['name']] =  $row['groupid'];
        }
        $result -> close();
        return $groupid;
 }

 function confirmRequest($groupid, $username){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$status = CONFIRMED;
 	$query = "update jnjn_user_group set status='$status' where groupid='$groupid' and username='$username'";
 	if($con -> query($query))
 		return SUCCESS;
 	else
 		return FAIL;
 } 

 //This function  
 function createGroup($username, $url, $description, $numofpages, $name){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$date = date("Y-m-d H:i:s");
 	$query = "insert into jnjn_group (url, description, numofpages, setupTime, name) values('$url','$description','$numofpages','$date','$name')";
 	if($con -> query($query)){
 		$insert_id = $con -> insert_id;
		$token = ''.$insert_id.$url;
		$token = encrypt($token, SALT);
		$query = "update jnjn_group set token='$token' where groupid='$insert_id'";
		if(!$con -> query($query)){
			$query = "delete from jnjn_group where groupid='$insert_id'";
			$con -> query($query);
			return FAIL;
		}
 		$status = COMFIRMED;
 		$permission = MANAGER;
 		$query = "insert into jnjn_user_group (groupid, username, status, permission) values('$insert_id', '$username', '$status', '$permission')";
 		if($con -> query($query))
 			return $insert_id;
		else{
			$query = "delete from jnjn_group where groupid='$insert_id'";
			$con -> query($query);
			return FAIL;
		}
 	}else
 		return FAIL;
 }
// This function returns the token for group
 function showToken($groupid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
	$query = "select token from jnjn_group where groupid='$groupid'";
	$result = $con -> query($query);
	if($result -> num_rows == 1){
		$row = mysqli_fetch_array($result);
		return $row['token'];
	}else
		return FAIL;
 }

 function tokenToGroupid($token){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$query = "select groupid from jnjn_group where token='$token'";
 	$result = $con -> query($query);
 	if($result -> num_rows == 1){
 		$row = mysqli_fetch_array($result);
 		return $row['groupid'];
 	}else
 		return FAIL;
 	
 }

function postError($groupid, $errorType, $url, $errorInfo,$line){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$time = date('Y-m-d H:i:s');
 	$errorStatus = NEW_ERROR;
 	$priority = P3;
 	$query = "insert into jnjn_error (errorType, time, url, errorInfo, errorStatus, priority, rating, numofvote, groupid,line)
 			values('$errorType', '$time', '$url', '$errorInfo', '$errorStatus', '$priority', '0.0', '0', '$groupid', '$line')";
 	if($con -> query($query))
 		return SUCCESS;
 	else 
 		return FAIL;
 }

function updateRating($errorid, $rating){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	$query = "select * from jnjn_error where id='$errorid'";
	$result = $con -> query($query);

	if($result -> num_rows == 1){
		$row = mysqli_fetch_array($result);
		$numofvotes =  $row['numofvote'];
		
		if($numofvotes != 0){
			$avg_rate = $row['rating'];
			$avg_rate = ($avg_rate + $rating)/2;
			$avg_rate = round($avg_rate);
		}

		else
			$avg_rate = $rating;

		$numofvotes = $numofvotes +  1;
		$query = "update jnjn_error set rating='$avg_rate', numofvote='$numofvotes' where id='$errorid'";
		if($con -> query($query)){
			return SUCCESS;
		}
		else
			return FAIL;
	}
	else
		return FAIL;
	
}

function getAvgRating($errorid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
        $query = "select * from jnjn_error where id='$errorid'";
        $result = $con -> query($query);

	if($result -> num_rows == 1){
		$row = mysqli_fetch_array($result);
		$avg = $row['rating'];
		return $avg;
	}
	else
		return FAIL;
}
//insert a comment into table and return the commentid
function postComment($errorId, $comment, $screenshot, $username){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$time = date('Y-m-d H:i:s');
 	$query = "insert into jnjn_comment(errorId, comment, time, screenShot, username) values('$errorId', '$comment', '$time', '$screenshot', '$username')";
 	if($con -> query($query))
 		return $con -> insert_id;
 	else 
 		return FAIL;
 }

//return first 20 comments objects before $time that in descending order by time

function allComment($errorId, $time){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$query = "select comment, time, screenShot, username, id from jnjn_comment where errorId = '$errorId' and time < '$time' order by time DESC limit 20";
 	$result = $con -> query($query);
 	$comments = array();
 	$i = 0;
 	while ($row = mysqli_fetch_array($result)) {
 		$comments[] = new Comment($row['comment'],$row['time'],$row['screenShot'],$row['username'], $row['id']);
 	}
 	$result -> close();
 	return $comments;
 }

//return first 20 error objects before $time that in descending order by time
function allError($groupid, $time){
 	$con = new  mysqli('localhost','heng','@powell135','200ok');
 	if ($con -> connect_errno){
 		return CONNECTION_FAIL;
 	}
 	$query = "select errorType, time, url, errorInfo, errorStatus, priority, username, rating, numofvote, line,id from jnjn_error where groupid = '$groupid' and time < '$time' order by time DESC limit 5";
 	$result = $con -> query($query);
 	$errors = array();
 	$i = 0;
 	while ($row = mysqli_fetch_array($result)) {
 		$errors[] = new Error($row['errorType'],$row['time'],$row['url'],$row['errorInfo'],$row['errorStatus'],$row['priority'],$row['username'],$row['rating'],$row['numofvote'],$groupid,$row['line'],$row['id']);
 	}
 	$result -> close();
 	return $errors;
 }

function getError($errorid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }
	$query = "select errorType, time, url, errorInfo, errorStatus, priority, username, rating, numofvote,groupid, line,id from jnjn_error where id = '$errorid'";

	$result = $con -> query($query);
	if($result -> num_rows == 1){
		$row = mysqli_fetch_array($result);
		return new Error($row['errorType'],$row['time'],$row['url'],$row['errorInfo'],$row['errorStatus'],$row['priority'],$row['username'],$row['rating'],$row['numofvote'],$row['groupid'],$row['line'],$row['id']);
	}
	else
		return NULL;
}

function errorReport($groupid){
	$con = new  mysqli('localhost','heng','@powell135','200ok');
        if ($con -> connect_errno){
                return CONNECTION_FAIL;
        }

	$query = "select distinct errorType from jnjn_error where groupid='$groupid'";
	$result = $con -> query($query);
	$report = array();
	while($row = mysqli_fetch_array($result)){
		$query = "select count(*) from jnjn_error where groupid='$groupid' and errorType='".$row['errorType'].'\'';
		$r = $con -> query($query);
		$count = mysqli_fetch_array($r);
		$report[$row['errorType']] = $count[0];
	}
	return $report;
}
?>

