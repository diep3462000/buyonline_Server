<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');

        admin_access();
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form
			$errors = array();

            // Check first name null ?
            if (empty($_POST['first_name'])) {
                $errors[] = "first_name";
            }else {
                $first_name = mysqli_real_escape_string($dbc,strip_tags($_POST['first_name']));
            }

            // Check last name null ?
            if (empty($_POST['last_name'])) {
                $errors[] = "last_name";
            }else {
                $last_name = mysqli_real_escape_string($dbc,strip_tags($_POST['last_name']));
            }
			
            // Check email null ?           
            if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $email = mysqli_real_escape_string($dbc,strip_tags($_POST['email']));
            }else {
                $errors[] = "email";
            }// Chấp nhận ko có dot(.) sẽ update lại sau 
			
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

            // Check user level
            if($_POST['level_id'] == 0){
                $errors[] = "level_id";
            }else {
                $level_id = $_POST['level_id'];
            }

            //Check avatar , if avatar nil , set it by no_avatar , else , set it 
            $avatar = mysqli_escape_string($dbc,strip_tags($_POST['avatar']));

            if (empty($avatar)) {
                $avatar = "no_avatar.jpg";
            }else {
                $avatar = mysqli_real_escape_string($dbc,strip_tags($_POST['avatar']));
            }

            //Value save all avarible can be null
            $website = mysqli_real_escape_string($dbc,strip_tags($_POST['website']));
            $yahoo = mysqli_real_escape_string($dbc,strip_tags($_POST['yahoo']));
            
			if(empty($mobile_phone)){
				$mobile_phone = 'NULL';
			}else {
				$mobile_phone = mysqli_real_escape_string($dbc,strip_tags($_POST['mobile_phone']));
			}
			
			if(empty($home_phone)){
				$home_phone = 'NULL';
			}else {
				$home_phone = mysqli_real_escape_string($dbc,strip_tags($_POST['home_phone'])); 
			}
            $bio = mysqli_real_escape_string($dbc,strip_tags($_POST['bio']));


            //Check answer question random
            if (isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
                $errors[] = "wrong";
            }

            //Check spam bot with field FILL

            if (!empty($_POST['url'])) {
                redirect_to('thankyou.html');
            }

            if (empty($errors)) {
                // Inset data into database
                $q = "INSERT INTO users(first_name,last_name,email,password, website, yahoo, bio, sex, mobile_phone, home_phone, avatar, level_id, registration_date) VALUES (
                                  '{$first_name}', '{$last_name}', '{$email}', SHA1('{$password}'), '{$website}', '{$yahoo}', '{$bio}', {$sex}, {$mobile_phone}, {$home_phone}, '{$avatar}', {$level_id}, NOW()) ";
                $r = mysqli_query($dbc,$q);
				confirm_query($r,$q);

                if (mysqli_affected_rows($dbc) == 1) {
                    $messages = "<p class='success'>The User was added successfully .</p>";
                }else{
                    $messages = "<p class='warning'>Could not added user to the database due to a system error.</p>";
                }
            }else{
                $messages = "<p class='error_warning'>Please fill a field required</p>";
            }
        }// END main IF submit condition
?>
    <div id="left-content">
    	<div id="container">
        	<h2  class="add_new">Create a user</h2>
    <?php 
        if (!empty($messages)) {
            echo $messages;
        }
    ?>
        <form id="add-user" class="margin_form" action="" method="post">
        	<fieldset>
            	<legend>Add a User</legend>
                <div class="height_row">
                	<label for="first_name" class="margin_right">First Name : <span class="required">*</span></label>
                    <input type="text" name="first_name" class="margin_input" id="first_name" value="<?php if(isset($_POST['first_name'])) echo strip_tags($_POST['first_name']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('first_name', $errors)) {
                               echo "<p class='warning'>Please fill in the category first name</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                	<label for="last_name" class="margin_right">Last Name : <span class="required">*</span></label>
                    <input type="text" name="last_name" class="margin_input" id="last_name" value="<?php if(isset($_POST['last_name'])) echo strip_tags($_POST['last_name']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('last_name', $errors)) {
                               echo "<p class='warning'>Please fill in the category Last Name</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                	<label for="email" class="margin_right">Email : <span class="required">*</span></label>
                    <input type="text" name="email" class="margin_input" id="email" value="<?php if(isset($_POST['email'])) echo strip_tags($_POST['email']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('email', $errors)) {
                               echo "<p class='warning'>Please fill in the Email</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                	<label for="password" class="margin_right">Password : <span class="required">*</span></label>
                    <input type="password" name="password" id="password" class="margin_input" value="<?php if(isset($_POST['password'])) echo strip_tags($_POST['password']);?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('password', $errors)) {
                               echo "<p class='warning'>Please fill in the Password</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                	<label for="website" class="margin_right">website : <span class="required"></span></label>
                    <input type="text" name="website" id="website" class="margin_input" value="<?php if(isset($_POST['website'])) echo strip_tags($_POST['website']);?>" size="20" maxlength="80" tabindex="1"/>
                </div>

				<div class="height_row">
                	<label for="yahoo" class="margin_right">yahoo : <span class="required"></span></label>
                    <input type="text" name="yahoo" id="yahoo" class="margin_input" value="<?php if(isset($_POST['yahoo'])) echo strip_tags($_POST['yahoo']);?>" size="20" maxlength="80" tabindex="1"/>
                </div>
                
                <div class="height_row">
                	<label for="sex" class="margin_right">Sex: <span class="required">*</span></label>                    
                    <select name="sex" style='float:left;'>
                    	<option>Select sex</option>
                        <option value='1'>Male</option>
                        <option value='2'>Female</option>
                    </select>
                    <?php
                            if (isset($errors) && in_array('sex', $errors)) {
                               echo "<p class='warning' style='margin-left: 270px;margin-top: -24px;'>Please choose Sex</p>";
                            }
                    ?>    
                </div>
                
                <div class="height_row">
                	<label for="mobile_phone" class="margin_right">Mobile phone : <span class="required"></span></label>
                    <input type="text" name="mobile_phone" class="margin_input" id="mobile_phone" value="<?php if(isset($_POST['mobile_phone'])) echo strip_tags($_POST['mobile_phone']);?>" size="20" maxlength="80" tabindex="1"/>
                </div>
                
                <div class="height_row">
                	<label for="home_phone" class="margin_right">Home phone : <span class="required"></span></label>
                    <input type="text" name="home_phone" class="margin_input" id="home_phone" value="<?php if(isset($_POST['home_phone'])) echo strip_tags($_POST['home_phone']);?>" size="20" maxlength="80" tabindex="1"/>
                </div>
                
                <div class="height_row">
                	<label for="avatar" class="margin_right">Avatar : <span class="required"></span></label>
                    <input type="text" name="avatar" id="avatar" class="margin_input" value="<?php if(isset($_POST['avatar'])) echo strip_tags($_POST['avatar']);?>" size="20" maxlength="80" tabindex="1"/>
                </div>
                
                <div class="height_row">
                	<label for="level_id" class="margin_right">Level User: <span class="required">*</span></label>                    
                    <select name="level_id" style='float:left;'>
                        <option value='0'>Choose level user</option>
                    	<?php 
                            $q = "SELECT level_id, level_name FROM level_user";
                            $r = mysqli_query($dbc,$q);
							confirm_query($r, $q);
                            if (mysqli_num_rows($r)>0) {
                                while ($level = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                    echo "<option value='{$level['level_id']}'";
                                        if (isset($_POST['level_id']) && ($_POST['level_id'] == $level['level_id'])) {
                                            echo "selected='selected'";
                                        }
                                    echo">".$level['level_name']."</option'>";
                                }
                            }
                        ?>
                    </select>
                    <?php
                            if (isset($errors) && in_array('level_id', $errors)) {
                               echo "<p class='warning'  style='margin-left: 270px;margin-top: -24px;'>Please fill in the Level User</p>";
                            }
                    ?>
                </div>
                
                <div>
                	<label for="bio" class="margin_right">bio: <span class="required"></span></label>
                    <textarea name="bio" cols="50" rows="20"><?php if(isset($_POST['bio'])) echo strip_tags($_POST['bio'])?></textarea>
                </div>
                <div class="height_row">
                    <label for="captcha" class="margin_right">Điền vào giá trị số câu hỏi sau : <?php echo captcha();?> <span class="required">*</span></label>
                    <input type="text" name="captcha" id="captcha" class="margin_input" value="" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('wrong', $errors)) {
                               echo "<p class='warning'>Wrong answer !!!</p>";
                            }
                    ?> 
                </div>

                <div class="height_row hidden_field">
                    <label for="url" class="margin_right">Please DON'T fill this field !!!!<span class="required"></span></label>
                    <input type="text" name="url" id="url" class="margin_input" value="" size="20" maxlength="20" tabindex="1"/>
                </div>

            </fieldset>
            <p><input type="submit" name="submit" value="Add User" /></p>
        </form>
        </div> <!--End container-->
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>