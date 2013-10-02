<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	$title = 'Delte Users'; include('includes/header.php');
	include('includes/check_level_user.php'); 
?>
<div id="left-content">
	<div id="container">
        <?php
            admin_access();
            if ((isset($_GET['user_id'])) && (isset($_GET['email'])) && filter_var($_GET['user_id'], FILTER_VALIDATE_INT, array('min_range' => 1 && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)))) {
                $user_id = $_GET['user_id'];
                $email = $_GET['email'];
				
				// $q = "SELECT first_name FROM users WHERE user_id = {$user_id} AND email = '{$email}'";
				// echo $q;
				// $r = mysqli_query($dbc, $q);
				// confirm_query($r, $q);
				
				//if(mysqli_affetch_rows($r) == 1){
					// Nếu user_id và email tồn tại thì sẽ xoá user khỏi csdl
					if($_SERVER['REQUEST_METHOD'] == 'POST'){
						//Xử lý form
						if (isset($_POST['delete']) && $_POST['delete'] == 'yes') {
							$q = "DELETE FROM users WHERE user_id = {$user_id} LIMIT 1";
							$r = mysqli_query($dbc, $q);
							confirm_query($r, $q);
							if (mysqli_affected_rows($dbc) == 1) {
								// Xoá thành công , thông báo cho người dùng .
								$messages = "<p class='success'>The user was deleted successfully.</p>";
							}else {
								$messages = "<p class='error_warning'>The user was not deleted due to a system error.</p>";
							}
						}else {
							// Không muốn delete user
							$messages = "<p class='error_warning'>I thought so too! shouldn't be deleted.</p>";
						}
					}
				// }else {					
				// 	 //Nếu user_id không tồn tại và không dúng định dạng mong muốn . 
    //             	redirect_to('index.php');
				// }
            }else {
                //Nếu user_id không tồn tại và không dúng định dạng mong muốn . 
                redirect_to('index.php');
            }
            
        ?>
    	<h2 class="add_new">Delete User: <?php if (isset($email)) {
            echo htmlentities($email, ENT_COMPAT, 'UTF-8');
        }?></h2>
        <?php if (!empty($messages)) {
            echo $messages;
        }?>
        <form action="" method="post">
        	<fieldset>
            	<legend>Delete User</legend>
                <label for="delete">Are you sure ?</label>
                <div>
                	<input type="radio" name="delete" value="no" checked="checked" /> No
                    <input type="radio" name="delete" value="yes" /> Yes
                </div>
                <div><input type="submit" name="submit" value="Delete" onclick="return confirm('Are you sure ?');"/></div>
            </fieldset>
        </form>
    <div class="clear"></div>
	</div> <!--End container-->    
</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>