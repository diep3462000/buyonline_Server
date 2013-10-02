<?php 
 include('includes/mysqli_connect.php');
 include('includes/functions.php');
 $title = 'Manager User'; 
 include('includes/header.php');
 include('includes/check_level_user.php');
 ?>
<div id="left-content" style="width:999px">
	<div id="container">
		<h2 class="add_new">Manage Users</h2>
		<table style="font-size:13px">
			<thead>
		    	<tr>
		        	<th><a href='view_users.php?sort=id'>ID</a></th> 
		            <th><a href='view_users.php?sort=name'>Name</a></th>
		            <th><a href='view_users.php?sort=email'>Email</a></th>
		            <th><a href='#'>Password</a></th>
		            <th><a href='#'>Website</a></th>
		            <th><a href='#'>Yahoo</a></th>
		            <th><a href='#'>Bio</a></th>
		            <th><a href='view_users.php?sort=sex'>Sex</a></th>
		            <th><a href='#'>Mobile Phone</a></th>
		            <th><a href='#'>Home Phone</a></th>
		            <th><a href='#'>Avatar</a></th>
		            <th><a href='#'>active</a></th>
		            <th><a href='view_users.php?sort=reg'>Reg Date</a></th>
		            <th>Edit</th>
		            <th>Delete</th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php
		        	//Kiểm tra xem biến sort tồn tại ko ? mặc đinh là sắp xếp theo id.
		        	$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'id';

		            // Sắp xếp theo thứ tự của table head
		                $order_by = sort_table_users($sort);
		            

		            // Truy xuất CSDL để hiển thị user
		            	$users = fetch_users($order_by);
		                
		                // In kết quả ra .
		            	foreach ($users as $user) {?>
		                    <tr>
		                        <td><?php echo $user['user_id']?></td>
		                        <td><?php echo $user['name'] ?></td>
		                        <td><?php echo $user['email'] ?></td>
		                        <td><?php echo "******" ?></td>  <!--Nếu muốn show thì  $user['password'] -->
		                        <td><?php echo $user['website'] ?></td>
		                        <td><?php echo $user['yahoo'] ?></td>
		                        <td><?php echo $user['bio'] ?></td>
		                        <td>
									<?php if($user['sex'] == 1){
										echo "Nam";
									}else echo "Nữ";
									?>
                                </td>
		                        <td><?php echo $user['mobile_phone'] ?></td>
		                        <td><?php echo $user['home_phone'] ?></td>
								<td><img class='thumb_image' src='<?php if(!empty($user['avatar'])){
										echo BASE_URL."uploads/images/".$user['avatar'];
									}else {
										echo BASE_URL."uploads/images/no_avatar.jpg";	
									}?>' /></td>
		                        <td><?php echo $user['active'] ?></td>
		                        <td><?php echo $user['date'] ?></td>
		                        <td><a class='edit' href='edit_user.php?user_id=<?php echo $user['user_id']?>'>Edit</a></td>
		                        <td><a class='delete' href='delete_user.php?user_id=<?php echo $user['user_id']?>&email=<?php echo $user['email']?>'>Delete</a></td>
		                    </tr>		                
		            	<?php } ?>

		        
		    	
		    </tbody>
		</table>
        <div class="clear"></div>
	</div> <!--End container-->
    
</div> <!--End left-content-->
<?php include('includes/footer.php');?>