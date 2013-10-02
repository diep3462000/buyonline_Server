<div id="left-content">
        	<div class="search-background">
                  <label><img src="images/loader.gif" alt="Loading..." title="Loading..." /></label>
            </div>
              <?php 
			  	$display = 5;
				$q = "SELECT COUNT(product_id) FROM products";
				$page = pagination($display,$q);
				$start = (isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['page'] : 0;
			  ?>
              <div id="content_show">
              </div> 
            	<div id='paging' class='main_nav'>
                       <ul>
						   <?php
                            if ($page > 1) {
                              $current_page = ($start/$display) + 1;
                              // Nếu không phải ở trang đầu thì sẽ hiển thị trang trước .
                              if ($current_page != 1) {
                                echo "<li><a href='index.php?page=".($start - $display)."'><span>Prev</span></a></li>";
                              }
    
                              // Hiển thì những phần số còn lại của trang
                              for ($i=1; $i <= $page ; $i++) { 
                                if ($i != $current_page) {
                                 // echo "<li><a href='index.php?s=".{$display * ($i - 1}."&p={$page}'>{$i}</a></li>";
            
                                 echo "<li id='".$i."'><a><span>".$i."</span></a></li>";
                                }else {
                                  echo "<li class='active' id='".$i."'><a><span>".$i."</span></a></li>";
                                }
                                
                              }// END FOR LOOP
    
                                // Nếu không phải trang cuối thì hiển thị trang kế tiếp .
                                if ($current_page != $page) {
                                  echo "<li><a href='pagination.php?s=".($start - $display)."&p={$page}'><span>Next</span></a></li>";
                                }
                            }// END pagination
                            ?>
                        </ul>
                </div>                 
                <div id="last-div">
        			<?php include('subcribe_friend.php');?>                    
            	</div>
</div> <!--End left-content-->