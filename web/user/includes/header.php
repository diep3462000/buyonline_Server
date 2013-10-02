<?php 
ini_set('session.use_only_cookies', true);
if(!isset($_SESSION)){
    session_start();
}
if (!isset($_SESSION['time']) || $_SESSION['time'] < (time()-30)) {
    session_regenerate_id();
    $_SESSION['time'] = time();
}
if(!isset($_SESSION['uid'])){
	redirect_to('login_page.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buy Online - <?php echo(isset($title)) ? $title : 'My Home Page';?></title>
<link type="text/css" rel="stylesheet" href="css/reset.css"/>
<link  type="text/css" rel="stylesheet" href="css/mainstyle.css"/>
<link type="text/css" rel="stylesheet" href="css/form/animate.css"/>
<link type="text/css" rel="stylesheet" href="css/form/styles.css"/>
<link  type="text/css" rel="stylesheet" href="css/details_product.css"/>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript" src="js/check_ajax.js"></script>
<script language="javascript" src="js/lazy_load.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript"  src="js/functions_ajax.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.totemticker.min.js"></script>


</head>
<body>
	<div id="wrapper">
    	<div id="wrap-header">
        	<div id="header">
                <div id="logo">
                	<a href="#"><img  src="images/logo.png" alt="Buy Online" title="Buy Online"/></a>
                </div> <!--End logo-->
                <div id="add_section">
                	<a href="#"><img  src="images/low_icon.png" title="Lowest Price" alt="Lowest Price"/></a>
                    <a href="#"><img  src="images/100_icon.png" title="Money Back 100%" alt="Money Back 100%"/></a>
                    <a href="#"><img src="images/contact_no.png" title="Phone Number" alt="Phone Number" /></a>
                </div> <!--End add_section-->
                <div class="clear"></div>
                <div id="menu">
                	<ul>
                        <?php 
                        // Xác định cat_id để tô đậm link
                        if (isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
                            $cid  = $_GET['cid'];
                        }else{
                            $cid = NULL;
                        }
						// Truy xuất categories
                        	$q = "SELECT cat_name,cat_id,link FROM categories ORDER BY position ASC";
                            $r = mysqli_query($dbc, $q);
							confirm_query($r,$q);
                            while ($cats = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                                echo "<li><a href='".BASE_URL."{$cats['link']}'";
                                    if ($cats['cat_id'] == $cid) {
                                        echo "class='active'";
                                    }

                                echo ">".$cats['cat_name']."</a></li>";
                            } //End WHILE cats
                        ?>                        
                    </ul>
                </div> <!--End menu-->
                <p class="blog">Blog</p>
                <div class="clear"></div>
                <div id="breadcrumb">
                	<span>Bring every thing to you by ONLY a call !</span>
                </div> <!--End breadcrumb -->
            </div> <!--End header-->
</div> <!--End wrap-header-->
        <div id="content">