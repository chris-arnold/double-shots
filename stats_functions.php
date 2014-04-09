<?php
session_start();
include('database_connection.php');
if($_POST['stats'] == '1v1')
{
	gen_1v1_stats();
}
elseif($_POST['player'] == 'p2')
{
	get_player_2((int)($_POST['pid']));
}
elseif($_POST['newmatch'] == '1v1')
{
	gen_1v1_form();
}
elseif($_POST['newmatch'] == '2v1')
{
	gen_2v1_form();
}
elseif($_POST['player'] == 'solo')
{
	get_solo_players( (int)($_POST['tid']) );
}
elseif($_POST['stats'] == '2v1')
{
	gen_2v1_stats();
}
elseif($_POST['stats'] == 'overall')
{
	gen_overall_stats();
}
elseif($_POST['stats'] == '2v2')
{
	gen_2v2_stats();
}
elseif($_POST['player'] == '2v2')
{
	get_team_2( (int)($_POST['tid']) );
}
elseif($_POST['newmatch'] == '2v2')
{
	gen_2v2_form();
}
elseif($_POST['archive'] == '1v1')
{
	gen_1v1_archive(1);
}
elseif($_POST['archive'] == '2v1')
{
	gen_2v1_archive(1);
}
elseif($_POST['archive'] == '2v2')
{
	gen_2v2_archive(1);
}
elseif($_POST['archive'] == 'previous_1v1')
{
	$page = (int)($_POST['page']);
	gen_1v1_archive($page-1);
}
elseif($_POST['archive'] == 'next_1v1')
{
	$page = (int)($_POST['page']);
	gen_1v1_archive($page+1);
}
elseif($_POST['archive'] == 'previous_2v1')
{
	$page = (int)($_POST['page']);
	gen_2v1_archive($page-1);
}
elseif($_POST['archive'] == 'next_2v1')
{
	$page = (int)($_POST['page']);
	gen_2v1_archive($page+1);
}
elseif($_POST['archive'] == 'previous_2v2')
{
	$page = (int)($_POST['page']);
	gen_2v2_archive($page-1);
}
elseif($_POST['archive'] == 'next_2v2')
{
	$page = (int)($_POST['page']);
	gen_2v2_archive($page+1);
}

function recent_1v1()
{
	$db = db_connect();
	$query = "SELECT p1.first_name AS p_name_one, score_one, p2.first_name AS p_name_two, score_two, date_format(date, '%b %e') as date
				FROM one_v_one, players p1, players p2
				WHERE p1.player_id = player_one
				AND   p2.player_id = player_two
				ORDER BY match_id DESC LIMIT 5;";
				
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
		echo "<tr>";
			echo "<td $style>";
				echo $row['p_name_one'];
			echo "</td>";
			echo "<td>";
				echo $row['score_one'];
			echo "</td>";
			echo "<td $style1>";
				echo $row['p_name_two'];
			echo "</td>";
			echo "<td>";
				echo $row['score_two'];
			echo "</td>";
			echo "<td>";
				echo $row['date'];
			echo "</td>";
		echo "</tr>";
	}

}

function get_player_1()
{
	$db = db_connect();
	$query = "SELECT player_id, first_name
				FROM players;";
	$result = mysqli_query($db, $query);
	$return="";
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['player_id'];
		$name = $row['first_name'];
		$return .= "<option value=\"$id\">$name</option>\n";
	}
	return $return;
}

function get_player_2( $play1_id )
{
	$db = db_connect();
	$query = "SELECT player_id, first_name
				FROM players
				WHERE player_id != $play1_id
				;";
	$return="<option selected disabled hidden></option>";
	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['player_id'];
		$name = $row['first_name'];
		$return .= "<option value=\"$id\">$name</option>\n";
	}
	echo $return;
}
function get_teams()
{
	$db = db_connect();
	$query = "SELECT team_id, p1.first_name as p1_name, p2.first_name as p2_name 
				FROM teams t, players p1, players p2
				WHERE p1.player_id = t.player_id_one
				AND   p2.player_id = t.player_id_two";
	$result = mysqli_query($db, $query);
	$return="";
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['team_id'];
		$name = $row['p1_name']." and ".$row['p2_name'];
		$return .= "<option value=\"$id\">$name</option>\n";
	}
	return $return;

}

function get_team_2( $team )
{
	$db = db_connect();
	$query = "SELECT player_id_one, player_id_two FROM teams WHERE team_id = $team";
	$result = mysqli_query($db, $query);
	$return="<option selected disabled hidden></option>";
	while($row = mysqli_fetch_array($result))
	{
		$player1 = $row['player_id_one'];
		$player2 = $row['player_id_two'];
	}
	$query = "SELECT team_id, p1.first_name as p1_name, p2.first_name as p2_name
				FROM teams t, players p1, players p2
				WHERE player_id_one != $player1
				AND   player_id_one != $player2
				AND   player_id_two != $player1
				AND   player_id_two != $player2
				AND   p1.player_id   = t.player_id_one
				AND   p2.player_id   = t.player_id_two
				;";

	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['team_id'];
		$name = $row['p1_name']." and ".$row['p2_name'];
		$return .= "<option value=\"$id\">$name</option>\n";
	}
	echo $return;
}

function get_solo_players( $team )
{
	//return selection list of players not on $team
	$db = db_connect();
	$query = "SELECT player_id_one, player_id_two FROM teams WHERE team_id = $team";
	$result = mysqli_query($db, $query);
	$return="<option selected disabled hidden></option>";
	while($row = mysqli_fetch_array($result))
	{
		$player1 = $row['player_id_one'];
		$player2 = $row['player_id_two'];
	}
	$query = "SELECT player_id, first_name
				FROM players
				WHERE player_id != $player1
				AND   player_id != $player2";

	$result = mysqli_query($db, $query);
	while($row = mysqli_fetch_array($result))
	{
		$id = $row['player_id'];
		$name = $row['first_name'];
		$return .= "<option value=\"$id\">$name</option>\n";
	}
	echo $return;

}

function gen_1v1_stats()
{
	$db = db_connect();
	$query = "SELECT * FROM players";
	$result = mysqli_query($db, $query);
	$table="					<thead>	
									<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">GP</th>
									<th valign=\"top\" id=\"tot1v1win\" class=\"bodyblack_bold\">Wins</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Loss</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Win %</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PF</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PA</th>
								</tr>
								</thead>";

	while($row = mysqli_fetch_array($result))
	{
		$pid = $row['player_id'];
		$name = $row['first_name'];
		$wins=0;
		$pf=0;
		$pa=0;
		$loss=0;
		$query = "SELECT * FROM one_v_one
					WHERE ( player_one = $pid OR player_two = $pid);";
		$result1 = mysqli_query($db, $query);
		while($row1 = mysqli_fetch_array($result1))
		{
			//echo ($row1['player_one']);
			//exit;
			if( (($row1['player_one']) == $pid) && (($row1['score_one']) > ($row1['score_two'])))
			{
				$wins=$wins+1;
				$pf+=$row1['score_one'];
				$pa+=$row1['score_two'];
			}
			elseif( (($row1['player_one']) == $pid) && (($row1['score_one']) < ($row1['score_two'])) )
			{
				$loss=$loss+1;
				$pf+=$row1['score_one'];
				$pa+=$row1['score_two'];
			}
			elseif( (($row1['player_two']) == $pid) && (($row1['score_one']) > ($row1['score_two'])))
			{
				$loss=$loss+1;
				$pf+=$row1['score_two'];
				$pa+=$row1['score_one'];
			}
			elseif( (($row1['player_two']) == $pid) && (($row1['score_one']) < ($row1['score_two'])))
			{
				$wins=$wins+1;
				$pf+=$row1['score_two'];
				$pa+=$row1['score_one'];
			}
		}
		$total=$wins + $loss;
		if($total == 0)
			$winper = 0;
		else
			$winper=$wins/$total;
		$winper=number_format($winper,2);
		//generate row of table stats here.
		if($total > 0)
		{
			$table .= "<tr>
					<td>
						$name
					</td>
					<td>
						$total
					</td>
					<td>
						$wins
					</td>
					<td>
						$loss
					</td>
					<td>
						$winper
					</td>
					<td>	
						$pf
					</td>
					<td>
						$pa
					</td>
				</tr>";
		}

	}
	echo $table;
}

function  gen_1v1_form()
{			
	$p1=get_player_1();
	echo "<form id=\"add_match_form\" action=\"newmatch.php\" method=\"POST\">
			<table id=\"one_v_one_table\" border=\"1\" class = \"table\" > 	
										<tr>
											<th>Player/Player</th>
											<th>Score</th>
										</tr>
										<tr>
											<td> 
												<select name=\"player1\" id=\"player1\">
													<option selected disabled hidden></option>
													".$p1."
												</select>
											</td>
											<td> 
												<input type=\"number\" name=\"p1_score\">
											</td>
										</tr>
										<tr>
											<td> 
												<select name=\"player2\" id=\"player2\">
												<option selected disabled hidden value=''></option>
												
												</select>
											</td>
											<td>	
												<input type=\"number\" name=\"p2_score\">
											</td>
										</tr>
										<tr>
											<td>	
												<input type=\"date\" name=\"date_1v1\" value=\"".date('Y-m-d')."\">
											</td>
											<td>	
												<input type=\"submit\" class=\"button\" id=\"submit_1v1\">
											</td>
										</tr>
									</table>	
									</form>";
}

function gen_2v1_form()
{
	$team = get_teams();
		echo "<form id=\"add_match_form\" action=\"newmatch.php\" method=\"POST\">
			<table id=\"two_v_one_table\" border=\"1\" class = \"table\" > 	
										<tr>
											<th>Team/Player</th>
											<th>Score</th>
										</tr>
										<tr>
											<td> 
												<select name=\"team1\" id=\"team1\">
													<option selected disabled hidden value=''></option>
													".$team."
												</select>
											</td>
											<td> 
												<input type=\"number\" name=\"team_score\">
											</td>
										</tr>
										<tr>
											<td> 
												<select name=\"soloplayer\" id=\"soloplayer\">
												</select>
											</td>
											<td>	
												<input type=\"number\" name=\"player_score\">
											</td>
										</tr>
										<tr>
											<td>	
												<input type=\"date\" name=\"date_2v1\" value=\"".date('Y-m-d')."\">
											</td>
											<td>	
												<input type=\"submit\" class=\"button\" id=\"submit_2v1\">
											</td>
										</tr>
									</table>	
									</form>";
}

function gen_2v2_form()
{
	$team = get_teams();
		echo "<form id=\"add_match_form\" action=\"newmatch.php\" method=\"POST\">
			<table id=\"two_v_one_table\" border=\"1\" class = \"table\" > 	
										<tr>
											<th>Team/Team</th>
											<th>Score</th>
										</tr>
										<tr>
											<td> 
												<select name=\"team2_1\" id=\"team2_1\">
													<option selected disabled hidden value=''></option>
													".$team."
												</select>
											</td>
											<td> 
												<input type=\"number\" name=\"team1_score\">
											</td>
										</tr>
										<tr>
											<td> 
												<select name=\"team2_2\" id=\"team2_2\">
												</select>
											</td>
											<td>	
												<input type=\"number\" name=\"team2_score\">
											</td>
										</tr>
										<tr>
											<td>	
												<input type=\"date\" name=\"date_2v2\" value=\"".date('Y-m-d')."\">
											</td>
											<td>	
												<input type=\"submit\" class=\"button\" id=\"submit_2v1\">
											</td>
										</tr>
									</table>	
									</form>";
}

function gen_2v1_stats()
{
	$db = db_connect();
	$query = "SELECT * FROM players";
	$result = mysqli_query($db, $query);
	$table="					<thead>	
									<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">GP</th>
									<th valign=\"top\" id=\"tot1v1win\" class=\"bodyblack_bold\">Wins</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Loss</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Win %</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PF</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PA</th>
								</tr>
								</thead>";

	while($row = mysqli_fetch_array($result))
	{
		$pid = $row['player_id'];
		$name = $row['first_name'];
		$wins=0;
		$pf=0;
		$pa=0;
		$loss=0;
		$query = "SELECT * FROM two_v_one
					WHERE ( (player_id = $pid) 
							OR ( team_id 
								IN ( SELECT team_id 
									 FROM teams 
									 WHERE ( (player_id_one = $pid) 
									 	  OR (player_id_two = $pid) )
									)
								)
						   );";
		$result1 = mysqli_query($db, $query);
		//result1 contains all matchs involving player $pid
		//now process and determine if winner or loser.
		//player_id, player_score, 
		while($row1 = mysqli_fetch_array($result1))
		{
			//var_dump($row1);
			//echo ($row1['player_one']);
			//exit;
			if( (($row1['player_id']) == $pid) && (($row1['player_score']) > ($row1['team_score'])))
			{
				$wins=$wins+1;
				$pf+=$row1['player_score'];
				$pa+=$row1['team_score'];
			}
			elseif( (($row1['player_id']) == $pid) && (($row1['player_score']) < ($row1['team_score'])) )
			{
				$loss=$loss+1;
				$pf+=$row1['player_score'];
				$pa+=$row1['team_score'];
			}
			elseif( (($row1['player_score']) > ($row1['team_score'])))		//player is on team
			{
				$loss=$loss+1;
				$pf+=$row1['team_score'];
				$pa+=$row1['player_score'];
			}
			elseif( (($row1['player_score']) < ($row1['team_score'])))
			{
				$wins=$wins+1;
				$pf+=$row1['team_score'];
				$pa+=$row1['player_score'];
			}
		}
		$total=$wins + $loss;
		if($total == 0)
			$winper = 0;
		else
			$winper=$wins/$total;
		$winper=number_format($winper,2);
		//generate row of table stats here.
		if($total > 0)
		{
			$table .= "<tr>
					<td>
						$name
					</td>
					<td>
						$total
					</td>
					<td>
						$wins
					</td>
					<td>
						$loss
					</td>
					<td>
						$winper
					</td>
					<td>	
						$pf
					</td>
					<td>
						$pa
					</td>
				</tr>";
		}
	}
	echo $table;
}

function gen_2v2_stats()
{
	//still needs work.
	$db = db_connect();
	$query = "SELECT * FROM teams";
	$result = mysqli_query($db, $query);
	$table="					<thead>	
									<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">GP</th>
									<th valign=\"top\" id=\"tot1v1win\" class=\"bodyblack_bold\">Wins</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Loss</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Win %</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PF</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PA</th>
								</tr>
								</thead>";

	while($row = mysqli_fetch_array($result))
	{
		$tid = $row['team_id'];
		$p1 = $row['player_id_one'];
		$p2 = $row['player_id_two'];
		$query = "SELECT first_name 
					FROM players 
					WHERE ( ( player_id = $p1 ) 
						 OR ( player_id = $p2 ) ); ";
		$result2 = mysqli_query($db, $query);
		$i=0;
		$name="";
		while($row2 = mysqli_fetch_array($result2))
		{
			if($i == 0)
			{
				$name .= $row2['first_name'];
			}
			else
			{
				$name .= " and ".$row2['first_name'];
			}
			$i++;
		}
		$wins=0;
		$pf=0;
		$pa=0;
		$loss=0;
		$query = "SELECT * FROM two_v_two
					WHERE ( (team_id_two = $tid)
							OR ( team_id_one = $tid) );";
		$result1 = mysqli_query($db, $query);
		//result1 contains all matchs involving player $pid
		//now process and determine if winner or loser.
		//player_id, player_score, 
		while($row1 = mysqli_fetch_array($result1))
		{
			//var_dump($row1);
			//echo ($row1['player_one']);
			//exit;
			if( (($row1['team_id_one']) == $tid) && (($row1['team_one_score']) > ($row1['team_two_score'])))
			{
				$wins=$wins+1;
				$pf+=$row1['team_one_score'];
				$pa+=$row1['team_two_score'];
			}
			elseif( (($row1['team_id_one']) == $tid) && (($row1['team_one_score']) < ($row1['team_two_score'])) )
			{
				$loss=$loss+1;
				$pf+=$row1['team_one_score'];
				$pa+=$row1['team_two_score'];
			}
			elseif( (($row1['team_id_two']) == $tid) && (($row1['team_one_score']) > ($row1['team_two_score'])) )		//player is on team
			{
				$loss=$loss+1;
				$pf+=$row1['team_two_score'];
				$pa+=$row1['team_one_score'];
			}
			elseif( (($row1['team_id_two']) == $tid) && (($row1['team_one_score']) < ($row1['team_two_score'])) )
			{
				$wins=$wins+1;
				$pf+=$row1['team_two_score'];
				$pa+=$row1['team_one_score'];
			}
		}
		$total=$wins + $loss;
		if($total ==0)
			$winper=0;
		else
			$winper=$wins/$total;

		$winper=number_format($winper,2);
		//generate row of table stats here.
		if($total > 0)
		{
			$table .= "<tr>
					<td>
						$name
					</td>
					<td>
						$total
					</td>
					<td>
						$wins
					</td>
					<td>
						$loss
					</td>
					<td>
						$winper
					</td>
					<td>	
						$pf
					</td>
					<td>
						$pa
					</td>
				</tr>";
			}

	}
	echo $table;
}

function gen_overall_stats()
{
	$db = db_connect();
	$query = "SELECT * FROM players";
	$result = mysqli_query($db, $query);
	$table="					<thead>	
									<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">GP</th>
									<th valign=\"top\" id=\"tot1v1win\" class=\"bodyblack_bold\">Wins</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Loss</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Win %</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PF</th>
									<th valign=\"top\" class=\"bodyblack_bold\">PA</th>
								</tr>
								</thead>";

	while($row = mysqli_fetch_array($result))
	{
		$pid = $row['player_id'];
		$name = $row['first_name'];
		$wins=0;
		$pf=0;
		$pa=0;
		$loss=0;

		//get two v one stats
		$query = "SELECT * FROM two_v_one
					WHERE ( (player_id = $pid) 
							OR ( team_id 
								IN ( SELECT team_id 
									 FROM teams 
									 WHERE ( (player_id_one = $pid) 
									 	  OR (player_id_two = $pid) )
									)
								)
						   );";
		$result1 = mysqli_query($db, $query);
		//result1 contains all matchs involving player $pid
		//now process and determine if winner or loser.
		//player_id, player_score, 
		while($row1 = mysqli_fetch_array($result1))
		{
			//var_dump($row1);
			//echo ($row1['player_one']);
			//exit;
			if( (($row1['player_id']) == $pid) && (($row1['player_score']) > ($row1['team_score'])))
			{
				$wins=$wins+1;
				$pf+=$row1['player_score'];
				$pa+=$row1['team_score'];
			}
			elseif( (($row1['player_id']) == $pid) && (($row1['player_score']) < ($row1['team_score'])) )
			{
				$loss=$loss+1;
				$pf+=$row1['player_score'];
				$pa+=$row1['team_score'];
			}
			elseif( (($row1['player_score']) > ($row1['team_score'])))		//player is on team
			{
				$loss=$loss+1;
				$pf+=$row1['team_score'];
				$pa+=$row1['player_score'];
			}
			elseif( (($row1['player_score']) < ($row1['team_score'])))
			{
				$wins=$wins+1;
				$pf+=$row1['team_score'];
				$pa+=$row1['player_score'];
			}
		}
		//get 1v1 stats
		$query = "SELECT * FROM one_v_one
					WHERE ( player_one = $pid OR player_two = $pid);";
		$result1 = mysqli_query($db, $query);
		while($row1 = mysqli_fetch_array($result1))
		{
			//echo ($row1['player_one']);
			//exit;
			if( (($row1['player_one']) == $pid) && (($row1['score_one']) > ($row1['score_two'])))
			{
				$wins=$wins+1;
				$pf+=$row1['score_one'];
				$pa+=$row1['score_two'];
			}
			elseif( (($row1['player_one']) == $pid) && (($row1['score_one']) < ($row1['score_two'])) )
			{
				$loss=$loss+1;
				$pf+=$row1['score_one'];
				$pa+=$row1['score_two'];
			}
			elseif( (($row1['player_two']) == $pid) && (($row1['score_one']) > ($row1['score_two'])))
			{
				$loss=$loss+1;
				$pf+=$row1['score_two'];
				$pa+=$row1['score_one'];
			}
			elseif( (($row1['player_two']) == $pid) && (($row1['score_one']) < ($row1['score_two'])))
			{
				$wins=$wins+1;
				$pf+=$row1['score_two'];
				$pa+=$row1['score_one'];
			}
		}

		//2v2
		$query = "SELECT * FROM two_v_two
							WHERE ( (team_id_one IN ( SELECT team_id
														FROM teams
													   WHERE ( (player_id_one = $pid)
												  		  OR   (player_id_two = $pid) 
												  		     ) 
												  	) 
									)
							   OR   (team_id_two IN ( SELECT team_id 
							    					   FROM teams
													  WHERE ( (player_id_one = $pid) 
													     OR   (player_id_two = $pid)
													        )
													) 
								    ) 
								  );
												";
		$result1 = mysqli_query($db, $query);
		while($row1 = mysqli_fetch_array($result1))
		{
			$t1 = $row1['team_id_one'];
			$query = " SELECT * FROM teams 
							   WHERE team_id = $t1 
							     AND ( ( player_id_one = $pid )
							      OR   ( player_id_two = $pid )
							     	 );
					 ";

					 //if query returns a value, player is on this team.
					 //if no return value, player is on team 2
			$result3 = mysqli_query($db, $query);
			if( mysqli_num_rows($result3) > 0)
			{
				$team=1;
			}
			else
			{
				$team=2;
			}

			if( ($row1['team_one_score']) > ($row1['team_two_score']) )
			{
				if($team == 1)
				{
					$wins=$wins+1;
					$pf+=$row1['team_one_score'];
					$pa+=$row1['team_two_score'];
				}
				else
				{
					$loss=$loss+1;
					$pf+=$row1['team_two_score'];
					$pa+=$row1['team_one_score'];

				}
			}
			else //if( ($row1['team_one_score']) < ($row1['team_two_score']) )
			{
				if($team == 1)
				{
					$loss=$loss+1;
					$pf+=$row1['team_one_score'];
					$pa+=$row1['team_two_score'];
				}
				else
				{
					$win=$win+1;
					$pf+=$row1['team_two_score'];
					$pa+=$row1['team_one_score'];
				}
			}
		}
		//end 2v2

		$total=$wins + $loss;
		if($total == 0)
			$winper = 0;
		else
			$winper=$wins/$total;
		$winper=number_format($winper,2);
		//generate row of table stats here.
		if($total > 0)
		{
			$table .= "<tr>
					<td>
						$name
					</td>
					<td>
						$total
					</td>
					<td>
						$wins
					</td>
					<td>
						$loss
					</td>
					<td>
						$winper
					</td>
					<td>	
						$pf
					</td>
					<td>
						$pa
					</td>
				</tr>";
			}

	}
	echo $table;
}

function gen_1v1_archive( $page )
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
		$navbuttons .= "<input type=\"button\" onClick=\"archive_previous_1v1($page)\" style=\"float:left\" value=\"Previous\">";
	}
	$navbuttons .= "Viewing page $page of $pages";
	if($page < $pages)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"archive_next_1v1($page)\" style=\"float:right\" value=\"Next\" >";
	}
	$table = "	<p>$navbuttons</p>
				<table id=\"archive_1v1\">
								<thead>
								<tr bgcolor=\"#FFCC33\">
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Player</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Score</th>
									<th valign=\"top\" class=\"bodyblack_bold\">Date</th>
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
		$table .= "</tr>";
	}
	$table .= "</tbody>
							</table>";
	echo $table;
}

function gen_2v1_archive( $page )
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
		$navbuttons .= "<input type=\"button\" onClick=\"archive_previous_2v1($page)\" style=\"float:left\" value=\"Previous\">";
	}
	$navbuttons .= "Viewing page $page of $pages";
	if($page < $pages)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"archive_next_2v1($page)\" style=\"float:right\" value=\"Next\" >";
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
		$table .= "</tr>";
	}
	$table .= "</tbody>
							</table>";
	echo $table;
}

function gen_2v2_archive( $page )
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
		$navbuttons .= "<input type=\"button\" onClick=\"archive_previous_2v2($page)\" style=\"float:left\" value=\"Previous\">";
	}
	$navbuttons .= "Viewing page $page of $pages";
	if($page < $pages)
	{
		$navbuttons .= "<input type=\"button\" onClick=\"archive_next_2v2($page)\" style=\"float:right\" value=\"Next\" >";
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
		$table .= "</tr>";
	}
	$table .= "</tbody>
							</table>";
	echo $table;
}

?> 