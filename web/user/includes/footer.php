        </div> <!--End content-->
        <div class="clear"></div>
        <div id="footer">
        	<div id="inner-footer">
            	<div id="top-inner-footer">
                	<div id="div1">
                    	<h3>Links</h3>
                        <ul>
                        	<li><a href="https://www.facebook.com/FramgiaVietnam?fref=ts">Framgia Facebook</a></li>
                        	<li><a href="http://tech.blog.framgia.com/vn/">Techogony Framgia</a></li>
                            <li><a href="https://www.facebook.com/pages/Framgia-Vietnam-Confessions/218413071636350?fref=ts">Framgia Vietnam Confessions</a></li>
                            <li><a href="http://framgia.com/vn/company/vn/index.html">Company Framgia</a></li>
                            <li><a href="http://www.vanhocmang.vn/">Văn học mạng</a></li>
                        </ul>
                    </div> <!--End div1-->
                    <div id="div2">
                    	<h3>Write for us</h3>
                        <p>We create free premium graphic, design and web resources. We thrive to bring you the best of the best in each of our beautifully crafted resources. Share the love around, enjoy it at will, and be sure to give us your feedback to make pixeden.</p>
                        <a href="contact.php"><img alt="Contact Us" title="Contact Us" src="images/contact.png"></a>
                    </div> <!--End div2-->
                    <div id="div3">
                    	<h3>Social Media</h3>
                        <ul>
                        	<li><a style="color:#ff6600; background:url(images/icon_wifi_color1.png) no-repeat left center" href="#">RSS Feed</a></li>
                            <li><a style="color:#33bef0; background:url(images/icon_twiiter_color1.png) no-repeat left center" href="https://twitter.com/sanojimaru/statuses/335303599422857216">Twitter</a></li>
                            <li><a style="color:#5574ae; background:url(images/icon_facebook_color1.png) no-repeat left center" href="https://www.facebook.com/FramgiaVietnam?fref=ts">Facebook</a></li>
                        </ul>
                        <div class="clear">
                        </div> <!--End clear-->
                        <p>Aiming to become the IT corporation with the highest techniques in Asia.We believe that this is the biggest disadvantage of offshore development
However, Framgia belives that all these disadvantages can be reduced by carrying out the right technology for offshore development.</p>
                    </div> <!--End div3-->
                </div> <!--End top-inner-footer-->
                <div id="bottom-inner-footer">
                	<ul class="navigation">
                         <?php 
                        // Xác định cat_id để tô đậm link
                        if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
                            $cid  = $_GET['cid'];
                        }else{
                            $cid = NULL;
                        }
						// Truy xuất categories
                        	$q = "SELECT cat_name,cat_id, link FROM categories ORDER BY position ASC";
                            $r = mysqli_query($dbc, $q);
							confirm_query($r,$q);
                            while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                echo "<li><a href='".BASE_URL."{$cats['link']}'";
                                    if ($cats['cat_id'] == $cid) {
                                        echo "class='active-menu-bottom'";
                                    }
                                echo ">".$cats['cat_name']."</a></li>";
                            } //End WHILE cats
                        ?>                        
                    </ul> <!--End ul.navigation-->
                    <span>Copyright &copy; 2013-2023. Framgia Company All rights reserved</span>
                </div> <!--End bottom-inner-footer-->
            </div> <!--End inner-footer-->
        </div>
    </div> <!--End wrapper-->
</body>
<script language="javascript" type="text/javascript" src="js/js.js"></script>
</html>
