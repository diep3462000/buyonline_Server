<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');    
        admin_access();
        // Confirm variable GET 
        if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $cid = $_GET['cid'];
        } else {
            redirect_to('admin/index.php');
        }    	
		if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri tri ton tai, xu ly form.
            $errors = array();
            // Kiem tra ten cua category
            if(empty($_POST['category'])) {
                $errors[] = "category";
            } else {
            	$cat_name = mysqli_real_escape_string($dbc,strip_tags($_POST['category']));
            }
            
            // Kiem tra link cua category
            if(empty($_POST['link'])) {
                $errors[] = "link";
            } else {
                $link = mysqli_real_escape_string($dbc,strip_tags($_POST['link']));
            }

            // Kiem tra position cua category
            if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
                $position = $_POST['position'];
            } else {
                $errors[] = "position";
            }
			
			
			//Check category đã tồn tại chưa ? 			
			$query = "SELECT cat_name FROM categories WHERE cat_name = '{$cat_name}'";
			$result = mysqli_query($dbc, $query);
			confirm_query($result, $query);
			if(mysqli_num_rows($result) != 0){
				list($cat0) = mysqli_fetch_array($result, MYSQLI_NUM);
				
				$q = "SELECT cat_name FROM categories WHERE cat_id = {$cid}";
				$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
				list($cat1) = mysqli_fetch_array($r, MYSQLI_NUM);
				
				if($cat0 != $cat1){
					//cat này đã có , bắt phải nhập cat khác
					$errors[] = 'category-uni';
				}				
			}	
				
			
            //Check data and update to database
            if(empty($errors)) {
				 
                // Neu khong co loi xay ra, thi chen vao csdl.
				$q = "UPDATE categories SET cat_name = '{$cat_name}', position = $position, link = '{$link}' WHERE cat_id = {$cid} LIMIT 1";				
				$r = mysqli_query($dbc, $q);				
				confirm_query($r, $q);   
				 echo $r;
				echo "Affected rows: " . mysqli_affected_rows($dbc);      
             	if(mysqli_affected_rows($dbc) == 1) {
                $messages = "<p class='success'>The category was edited successfully.</p>";
				} else {
					$messages = "<p class='error_warning'>Could not edit the category due to a system error.</p>";
				}
			} else {
            $messages = "<p class='error_warning'>Please fill all the required fields</p>";
        }
        } // END main IF submit condition
    ?>
    <div id="left-content">
		<div id="container">
        	<?php
				$q = "SELECT cat_name, position, link FROM categories WHERE cat_id = {$cid}";
				$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
				if(mysqli_num_rows($r) == 1) {
					// Neu category ton tai trong database, dua vao CID, xuat du lieu ra ngoai trinh duyet
					list($cat_name, $position, $link) = mysqli_fetch_array($r, MYSQLI_NUM);
				} else {
					// Neu CID khong hop le, se khong the hien thi category
					$messages = "<p class='error_warning'>The category does not exist.</p>";
				}
        	?>
            <h2 class="add_new">Edit category: <?php if(isset($cat_name)) echo $cat_name; ?></h2>
            <?php if (!empty($messages)) {
                echo $messages;
            }?>
            <form id="edit_cat" class="margin_form" action="" method="post">
                <fieldset>
                    <legend>Edit category</legend>
                    <div class="height_row">
                        <label for="category" class="margin_right">Category Name: <span class="required">*</span></label>                    
                        <input type="text" name="category" id="category" value="<?php if (isset($cat_name)) {
                            echo $cat_name;
                        }?>" size="20" maxlength="150" tabindex="1"/>
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
                                            if (isset($position) && ($position == $i)) {
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
                        <label for="link" class="margin_right">Link: <span class="required">*</span></label>                    
                        <input type="text" name="link" id="link" value="<?php if (isset($link)) {
                            echo $link;
                        }?>" size="20" maxlength="150" tabindex="1"/>
                        <?php
                                if(isset($errors) && in_array('link', $errors)){
                                    echo "<p class='warning'>Please fill in the category Link</p>";
                                }
                        ?>
                    </div>
                </fieldset>
                <div id="lower"><input type="submit" name="submit" value="Change Category" /></div>
            </form>
        
        </div> <!--End div continer-->
        <div class="clear"></div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>