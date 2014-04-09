<?php
session_start();
if(! ($_SESSION["admin"] == 1))
{
	header("Location:login.php");
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
		<script src="js/sorttable.js"></script>
		<script src="js/admin-ondemand.js"></script>
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
							<h1 id="title">Admin</h1>
							<span class="byline">Racquetball Stats Admin</span>
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
								<li><a href="#top" id="top-link" class="skel-panels-ignoreHref"><span class="fa fa-wrench">Admin</span></a></li>
								<li><a href="#main" id="add_player-link" class="skel-panels-ignoreHref"><span class="fa fa-group">Add a Player</span></a></li>
								<li><a href="#main" id="modify_game-link" class="skel-panels-ignoreHref"><span class="fa fa-gamepad">Remove a Game</span></a></li>
								<li><a href="logout.php"><span class="fa fa-sign-out">Logout</span></a></li>
							</ul>
						</nav>
						
				</div>
				
				<div class="bottom">

					<!-- Social Icons -->
						<ul class="icons">
							<li><a href="login.php" class="fa fa-sign-in solo"><span>Sign In</span></a></li>
							<li><a href="#" class="fa fa-level-up solo"><span>Create Account</span></a></li>
							<li><a href="logout.php" class="fa fa-sign-out solo"><span>Sign Out</span></a></li>
						</ul>
				
				</div>
			
			</div>

		<!-- Main -->
			<div id="main">
			
				<!-- Intro -->
					<section id="top" class="one">
						<div class="container">
							<h1>
								Welcome <?php echo $_SESSION["username"]; ?>!
							</h1>
							<p>
								This is a secure page with the ability to add a player, which automatically generates new teams based on the players.
								Games can also be modified or removed in case of an entry mistake.
								Javascript will be used to modify the div id="main_admin"
							</p>
						</div>
					</section>
					<section id="main" class="two">
						<div class="container">
							<p>Body of the admin page with all items.
							</p>
							<div id="main_admin">

							</div>

						</div>
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
