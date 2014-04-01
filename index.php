<?php
include('stats_functions.php');
session_start();
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
		<script src="js/onevent_functions.js"></script>
		<?php
			session_start();
			$t = $_SESSION['response'];
			if( $t != "" )
			{
				echo "<script>
					$(document).ready(function (){
                    alert('$t');
                });
				</script> ";
				unset($_SESSION['response']);
			}
		?>
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
							<h1 id="title">Stats</h1>
							<span class="byline">Racquetball Stats</span>
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
								<li><a href="#top" id="top-link" class="skel-panels-ignoreHref"><span class="fa fa-home">Overview</span></a></li>
								<li><a href="#addgame" id="addgame-link" class="skel-panels-ignoreHref"><span class="fa fa-magic">Add Game</span></a></li>
								<li><a href="#stats" id="stats-link" class="skel-panels-ignoreHref"><span class="fa fa-trophy">Stats</span></a></li>
								<li><a href="#archive" id="archive-link" class="skel-panels-ignoreHref"><span class="fa fa-archive">Browse Games</span></a></li>
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

							<!--<a href="http://ineedchemicalx.deviantart.com/art/Moonscape-381829905" class="image featured"><img src="images/pic01.jpg" alt="" /></a>
							-->
							<header>
								<h2 class="alt">This is <strong>Prologue</strong>. A <a href="http://html5up.net/license">free</a>, fully responsive<br />
								site template by <a href="http://html5up.net">HTML5 UP</a>.</h2>
							</header>
							
							<p>This site holds racquetball statistics for games played. 
							It supports 1v1, 2v1 and 2v2 games. 
							Stats can be generated based upon specific game type or all games types together. 
							Currently Basic Stats are only supported. 
							That is score, win, loss, PF/PA, win percentage</p> 							
							<p>Here are the last 5 games played</p>

							<table id="lastfive">
								<thead>
								<tr bgcolor="#FFCC33">
									<th valign="top" class="bodyblack_bold">Player</th>
									<th valign="top" class="bodyblack_bold">Score</th>
									<th valign="top" class="bodyblack_bold">Player</th>
									<th valign="top" class="bodyblack_bold">Score</th>
									<th valign="top" class="bodyblack_bold">Date</th>
								</tr>
							</thead><tbody>
								<?php
									recent_1v1();
								?> 
							</tbody>
							</table>

							<footer>
								<a href="#addgame" class="button scrolly">Add a game</a>
							</footer>

						</div>
					</section>
					
				<!-- Portfolio -->
					<section id="addgame" class="two">
						<div class="container" id="addagame">
					
							<header>
								<h2>Add Game</h2>
							</header>
							
							<p>Choose a Game Type:</p>
							<input type="button" class="button" id="addonevone" value="1 v 1"/>
							<input type="button" class="button" id="addtwovone" value="2 v 1"/>
							<input type="button" class="button" id="addtwovtwo" value="2 v 2"/>
							<input type="button" class="button" id="clear" value="Clear"/>
							<div id="add_a_game">


							</div>
							<!-- </div> -->

							<footer>
								<a href="#stats" class="button scrolly">Stats</a>
							</footer>

						</div>
					</section>

				<!-- About Me -->
					<section id="stats" class="three">
						<div class="container">

							<header>
								<h2>Stats</h2>
							</header>
							<p>Choose A Game Type</p>
							<input type="button" class="button" id="onevone" value="1 v 1"/>
							<input type="button" class="button" id="twovone" value="2 v 1"/>
							<input type="button" class="button" id="twovtwo" value="2 v 2"/>
							<input type="button" class="button" id="overall" value="Overall"/>
						</br>
							<table id="statstable">
							</table>

							<footer>
								<a href="#top" class="button scrolly">Top</a>
							</footer>

						</div>
					</section>
			
				<!-- Contact -->
					<section id="archive" class="four">
						<div class="container">

							<header>
								<h2>Archive</h2>
							</header>
							<p>Choose A Game Type</p>
							<input type="button" class="button" id="onevone_archive" value="1 v 1"/>
							<input type="button" class="button" id="twovone_archive" value="2 v 1"/>
							<input type="button" class="button" id="twovtwo_archive" value="2 v 2"/>

						<p>Archived games. Area to browse previous games. Split up by game type and sortable via player.
							Limit to groups of 20. Sortable tables.</p>	

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
