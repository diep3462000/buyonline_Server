<?php 
	include('../includes/mysqli_connect.php'); 
	include('../includes/functions.php'); 
	
	if(isset($_GET['uid'])){
		$uid = $_GET['uid'];
		$query = "SELECT CONCAT_WS(' ', first_name, last_name) AS name, email, website, yahoo, bio, sex, mobile_phone, home_phone, avatar
				  FROM users
				  WHERE user_id = {$uid} LIMIT 1";	
		$result = mysqli_query($dbc, $query);
		confirm_query($result, $query);
		
		$row = array();
		if (mysqli_num_rows($result) ==1) {
			//list($name,$email,$website,$yahoo,$bio,$sex,$mobi_phone,$home_phone,$avatar) = mysqli_fetch_array($r,MYSQLI_NUM);
			$row = mysqli_fetch_array($result, MYSQLI_NUM);
		}
		echo json_encode($row);
	}
?>
