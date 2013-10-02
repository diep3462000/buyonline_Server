<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = array();
		$errors_image = array();
		
        // Check product name
        if (empty($_POST['product_name'])) {            
			$errors[] = 'product_name';
        } else {            
			$product_name = mysqli_real_escape_string($dbc,strip_tags($_POST['product_name']));
        }
		
         $product_des = mysqli_real_escape_string($dbc, strip_tags($_POST['product_des']));

        //Check product image
        if(isset($_FILES['product_image'])) {			
			// Tao mot array, de kiem tra xem file upload co thuoc dang cho phep
			$allowed = array('image/jpeg', 'image/jpg', 'image/png', 'images/x-png');

			// Kiem tra xem file upload co nam trong dinh dang cho phep
			if(in_array(strtolower($_FILES['product_image']['type']), $allowed)) {
				// Neu co trong dinh dang cho phep, tach lay phan mo rong
				$tmp = explode('.', $_FILES['product_image']['name']);
				$ext = end($tmp);
				$renamed = uniqid(rand(), true).'.'."$ext";

				if(!move_uploaded_file($_FILES['product_image']['tmp_name'], "uploads/images/".$renamed)) {
					$errors_image[] = "<p class='error'>Server problem</p>";
				}
			} else {
				// FIle upload khong thuoc dinh dang cho phep
				$errors_image[] = "<p class='error'>Your file is not a valid type. Please choose a jpg or png image to upload.</p>";
			} 			
		}else {
			$errors[]='product_image';
		}
		
			 // Check for an error
				if($_FILES['product_image']['error'] > 0) {
					
					$errors_image[] = "<p class='error'>The file could not be uploaded because: <strong>";
			
					// Print the message based on the error
					switch ($_FILES['product_image']['error']) {
						case 1:
							$errors_image[] .= "The file exceeds the upload_max_filesize setting in php.ini";
							break;
							
						case 2:
							$errors_image[] .= "The file exceeds the MAX_FILE_SIZE in HTML form";
							break;
						 
						case 3:
							$errors_image[] .= "The was partially uploaded";
							break;
						
						case 4:
							$errors_image[] .= "NO file was uploaded";
							break;
			
						case 6:
							$errors_image[] .= "No temporary folder was available";
							break;
			
						case 7:
							$errors_image[] .= "Unable to write to the disk";
							break;
			
						case 8:
							$errors_image[] .= "File upload stopped";
							break;
						
						default:
							$errors_image[] .= "a system error has occured.";
							break;
					} // END of switch
			
					$errors_image[] .= "</strong></p>";
				} // END of error IF

		// Xoa file da duoc upload va ton tai trong thu muc tam
		if(isset($_FILES['product_image']['tmp_name']) && is_file($_FILES['product_image']['tmp_name']) && file_exists($_FILES['product_image']['tmp_name'])) {
			unlink($_FILES['product_image']['tmp_name']);
		}
        //Check product price
        if (empty($_POST['product_price'])) {            
			$errors[] = 'product_price';
        } else {            
			$product_price = mysqli_real_escape_string($dbc,strip_tags($_POST['product_price']));
        }
		
		//Post cái khuyến mãi lên , check xem nó có khuyến mãi ko ?
		//Nếu như có km thì check xem ở cái count_rate_product,date_start , date_end nó có giá trị ko , nếu ko có thì bắt nó nhập vào .
		//Nếu như không có Khuyến mãi thì xoá đi cái value của date_start , date_end , km . trước khi chèn vào	
		
		if(isset($_POST['sale_the_product'])){
			if(empty($_POST['date_start'])){
				$errors[] = 'date_start';
			}else if (empty($_POST['date_end'])){
				$errors[] = 'date_end';
			}else if (empty($_POST['count_rate'])){
				$errors[] = 'count_rate';
			}else {
				$date_start = $_POST['date_start'];
				$date_end = $_POST['date_end'];
				$count_rate = $_POST['count_rate'];
			}
		}else {
			$date_start = 'now()';
			$date_end = 'now()';
			$count_rate = 0;
		}

        //Check product position
        if (isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $position = $_POST['position'];
        }else {
            $errors[] = 'position';
        }

        //Check Type Of Product 
        if (isset($_POST['product_type_id']) && ($_POST['product_type_id'] != 0)) {
            $product_type_id = $_POST['product_type_id'];
        }else {
            $errors[] = 'product_type_id';
        }
        $user_id = $_SESSION['uid'];		
		
		
		//Check province		
		if(empty($_POST['array_city'])) {		
			$errors[] = 'array_city';
		}
		

        if(empty($errors) && empty($errors_image)){
            // Insert data into database
            $q = "INSERT INTO products(product_name,product_description, product_image, product_price,sale_product, position, product_type_id, user_id, date_start_sale, date_end_sale) ";
            $q .= " VALUES('{$product_name}', '{$product_des}', '{$renamed}', $product_price, $count_rate, $position, $product_type_id, $user_id,{$date_start}, {$date_end})";
			
			
            $r = mysqli_query($dbc,$q);
            confirm_query($r,$q);
            if (mysqli_affected_rows($dbc) == 1) {
				$id =  mysqli_insert_id($dbc);
				// Nếu như chèn sản phẩm thành công , thì sau đó chèn vào những thành phố có sản phẩm này 								
				foreach($_POST['array_city'] as $check) {
					$query = "INSERT INTO province_products (province_id, product_id) VALUES ({$check}, {$id})";
					$result = mysqli_query($dbc, $query);
					if (mysqli_affected_rows($dbc) > 0) {
						$messages = "<p class='success'>The city insert added successfully. </p>";
					}else {
						$messages = "<p class='success'>The city insert NOOOOOOOOOOO. </p>";
					} 
				}
                $messages = "<p class='success'>The Product was added successfully. </p>";
            }else {
                $messages = "<p class='warning'>Could not added Product to the database due to a system error. </p>";
            }
        }else{
                $messages = "<p class='error_warning'>Please fill a field required</p>";
        }
    }
?>
    <div id="left-content">
    	<div id="container">
        	<h2 class="add_new">Create a Product</h2>
            <?php if(!empty($messages)) echo $messages; ?>
            <form id="add_product" class="margin_form" action="" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Add Product</legend>
                    <div class="height_row">
                        <label for="product_name" class="margin_right">Name Of Product: <span class="required">*</span></label>
                        <input type="text" name="product_name" id="product_name" value="<?php if (isset($_POST['product_name'])) echo strip_tags($_POST['product_name']);?>" />
                        <?php 
                                if (isset($errors) && in_array('product_name', $errors)) {
                                    echo "<p class='warning'>Please fill Product Name. </p>";
                                }
                        ?>
                    </div>

                    <div>
                        <label for="product_des" class="margin_right">Description Of Product: <span class="required"></span></label>
                        <textarea name="product_des" cols="50" rows="20"><?php if(isset($_POST['product_des'])) echo strip_tags($_POST['product_des'])?></textarea>
                    </div>

                    <div class="height_row">
                        <label for="product_image" class="margin_right">Image Product: <span class="required">*</span></label>
                        <input type="file" name="product_image" id="product_image" value="<?php if (isset($_POST['product_image'])) echo strip_tags($_POST['product_image']);?>" />
                        <?php 
                                if (isset($errors) && in_array('product_image', $errors)) {
                                    echo "<p class='warning'>Please fill Product Image. </p>";
                                }
                        ?>
                    </div>
                    
                    <div class="height_row">
                        <label for="product_price" class="margin_right">Price Product: <span class="required">*</span></label>
                        <input type="text" name="product_price" onkeypress='validate(event)' id="product_price" value="<?php if (isset($_POST['product_price'])) echo strip_tags($_POST['product_price']);?>" />
                        <?php
                            if (isset($errors) && in_array('product_price', $errors)) {
                               echo "<p class='warning'>Please fill in the Product price</p>";
                            }
                   		?>
                    </div>
                    
                    <div class="height_row">
                        <label for="sale_the_product" class="margin_right">Sale this product ? <span class="required"></span></label>
                        <input type="checkbox" id="cb-sale" name="sale_the_product"  />        
                        <?php #if(isset($_POST['sale_the_product'])){echo "checked = 'checked'"; } ?>                
                    </div>
                    
                    <div id="div_sale" style="display:none">
                        <label for="count_rate_product" class="margin_right">Count rate: <span class="required">Enter Only Number</span></label>
                        <input type="text" name="count_rate" id="count_rate" onkeypress='validate(event)' value="<?php if (isset($_POST['count_rate'])) echo strip_tags($_POST['count_rate']);?>" />
                        <?php
                            if (isset($errors) && in_array('count_rate', $errors)) {
                               echo "<p class='warning'>Please fill in the count rate.</p>";
                            }
                   		?>
                        <input type="text" name="count_rate_percent" id="count_rate_percent" style="margin-top:3px;" readonly="readonly" />
                        <div class="clear"></div>
                        <label for="product_price" class="margin_right">Date start:</label>
                        <input type="text" name="date_start" class='datepicker' readonly="readonly" value="<?php if (isset($_POST['date_start'])) echo strip_tags($_POST['date_start']);?>" /> 
                        <?php
                            if (isset($errors) && in_array('date_start', $errors)) {
                               echo "<p class='warning'>Please fill in the start product sale. </p>";
                            }
                   		?>
                        <div class="clear"></div>
                        <label for="product_price" class="margin_right">Date end:</label>
                        <input type="text" name="date_end" class='datepicker' readonly="readonly" value="<?php if (isset($_POST['date_end'])) echo strip_tags($_POST['date_end']);?>" />
                        <?php
                            if (isset($errors) && in_array('date_end', $errors)) {
                               echo "<p class='warning'>Please fill in the end product sale. </p>";
                            }
                   		?>
                    </div>
                    
                    <div class="height_row">
                        <label for="position" class="margin_right">Position: <span class="required">*</span></label>
                        <select name="position" tabindex="2" style="float:left;">
                            <?php 
                                $q = "SELECT count(product_id) AS count FROM products where user_id = {$_SESSION['uid']}";
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
                                    echo "<p class='warning'>Please fill in the position</p>";
                                }
                        ?>
                    </div>
                    
                    <div class="height_row">
                        <label for='product_type_id' class="margin_right">Type of Product: <span class='required'>*</span></label>
                        <select name='product_type_id' tabindex="3" style="float:left;">
                            <option value="0">Choose Type Product</option>
                            <?php
                                $q = "SELECT product_type_id,product_type_name FROM products_type";
                                $r = mysqli_query($dbc,$q);
                                confirm_query($r, $q);
                                if (mysqli_num_rows($r) > 0) {
                                    while ($products_type = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                        echo "<option value='{$products_type['product_type_id']}'";
                                        if (isset($_POST['product_type_id']) && ($_POST['product_type_id']) == $products_type['product_type_id']) {
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
                        <div class="list_cities" style="float:right; width:470px; margin:0px">
                        	<?php 
								$q = "SELECT province_name,province_id FROM provinces";
								$r = mysqli_query($dbc, $q);
								confirm_query($r , $q);
								if(mysqli_num_rows($r) > 0){
									while ($name_city = mysqli_fetch_array($r, MYSQLI_ASSOC)){
										echo "<div style='float:left;margin:0px;'>";
										echo "<input type='checkbox' value='".$name_city['province_id']."' name='array_city[]'";
										if(isset($_POST['array_city'])){
											foreach($_POST['array_city'] as $check){
												if($check == $name_city['province_id']){
													echo "checked='checked'";	
												}
											}
										}
										echo "/>".$name_city['province_name'];
										echo "</div>";
									}
								}
									if(isset($errors) && in_array('array_city', $errors)){
										echo "<p class='warning'>Please choose least a city . </p>";
									}
							?>
                            <div class='clear'></div>
                        </div>
                    </div>
                                     
                </fieldset>
                <p><input type="submit" name="submit" value="Add Product" /></p>
            </form>
        </div>
    </div> <!-- End left-content -->

<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>