<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	$title = 'Danh sách loại sản phẩm'; 
	include('includes/header.php');
	include('includes/check_level_user.php');
 ?>

	    <div id="left-content">
            <?php 
            	if (isset($_SESSION['uid']) && isset($_GET['p']) && filter_var($_GET['ltp'],FILTER_VALIDATE_INT, array('min_range' => 1)) && filter_var($_GET['p'],FILTER_VALIDATE_INT, array('min_range' => 1))) {
					$ltp = $_GET['ltp'];
					$page_cu = $_GET['p'];
					//Phân trang
					$display = 5;
					$query = "SELECT COUNT(product_id) FROM products WHERE product_type_id = {$ltp}";
					$page = pagination($display,$query);
					$start = (isset($page_cu) && filter_var($page_cu, FILTER_VALIDATE_INT, array('min_range' => 1))) ? $page_cu : 0;
					$pages = ($start-1)*5;				
					
					$q = "SELECT product_id, product_name, LEFT(product_description, 400) AS product_description, product_image, product_price, product_type_id, user_id, position, DATE_FORMAT(date_post_product, '%b %d %Y') AS date_post ";
					$q .= " FROM products ";
					$q .= " WHERE product_type_id = {$ltp}";
					$q .= " ORDER BY date_post_product DESC LIMIT {$pages}, {$display}";
					$r = mysqli_query($dbc,$q);
					
					confirm_query($r, $q);
					 if (mysqli_num_rows($r) > 0) {
                    // Có product
                    while ($pro = mysqli_fetch_array($r , MYSQLI_ASSOC)) {                       
                       list($month, $day, $year) = explode(" ",$pro['date_post']);
		?>
					   <div class='product_content'>
                       <?php
					   	  if (isset($_SESSION['level_id']) && ($_SESSION['level_id']) == 1) {
                           echo "<a id='{$pro['product_id']}' class='delete_product'></a>";
                         }
						 ?>
                       <h3 class='curriculum-vitae'><?php echo $pro['product_name'];?></h3>
                       <div class='infomation'>
                       <div class='left-infomation'>
                       <div class='time'><img  src='images/clock.png' title='' alt='Timer'/></div>
                       <div class='date'>
                       <div class='year'><p><?php echo $year; ?></p></div><!--End year-->
                       <div class='clear'></div>
                       <div class='day'><p><?php echo $day; ?></p></div> <!--End day-->
                       <div class='month'><p><?php echo $month; ?></p></div> <!--End month-->
                       <div class='category'><p>Type Product:</p></div> <!--End category-->
                       <p class='uncate'>
                       <?php 
                            $q1 = "SELECT product_type_name, product_type_id FROM products_type WHERE product_type_id = {$pro['product_type_id']}";
                            $r1 = mysqli_query($dbc, $q1);
                            confirm_query($r1, $q1);
                            if (mysqli_num_rows($r1) == 1) {
                                $pro_type = mysqli_fetch_array($r1 ,MYSQLI_ASSOC);
                                echo "<a href='list_type_products?ltp=".$pro_type['product_type_id']."'>".$pro_type['product_type_name']."</a>";
                            }else {
                                echo "<p class= 'error_warning'>ERORR</p>";
                            }
                       echo "</p>";
                       echo "<div class='comment'><p>Count Orders :";
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
                       </p></div> <!--End comment-->
					   
                       </div> <!--End date-->
                       </div> <!--End left-infomation-->
                       <div class='right-infomation'>
                       <div class='representative'>
                       <div class='inner-representative'>
                       <img class= 'product_image' src='<?php echo BASE_URL."uploads/images/".$pro['product_image'];?>' title='<?php echo $pro['product_name'];?>' alt='<?php echo $pro['product_name'];?>'/>
                       </div> <!--End inner-representative-->
                       </div> <!--End representative-->
                       <div class='introduction'>
                       	<p><?php echo the_excerpt($pro['product_description']); ?></p>
                       </div> <!--End introduction-->
                       <div class='read-more'>
                       <div class='tag'>
                        <p>Tag : <span><?php 
						 	$tag_name = explode(" ", $pro['product_name']);
							shuffle($tag_name);
							foreach ($tag_name as $tag){
								echo "<a href='".BASE_URL."search_products.php?search=".$tag."' style='margin-right: 5px; margin-top: 0px; padding-left: 5px;'>".$tag.",</a>";
							}
						  ?></span></p>
                       </div> <!--End tag-->
                       <div class='read'>
                       <a href='detail_product.php?pro_id=<?php echo $pro['product_id']?>'><input  type='image' src='images/read_more.png' title='' alt='Read More'/></a>
                       </div> <!--End read-->
                       </div> <!--End read-more-->
                       </div> <!--End right-infomation-->
                       <div class='clear'></div>
                       </div> <!--End infomation-->
						</div>
                 <?php } 
					}else {
						// Không có product nào.
					}
				?>
           <?php     
                 }else {
            	// Xử lý khi ltp có id không hợp lệ 
            	echo "<p class='error_warning' style='margin-top:30px; margin-bottom:60px;'> The List Type Product you choose don't extractly . </p>";
            }
			?>
           
            <div id='paging' class='list_type'>
                       <ul>
						   <?php   
                             for ($i=1; $i <= $page ; $i++) {          
                                  echo "<li id='".$i."'><a href='".BASE_URL."list_type_products.php?ltp=".$ltp."&p=".$i."'><span>".$i."</span></a></li>";
                            }// END pagination
                            ?>
                        </ul>
                </div>                 
                <div id="last-div">
        			<?php include('includes/subcribe_friend.php');?>                    
            	</div>
</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>