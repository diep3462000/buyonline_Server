<?php 
	include('../includes/mysqli_connect.php'); 
	include('../includes/functions.php'); 
	
	if(isset($_GET['province_id']) && isset($_GET['product_type_id']) && isset($_GET['display']) && isset($_GET['page'])){
		$display = $_GET['display'];	
		$province_id = $_GET['province_id'];
		$product_type_id = $_GET['product_type_id'];	
		$start = (isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['page'] : 0;
		$pages = ($start-1)*$display;
			
		$query = " SELECT SQL_CALC_FOUND_ROWS products.*, order_items.quantity as quantity,CONCAT_WS(' ', first_name, last_name) AS name
					FROM products 
					LEFT JOIN province_products ON (products.product_id = province_products.product_id)
					LEFT JOIN products_type ON (products.product_type_id = products_type.product_type_id)
					LEFT JOIN order_items ON (products.product_id = order_items.product_id)
					LEFT JOIN users ON (products.user_id = users.user_id)
					WHERE province_products.province_id = {$province_id} 
					AND products.product_type_id = {$product_type_id}
					ORDER BY position ASC LIMIT {$pages}, {$display}";
		$result = mysqli_query($dbc, $query);
		confirm_query($result, $query);
		$total_products = mysqli_fetch_array(mysqli_query($dbc,"SELECT FOUND_ROWS() as count"));
		$rows = array();
		if (mysqli_num_rows($result) > 0) {
			 while ($row = mysqli_fetch_array($result , MYSQLI_ASSOC)) {
				$rows[] = $row;
			 }
		}
		//echo json_encode($total_rows);		
		$array_x = $rows + $total_products;
		echo json_encode($array_x);
		
	}
?>
