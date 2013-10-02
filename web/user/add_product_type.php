<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');  	
        admin_access();	
		
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form			
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
			
			if(empty($_POST['pro_type']))
				{
					$errors[] = "pro_type";
				} 
			else
				{
					$pro_type = mysqli_real_escape_string($dbc,strip_tags($_POST['pro_type']));
				}
		
		if(empty($errors))
			{
					// If no have error , inset data to database
				$q = "INSERT INTO products_type (product_type_name, product_type_image) VALUES ('{$pro_type}', '{$renamed}')";
				$r = mysqli_query($dbc, $q);
				confirm_query($r,$q);
				if (mysqli_affected_rows($dbc) == 1 ) 
					{
						$messages = "<p class='success'>The Product Type was added successfully.</p>";
					}
				else
					{
						$messages = "<p class='error_warning'>Could not added to the database due to a system error.</p>";
					}				
			}
		else 
			{
				$messages = "<p class='error_warning'>Please fill all the reuired fields </p>";
			}
        }// END main IF submit condition
    ?>
    <div id="left-content">
		<div id="container">
        	    <h2 class="add_new">Create a Product Type</h2>
				<?php if(!empty($messages)) echo $messages; ?>
                <form id="add_pro_type" class="margin_form" action="" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Add Product Type</legend>
                        <div class="height_row">
                            <label for="pro_type" class="margin_right">Product Type Name: <span class="required">*</span></label>                    
                            <input type="text" name="pro_type" id="pro_type" value="<?php if (isset($_POST['pro_type'])) {
                                echo strip_tags($_POST['pro_type']);} ?>" size="20" maxlength="150" tabindex="1"/>
                            <?php
                                    if(isset($errors) && in_array('pro_type', $errors)){
                                        echo "<p class='warning'>Please fill in the product type name</p>";
                                    }
                            ?>
                        </div>
                         <div class="height_row">
                            <label for="product_type_image" class="margin_right">Image Type Product: <span class="required">*</span></label>
                            <input type="file" name="product_type_image" id="product_type_image" value="<?php if (isset($_POST['product_type_image'])) echo strip_tags($_POST['product_type_image']);?>" />
                            <?php 
                                    if (isset($errors) && in_array('product_type_image', $errors)) {
                                        echo "<p class='warning'>Please fill Product Type Image. </p>";
                                    }
                            ?>
                        </div>
                </fieldset>
                <div id="lower"><input type="submit" name="submit" value="Add Product Name" /></div>
            </form>
        </div> <!--End div continer-->
        <div class="clear"></div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>