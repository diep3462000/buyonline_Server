<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');
?>
<h2 class="add_new">Manage Products</h2>
<table style="width: 80%; min-width:80%">
    <thead>
        <tr>
            <th><a href='view_products.php?sort=name'>Name Product</a></th> 
            <th><a href='view_products.php?sort=img'>Image Product</a></th>
            <th><a href='view_products.php?sort=pri'>Price Product</a></th>
            <th><a href='view_products.php?sort=type'>Type Product</a></th>
            <th><a href='view_products.php?sort=by'>Post by</a></th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // Sắp xếp theo thứ tự của table head
            if (isset($_GET['sort'])) {
                switch ($_GET['sort']) {
                    case 'name':
                       $order_by = 'product_name';
                        break;
                    case 'img':
                        $order_by = 'product_image';
                        break;
                    case 'pri':
                        $order_by = 'product_price';
                        break;
                    case 'type':
                        $order_by = 'type';
                        break;
                    case 'by':
                        $order_by = 'name';
                        break;
                    default:
                        $order_by = 'position';
                        break;
                } // END switch
            } else{
                    $order_by = 'position';
                }

            // Truy xuất CSDL để hiển thị products
            $q = "SELECT p.product_id, p.product_name, p.product_image, p.product_price, p.position, p.product_type_id, p.user_id, product_type_name AS type , CONCAT_WS(' ', first_name, last_name ) AS name ";
            $q .= " FROM products AS p ";
            $q .= " JOIN users AS u USING ( user_id ) ";
            $q .= " JOIN products_type USING ( product_type_id ) ";            
			$q .= "WHERE user_id = {$_SESSION['uid']}";
			$q .= " ORDER BY {$order_by} ASC ";

            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
			if(count(mysqli_fetch_array($r, MYSQLI_ASSOC)) > 0){
            while ($products = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                echo "
                    <tr>
                        <td>{$products['product_name']}</td>                        
						<td style='text-align:center;'><img src='".BASE_URL."uploads/images/".$products['product_image']."' title='".$products['product_name']." alt='".$products['product_name']."'' style='width:60px; height:60px;' /></td>						
                        <td>{$products['product_price']}</td>
                        <td>{$products['type']}</td>
                        <td>{$products['name']}</td>
                        <td><a class='edit' href='edit_product.php?pro_id={$products['product_id']}'>Edit</a></td>
                        <td><a class='delete' href='delete_product.php?pro_id={$products['product_id']}&pro_name={$products['product_name']}'>Delete</a></td>
                    </tr>
                ";
            } // END while
			}else {
				echo "<p class='error_warning'>Không có sản phẩm nào của bạn .</p>";	
			}
        ?>
    </tbody>
</table>

<?php include('includes/footer.php');?>