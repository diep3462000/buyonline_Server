<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php'); 
	admin_access();
	include('includes/header.php');
	include('includes/check_level_user.php'); 
?>
<div id="left-content">
	<div id="container">
		<h2 class="add_new">Manage Categories</h2>
		<table>
			<thead>
		    	<tr>
		        	<th><a href='view_categories.php?sort=cat'>Categories</a></th> 
		            <th><a href='view_categories.php?sort=pos'>Position</a></th>
		            <th><a href='view_categories.php?sort=by'>Link</a></th>
		            <th>Edit</th>
		            <th>Delete</th>
		        </tr>
		    </thead>
		    <tbody>
		        <?php
		            // Sắp xếp theo thứ tự của table head
		            if (isset($_GET['sort'])) {
		                switch ($_GET['sort']) {
		                    case 'cat':
		                       $order_by = 'cat_name';
		                        break;
		                    case 'pos':
		                        $order_by = 'position';
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

		            // Truy xuất CSDL để hiển thị cats
		            $q = "SELECT c.cat_id, c.cat_name, c.position, c.link ";
		            $q .= " FROM categories AS c ";
		            $q .= " ORDER BY {$order_by} ASC";

		            $r = mysqli_query($dbc, $q);
		            confirm_query($r, $q);

		            while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		                echo "
		                    <tr>
		                        <td>{$cats['cat_name']}</td>
		                        <td>{$cats['position']}</td>
		                        <td>{$cats['link']}</td>
		                        <td><a class='edit' href='edit_category.php?cid={$cats['cat_id']}'>Edit</a></td>
		                        <td><a class='delete' href='delete_category.php?cid={$cats['cat_id']}&cat_name={$cats['cat_name']}'>Delete</a></td>
		                    </tr>
		                ";
		            }
		        ?>
		    	
		    </tbody>
		</table>
        <div class="clear"></div>
	</div> <!--End container-->
    
</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>