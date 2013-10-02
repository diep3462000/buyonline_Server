<?php 
	include('../includes/mysqli_connect.php'); 
	include('../includes/functions.php'); 
	

		$query = " SELECT *
				   FROM products_type ORDER BY product_type_name";
		$result = mysqli_query($dbc, $query);
		confirm_query($result, $query);
		
		$rows = array();
		if (mysqli_num_rows($result) > 0) {
			 while ($row = mysqli_fetch_array($result , MYSQLI_ASSOC)) {
				$rows[] = $row;
			 }
		}
		echo json_encode($rows);
	
?>