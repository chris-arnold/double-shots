<?php
session_start();
include('database_connection.php');
if($_POST['stats'] == '1v1')
{
	gen_1v1_stats();
}
elseif($_POST['player'] == 'p2')
{
	get_player_2(($_POST['pid']));
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
	get_solo_players( ($_POST['tid']) );
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
	get_team_2( ($_POST['tid']) );
}
elseif($_POST['newmatch'] == '2v2')
{
	gen_2v2_form();
}


function recent_1v1()
{
	$db = db_connect();
	$query = "SELECT p1.first_name AS p_name_one, score_one, p2.first_name AS p_name_two, score_two, date_format(date, '%b %e') as date
				FROM one_v_one, players p1, players p2
				WHERE p1.player_id = player_one
				AND   p2.player_id = player_two
				ORDER BY date DESC LIMIT 5;";
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

?> 