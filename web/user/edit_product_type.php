<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');
    
        admin_access();
        // Confirm variable GET 
        if(isset($_GET['pro_type_id']) && filter_var($_GET['pro_type_id'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $pro_type_id = $_GET['pro_type_id'];
        } else {
            redirect_to('admin/index.php');
        }       
        if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri tri ton tai, xu ly form.
            $errors = array();
			
			 //Check product image
			if(isset($_FILES['product_type_image'])) 
				{			
					// Tao mot array, de kiem tra xem file upload co thuoc dang cho phep
					$allowed = array('image/jpeg', 'image/jpg', 'image/png', 'images/x-png');
		
					// Kiem tra xem file upload co nam trong dinh dang cho phep
					if(in_array(strtolower($_FILES['product_type_image']['type']), $allowed)) 
						{
							// Neu co trong dinh dang cho phep, tach lay phan mo rong
							$tmp = explode('.', $_FILES['product_type_image']['name']);
							$ext = end($tmp);
							$renamed = uniqid(rand(), true).'.'."$ext";
			
							if(!move_uploaded_file($_FILES['product_type_image']['tmp_name'], "uploads/images/".$renamed)) 
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
					$errors[]='product_type_image';
				}
			
            // Kiem tra ten cua pro_type
            if(empty($_POST['pro_type'])) {
                $errors[] = "pro_type";
            } else {
                $pro_type_name = mysqli_real_escape_string($dbc,strip_tags($_POST['pro_type']));
            }
			
			

            //Check data and update to database
            if(empty($errors)) {                 
                // Neu khong co loi xay ra, thi chen vao csdl.
                $q = "UPDATE products_type SET product_type_name = '{$pro_type_name}', product_type_image = '{$renamed}' WHERE product_type_id = {$pro_type_id} LIMIT 1";              
                $r = mysqli_query($dbc, $q);                
                confirm_query($r, $q);       
                if(mysqli_affected_rows($dbc) == 1) {
                $messages = "<p class='success'>The Product type was edited successfully.</p>";
                } else {
                    $messages = "<p class='warning'>Could not edit the Product due to a system error.</p>";
                }
            } else {
            $messages = "<p class='error_warning'>Please fill all the required fields</p>";
        }
        } // END main IF submit condition
    ?>
    <div id="left-content">
		<div id="container">
        	<?php
				$q = "SELECT * FROM products_type WHERE product_type_id = {$pro_type_id}";
				$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);
				if(mysqli_num_rows($r) == 1) {
					// Neu product ton tai trong database, dua vao pro_id, xuat du lieu ra ngoai trinh duyet
					$pro = mysqli_fetch_array($r, MYSQLI_ASSOC);
				} else {
					// Neu CID khong hop le, se khong the hien thi category
					$messages = "<p class='error_warning'>The Product Type does not exist.</p>";
				}
        	?>
        	    <h2 class="add_new">Edit Product Type</h2>
				<?php if(!empty($messages)) echo $messages; ?>
                <form id="edit_pro_type" class="margin_form" action="" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Edit Product Type</legend>
                        <div class="height_row">
                            <label for="pro_type" class="margin_right">Product Type Name: <span class="required">*</span></label>                    
                            <input type="text" name="pro_type" id="pro_type" value="<?php if (isset($_POST['pro_type'])) {
                                echo strip_tags($_POST['pro_type']);}else {echo $pro['product_type_name'];} ?>" size="20" maxlength="150" tabindex="1"/>
                            <?php
                                    if(isset($errors) && in_array('pro_type', $errors)){
                                        echo "<p class='warning'>Please fill in the product type name</p>";
                                    }
                            ?>
                        </div>
                        <div class="height_row">
                            <label for="product_type_image" class="margin_right">Image Type Product: <span class="required">*</span></label>
                            <img class="avatar" src="uploads/images/<?php echo (is_null($pro['product_type_image']) ? "no_avatar.jpg" : $pro['product_type_image']); ?>" alt='<?php echo $pro['product_type_name']?>' />
                            <input type="file" name="product_type_image" id="product_type_image" value="<?php if (isset($_POST['product_type_image'])) echo strip_tags($_POST['product_type_image']);?>" />
                            <?php 
                                    if (isset($errors) && in_array('product_type_image', $errors)) {
                                        echo "<p class='warning'>Please fill Product Type Image. </p>";
                                    }
                            ?>
                        </div>
                        
                </fieldset>
                <div id="lower"><input type="submit" name="submit" value="Edit Product Name" /></div>
            </form>
        </div> <!--End div continer-->
        <div class="clear"></div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>