<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	$title = "Active Account"; 
	include('includes/header.php');
	include('includes/check_level_user.php');

      if (isset($_GET['x'], $_GET['y']) && filter_var($_GET['x'], FILTER_VALIDATE_EMAIL) && strlen($_GET['y']) == 32) {
            //Nếu hợp lý	
      	$e = mysqli_real_escape_string($dbc, $_GET['x']);
      	$a = mysqli_real_escape_string($dbc, $_GET['y']);

      	$q = "UPDATE users SET active = NULL WHERE email = '{$e}' AND active = '{$a}' LIMIT 1";
      	$r = mysqli_query($dbc, $q);
      	confirm_query($r, $q);
      	if (mysqli_affected_rows($dbc) == 1) {
      		$messages = "<p class='success' style='margin-top:50px;'>Tài khoản của bạn đã kích hoạt thành công , Bạn có thể <a href='".BASE_URL."user/login.php'>đăng nhập</a> bây giờ/ </p>";
      	}else {
      		$messages = "<p class='error_warning' style='margin-top:50px;'>Tài khoản của bạn không hợp lệ , xin vui lòng thử lại .</p>";
      	}
       }else {
       	// Nếu thông tin không hợp lệ .
       	redirect_to();
       }
?>
    <div id="left-content">
    	<div id="container">
        	<?php
            	if (!empty($messages)) {
					echo $messages;
				}
			?>
        </div> <!--End container-->
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>