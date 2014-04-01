<?php

function db_connect()
{
	$db = mysqli_connect("localhost", "user", "pw", "database"); 
	return $db;
}

?>
