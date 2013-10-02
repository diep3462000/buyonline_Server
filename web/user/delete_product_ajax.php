<?php 
include('includes/mysqli_connect.php');
include('includes/functions.php');

	if (isset($_POST['pro_id']) && filter_var($_POST['pro_id'], FILTER_VALIDATE_INT)) {
		$pro_id = $_POST['pro_id'];
		$q = "DELETE FROM products WHERE product_id = $pro_id LIMIT 1";
		$r = mysqli_query($dbc, $q);
		confirm_query($r, $q);
	}
?>