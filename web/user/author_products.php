<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	$title = 'Danh sách loại sản phẩm'; 
	include('includes/header.php');
	include('includes/check_level_user.php');
?>

	    <div id="left-content">
            <?php			  
            	if (isset($_GET['aut']) && filter_var($_GET['aut'],FILTER_SANITIZE_STRING)) {
					$aut = mysqli_real_escape_string($dbc, strip_tags($_GET['aut']));
					if(isset($_GET['p'])){
						$page_cu = $_GET['p'];						
					}else {
						$start = 0;
					}						
					$start = (isset($page_cu) && filter_var($page_cu, FILTER_VALIDATE_INT, array('min_range' => 1))) ? $page_cu : 1;
					$pages = ($start-1)*5;				
					
					$display = 5;
					$query = "SELECT COUNT(product_id) FROM products WHERE user_id = {$aut}";
					$page = pagination($display,$query);	
					
					$q = "SELECT product_id, product_name, LEFT(product_description, 400) AS product_description, product_image, product_price, product_type_id, user_id, position, DATE_FORMAT(date_post_product, '%b %d %Y') AS date_post ";
					$q .= " FROM products ";
					$q .= " WHERE user_id = {$aut} ";
					$q .= " ORDER BY date_post_product DESC LIMIT {$pages}, {$display}";
					$r = mysqli_query($dbc,$q);
					
					confirm_query($r, $q);
					
					//Hiển thì products
					include('includes/display_products.php'); 
				}else {
            	// Xử lý khi ltp có id không hợp lệ 
            	echo "<p class='error_warning' style='margin-top:30px; margin-bottom:60px;'> The List Type Product you choose don't extractly . </p>";
            	} 
				?>
                        
            <div id='paging' class='list_type'>
                       <ul>
                            <?php 
								if($page > 1) {
									if(isset($_GET['p'])){
										$page_cu = $_GET['p']; 
									}else {
										$page_cu = 1 ;
									}									
									// Nếu không phải ở trang đầu (hoặc 1) thì sẽ hiển thị Trang trước.
									if($page_cu != 1) {
										echo "<li><a href='author_products.php?aut=".$_GET['aut']."&p=".($page_cu-1)."'><span>Prev</span></a></li>";
									}
									
									
									 for ($i=1; $i <= $page ; $i++) { 
										  if($i == $page_cu){
											 echo "<li class='active' id='".$i."'><a href='".BASE_URL."author_products.php?aut=".$_GET['aut']."&p=".$i."'><span>".$i."</span></a></li>";
										  }else {
											echo "<li id='".$i."'><a href='".BASE_URL."author_products.php?aut=".$_GET['aut']."&p=".$i."'><span>".$i."</span></a></li>";
										  }
									}
									
									// Nếu không phải trang cuối, thì hiển thị trang kế.
									if($page_cu != $page) {
										echo  "<li><a href='author_products.php?aut=".$_GET['aut']."&p=".($page_cu +1)."'><span>Next</span></a></li>";
									}
								} // END pagination section
							
							?>
                        </ul>
                </div>                 
                <div id="last-div">
        			<?php include('includes/subcribe_friend.php');?>                    
            	</div>
</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>