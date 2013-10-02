<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	admin_access();
	include('includes/check_level_user.php');
?>
<div id="left-content">
	<div id="container">
        <?php
            admin_access();
            if (isset($_GET['provinces'], $_GET['province_name']) && filter_var($_GET['provinces'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
                $province_id = $_GET['provinces'];
                $province_name = $_GET['province_name'];
                // Nếu province_id và province_name tồn tại thì sẽ xoá category khỏi csdl
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    //Xử lý form
                    if (isset($_POST['delete']) && $_POST['delete'] == 'yes') {
                        $q = "DELETE FROM provinces WHERE province_id = {$province_id} LIMIT 1";
                        $r = mysqli_query($dbc, $q);
                        confirm_query($r, $q);
                        if (mysqli_affected_rows($dbc) == 1) {
                            // Xoá thành công , thông báo cho người dùng .
                            $messages = "<p class='success'>The province was deleted successfully.</p>";
                        }else {
                            $messages = "<p class='error_warning'>The province was not deleted due to a system error.</p>";
                        }
                    }else {
                        // Không muốn delete category
                        $messages = "<p class='error_warning'>I thought so too! shouldn't be deleted.</p>";
                    }
                }
            }else {
                //Nếu province_id không tồn tại và không dúng định dạng mong muốn . 
                redirect_to('admin/view_provinces.php');
            }
        ?>
    	<h2 class="add_new">Delete Province: <?php if (isset($province_name)) {
            echo htmlentities($province_name, ENT_COMPAT, 'UTF-8');
        }?></h2>
        <?php if (!empty($messages)) {
            echo $messages;
        }?>
        <form action="" method="post">
        	<fieldset>
            	<legend>Delete Province</legend>
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