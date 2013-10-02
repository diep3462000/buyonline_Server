<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');

		is_logged_in();
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form
			$errors = array();
			
            //Check current password có đúng ko ?

            if (isset($_POST['cur_password']) && (preg_match('/^[a-z0-9_-]{6,40}$/i', trim($_POST['cur_password'])))) {
                $cur_password = mysqli_real_escape_string($dbc, trim($_POST['cur_password']));
                $q = "SELECT first_name FROM users WHERE password = SHA1('{$cur_password}') AND user_id = {$_SESSION['uid']} LIMIT 1";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);

                // Nếu ok thì tiếp , ko thì pass cũ sai , bắt nhập lại .
                if (mysqli_num_rows($r) == 1) {
                    // Nếu pass cũ ok thì tiếp tục check pass mới .
                    if (isset($_POST['new_password']) && (preg_match('/^[a-z0-9_-]{6,40}$/i', trim($_POST['new_password'])))) {
                        //Check password và confirm password .
                        if ($_POST['new_password'] == trim($_POST['confirm_password'])) {
                            // Nếu ok thì lưu vào database .
                            $np = mysqli_real_escape_string($dbc, trim($_POST['new_password']));
                            $q = "UPDATE users SET password = SHA1('{$np}') WHERE user_id = {$_SESSION['uid']} LIMIT 1";
                            $r = mysqli_query($dbc, $q);
                            confirm_query($r, $q);
                            //Kiểm tra có update ok ko ?
                            if (mysqli_affected_rows($dbc) == 1) {
                                // Update ok
                                $messages = "<p class='success'>Your password has been changed successfully. </p>";
                            }else {
                                // Update false
                                $messages = "<p class='warning_error'>Your password could not be changed due to a system error . </p>";
                            }
                        }else {
                            $errors[] = 'confirm_password';
                        }
                    }else {
                        $errors[] = 'new_password';
                    }
                }else {
                    $errors[] = 'cur_password';
                }
            } else {
                $errors[] = 'wrong_define_password';
            }  
        }// END main IF submit condition
?>
    <div id="left-content">
    	<div id="container">
        	<h2  class="add_new">Change Password</h2>
    <?php 
        if (!empty($messages)) {
            echo $messages;
        }
    ?>
        <form id="add-user" class="margin_form" action="" method="post">
        	<fieldset>
            	<legend>Change Password User</legend>
                <div class="height_row">
                	<label for="cur_password" class="margin_right">Current Password : <span class="required">*</span></label>
                    <input type="password" name="cur_password" class="margin_input" id="cur_password" value="<?php if(isset($_POST['cur_password'])) echo strip_tags($_POST['cur_password']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('cur_password', $errors)) {
                               echo "<p class='warning'>Wrong old password .</p>";
                            }
                    ?>
                    <?php
                            if (isset($errors) && in_array('wrong_define_password', $errors)) {
                               echo "<p class='warning'>Wrong type password .</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                	<label for="new_password" class="margin_right">New Password : <span class="required">*</span></label>
                    <input type="password" name="new_password" class="margin_input" id="new_password" value="<?php if(isset($_POST['new_password'])) echo strip_tags($_POST['new_password']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('new_password', $errors)) {
                               echo "<p class='warning'>Please fill in the new password</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                	<label for="confirm_password" class="margin_right">Confirm Password : <span class="required">*</span></label>
                    <input type="password" name="confirm_password" class="margin_input" id="confirm_password" value="<?php if(isset($_POST['confirm_password'])) echo strip_tags($_POST['confirm_password']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('confirm_password', $errors)) {
                               echo "<p class='warning'>Confirm password NOT match .</p>";
                            }
                    ?>
                </div>
            </fieldset>
            <p><input type="submit" name="submit" value="Change Password" /></p>
        </form>
        </div> <!--End container-->
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>