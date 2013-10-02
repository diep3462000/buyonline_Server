<?php
	//connect to database
	$database = 'buy_online';

	$dbc = new mysqli("localhost:80", "rails", "VISLq8e85t", $database);
	if ($dbc->connect_errno) {
		echo "Failed to connect to MySQL: (" . $dbc->connect_errno . ") " . $dbc->connect_error;
	}
	if(!$dbc){
		trigger_error("Counld not connect to DB:" .mysqli_connect_error());
	}else {
		//set method connect is utf-8
		mysqli_set_charset($dbc,'utf-8');
	}	
?>