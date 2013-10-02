<?php 
	include('includes/mysqli_connect.php');	
	include('includes/functions.php');

      // Nếu lấy đc product id hợp lệ
  if ($pro_id = validate_id($_GET['pro_id'])){
    $q = "SELECT product_id, product_name, LEFT(product_description, 400) AS product_description, product_image, product_price, product_type_id, user_id, position, DATE_FORMAT(date_post_product, '%b %d %Y') AS date_post FROM products WHERE product_id = {$pro_id}";
    $r = mysqli_query($dbc, $q);
    confirm_query($r, $q);
    $count_views = view_counter($pro_id);
    $pro = array(); //tạo một mảng để lưu lại giá trị , tác dụng nó để lấy title .
    if (mysqli_num_rows($r) == 1) {
      $product = mysqli_fetch_array($r, MYSQLI_ASSOC);
      $title = $product['product_name'];
      $pro = array('product_id' => $product['product_id'], 
                  'product_name' => $product['product_name'], 
                  'product_description' => $product['product_description'], 
                  'product_image' => $product['product_image'], 
                  'product_price' => $product['product_price'], 
                  'product_type_id' => $product['product_type_id'], 
                  'user_id' => $product['user_id'], 
                  'position' => $product['position'], 
                  'date_post' => $product['date_post']);
    }else {
      echo "<p>Product don't extractly.</p>";
    }
  }else {
         //Nếu pro_id không tồn tại và không dúng định dạng mong muốn . 
         redirect_to('index.php');
      }

  include('includes/header.php');
  include('includes/manager_by_admin.php');
?>
<div class="detail">
  <div class="detail_left">
    <div class="gallery">
      <img class= 'product_image' src='<?php echo BASE_URL."uploads/images/".$pro['product_image'];?>' title='<?php echo $pro['product_name'];?>' alt='<?php echo $pro['product_name'];?>'/>
    </div>
    <h1 class="productname"><?php if (isset($pro['product_name'])) {
            	echo $pro['product_name'];
            }?>
    </h1>
  </div>
  <div class="detail_right">
    <form name="cartform" class="mycart product validate detail_section" method="post" action="">
      <legend class="enter_detail"></legend>
      <div class="left_field">
        <div class="price">Price <span> <?php echo $pro['product_price']. ".000 VND"?></span></div>
        <div class="divider"></div>
        <div class="offer_section">
          <div class="low_add"></div>
          <div class="money_return"></div>
        </div>
      </div>
      <div class="right_field">
        <div class="product_outer">
          <div class="product_detail">
            <label>Publish by: 
            	<a href='#'>
                		<?php
                			$q = "SELECT CONCAT_WS(' ', first_name, last_name) AS name FROM users WHERE user_id = {$pro['user_id']}";
                			$r = mysqli_query($dbc, $q);
                			confirm_query($r, $q);
                			$name_u = mysqli_fetch_array($r, MYSQLI_ASSOC);                			
                			echo $name_u['name'];
                		?>
                	</a>
            </label>
          </div>
          <div class="product_detail">
            <label>In category: 
            	<a href='#'>  
                		<?php 
                			$q = "SELECT product_type_name FROM products_type WHERE product_type_id = {$pro['product_type_id']}";
                			$r = mysqli_query($dbc, $q);
                			confirm_query($r, $q);
                			$name_type_pro = mysqli_fetch_array($r, MYSQLI_ASSOC);
                			echo $name_type_pro['product_type_name'];
                		?>
                </a>
            </label>
          </div>
          <div class="product_detail">
            <label>Date :
                	<a href='#'> 
                		<?php
                			echo $pro['date_post'];
                		?> 
                	</a></label>
          </div>
          <div class="product_detail">
            <label>Quantity Order:
            	<?php
                		$q2 = "SELECT SUM(quantity) AS quantity FROM order_items WHERE product_id = {$pro['product_id']}";
                            
                            $r2 = mysqli_query($dbc, $q2);
                            confirm_query($r2, $q2);
                            if (mysqli_num_rows($r2) == 1) {
                                $quan = mysqli_fetch_array($r2, MYSQLI_ASSOC);
                                if ($quan['quantity'] > 0) {
                                    echo $quan['quantity'];
                                }else {
                                    echo "0";
                                }
                            }else {
                                echo "<p class= 'error_warning'>ERORR</p>";
                           }
                ?>
            </label>
          </div>
          <div class="product_detail">
            <label>Position: <span><?php echo $pro['position']?></label>
          </div>
          <div class="product_detail">
            <label>Count Views: <span><?php echo $count_views;?></label>
          </div>
          <!--End wrapper-details--> 
          
        </div>
      </div>
      <div class="clear"></div>
      <?php 
	  	if(($pro['user_id'] == $_SESSION['uid']) || is_admin()){
			echo "<div class='success'><a href='edit_product.php?pro_id={$pro['product_id']}'>Change info this product. </a></div>";
		}else {
			echo "<div id='warring'>Don't have permission change this product.</div>";
		}	  
	  ?>
      <!--End warring-->
    </form>
  </div>
  
  <!-- end main product details -->
  <div class="clear"></div>
  <div class="container_outer">
    <div class="containter_middle">
      <div class="containter_top">
        <div class="containter_bottom">
          <h1>Additional Information</h1>
          <div class="clear"></div>
          <span>
            <?php
              echo "<p>".the_content($pro['product_description'])."</p>";
            ?>
          </span>
        </div>
      </div>
    </div>
  </div>
  <!-- end details tab --> 
</div>

<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>





