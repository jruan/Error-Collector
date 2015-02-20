<!DOCTYPE html>
<?php
require 'db.php';
require 'upload.php';
	session_start();
	$user = '';
	$time = '';
	$error_type = '';
	$error_info = '';
	$empty_comment = false;
	$rated = false;

	if(!(isset($_SESSION['jnjn_username']))){
		header("Location:http://104.131.199.122/mysql_login.php");
		die();
	}

	if(getPermission($_SESSION['jnjn_username']) == ADMIN && getPermission($_SESSION['jnjn_username']) != USER){
                header("Location: http://104.131.199.122/admin_dashboard.php");
		die();
        }

	else{
		if(!(empty($_GET['deleteid']))){
			$commentId = $_GET['deleteid'];
			$commentId = cleanInput($commentId);
			if(deleteComment($commentId) == FAIL){
				 header("Location:http://104.131.199.122/homepage.php");
			}
		}

		$error_id = $_GET['eid'];
		if(isset($_SESSION['tok']) && isset($_SESSION['name'])){
			$tok = $_SESSION['tok'];
			$name = $_SESSION['name'];
		
		}
		if(!isset($error_id))
			$error_id = $_POST['eid'];
		if(!isset($error_id)){
			header('location: /404.html');
			die();
		}	
		$user = $_SESSION['jnjn_username'];
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			
                        $error_id = cleanInput($error_id);
                        $error_id = (int)$error_id;
			if(!empty($_POST['comment'])){
				$comment = $_POST['comment'];
				$comment = cleanInput($comment);
				$filename='';
				if(is_uploaded_file($_FILES['upfile']['tmp_name'])){
					$filename = upload();
				}
				postComment($error_id, $comment, $filename, $user);
			}

			if(!empty($_POST['rating'])){
				$rating = $_POST['rating'];
				if(updateRating($error_id, $rating) != FAIL){
					$rated = true;
				}
			}
			
		}

		$error_id = cleanInput($error_id);
		$error_id = (int)$error_id;			
			
		$error = getError($error_id);
		if($error != NULL){
			$groupid = $error -> getGroupid();
			$group_permission = getGroupPermission($groupid, $user);
	
			if($group_permission == FAIL || ($group_permission != MANAGER && $group_permission != DEVELOPER)){
	                                header("Location:http://104.131.199.122/homepage.php");
					die();
        	        }
				
			else{
				$error_type = $error -> getErrorType();
				$error_info = $error -> getErrorInfo();
				$time = $error -> getTime();
        	        }

		}
		else{
			header("Location:http://104.131.199.122/homepage.php");
			die();
		  	
		}

	}
?>

<html>
	 <head>
                <meta charset = "UTF-8">
                <meta name = "viewport" content = "height= device-height, width = device-width, initial-scale = 1.0">
                <link rel ="stylesheet" type = "text/css" href = "http://104.131.199.122/css/bootstrap.css">
                <link rel = "stylesheet" type = "text/css" href = "http://104.131.199.122/css/error_detail_css.css">
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link href="/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="/javascript/star-rating.js" type="text/javascript"></script>
                <!--Import JavaScript-->
                <script src = "http://104.131.199.122/javascript/bootstrap.min.js"></script>
		<script type="text/javascript">
             function showImage(imgName) {
                 document.getElementById('largeImg').src = imgName;
                 showLargeImagePanel();
                 unselectAll();
             }
             function showLargeImagePanel() {
                 document.getElementById('largeImgPanel').style.visibility = 'visible';
             }
             function unselectAll() {
                 if(document.selection) document.selection.empty();
                 if(window.getSelection) window.getSelection().removeAllRanges();
             }
             function hideMe(obj) {
                 obj.style.visibility = 'hidden';
             }

		function allComment(time){
				var formData =  { 
					'action' : 'search_all_comment',
					'time' : time,
					'errorid' : '<?=$error_id; ?>'
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
					$("ul#comments li:last").after(msg);
				})
				.fail(function(xhr, status, error){
					alert( xhr.responseText );
				})
				return false;
		}
		</script>
<style type="text/css">
            #largeImgPanel {
                text-align: center;
                visibility: hidden;
                position: fixed;
                z-index: 100;
                top: 0; left: 0; width: 100%; height: 100%;
                background-color: rgba(100,100,100, 0.5);
            }
</style>		
                <title> Error Detail </title>
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
							<li><a href='http://104.131.199.122/dashboard.php?id=<?=$tok?>&name=<?=$name?>'>Dashboard</a></li>
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

		<div class = "container">
                    <div class = "row">
                        <div class="col-sm-12">
                                <div class="table-responsive" style="position:relative;">
                                                <table class="table table-striped">
                                                        <thead>
                                                                <tr>
                                                                        <th><span class="header">Date</span></th>
                                                                        <th><span class="header">Error Type</span></th>
                                                                        <th><span class="header">Error Description</span></th>
                                                                </tr>
                                                        </thead>

                                                        <tbody>
                                                                <tr>
                                                                	<?php 
										echo "<td> $time </td>\r\n";
										echo "<td> $error_type </td>\r\n";
										echo "<td> $error_info </td>\r\n";
									?>
								</tr>
                                                        </tbody>
                                                </table>
                                </div>
                           </div>
                     </div>

		     <div class = "row">
				<div class = "col-sm-7">
					 <h3> Leave a Comment </h3>
						<br>
						 <form action="http://104.131.199.122/error_detail.php?eid=<?=$error_id?>" METHOD = "POST" enctype="multipart/form-data">
                                                 <label> Upload a screen shot </label> <input type="file" name ="upfile" id="upfile" class = "btn btn-primary">
						<input type="hidden" name="eid" value="<?=$error_id?>"><br>	
                                              	<textarea rows="5" cols = "90" placeholder="Leave a Comment" name = "comment" style = "border:1px solid black;" required></textarea><br><br>
						<input type = "submit" class = "btn btn-primary" value = "Submit">
                                        </form>

				</div>

				<div class = "col-sm-5">
					
					<h3> Severity </h3>
					<form action = "http://104.131.199.122/error_detail.php?eid=<?=$error_id?>" METHOD = "POST">
					<?php
						if($rated){
							echo "<p> Thanks for Rating </p>";
						}
					?>
					<input id="input-21d" name="rating" value="0" type="number" class="rating" min=0 max=5 step=0.5 data-size="sm"><br>
					<input type="hidden" name="eid" value="<?=$error_id?>"><br>
					<?php
						if($rated){
							$avg = getAvgRating($error_id);
							if($avg != FAIL){
								echo "<p> Average Rating: $avg";
							}
						}
					?>

					<button class="btn btn-primary" type="submit">Submit</button>
					</form>
                                        <br><br>	
				</div>
		     </div>		
			
		     <div class ="row">
				<div class = "col-sm-12">
					<h3> Comments</h3>
					<div id = "comment_container">
						<ul id = "comments">
							<?php
								$commentArr = array();
								$requestTime = $_SERVER['REQUEST_TIME'];
                                                                $time = date('l, F j, Y g:i a', $requestTime);
								$commentArr = allComment($error_id, $time);
								$list_of_comments = '';
								
								if(count($commentArr) == 0){
									$list_of_comments .= <<<HTML
									<li> No Comments Posted Yet </li>
HTML;
								}
					
								else{
									$last_comment_time = '';
									foreach($commentArr as $commentObj){
										$username = $commentObj -> getUsername();
										$comment = $commentObj -> getComment();
										$screenshot = $commentObj -> getScreenShot();
										$time = $commentObj -> getTime();
										$id = $commentObj -> getCommentId();
										$list_of_comments .= <<<HTML
										<li><strong>$username</strong>: $comment</li>
										<li style = "font-size:10pt;">Date Posted: $time </li>
HTML;
										if($screenshot != ''){
											$list_of_comments .= <<<HTML
											<li><img src = "/images/icon.png" style="cursor: pointer" onclick="showImage('/upload/$screenshot')"></li>
HTML;
										}
										if($group_permission == MANAGER){
											$list_of_comments .= <<<HTML
											<li><a href = 'http://104.131.199.122/error_detail.php?deleteid=$id&eid=$error_id' style = "font-size:10pt;">Delete</a></li>
HTML;
										}
										$list_of_comments .= <<<HTML
										<hr>
HTML;
										$last_comment_time = $time;
									}
								}
								echo $list_of_comments;
								echo <<<HTML
									<li id="$last_comment_time"><button type="button" class="btn btn-default" onclick="return allComment('$last_comment_time')">See More</button></li>
HTML;
							?>
						</ul>	
						

					</div>
				</div>
		     </div>

		    <br><br>
		</div>
		<div id="largeImgPanel" onclick="hideMe(this);">
            		<img id="largeImg" style="height: 90%; margin: 0; padding: 0;" />
        	</div>
		 <div class="footer">
                        <div class="container">
                                        <p class = "text-muted"><a href = "http://104.131.199.122/index">About Us</a></p>
                        </div>
                </div>

	</body>
</html>

