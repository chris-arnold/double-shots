<?php
session_start();
include('database_connection.php');
$_SESSION["logged_in"] = 0;
if ( isset( $_POST["submit"] ) )
{
	$e = trim($_POST["username"]);
	$p = trim($_POST["pwd"]);
	$e = mysql_real_escape_string($e);
	if(strlen($e) >0 && strlen($p) >0)
	{
		$db = db_connect();
		if (!$db)
		{
			$_SESSION["pageerror"] = "Error Connecting to Database";
		}
		else
		{
			$query = "SELECT user_id, password from users where username = '$e' AND status = 1;";
			$result = mysqli_query($db, $query);
			$row = mysqli_fetch_assoc($result);
			if(crypt($p, $row['password']) == $row['password'])
			{
				$_SESSION["logged_in"] = 1;
				$_SESSION["admin"] = 1;
				$_SESSION["username"] = $e;
				$_SESSION["user_id"] = $row['user_id'];
				mysqli_close($db);
				$_SESSION["pageerror"] = "SUCCESS";
				header("Location:admin.php");
			}
			else
			{
				$_SESSION["pageerror"] = "Incorrect username/password.";
				header("Location:login.php");
			}
		}
	}
	else
	{
		$_SESSION["pageerror"] = "Incorrect username/password.";
		header("Location:login.php");
	}
}
?>
<!DOCTYPE HTML>
<!--
	Prologue 1.2 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Prologue by HTML5 UP</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600" rel="stylesheet" type="text/css" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
	</head>
	<body>

		<!-- Header -->
			<div id="header" class="skel-panels-fixed">

				<div class="top">

					<!-- Logo -->
						<div id="logo">
							<span class="image avatar48"><img src="images/avatar.jpg" alt="" /></span>
							<h1 id="title">Login</h1>
							<span class="byline">Racquetball Stats Admin Login</span>
						</div>

					<!-- Nav -->
						<nav id="nav">
							<!--
							
								Prologue's nav expects links in one of two formats:
								
								1. Hash link (scrolls to a different section within the page)
								
								   <li><a href="#foobar" id="foobar-link" class="skel-panels-ignoreHref"><span class="fa fa-whatever-icon-you-want">Foobar</span></a></li>

								2. Standard link (sends the user to another page/site)

								   <li><a href="http://foobar.tld"><span class="fa fa-whatever-icon-you-want">Foobar</span></a></li>
							
							-->
							<ul>
								<li><a href="#top" id="top-link" class="skel-panels-ignoreHref"><span class="fa fa-wrench">Admin Login</span></a></li>
								<li><a href="index.php"><span class="fa fa-sign-out">Overview</span></a></li>
							</ul>
						</nav>
						
				</div>
				
				<div class="bottom">

					<!-- Social Icons -->
						<ul class="icons">
							<li><a href="#" class="fa fa-sign-in solo"><span>Sign In</span></a></li>
							<li><a href="#" class="fa fa-level-up solo"><span>Create Account</span></a></li>
							<li><a href="#" class="fa fa-sign-out solo"><span>Sign Out</span></a></li>
						</ul>
				
				</div>
			
			</div>

		<!-- Main -->
			<div id="main">
			
				<!-- Intro -->
					<section id="top" class="one">
						<div class="container">
						</br>
							<header>
								<h2 class="alt">
									Sign In
								</h2>
							</header>
						</div>
					</section>
					<section id="login" class="two">
							<form action="login.php" method="post">
                                <p>Username: <input class= "input-login" type="text" name="username" id="username" /></p>
		                        <p>Password: <input class= "input-login" type="password" name="pwd" id="pwd" /></p>
		                        <p><input class ="button button-login"type="submit" name="submit" value="Submit" id="submit"/></p>
		                        <div id="pageerror"><?php print $_SESSION["pageerror"]; $_SESSION["pageerror"] = "" ?></div>
		            	    </form>
					</section>
			
			</div>

		<!-- Footer -->
			<div id="footer">
				
				<!-- Copyright -->
					<div class="copyright">
						<p>&copy; 2013. All rights reserved.</p>
						<ul class="menu">
							<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
						</ul>
					</div>
				
			</div>

	</body>
</html>
