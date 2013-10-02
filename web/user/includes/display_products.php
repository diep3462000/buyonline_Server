<?php 
					 if (mysqli_num_rows($r) > 0) {
                    // Có product
                    while ($pro = mysqli_fetch_array($r , MYSQLI_ASSOC)) {                       
                       list($month, $day, $year) = explode(" ",$pro['date_post']);
				?>
					   <div class='product_content'>
                       <?php
					   	  if (is_admin()) {
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
                                            echo "<a href='list_type_products.php?ltp=".$pro_type['product_type_id']."'>".$pro_type['product_type_name']."</a>";
                                        }else {
                                            echo "<p class= 'error_warning'>ERORR</p>";
                                        }
									?>
                                   </p>
                                   <div class='comment'><p>Count Orders :
                                   <?
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
                               </p></div> <!--End uncate-->
                           
                           </div> <!--End date-->
                           <div class="author">
                                <?php 
                                    $query = "SELECT first_name FROM users WHERE user_id = {$pro['user_id']} LIMIT 1";
                                    $result = mysqli_query($dbc, $query);
                                    confirm_query($result, $query);
                                    list($first_name) = mysqli_fetch_array($result, MYSQLI_NUM);
                                    
                                    
                                ?>
                                <p>Post By :</p> 
                                <a href='<?php echo BASE_URL."author_products.php?aut=".$pro['user_id'].""?>'>
                                    <?php echo $first_name?>
                                </a>
                           </div>
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
                                <p><span>Tag : </span>
									<?php 
                                        $tag_name = explode(" ", $pro['product_name']);
                                        shuffle($tag_name);
                                        foreach ($tag_name as $tag){
                                            echo "<a href='".BASE_URL."search_products.php?search=".$tag."' style='margin-right: 5px; margin-top: 0px; padding-left: 5px;'>".$tag.",</a>";
                                        }
                                     ?>
                                  </p>
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
						echo "<p class='error_warning' style='margin-top:30px; margin-bottom:60px;'> Don't have any product you want to search. </p>";						
					}  					                 
?> 
<script type="text/javascript">
	$(document).ready(function(e) {		
			//Delete product
			$('.delete_product').click(function(){
				if (confirm("Are you sure?")) {
					var container = $(this).parent();
				var pro = $(this).attr('id');
				var string = 'pro_id='+ pro;
				$.ajax({
					type: "POST",
					url: "delete_product_ajax.php",
					data: string,
					success: function() {
						container.slideUp('slow', function() {container.remove();});
					}
				});
				}
				return false;				
			});			
    });
</script>