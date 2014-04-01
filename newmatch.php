<?php
include('stats_functions.php');
session_start();
$response = array();




if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $db = db_connect();
  if( isset($_POST['date_1v1']))
  {
    // if form has been posted process data

    // you dont need the addContact function you jsut need to put it in a new array
    // and it doesnt make sense in this context so jsut do it here
    // then used json_decode and json_decode to read/save your json in
    // saveContact()
    // $play1 = $_POST['player1'];

    $player1 = (int)$_POST['player1'];
    $p1_score = (int)$_POST['p1_score'];
    $player2 = (int)$_POST['player2'];
    $p2_score = (int)$_POST['p2_score'];
    $date_1v1 = mysql_real_escape_string(($_POST['date_1v1']));
    if( is_null($date_1v1) )
    {
      $date_1v1 = date('Y-m-d');
    }
    if( ($p1_score == "") || ($p2_score == "") || ($player1 == $player2) || ($p1_score == $p2_score))
    {
      //var_dump($_POST);
      //echo "ERROR";
      //session_regenerate_id(true);

      $_SESSION['response']="There was an error with your 1v1 submission.";
      header("Location: index.php");
    //  session_write_close();
      exit();
    }
    else
    {
                $query = "INSERT INTO one_v_one (player_one, score_one, player_two, score_two, date) 
                          VALUES ($player1, $p1_score, $player2, $p2_score, \"$date_1v1\");";
                          echo $query;
                $result = mysqli_query($db, $query);
                header("Location: index.php");
                die();


    }
  }
  elseif( isset($_POST['date_2v1']) ) //2v1 match. process and update
  {
    $team1 = $_POST['team1'];
    $team_score = $_POST['team_score'];
    $player1 = $_POST['soloplayer'];
    $p1_score = $_POST['player_score'];
    $date_2v1 = $_POST['date_2v1'];

    if( is_null($date_2v1) )
    {
      $date_2v1 = date('Y-m-d');
    }
    if( ($team_score == "") || ($p1_score == "") || ($team1 == "") || ($player1 == ""))
    {
      $_SESSION['response']="There was an error with your 2v1 submission.";
      header("Location: index.php");
      die();
    }
    else
    {
      $query = "INSERT INTO two_v_one (team_id, team_score, player_id, player_score, date) 
                VALUES ($team1, $team_score, $player1, $p1_score, \"$date_2v1\");";
     // echo $query;
      $result = mysqli_query($db, $query);
      header("Location: index.php");
      die();


    }
  }
  elseif( isset($_POST['date_2v2']) ) //2v1 match. process and update
  {
    $team1 = $_POST['team2_1'];
    $team1_score = $_POST['team1_score'];
    $team2 = $_POST['team2_2'];
    $team2_score = $_POST['team2_score'];
    $date_2v2 = $_POST['date_2v2'];

    if( is_null($date_2v2) )
    {
      $date_2v2 = date('Y-m-d');
    }
    if( ($team1_score == "") || ($team2_score == "") || ($team1 == "") || ($team2 == ""))
    {
      $_SESSION['response']="There was an error with your 2v2 submission.";
      header("Location: index.php");
      die();
    }
    else
    {
      $query = "INSERT INTO two_v_two (team_id_one, team_one_score, team_id_two, team_two_score, date) 
                VALUES ($team1, $team1_score, $team2, $team2_score, \"$date_2v2\");";
     // echo $query;
      $result = mysqli_query($db, $query);
      header("Location: index.php");
      die();
    }
  }
}
?>