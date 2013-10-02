<?php 
	include('../includes/mysqli_connect.php'); 
	include('../includes/functions.php'); 
	
	if(isset($_GET['uid'])){
		$uid = $_GET['uid'];
		$query = "SELECT product_name, product_description, product_image, product_price, product_type_id, DATE_FORMAT(date_post_product, '%m %d %Y') AS date_post_product
				  FROM products
				  WHERE user_id = {$uid}";	
		$result = mysqli_query($dbc, $query);
		confirm_query($result, $query);
		
		$rows = array();
		if (mysqli_num_rows($result) >0) {
			 while ($row = mysqli_fetch_array($result , MYSQLI_ASSOC)) {
				$rows[] = $row;
			 }
		}
		echo json_encode($rows);
	}
?>
