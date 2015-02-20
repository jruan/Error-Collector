<?php
        require 'db.php';
        $to = $_POST['email'];
        $to = cleanInput($to);
        $username = $_POST['username'];
        $username = cleanInput($username);
        $nanotime = exec('date +%s%N');
        $hash = sha1($username.''.$nanotime); // generate a temporary token for user which can identify the user
        //verify the username and email, also suspend the account
        $result = suspendUser($username, $to, $hash);
        if($result != SUCCESS){
                echo $result;
                session_start();
                $_SESSION['passwordReset']=CONFLICT;
                header('location:/password_reset.php');
                die();
        }

    $from = '<cse135@200ok.com>';
    $subject = "Password Reset";
    $msg = 'Please follow the link and reset your password: ';
    $msg = $msg."http://104.131.199.122/reset_link.php?q=$hash";
    $msg = wordwrap($msg, 70);

        if(!mail($to,$subject,$msg,"From: $from\n")){
                session_start();
                $_SESSION['passwordReset']=EMAIL_FAIL;
                header('locaiont:/password_reset.php');
                die();
        }
?>

<html>
	<head>
		<meta name = "viewport" content = "height = device-height, width = device-width, initial-scale = 1.0">
                <meta charset = "UTF-8">
                <link href = "http://104.131.199.122/css/bootstrap.css" rel = "stylesheet" type = "text/css">
                <link href = "http://104.131.199.122/css/login_css.css" rel = "stylesheet" type = "text/css">

                <title> Password Reset </title>

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
                                                        <li><a href = "http://104.131.199.122/mysql_login.php"> Sign In </a></li>
                                                </ul>
                                        </div>
                                </div>
                        </div>
			<div class="container">
			 <div class="alert alert-success" role="alert">
                                Password recovery email has successfully sent, Please check the email and follow the instruction.
                                <a href="/mysql_login.php" class="alert-link">Click here to login</a>
                        </div>
			</div>

	</body>
</html>
	

