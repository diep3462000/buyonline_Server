<?php
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');
        // Confirm variable GET 
        if(isset($_GET['pro_id']) && filter_var($_GET['pro_id'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $pro_id = $_GET['pro_id'];
        } else {
            redirect_to('index.php');
        } 
    	if($_SERVER['REQUEST_METHOD'] == 'POST') {		
			$errors = array();
			// Check product name
			if (empty($_POST['product_name'])) {
				$errors[] = 'product_name';
			} else {
				$product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
			}
            $product_des = mysqli_real_escape_string($dbc, strip_tags($_POST['product_des']));
			
			//Check product price
			if (empty($_POST['product_price'])) {
				$errors = 'product_price';
			} else {
				$product_price = mysqli_real_escape_string($dbc,strip_tags($_POST['product_price']));
			}
	
			//Check product position
			if (isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
				$position = $_POST['position'];
			}else {
				$errors[] = 'position';
			}
	
			//Check Type Of Product 
			if (isset($_POST['product_type_id']) && $_POST['product_type_id'] != 0 && filter_var($_POST['pro_id'],FILTER_VALIDATE_INT)) {
				$product_type_id = $_POST['product_type_id'];
			}else {
				$errors[] = 'product_type_id';
			}
			
			//Check province
			if(empty($_POST['array_city'])) {		
				$errors[] = 'array_city';
			}
	
			if(empty($errors)){
				// Insert data into database
				$q = "UPDATE products ";
				$q .= " SET product_name = '{$product_name}',product_description = '{$product_des}', product_price = $product_price, position = $position, product_type_id = $product_type_id ";
				$q .= " WHERE product_id = {$pro_id} LIMIT 1";
				//echo $q;
				$r = mysqli_query($dbc,$q);
				confirm_query($r,$q);
			   //if (mysqli_affected_rows($dbc) == 1) {
					// Nếu như chèn sản phẩm thành công , thì sau đó chèn vào những thành phố có sản phẩm này 								
					foreach($_POST['array_city'] as $check) {
						$query = "UPDATE province_products SET province_id = $check WHERE product_id = $pro_id";
						//echo $query;
						$result = mysqli_query($dbc, $query);
						if (mysqli_affected_rows($dbc) > 0) {
							$messages = "<p class='success'>The city UPDATE successfully. </p>";
						}else {
							$messages = "<p class='success'>The city UPDATE NOOOOOOOOOOO. </p>";
						} 
				//	}
					$messages = "<p class='success'>The Product was edited successfully. </p>";
				//}else {
					//$messages = "<p class='warning'>Could not edit Product to the database due to a system error. </p>";
					//echo mysqli_affected_rows($dbc);
				}			   
			}else{
					$messages = "<p class='error_warning'>Please fill a field required</p>";
			}
		}
?>

    <div id="left-content">
    	<div id="container">
    		<?php
				$q = "SELECT * FROM products WHERE product_id = {$pro_id}";
				$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
				if(mysqli_num_rows($r) == 1) {
					// Neu product ton tai trong database, dua vao pro_id, xuat du lieu ra ngoai trinh duyet
					$pro = mysqli_fetch_array($r, MYSQLI_ASSOC);
				} else {
					// Neu CID khong hop le, se khong the hien thi category
					$messages = "<p class='error_warning'>The category does not exist.</p>";
				}
        	?>
        	<h2 class="add_new">Edit a Product</h2>
            <?php if(!empty($messages)) echo $messages; ?>
            
            <form enctype="multipart/form-data" action="processor/product_image.php" method="post">
            	<fieldset>
                	<legend>Image of product</legend>
                    <div style="height:80px;">
                        <label for="product_image" class="margin_right">Image Product: <span class="required">*</span></label>                        
                        <div>            
                        	<input type="hidden" name="pro_id" value="<?php echo $pro_id?>" />        	
                            <img class="avatar" src="uploads/images/<?php echo (is_null($pro['product_image']) ? "no_avatar.jpg" : $pro['product_image']); ?>" alt='<?php echo $pro['product_name']?>' />
                            <p>Please select a image of 2MB or smaller. </p>
                            <input type="hidden" name="MAX_FILE_SIZE" value="2024288" />
                            <input type="file" name="image" />
                        	<p><input class="change" type="submit" name="upload" value="Save Change"/></p>
                        </div>
                    </div>
                </fieldset>
            </form>
            
            
            
            <form id="add_product" class="margin_form" action="" method="post">
                <fieldset>
                    <legend>Edit Product</legend>
                    <input type="hidden" name="pro_id" value='<?php echo"{$pro_id}"?>'/>
                    <div class="height_row">
                        <label for="product_name" class="margin_right">Name Of Product: <span class="required">*</span></label>
                        <input type="text" name="product_name" id="product_name" value="<?php if (isset($pro['product_name'])) echo strip_tags($pro['product_name']);?>" />
                        <?php 
                                if (isset($errors) && in_array('product_name', $errors)) {
                                    echo "<p class='warning'>Please fill Product Name. </p>";
                                }
                        ?>
                    </div>

                    <div>
                        <label for="product_des" class="margin_right">Description Of Product: <span class="required"></span></label>
                        <textarea name="product_des" cols="50" rows="20"><?php if(isset($pro['product_description'])) echo strip_tags($pro['product_description'])?></textarea>
                    </div>

                   
                    <div class="clear"></div>
                    <div class="height_row">
                        <label for="product_price" class="margin_right">Price Product: <span class="required">*</span></label>
                        <input type="text" name="product_price" id="product_price" value="<?php if (isset($pro['product_price'])) echo strip_tags($pro['product_price']);?>" />
                        <?php
                            if (isset($errors) && in_array('product_price', $errors)) {
                               echo "<p class='warning'>Please fill in the Product price</p>";
                            }
                   		?>
                    </div>
                    
                    <div class="height_row">
                        <label for="position" class="margin_right">Position: <span class="required">*</span></label>
                        <select name="position" tabindex="2">
                            <?php 
                                $q = "SELECT count(product_id) AS count FROM products WHERE user_id = {$_SESSION['uid']}";
                                $r = mysqli_query($dbc,$q);
                                confirm_query($r,$q);
                                if (mysqli_num_rows($r)==1) {
                                    list($num) = mysqli_fetch_array($r,MYSQLI_NUM);
                                    //mysqli_fetch_array để nói với nó là trả về 1 array . và kiểu trả đây là Num ,
                                    // tương tự ở bên header thì lại trả về MYSQLI_ASSOC trả về theo mảng 
                                    for ($i=1; $i<=$num+1; $i++) { 
                                        //+1 để có nghĩa là thêm 1 giá trị position , tạo giá trị mới .
                                        echo "<option value='{$i}'";
                                            if (isset($pro['position']) && $pro['position'] == $i) {
                                                echo "selected='selected'";
                                            }
                                        echo ">".$i."</option>";
                                    }
                                }
                            ?>
                        </select>
                        <?php
                                if(isset($errors) && in_array('position', $errors)){
                                    echo "<p class='warning'>Please fill in the position</p>";
                                }
                        ?>
                    </div>
                    
                    <div class="height_row">
                        <label for='product_type_id' class="margin_right">Type of Product: <span class='required'>*</span></label>
                        <select name='product_type_id' tabindex="3">
                            <option>Choose Type Product</option>
                            <?php
                                $q = "SELECT product_type_id,product_type_name FROM products_type";
                                $r = mysqli_query($dbc,$q);
                                confirm_query($r, $q);
                                if (mysqli_num_rows($r) > 0) {
										echo "<option value='0'>Chọn loại sản phẩm</option>";
                                    while ($products_type = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                        echo "<option value='{$products_type['product_type_id']}'";
                                        if (isset($pro['product_type_id']) && ($pro['product_type_id']) == $products_type['product_type_id']) {
                                            echo "selected='selected'";
                                        }
                                        echo ">".$products_type['product_type_name']."</option>";
                                    }
                                }
                            ?>
                        </select>
                        <?php 
                                if(isset($errors) && in_array('product_type_id', $errors)){
                                    echo "<p class='warning'>Please fill in the Type of Product </p>";
                                }
                        ?>
                    </div> 
                   <div class='choose_city'>
                    	<label for='choose_city' class="margin_right">Choose City have product: <span class='required'>*</span></label>
                        <?php 
							$q = "SELECT province_name,province_id FROM provinces";
							$r = mysqli_query($dbc, $q);
							confirm_query($r , $q);
							
							$query = "SELECT province_id FROM province_products WHERE product_id = $pro_id";
							$result = mysqli_query($dbc, $query);
							confirm_query($result, $query);
							
							if(mysqli_num_rows($r) > 0){
								while ($name_city = mysqli_fetch_array($r, MYSQLI_ASSOC)){
									echo "<input type='checkbox' value='".$name_city['province_id']."' name='array_city[]'";									
											while ($checked_city = mysqli_fetch_array($result, MYSQLI_ASSOC)){
												if($checked_city['province_id']== $name_city['province_id']){
														echo "checked='checked'";
												}											
										}									
										echo "/>".$name_city['province_name'];									
								}
							}
                                if(isset($errors) && in_array('array_city', $errors)){
                                    echo "<p class='warning'>Please choose least a city . </p>";
                                }
                        ?>
                    </div>                
                </fieldset>
                <p><input type="submit" name="submit" value="Edit Product" /></p>
            </form>
        </div>
    </div> <!-- End left-content -->

<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>