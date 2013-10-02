<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');

    if(isset($_GET['category'])) {
        $c = mysqli_real_escape_string($dbc, $_GET['category']);
        // Truy van csdl voi category vua nhan duoc
        $q = "SELECT cat_id FROM categories WHERE cat_name = '{$c}'";
        $r = mysqli_query($dbc, $q); confirm_query($r, $q);
        if(mysqli_num_rows($r) == 1) {
            echo "NO"; // not available
        } else {
            echo "YES"; // category avalialbe
        }
    }
?>