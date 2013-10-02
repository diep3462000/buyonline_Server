<?php 
	 include('includes/mysqli_connect.php');
	 include('includes/functions.php');
	 $title = "Edit User";
	 include('includes/header.php');
	include('includes/check_level_user.php');
	 admin_access();      
		  // Kiểm tra biến user_id từ biến $_GET .
		  if(isset($_GET['user_id']) && filter_var($_GET['user_id'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
			  $user_id = $_GET['user_id'];           	
			  if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form
				  $errors = array();
	  
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
			  if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				  $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
			  }else {
				  $errors[] = "email";
			  }
			  
			  if(preg_match('/^[a-z0-9_-]{6,40}$/i', trim($_POST['password']))){
					  $password = mysqli_real_escape_string($dbc,trim($_POST['password']));
				  }else {
					  $errors[] = 'password';
				  }
	  
				  if (($_POST['sex']) == 1 || $_POST['sex'] == 2) {
					  $sex = $_POST['sex'];
				  }else{
					  $errors[] = "sex";
				  }
  
				  if (filter_var($_POST['level_id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
					  $level_id = $_POST['level_id'];
				  }else{
					  $errors[] = "level_id";
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
	  
				  if (empty($errors)) {
					  // Update data into database
					  //$q = "	               //$r = mysqli_query($dbc,$q);
					  //confirm_query($r,$q);
  
					  // Kiểm tra email đã có trong hệ thống hay chưa ?
					  $q = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
					  if ($stmt = mysqli_prepare($dbc, $q)) {
						  // Gán tham số cho câu lệnh prepare .
						  mysqli_stmt_bind_param($stmt, 'si', $email, $user_id);
  
						  //Cho chạy câu lệnh prepare .
						  mysqli_stmt_execute($stmt);
  
						  //Lưu lại kết quả của câu lệnh prepare. Quan trọng , kiểm tra có lưu lại hay ko ?
						  mysqli_stmt_store_result($stmt);
  
						  if (mysqli_stmt_num_rows($stmt) == 0) {
							  // Email ok và chạy query để update database
							  $query = "UPDATE users ";
							  $query .= " SET first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}', password =  SHA1('{$password}'), website = '{$website}', yahoo = '{$yahoo}', bio = '{$bio}', sex = {$sex}, mobile_phone = {$mobile_phone}, home_phone = $home_phone, level_id = {$level_id} ";
							  $query .= " WHERE user_id = {$user_id} LIMIT 1";
  
							  $r = mysqli_query($dbc, $query);
								confirm_query($r, $query);
								if(mysqli_affected_rows($dbc) == 1) {
									$messages = "<p class='success'>The User was edit successfully .</p>";
								}else{
									$messages = "<p class='error_warning'>Could not edit user to the database due to a system error.</p>";
								}
							}else {							
							   $messages = "<p class='error_warning'>Email have in database.</p>";
						  }
				  }else{
					  $messages = "<p class='error_warning'>Please fill a field required</p>";
				}// END if($STMT)
			}// end empty($errors) 
		}// END main IF submit condition
	} else {
		//Nếu user_id không tồn tại thì redirect về trang index.php .
		redirect_to('index.php');
	} 
?>

<div id="left-content">
<?php 
        //Chọn user trong csdl để hiển thị ra 
			if($user = fetch_user($user_id)){		
?>
  <div id="container">
    <h2  class="add_new">Edit user:
      <?php if (isset($user['first_name']) && isset($user['last_name'])) echo "<span style='font-size:28px;font-weight:bold;'>".$user['first_name'].$user['last_name']."</span>" ?>
    </h2>
    		<?php 
                if (!empty($messages)) {
                    echo $messages;
                }
            ?>
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
        <legend>Edit User</legend>
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
        </div>
        <div class="height_row">
          <label for="password" class="margin_right">Password : <span class="required">*</span></label>
          <input type="password" name="password" id="password" class="margin_input" value="<?php if(isset($user['password'])) echo strip_tags($user['password'])?>" size="20" maxlength="80" tabindex="1"/>
          <?php
                            if (isset($errors) && in_array('password', $errors)) {
                               echo "<p class='warning'>Please fill in the Password</p>";
                            }
                    ?>
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
        <div class="height_row">
          <label for="level_id" class="margin_right">Level User: <span class="required">*</span></label>
          <select name="level_id">
            <option>Choose level user</option>
            <?php 
                            $q = "SELECT level_id, level_name FROM level_user";
                            $r = mysqli_query($dbc,$q);
                            confirm_query($r, $q);
                            if (mysqli_num_rows($r)>0) {
                                while ($level = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                    echo "<option value='{$level['level_id']}'";
                                        if (isset($user['level_id']) && ($user['level_id'] == $level['level_id'])) {
                                            echo "selected='selected'";
                                        }
                                    echo">".$level['level_name']."</option'>";
                                }
                            }
                        ?>
          </select>
          <?php
                            if (isset($errors) && in_array('level_id', $errors)) {
                               echo "<p class='warning'>Please fill in the Level User</p>";
                            }
                    ?>
        </div>
        <div>
          <label for="bio" class="margin_right">bio: <span class="required"></span></label>
          <textarea name="bio" cols="50" rows="20"><?php if(isset($user['bio'])) echo strip_tags($user['bio'])?>
</textarea>
        </div>
      </fieldset>
      <p>
        <input type="submit" name="submit" value="Change User" />
      </p>
    </form>
  </div>
  <!--End container--> 
  <?php }else {
          //Nếu không có user trả về 
          echo "<p class='error_warning'>No user found .</p>";
  }?>
</div>
<!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>