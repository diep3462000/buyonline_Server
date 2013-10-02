<?php 
 include('includes/mysqli_connect.php');
 include('includes/functions.php');
 include('includes/header.php');
 include('includes/check_level_user.php'); 
?>
<div id="left-content">
	<div id="container">
		<h2 class="add_new">Manage Products Type</h2>
		<table>
			<thead>
		    	<tr>
		        	<th><a href='view_products_type.php?sort=id'>ID</a></th> 
		            <th><a href='view_products_type.php?sort=name'>Name</a></th>
                    <th><a href='view_products_type.php?sort=image'>Image</a></th>
		            <th>Edit</th>
		            <th>Delete</th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php
		            // Sắp xếp theo thứ tự của table head
		            if (isset($_GET['sort'])) {
		                switch ($_GET['sort']) {
		                    case 'id':
		                       $order_by = 'product_type_id';
		                        break;
		                    case 'name':
		                        $order_by = 'product_type_name';
		                        break;
							case 'image':
		                        $order_by = 'product_type_image';
		                        break;
		                    default:
		                        $order_by = 'product_type_id';
		                        break;
		                } // END switch
		            } else{
		                    $order_by = 'product_type_id';
		                }

		            // Truy xuất CSDL để hiển thị Product Type
		            $q = "SELECT * FROM products_type ";
		            $q .= " ORDER BY {$order_by} ASC";

		            $r = mysqli_query($dbc, $q);
		            confirm_query($r, $q);

		            while ($pro_type = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						?>
		                    <tr>
		                        <td><?php echo $pro_type['product_type_id'];?></td>
		                        <td><?php echo $pro_type['product_type_name']?></td>
                                <td><img class='thumb_image' src="<?php echo BASE_URL."uploads/images/{$pro_type['product_type_image']}" ?>" title="<?php echo $pro_type['product_type_name']?>" /></td>
		                        <td><a class='edit' href='edit_product_type.php?pro_type_id=<?php echo $pro_type['product_type_id']?>'>Edit</a></td>
		                        <td><a class='delete' href='delete_product_type.php?pro_type_id=<?php echo $pro_type['product_type_id']?>&pro_type_name=<?php echo $pro_type['product_type_name']?>'>Delete</a></td>
		                    </tr>
		               
		            <?php } ?>
		    </tbody>
		</table>
        <div class="clear"></div>
	</div> <!--End container-->
    
</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>