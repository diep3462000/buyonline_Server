<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	$title = 'Edit Profiles'; 
	include('includes/header.php');
	include('includes/check_level_user.php');
	//Check đã loggin chưa ?
?>
<?php 
		// Truy xuất dữ liệu để hiển thị thông tin người dùng.
          $user = fetch_user($_SESSION['uid']);

				if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form
				$errors = array();
	
				// Cắt tất cả khoảng cách trống khi nhập vào .
				$trimed = array_map('trim', $_POST);
				// Check first name null ?
				if (empty($_POST['first_name'])) {
					$errors[] = "first_name";
				}else {
					$first_name = $trimed['first_name'];
				}
	
				// Check last name null ?
				if (empty($_POST['last_name'])) {
					$errors[] = "last_name";
				}else {
					$last_name = $trimed['last_name'];
				}
	
				// Check email null ?          
				if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
				}else {
					$errors[] = "email";
				}
	
				// Check sex isset?
				if (isset($_POST['sex'])) {
					$sex = $_POST['sex'];
				}else{
					$errors[] = "sex";
				}
				//Value save all avarible can be null
				$website = $trimed['website'];
				$yahoo = $trimed['yahoo'];
				
				if(empty($trimed['mobile_phone'])){
					$mobile_phone = 0;
				}else {
					$mobile_phone = $trimed['mobile_phone'];
				}
				
				if(empty($trimed['home_phone'])){
					$home_phone = 0;
				}else {
					$home_phone = $trimed['home_phone']; 
				}
				
				if(empty($trimed['home_phone'])){
					$bio = NULL;
				}else {
					$bio = $trimed['bio'];
				}
	
				if (empty($errors)) {
					// Update data into database
					$q = "UPDATE users SET first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}', website = '{$website}', yahoo = '{$yahoo}', bio = '{$bio}', sex = {$sex}, mobile_phone = {$mobile_phone}, home_phone = {$home_phone} ";
					$q .= " WHERE user_id = {$_SESSION['uid']}";
					$r = mysqli_query($dbc, $q);
					confirm_query($r, $q);
					if(mysqli_affected_rows($dbc) == 1) {
						$messages = "<p class='success'>The User was edit successfully .</p>";
					}else{
						$messages = "<p class='error_warning'>Could not edit user to the database due to a system error.</p>";
					}
				}else{
					$messages = "<p class='error_warning'>Please fill a field required</p>";
				}
			}// END main IF submit condition        
?>
    <div id="left-content">
		<div id="container">
        	<?php 
                if (!empty($messages)) {
                    echo $messages;
                }
            ?>
        	<h2 class="add_new">Edit Profile</h2>
			<form enctype="multipart/form-data" action="processor/avatar.php" method="post">
            	<fieldset>
                	<legend>Avatar</legend>
                    <div>                    	
                        <img class="avatar" src="uploads/images/<?php echo (is_null($user['avatar']) ? "no_avatar.jpg" : $user['avatar']); ?>" alt="avatar" />
                        <p>Please select a JPEG or PNG image of 512kb or smaller to use as avatar.</p>
                        <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
                        <input type="file" name="image" />
                        <p><input class="change" type="submit" name="upload" value="Save Change"/></p>
                    </div>
                </fieldset>
            </form>
            
            
            <form id="edit_user" class="margin_form" action="" method="post">
            <fieldset>
                <legend>User Info</legend>
                <div class="height_row">
                    <label for="first_name" class="margin_right">First Name : <span class="required">*</span></label>
                    <input type="text" name="first_name" class="margin_input" id="first_name" value="<?php if(isset($user['first_name'])) echo strip_tags($user['first_name'])?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('first_name', $errors)) {
                               echo "<p class='warning'>Please fill in the category first name</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                    <label for="last_name" class="margin_right">Last Name : <span class="required">*</span></label>
                    <input type="text" name="last_name" class="margin_input" id="last_name" value="<?php if(isset($user['last_name'])) echo strip_tags($user['last_name'])?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('last_name', $errors)) {
                               echo "<p class='warning'>Please fill in the category Last Name</p>";
                            }
                    ?>
                </div>
                
                <div class="height_row">
                    <label for="email" class="margin_right">Email : <span class="required">*</span></label>
                    <input type="text" name="email" class="margin_input" id="email" value="<?php if (isset($user['email'])) echo $user['email']; ?>" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('email', $errors)) {
                               echo "<p class='warning'>Please fill in the Email</p>";
                            }
                    ?>
                    <?php
                            if (isset($errors) && in_array('email-uni', $errors)) {
                               echo "<span class='error'>Email đã tồn tại , vui lòng nhập email khác .</span>";
                            }
                    ?>
                    <span id="available"></span>
                </div>
                
                <div class="height_row">
                    <label for="website" class="margin_right">website : <span class="required"></span></label>
                    <input type="text" name="website" id="website" class="margin_input" value="<?php if(isset($user['website'])) echo strip_tags($user['website'])?>" size="20" maxlength="80" tabindex="1"/>
                </div>

                <div class="height_row">
                    <label for="yahoo" class="margin_right">yahoo : <span class="required"></span></label>
                    <input type="text" name="yahoo" id="yahoo" class="margin_input" value="<?php if(isset($user['yahoo'])) echo strip_tags($user['yahoo'])?>" size="20" maxlength="80" tabindex="1"/>
                </div>
                
                <div class="height_row">
                    <label for="sex" class="margin_right">Sex: <span class="required">*</span></label>                    
                    <select name="sex">                    
                    <?php
                        $sex = array(1 => 'male', 2 => 'female');
                        echo "<option value = '0'>Choose Sex</option>";
                        foreach ($sex as $key => $s) {
                            echo "<option value='{$key}'";
                                if ($key == $user['sex']) {
                                    echo "selected = 'selected'";
                                }
                            echo ">".$s."</option>";
                        }
              ?>
                    </select>
                </div>
                
                <div class="height_row">
                    <label for="mobile_phone" class="margin_right">Mobile phone : <span class="required"></span></label>
                    <input type="text" name="mobile_phone" class="margin_input" id="mobile_phone" value="<?php if(isset($user['mobile_phone'])) echo strip_tags($user['mobile_phone'])?>" size="20" maxlength="80" tabindex="1"/>
                </div>
                
                <div class="height_row">
                    <label for="home_phone" class="margin_right">Home phone : <span class="required"></span></label>
                    <input type="text" name="home_phone" class="margin_input" id="home_phone" value="<?php if(isset($user['home_phone'])) echo strip_tags($user['home_phone'])?>" size="20" maxlength="80" tabindex="1"/>
                </div>               
                
                <div>
                    <label for="bio" class="margin_right">bio: <span class="required"></span></label>
                    <textarea name="bio" cols="50" rows="20"><?php if(isset($user['bio'])) echo strip_tags($user['bio'])?></textarea>
                </div>
            </fieldset>
            <p><input type="submit" name="submit" value="Save Change" /></p>
        </form>
            
            
        </div> <!--End div continer-->
        <div class="clear"></div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>