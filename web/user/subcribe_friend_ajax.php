<?php 
include('includes/mysqli_connect.php');
include('includes/functions.php');

	 if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {		 
		 $body = "Bạn của bạn là : ".$_SESSION['first_name']."đã giới thiệu bạn đến trang web Buy Online , hãy click vào link dưới và tham gia với chúng tôi ! <br />";
		 $body .= BASE_URL."register_page.php";
		 $body = wordwrap($body, 70, "\r\n");
		 $to = mysqli_real_escape_string($dbc, $_POST['email']);
		 $subject = 'Tham gia website với tôi';
         if(mail($to, $subject, $body))
			echo "OK";
	}else {
		echo "NO";
	}
?>
