<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');  
	admin_access();
	include('includes/header.php');
	include('includes/check_level_user.php');
  	
       	
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form			
			$errors = array();
			if(empty($_POST['province'])){
				$errors[] = "province";
			} else {
				$province_name = mysqli_real_escape_string($dbc,strip_tags($_POST['province']));
			}



			//Check product image
			if(isset($_FILES['province_image'])) 
				{			
					// Tao mot array, de kiem tra xem file upload co thuoc dang cho phep
					$allowed = array('image/jpeg', 'image/jpg', 'image/png', 'images/x-png');
		
					// Kiem tra xem file upload co nam trong dinh dang cho phep
					if(in_array(strtolower($_FILES['province_image']['type']), $allowed)) 
						{
							// Neu co trong dinh dang cho phep, tach lay phan mo rong
							$tmp = explode('.', $_FILES['province_image']['name']);
							$ext = end($tmp);
							$renamed = uniqid(rand(), true).'.'."$ext";
			
							if(!move_uploaded_file($_FILES['province_image']['tmp_name'], "uploads/images/".$renamed)) 
								{
									echo "<p class='error'>Server problem</p>";
								}
						} 
					else 
						{
							// FIle upload khong thuoc dinh dang cho phep
							$errors[] = "error";
							echo "<p class='error'>Your file is not a valid type. Please choose a jpg or png image to upload.</p>";
						} 			
				}
			else 
				{
					$errors[]='province_image';
				}



            if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))){ 
                // filter_var , truyền vào 3 tham số , đầu tiên là truyền vào cái mình muốn kiểm tra .
                // tham số thứ 2 là FILTER_VALIDATE_INT kiểm tra có phải integer ko , nếu đúng thì là true , ko là false
                // tham số thứ 3 không bắt buộc , và ở đây position mình muốn > 1 .
            $position = $_POST['position'];
			}else {
				$errors[] = "position";
			}
			
		if(empty($errors)){
                // If no have error , inset data to database
			$q = "INSERT INTO provinces (province_name,province_image,position) VALUES ('{$province_name}','{$renamed}', $position)";
			$r = mysqli_query($dbc, $q);
			confirm_query($r,$q);
			if (mysqli_affected_rows($dbc) == 1 ) {
				$messages = "<p class='success'>The province was added successfully.</p>";
			}else {
				$messages = "<p class='error_warning'>Could not added to the database due to a system error.</p>";
			}				
		}else {
            $messages = "<p class='error_warning'>Please fill all the reuired fields </p>";
        }
        }// END main IF submit condition
    ?>
    <div id="left-content">
		<div id="container">
        	    <h2 class="add_new">Add a Province</h2>
				<?php if(!empty($messages)) echo $messages; ?>
                <form id="add_province" class="margin_form" action="" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Add a Province</legend>
                        <div class="height_row">
                            <label for="province" class="margin_right">Province Name: <span class="required">*</span></label>                    
                            <input type="text" name="province" id="province" value="<?php if (isset($_POST['province'])) {
                                echo strip_tags($_POST['province']);} ?>" size="20" maxlength="150" tabindex="1"/>
                            <?php
                                    if(isset($errors) && in_array('province', $errors)){
                                        echo "<p class='warning'>Please fill in the province name</p>";
                                    }
                            ?>
                        </div>
                        
                        
                        <div class="upload_image">
                            <label for="province_image" class="margin_right">Image of Province: <span class="required">*</span></label>
                            <input type="file" name="province_image" id="province_image" value="<?php if (isset($_POST['province_image'])) echo strip_tags($_POST['province_image']);?>" />
                            <?php 
                                    if (isset($errors) && in_array('province_image', $errors)) {
                                        echo "<p class='warning'>Please fill Province Image. </p>";
                                    }
                            ?>
                        </div>
                        
                        <div class="height_row">
                            <label for="position" class="margin_right">Position: <span class="required">*</span></label>
                            <select name="position" tabindex="2"  style="margin-top:10px; float:left;">
                                <?php 
                                    $q = "SELECT count(province_id) AS count FROM provinces";
                                    $r = mysqli_query($dbc,$q);
                                    confirm_query($r,$q);
                                    if (mysqli_num_rows($r)==1) {
                                        list($num) = mysqli_fetch_array($r,MYSQLI_NUM);
                                        //mysqli_fetch_array để nói với nó là trả về 1 array . và kiểu trả đây là Num ,
                                        // tương tự ở bên header thì lại trả về MYSQLI_ASSOC trả về theo mảng 
											echo "<option value='0'>Choose Position</option>";
                                        for ($i=1; $i<=$num+1; $i++) { 
                                            //+1 để có nghĩa là thêm 1 giá trị position , tạo giá trị mới .											
                                            echo "<option value='{$i}'";
                                                if (isset($_POST['position']) && $_POST['position'] == $i) {
                                                    echo "selected='selected'";
                                                }
                                            echo ">".$i."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <?php
                                    if(isset($errors) && in_array('position', $errors)){
                                        echo "<p class='warning'>Please fill in the province position</p>";
                                    }
                            ?>
                        </div>
                        
                </fieldset>
                <div id="lower"><input type="submit" name="submit" value="Add Province" /></div>
            </form>
        </div> <!--End div container-->
        <div class="clear"></div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>