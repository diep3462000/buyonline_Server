<?php 
	include('../includes/mysqli_connect.php'); 
	include('../includes/functions.php'); 
	
	if(isset($_GET['province_id']) && isset($_GET['k'])){		
		$province_id = $_GET['province_id'];	
		$k = $_GET['k'];
		
		$query = " SELECT products.product_id, products.product_name, products.product_image,CONCAT_WS(' ', first_name, last_name) AS name
					FROM products 
					LEFT JOIN users ON (products.user_id = users.user_id)
					LEFT JOIN province_products ON (products.product_id = province_products.product_id)
					WHERE province_products.province_id = $province_id AND (product_name LIKE '%$k%' OR first_name LIKE '%$k%' OR last_name LIKE '%$k%')
					ORDER BY position ASC LIMIT 3";
		$result = mysqli_query($dbc, $query);
		confirm_query($result, $query);
		
		$rows = array();
		if (mysqli_num_rows($result) > 0) {
			 while ($row = mysqli_fetch_array($result , MYSQLI_ASSOC)) {
				$rows[] = $row;
			 }
		}
		echo json_encode($rows);
		
	}
?>
