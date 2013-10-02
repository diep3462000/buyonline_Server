<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');  
	admin_access();
	include('includes/header.php');
	include('includes/check_level_user.php');
  	
       	
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form			
			$errors = array();
			if(empty($_POST['category'])){
				$errors[] = "category";
			} else {
				$cat_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category']));
			}

            if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))){ 
                // filter_var , truyền vào 3 tham số , đầu tiên là truyền vào cái mình muốn kiểm tra .
                // tham số thứ 2 là FILTER_VALIDATE_INT kiểm tra có phải integer ko , nếu đúng thì là true , ko là false
                // tham số thứ 3 không bắt buộc , và ở đây position mình muốn > 1 .
            $position = $_POST['position'];
			}else {
				$errors[] = "position";
			}

            if(empty($_POST['link'])){
                $errors[] = "link";
            } else {
                $link = mysqli_real_escape_string($dbc,strip_tags($_POST['link']));
            }
			
			
			//Check category đã tồn tại chưa ? 			
			$q = "SELECT cat_id FROM categories WHERE cat_name = '{$_POST['category']}'";
			$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
			if(mysqli_num_rows($r) == 0){
				//Không có category này thì cho đăng ký
				$active = md5(uniqid(rand(), true));
			}else {
				//Email này đã có , bắt phải nhập email khác
				$errors[] = 'category-uni';
			}
			
			
		if(empty($errors)){
                // If no have error , inset data to database
			$q = "INSERT INTO categories (link,cat_name,position) VALUES ('{$link}', '{$cat_name}', $position)";
			$r = mysqli_query($dbc, $q);
			confirm_query($r,$q);
			if (mysqli_affected_rows($dbc) == 1 ) {
				$messages = "<p class='success'>The category was added successfully.</p>";
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
        	    <h2 class="add_new">Create a category</h2>
				<?php if(!empty($messages)) echo $messages; ?>
                <form id="add_cat" class="margin_form" action="" method="post">
                    <fieldset>
                        <legend>Add category</legend>
                        <div class="height_row">
                            <label for="category" class="margin_right">Category Name: <span class="required">*</span></label>                    
                            <input type="text" name="category" id="category" value="<?php if (isset($_POST['category'])) {
                                echo strip_tags($_POST['category']);} ?>" size="20" maxlength="150" tabindex="1"/>
                            <?php
                                    if(isset($errors) && in_array('category', $errors)){
                                        echo "<p class='warning'>Please fill in the category name</p>";
                                    }
                            ?>
                            <?php
                                    if(isset($errors) && in_array('category-uni', $errors)){
                                        echo "<p class='warning'>Category unique, check again. </p>";
                                    }
                            ?>
                            <span id="available"></span>
                        </div>
                        <div class="height_row">
                            <label for="position" class="margin_right">Position: <span class="required">*</span></label>
                            <select name="position" tabindex="2"  style="margin-top:10px">
                                <?php 
                                    $q = "SELECT count(cat_id) AS count FROM categories";
                                    $r = mysqli_query($dbc,$q);
                                    confirm_query($r,$q);
                                    if (mysqli_num_rows($r)==1) {
                                        list($num) = mysqli_fetch_array($r,MYSQLI_NUM);
                                        //mysqli_fetch_array để nói với nó là trả về 1 array . và kiểu trả đây là Num ,
                                        // tương tự ở bên header thì lại trả về MYSQLI_ASSOC trả về theo mảng 
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
                                        echo "<p class='warning'>Please fill in the category position</p>";
                                    }
                            ?>
                        </div> 
                        <div class="height_row">
                            <label for='link' class='margin_right'>Link: <span class='required'>*</span></label>
                             <input type="text" name="link" id="link" value="<?php if (isset($_POST['link'])) {
                                echo strip_tags($_POST['link']);} ?>" size="20" maxlength="150" tabindex="1"/>
                            <?php
                                    if(isset($errors) && in_array('link', $errors)){
                                        echo "<p class='warning'>Please fill in the Link.</p>";
                                    }
                            ?>
                        </div>
                </fieldset>
                <div id="lower"><input type="submit" name="submit" value="Add Category" /></div>
            </form>
        </div> <!--End div continer-->
        <div class="clear"></div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>