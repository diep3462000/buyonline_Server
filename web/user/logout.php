<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	if (!isset($_SESSION['first_name'])) {
		redirect_to('login_page.php');
	} else {
		//Nếu có thông tin đăng nhập thì Logout.
		$_SESSION = array(); // Xoá hết array của SESSION
		session_destroy(); // Xoá SESSION đã tạo
		setcookie(session_name(),'', time()-36000); // Xoá cookie của trình duyệt .
	}
	redirect_to('login_page.php');
?>