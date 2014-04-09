<?php
session_start();
include('database_connection.php');

if($_POST['action'] == 'playerform')
{
	gen_add_player();
}
elseif($_POST['action'] == 'modify_game_main')
{
	gen_mod_game_main();
}
elseif($_POST['action'] == 'modify_game_1v1')
{
	gen_1v1_game_table(1);
}
elseif($_POST['action'] == 'modify_game_2v1')
{
	gen_2v1_game_table(1);
}
elseif($_POST['action'] == 'modify_game_2v2')
{
	gen_2v2_game_table(1);
}
elseif($_POST['action'] == 'delete_1v1')
{
	$id = (int)($_POST['id']);
	delete_1v1($id);
}
elseif($_POST['action'] == 'delete_2v2')
{
	$id = (int)($_POST['id']);
	delete_2v2($id);
}
elseif($_POST['action'] == 'delete_2v1')
{
	$id = (int)($_POST['id']);
	delete_2v1($id);
}
elseif($_POST['action'] == 'add_player')
{
	$name = mysql_real_escape_string( ($_POST['name']) );
	add_player($name);
}
elseif($_POST['action'] == 'next_1v1')
{
	$page = (int)($_POST['page']);
	gen_1v1_game_table($page+1);
}
elseif($_POST['action'] == 'previous_1v1')
{
	$page = (int)($_POST['page']);
	gen_1v1_game_table($page-1);
}
//admin Functions.
//check if user is admin

//generate a create player form

//create the new player and associated teams.

//on click of modify game, generate 3 buttons, one for each type.
//load a table for each type with games and a modify button
	//select from table limit 20, next limit 20,40 to get in groups of 20. Use select count(*) from table to not limit over rows
	//modify button opens a form with prefilled info, ability to change, then update or delete.

function gen_add_player()
{
			echo "<form id=\"add_player\" action=\"addplayer.php\" method=\"POST\">
			<table id=\"add_player_table\" border=\"1\" class = \"table\" > 	
										<tr>
											<td> 
												Player Name
											</td>
											<td> 
												<input type=\"text\" id=\"newname\" name=\"player_name\">
											</td>
											<td>
												<input type=\"button\" onClick=\"add_player()\" class=\"button\" value=\"Submit\">
										</tr>
									</table>	
									</form>";
}

function gen_mod_game_main()
{
	echo " <p>Choose a Game Type to View</p>
	<input type=\"button\" id=\"mod1v1\" class=\"button\" onClick=\"mod_1v1();\" value=\"1 v 1\">
	<input type=\"button\" id=\"mod2v1\" class=\"button\" onClick=\"mod_2v1();\" value=\"2 v 1\">
	<input type=\"button\" id=\"mod2v2\" class=\"button\" onClick=\"mod_2v2();\" value=\"2 v 2\">
	<div id=\"mod_game_listing_area\">

	</div>

	";
}

function gen_1v1_game_table( $page )
{
	$db = db_connect();
	$perpage = 20;
	$min = ($page-1) * $perpage;
	$query = "SELECT count(*) as entries FROM one_v_one;";
	$result1 = mysqli_query($db, $query);
	while($row1 = mysqli_fetch_array($result1))
		$entries = $row1['entries'];
	$pages = ceil($entries/$perpage);
	$navbuttons="";
	if($page > 1)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"previous_1v1($page)\" style=\"float:left\" value=\"Previous\">";
	}
	$navbuttons .= "Viewing page $page of $pages";
	if($page < $pages)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"next_1v1($page)\" style=\"float:right\" value=\"Next\" >";
	}
	$table = "	<p>$navbuttons</p>
				<table id=\"modify_1v1\">
								<thead>
								<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Date</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Delete</th>
								</tr>
							</thead><tbody> ";
								
								
							

	$query = "SELECT match_id, p1.first_name AS p_name_one, score_one, p2.first_name AS p_name_two, score_two, date_format(date, '%b %e') as date
				FROM one_v_one, players p1, players p2
				WHERE p1.player_id = player_one
				AND   p2.player_id = player_two
				ORDER BY match_id DESC LIMIT $min, $perpage;";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
	{
		if($row['score_one'] < $row['score_two'])
		{
			$style="style=\"background-color: #FFB8B8\"";
			$style1="style=\"background-color:#C9FFC9\"";
		}
		else
		{
			$style="style=\"background-color:#C9FFC9\"";
			$style1="style=\"background-color: #FFB8B8\"";
		}
		$table .= "<tr>";
			$table .= "<td $style>";
				$table .= $row['p_name_one'];
			$table .= "</td>";
			$table .= "<td $style>";
				$table .= $row['score_one'];
			$table .= "</td>";
			$table .= "<td $style1>";
				$table .= $row['p_name_two'];
			$table .= "</td>";
			$table .= "<td $style1>";
				$table .= $row['score_two'];
			$table .= "</td>";
			$table .= "<td>";
				$table .= $row['date'];
			$table .= "</td>";
			$table .= "<td>
							<input type=\"button\" onClick=\"delete_1v1(".($row['match_id']).")\" value=\"Delete\" > 
							</td>
						";
		$table .= "</tr>";
	}
	$table .= "</tbody>
							</table>";
	echo $table;
}

function delete_1v1($id)
{
	$db = db_connect();
	$query = "DELETE FROM one_v_one WHERE match_id = $id;";
	$result = mysqli_query($db, $query);
	gen_1v1_game_table(1);
}

function delete_2v2($id)
{
	$db = db_connect();
	$query = "DELETE FROM two_v_two WHERE match_id = $id;";
	$result = mysqli_query($db, $query);
	gen_2v2_game_table(1);
}

function delete_2v1($id)
{
	$db = db_connect();
	$query = "DELETE FROM two_v_one WHERE match_id = $id;";
	$result = mysqli_query($db, $query);
	gen_2v1_game_table(1);
}

function add_player($name)
{
	$db = db_connect();
	$query = "SELECT count(*) AS rows FROM players WHERE first_name = \"$name\";";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
		$entries = $row['rows'];
	if($entries > 0)
	{
		echo "Player with the name $name already exists.";
		exit();
	}
	$query1 = "INSERT INTO players (first_name) VALUES (\"$name\");";
	$result = mysqli_query($db, $query1);
	//new player is inserted.
	$query = "SELECT player_id FROM players WHERE first_name = \"$name\";";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
		$pid = $row['player_id'];
	$query = "SELECT player_id FROM players WHERE player_id != $pid;";
	$result = mysqli_query($db, $query);
	//generate all teams for this player
	while($row = mysqli_fetch_array($result))
	{
		$otherplayer = $row['player_id'];
		$query = "INSERT INTO teams (player_id_one, player_id_two) VALUES ($pid, $otherplayer);";
		$result1 = mysqli_query($db, $query);
	}
	echo "Success. Added player $name and all associated teams.";
	exit();

}

function gen_2v1_game_table( $page )
{
	$db = db_connect();
	$perpage = 20;
	$min = ($page-1) * $perpage;
	$query = "SELECT count(*) as entries FROM two_v_one;";
	$result1 = mysqli_query($db, $query);
	while($row1 = mysqli_fetch_array($result1))
		$entries = $row1['entries'];
	$pages = ceil($entries/$perpage);
	$navbuttons="";
	if($page > 1)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"previous_2v1($page)\" style=\"float:left\" value=\"Previous\">";
	}
	$navbuttons .= "Viewing page $page of $pages";
	if($page < $pages)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"next_2v1($page)\" style=\"float:right\" value=\"Next\" >";
	}
	$table = "	<p>$navbuttons</p>
				<table id=\"modify_1v1\">
								<thead>
								<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Team</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Date</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Delete</th>
								</tr>
							</thead><tbody> ";
								
								
							

	$query = "SELECT match_id, p1.first_name AS t_name_one, p3.first_name AS t_name_two, team_score AS score_one, p2.first_name AS p_name_two, player_score AS score_two, date_format(date, '%b %e') as date_played
				FROM two_v_one m, players p1, players p2, players p3, teams t
				WHERE p2.player_id = m.player_id
				AND   t.team_id    = m.team_id
				AND   p1.player_id = t.player_id_one
				AND   p3.player_id = t.player_id_two
				ORDER BY match_id DESC LIMIT $min, $perpage;";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
	{
		if($row['score_one'] < $row['score_two'])
		{
			$style="style=\"background-color: #FFB8B8\"";
			$style1="style=\"background-color:#C9FFC9\"";
		}
		else
		{
			$style="style=\"background-color:#C9FFC9\"";
			$style1="style=\"background-color: #FFB8B8\"";
		}
		$table .= "<tr>";
			$table .= "<td $style>";
				$table .= ($row['t_name_one'])." and ".($row['t_name_two']);
			$table .= "</td>";
			$table .= "<td $style>";
				$table .= $row['score_one'];
			$table .= "</td>";
			$table .= "<td $style1>";
				$table .= $row['p_name_two'];
			$table .= "</td>";
			$table .= "<td $style1>";
				$table .= $row['score_two'];
			$table .= "</td>";
			$table .= "<td>";
				$table .= $row['date_played'];
			$table .= "</td>";
			$table .= "<td>
							<input type=\"button\" onClick=\"delete_2v1(".($row['match_id']).")\" value=\"Delete\" > 
							</td>
						";
		$table .= "</tr>";
	}
	$table .= "</tbody>
							</table>";
	echo $table;
}
function gen_2v2_game_table( $page )
{
	$db = db_connect();
	$perpage = 20;
	$min = ($page-1) * $perpage;
	$query = "SELECT count(*) as entries FROM two_v_two;";
	$result1 = mysqli_query($db, $query);
	while($row1 = mysqli_fetch_array($result1))
		$entries = $row1['entries'];
	$pages = ceil($entries/$perpage);
	$navbuttons="";
	if($page > 1)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"previous_2v2($page)\" style=\"float:left\" value=\"Previous\">";
	}
	$navbuttons .= "Viewing page $page of $pages";
	if($page < $pages)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"next_2v2($page)\" style=\"float:right\" value=\"Next\" >";
	}
	$table = "	<p>$navbuttons</p>
				<table id=\"modify_1v1\">
								<thead>
								<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Team</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Team</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Date</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Delete</th>
								</tr>
							</thead><tbody> ";
								
								
							

	$query = "SELECT 	match_id, 
						p1.first_name AS t_name_one, 
						p3.first_name AS t_name_two, 
						team_one_score AS score_one, 
						p2.first_name AS t1_name_one, 
						p4.first_name AS t1_name_two,
						team_two_score AS score_two, 
						date_format(date, '%b %e') AS date_played
				FROM two_v_two m, players p1, players p2, players p3, players p4, teams t, teams t1
				WHERE t1.team_id   = m.team_id_two
				AND   t.team_id    = m.team_id_one
				AND   p1.player_id = t.player_id_one
				AND   p3.player_id = t.player_id_two
				AND   p2.player_id = t1.player_id_one
				AND   p4.player_id = t1.player_id_two
				ORDER BY match_id DESC LIMIT $min, $perpage;";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
	{
		if($row['score_one'] < $row['score_two'])
		{
			$style="style=\"background-color: #FFB8B8\"";
			$style1="style=\"background-color:#C9FFC9\"";
		}
		else
		{
			$style="style=\"background-color:#C9FFC9\"";
			$style1="style=\"background-color: #FFB8B8\"";
		}
		$table .= "<tr>";
			$table .= "<td $style>";
				$table .= ($row['t_name_one'])." and ".($row['t_name_two']);
			$table .= "</td>";
			$table .= "<td $style>";
				$table .= $row['score_one'];
			$table .= "</td>";
			$table .= "<td $style1>";
				$table .= ($row['t1_name_one'])." and ".($row['t1_name_two']);
			$table .= "</td>";
			$table .= "<td $style1>";
				$table .= $row['score_two'];
			$table .= "</td>";
			$table .= "<td>";
				$table .= $row['date_played'];
			$table .= "</td>";
			$table .= "<td>
							<input type=\"button\" onClick=\"delete_2v2(".($row['match_id']).")\" value=\"Delete\" > 
							</td>
						";
		$table .= "</tr>";
	}
	$table .= "</tbody>
							</table>";
	echo $table;
}

?>