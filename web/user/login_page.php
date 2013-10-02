<?php 
 session_start(); 
 include('includes/mysqli_connect.php');
 include('includes/functions.php');

    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form
			$errors = array();

		if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
             $email = mysqli_real_escape_string($dbc, $_POST['email']);
          }else {
            $errors[] = 'email';
          }

          if (isset($_POST['password']) && preg_match('/^[a-z0-9_-]{6,40}$/i', trim($_POST['password']))) {
              $password = mysqli_real_escape_string($dbc, $_POST['password']);
          }else {
            $errors[] = 'password';
          }

          if (empty($errors)) {
            $q = "SELECT user_id, first_name, level_id FROM users WHERE (email = '{$email}' AND password = SHA1('$password')) AND active IS NULL LIMIT 1";
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
				if (mysqli_num_rows($r) == 1) {
					//Thay đổi session id
					session_regenerate_id();
					
				   // Đăng nhập thành công
				  list($uid, $first_name, $level_id) = mysqli_fetch_array($r, MYSQLI_NUM);				  
				  $_SESSION['uid'] = $uid;
				  $_SESSION['first_name'] = $first_name;
				  $_SESSION['level_id'] = $level_id; 
				  redirect_to('index.php');
				}else{ 
				  $messages = "<p class='error_warning'>The email or password not match , Or you have not activated your account</p>";
				}			
          }else {
              $messages = "<p class='error_warning'>Please fill in all the required fields. </p>";
          }

        }// END main IF submit condition
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Buy Onlin - Login Form</title>
  <link rel="stylesheet" href="css/login.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
			<h2 class="title-login">Login User</h2>
			  <?php if (!empty($messages)) {
                            echo $messages;
              }?>
			    <form method="post" action="" class="login">
                    <p>
                      <label for="email">Email:</label>
                      <input type="text" name="email" id="email" value="<?php if (isset($_POST['email'])) {
                        echo htmlentities($_POST['email']);
                      }?>">
                      <?php if (isset($errors) && in_array('email', $errors)) {
                          echo "<span class='warning email'>Please enter your email.</span>";
                        }?>
                    </p>                
                    <p>
                      <label for="password">Password:</label>
                      <input type="password" name="password" id="password" value="">
                      <?php if (isset($errors) && in_array('password', $errors)) {
                          echo "<span class='warning password'>Please enter your password.</span>";
                      }?>
                    </p>
                
                    <p class="login-submit">
                      <button type="submit" class="login-button">Login</button>
                    </p>
                
                   <p class="register"><a href="register_page.php">Register</a></p>
                   <p class="forgot-password"><a href="retrieve_password.php">Forgot your password?</a></p>
              </form>
	
</body>
</html>
