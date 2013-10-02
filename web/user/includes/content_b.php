
 <div id="right-content">
            <div id="search">
                    <form action="search_products.php" style="margin:0" method="get">
                        <input class="search" style="float:none" type="text" placeholder="Search..."  alt="search" name="search" />
                        <input id="submit" type="hidden" />
    				</form>
			</div> <!--End search-->
                <div id="meta" class="more_margin_bottom more_margin_top">
                    <p class="left-p">USER: 
                        <?php 
                             echo $_SESSION['first_name'];
                        ?>
                    </p>
                    <ul class="custom-ul">                       
                        <?php
                            echo "<li><a href='".BASE_URL."logout.php'>Log Out</a></li>";
                        ?>
                        
                        
                        <?php 
							echo "
								<li><a href='".BASE_URL."edit_profile.php'>Edit Profile</a></li>
                        		<li><a href='".BASE_URL."change_password.php'>Change Password</a></li>
                        		<li><a href='#'>Faceebook</a></li>								
								";							
						?>
                        
                        <div class="clear"></div>
                    </ul>
                </div> <!--End meta-->

                <div id="cate" class="more_margin_bottom more_margin_top">
                	<p class="left-p">Products Type:</p>
                    <ul class="custom-ul">
                        <?php 
                            $q = "SELECT product_type_name, product_type_id, COUNT( * ) AS count ";
                            $q .= " FROM products_type ";
                            $q .= " LEFT JOIN products ";
                            $q .= " USING ( product_type_id ) ";
                            $q .= " GROUP BY product_type_id";
                            $r = mysqli_query($dbc, $q);
                            confirm_query($r,$q);
                            if (mysqli_num_rows($r) > 0) {
                                // Nếu có phân loại các sản phẩm (product_type) thì hiển thị ra .
                                while ($pro_type = mysqli_fetch_array($r,MYSQLI_ASSOC)) 
                                {
                                    echo "<li><a href='list_type_products.php?ltp=".$pro_type['product_type_id']."&p=1'>".$pro_type['product_type_name']."</a><span> (".$pro_type['count'].")</span></li>";
                                } // End WHILE .
                            }else {
                                echo "<li><a href='#'></a><span>No have type Product .</span></li>";
                            }
                        ?>
                        <div class="clear"></div>
                    </ul>
                </div> <!--End cate-->
                
                <div id="recent-posts" class="more_margin_bottom more_margin_top">
                	<p class="left-p">Recent Products:</p>
                	<ul class="custom-ul" id="vertical-ticker">
                        <?php
                            $q = "SELECT product_name,product_image, date_post_product ";
                            $q .= " FROM products ";
                            $q .= " ORDER BY date_post_product DESC";
                            $q .= " LIMIT 10 ";
                            $r = mysqli_query($dbc, $q);
                            confirm_query($r, $q);
                            if (mysqli_num_rows($r) > 0) {
                                // Lấy 5 sản phẩm post mới nhất .
                                while ($pro = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                    echo "<li><div class='contain_thum_image'><img class='thumb_image' src='".BASE_URL."uploads/images/".$pro['product_image']."' alt='".$pro['product_name']."' title='".$pro['product_name']."' /></div><div><a href='#'>".$pro['product_name']."</a><span>".$pro['date_post_product']."</span></div></li>";
                                }
                            }else {
                                echo "<li><a href='#'></a><span>No have recent Product .</span></li>";
                            }
                        ?>
                        <div class="clear"></div>
                    </ul>
                </div> <!--End recent-posts -->
                
                <div id="archives" class="more_margin_bottom more_margin_top">
                	<p class="left-p">Archives:</p>
                    <ul class="custom-ul">
                    	<li><a href="#">Januray 2013</a></li>
                        <li><a href="#">March 2013</a></li>
                        <li><a href="#">February 2013</a></li>
                        <li><a href="#">April 2013</a></li>
                        <li><a href="#">May 2013</a></li>
                        <div class="clear"></div>
                    </ul>
                </div> <!--End archives-->
                
            </div> <!--End right-content-->
