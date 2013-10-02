<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Register User</title>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="js/check_ajax.js"></script>
<link type="text/css" href="css/real_register.css" rel="stylesheet" rev="stylesheet" />
<?php 
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form
			$errors = array();
						
			// Check first name null ?
			if(preg_match('/^[a-zA-Z -]+$/', trim($_POST['first_name']))){
				$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
			}else {
				$errors[] = "first_name";
			}
						
			// Check last name null ?
			if(preg_match('/^[a-zA-Z -]+$/', trim($_POST['last_name']))){
				$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
			}else {
				$errors[] = "last_name";
			}

            // Check email null ?   
			$REGEX_EMAIL = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';        
            if (isset($_POST['email']) && filter_var($REGEX_EMAIL, $_POST['email'])) {
                $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            }else {
                $errors[] = "email";
            }
			
			if(preg_match('/^[a-z0-9_-]{6,40}$/i', trim($_POST['password']))){
				if($_POST['password'] == $_POST['re-password']){
					// Nếu mật khẩu và gõ lại mật khẩu giống nhau thì lưu lại vào csdl .
					$password = mysqli_real_escape_string($dbc,trim($_POST['password']));
				}else {
					//Nếu mật khẩu không phù hợp với nhau .
					$errors[] = 're-password';
				}
			}else {
				//Check xem password có phù hợp không ?
				$errors[] = 'password';
			
			}
			
            // Check password null ?
            if (empty($_POST['password'])) {
                $errors[] = "password";
            }else {
                $password = mysqli_real_escape_string($dbc,strip_tags($_POST['password']));
            }

            // Check sex isset?
            if (($_POST['sex']) == 1 || $_POST['sex'] == 2) {
                $sex = $_POST['sex'];
            }else{
                $errors[] = "sex";
            }

            // user level            
                $level_id = 2;            

            //Set avatar 
           	$avatar = BASE_URL."uploads/images/no_avatar.jpg";            

            //Value save all avarible can be null
            $website = mysqli_real_escape_string($dbc,strip_tags($_POST['website']));
            $yahoo = mysqli_real_escape_string($dbc,strip_tags($_POST['yahoo']));
            
			if(empty($_POST['mobile_phone'])){
				$mobile_phone = 'NULL';
			}else {
				$mobile_phone = mysqli_real_escape_string($dbc,strip_tags($_POST['mobile_phone']));
			}
			
			if(empty($_POST['home_phone'])){
				$home_phone = 'NULL';
			}else {
				$home_phone = mysqli_real_escape_string($dbc,strip_tags($_POST['home_phone'])); 
			}
            $bio = mysqli_real_escape_string($dbc,strip_tags($_POST['bio']));
			$web = mysqli_real_escape_string($dbc, strip_tags($_POST['website']));

            //Check answer question random
             if(isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
                $errors[] = "wrong";
            }

            //Check spam bot with field FILL
            if (!empty($_POST['url'])) {
                redirect_to('thankyou.html');
            }
			
			//Check email đã tồn tại chưa ? 			
			$q = "SELECT user_id FROM users WHERE email = '{$_POST['email']}'";
			$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
			if(mysqli_num_rows($r) == 0){
				//Không có email này thì cho đăng ký
				$active = md5(uniqid(rand(), true));
			}else {
				//Email này đã có , bắt phải nhập email khác
				$errors[] = 'email-uni';
			}

            if (empty($errors)) {
                // Inset data into database
                $q = "INSERT INTO users(first_name,last_name,email,password, website, yahoo, bio, sex, mobile_phone, home_phone, avatar, level_id, active, registration_date) VALUES (
                                  '{$first_name}', '{$last_name}', '{$email}', SHA1('{$password}'), '{$web}', '{$yahoo}', '{$bio}', {$sex}, {$mobile_phone}, {$home_phone}, '{$avatar}', {$level_id},'{$active}', NOW()) ";
                $r = mysqli_query($dbc,$q);
				confirm_query($r,$q);

                if (mysqli_affected_rows($dbc) == 1) {
                    $body = "Cảm ơn bạn đã đăng ký ở trang Buy Online. Một email kích hoạt đã được gửi tới địa chỉ email mà bạn cung cấp.
								Phiền bạn click vào đường link để kích hoạt tài khoản .\n\n ";
					$body .= BASE_URL."user/active.php?x=".urlencode($_POST['email'])."&y={$active}";
					if(mail($_POST['email'], 'Kích hoạt tài khoản tại Buy-Online',$body, 'FROM localhost')){
						$messages = "<p class='success'>Tài khoản của bạn đã được đăng ký thành công , Email đã được gửi tới địa chỉ của bạn. Bạn phải ấn link để kích hoạt tài khoản trước khi sử dụng nó. </p>";
						
					}else {
						$messages = "<p class='warning'>Không thể dửi được mail cho bạn . Rất xin lỗi về sự bất tiện này. </p>";
					}
                }else{
                    $messages = "<p class='warning'>Could not added user to the database due to a system error.</p>";
                }
            }else{
                $messages = "<p class='error_warning'>Please fill a field required</p>";
            }
        }// END main IF submit condition
?>
</head>

<body>
    <div id="left-content">
    	<div id="container">
        	<h2><a href='<?php echo BASE_URL."login_page.php";?>'>Login</a></h2>
    <?php 
        if (!empty($messages)) {
            echo $messages;
        }
    ?>
        <form id="add-user" class="register" action="" method="post">
        	<fieldset>
            	<legend>Register User</legend>
                <p>
                	<label for="first_name">First Name : <span class="required">*</span></label>
                    <input type="text" name="first_name" class="text-field" id="first_name" value="<?php if(isset($_POST['first_name'])) echo strip_tags($_POST['first_name']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('first_name', $errors)) {
                               echo "<span class='error'>Please fill in the category first name</span>";
                            }
                    ?>
                </p>
                
                <p>
                	<label for="last_name">Last Name : <span class="required">*</span></label>
                    <input type="text" name="last_name" class="text-field" id="last_name" value="<?php if(isset($_POST['last_name'])) echo strip_tags($_POST['last_name']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('last_name', $errors)) {
                               echo "<span class='error'>Please fill in the category Last Name</span>";
                            }
                    ?>
                </p>
                
                <p>
                	<label for="email">Email : <span class="required">*</span></label>
                    <input type="text" name="email" class="text-field" id="email" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email'],ENT_COMPAT, 'UTF-8')?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('email', $errors)) {
                               echo "<span class='error'>Please fill in the Email</span>";
                            }
                    ?>
                    <?php
                            if (isset($errors) && in_array('email-uni', $errors)) {
                               echo "<span class='error'>Email đã tồn tại , vui lòng nhập email khác .</span>";
                            }
                    ?>
                    <span id="available"></span>
                </p>
                
                <p>
                	<label for="password">Password : <span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="text-field" value="<?php if(isset($_POST['password'])) echo strip_tags($_POST['password']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('password', $errors)) {
                               echo "<span class='error'>Password không đúng định dạng.</span>";
                            }
                    ?>
                </p>
                
                <p>
                	<label for="re-password">Re-Password : <span class="required">*</span></label>
                    <input type="password" name="re-password" id="re-password" class="text-field" value="<?php if(isset($_POST['re-password'])) echo strip_tags($_POST['re-password']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('re-password', $errors)) {
                               echo "<span class='error'>Password Nhập lại không chính xác .</span>";
                            }
                    ?>
                </p>
                
                <p>
                	<label for="website">website : <span class="required"></span></label>
                    <input type="text" name="website" id="website" class="text-field" value="<?php if(isset($_POST['website'])) echo strip_tags($_POST['website']);?>" size="20" maxlength="80" tabindex="1"/>
                </p>

				<p>
                	<label for="yahoo">yahoo : <span class="required"></span></label>
                    <input type="text" name="yahoo" id="yahoo" class="text-field" value="<?php if(isset($_POST['yahoo'])) echo strip_tags($_POST['yahoo']);?>" size="20" maxlength="80" tabindex="1"/>
                </p>
                
                <p>
                	<label for="sex">Sex: <span class="required">*</span></label>                    
                    <select name="sex">
                    	<option>Select sex</option>
                        <option value='1'>Male</option>
                        <option value='2'>Female</option>
                    </select>
                    <?php
                            if (isset($errors) && in_array('sex', $errors)) {
                               echo "<span class='error' style='margin-left: 270px;margin-top: -24px;'>Please choose Sex</span>";
                            }
                    ?>    
                </p>
                
                <p>
                	<label for="mobile_phone">Mobile phone : <span class="required"></span></label>
                    <input type="text" name="mobile_phone" class="text-field" id="mobile_phone" value="<?php if(isset($_POST['mobile_phone'])) echo strip_tags($_POST['mobile_phone']);?>" size="20" maxlength="80" tabindex="1"/>
                </p>
                
                <p>
                	<label for="home_phone">Home phone : <span class="required"></span></label>
                    <input type="text" name="home_phone" class="text-field" id="home_phone" value="<?php if(isset($_POST['home_phone'])) echo strip_tags($_POST['home_phone']);?>" size="20" maxlength="80" tabindex="1"/>
               </p>               
               
                	<label for="bio">bio: <span class="required"></span></label>
                    <textarea name="bio" cols="50" rows="20"><?php if(isset($_POST['bio'])) echo strip_tags($_POST['bio'])?></textarea>
                
                <p>
                    <label for="captcha">Điền vào giá trị số câu hỏi sau : <?php echo captcha();?> <span class="required">*</span></label>
                    <input type="text" name="captcha" id="captcha" class="text-field" value="" size="20" maxlength="80" tabindex="1" style="float:left; margin-left:50px; margin-top:10px;"/>
                    <?php
                            if (isset($errors) && in_array('wrong', $errors)) {
                               echo "<span class='error' style='margin-left:33px; margin-top:10px;'>Wrong answer !!!</span>";
                            }
                    ?> 
                </p>
                <p style="display:none">
                    <label for="url">Please DON'T fill this field !!!!<span class="required"></span></label>
                    <input type="text" name="url" id="url" class="text-field" value="" size="20" maxlength="20" tabindex="1"/>
                </p>

            </fieldset>
            <p><input type="submit" name="submit" value="Register" class="button" /></p>
            <p><a href='<?php echo BASE_URL."login_page.php";?>' class="button">Login</a></p>
        </form>
        </div> <!--End container-->
	</div> <!--End left-content-->
</body>
</html>
