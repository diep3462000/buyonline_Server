<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php'); 
	admin_access();
	include('includes/header.php');
	include('includes/check_level_user.php'); 
?>
<div id="left-content">
	<div id="container">
		<h2 class="add_new">Manage Provinces</h2>
		<table>
			<thead>
		    	<tr>
		        	<th><a href='view_provinces.php?sort=name'>Provinces Name</a></th> 
                    <th><a href=''>Provinces Image</a></th>
		            <th><a href='view_provinces.php?sort=pos'>Position</a></th>
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
		                       $order_by = 'province_name';
		                        break;
		                    case 'pos':
		                        $order_by = 'position';
		                        break;
		                    default:
		                        $order_by = 'position';
		                        break;
		                } // END switch
		            } else{
		                    $order_by = 'position';
		                }

		            // Truy xuất CSDL để hiển thị cats
		            $q = "SELECT p.province_id, p.province_image, p.province_name, p.position ";
		            $q .= " FROM provinces AS p ";
		            $q .= " ORDER BY {$order_by} ASC";

		            $r = mysqli_query($dbc, $q);
		            confirm_query($r, $q);

		            while ($provinces = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		                echo "
		                    <tr>
		                        <td>{$provinces['province_name']}</td>
								<td style='text-align:center;padding-top:10px;'><img src='".BASE_URL."uploads/images/".$provinces['province_image']."' title='".$provinces['province_name']." alt='".$provinces['province_name']."'' style='width:60px; height:60px;' /></td>
		                        <td>{$provinces['position']}</td>
		                        <td><a class='edit' href='edit_province.php?provinces={$provinces['province_id']}'>Edit</a></td>
		                        <td><a class='delete' href='delete_province.php?provinces={$provinces['province_id']}&province_name={$provinces['province_name']}'>Delete</a></td>
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