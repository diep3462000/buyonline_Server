<?php 
	include('mysqli_connect.php');
	include('functions.php');
?>
<script type="text/javascript">
$(document).ready(function(){
	var Timer  = '';
	var selecter = 0;
	var Main =0;
	
	bring(selecter);
	
});

function bring ( selecter )
{	
	$('div.product_content:eq(' + selecter + ')').stop().animate({
		opacity  : '1.0',
		//height: '60px'
		
	},300,function(){
		
		if(selecter < 6)
		{
			clearTimeout(Timer); 
		}
	});
	
	selecter++;
	var Func = function(){ bring(selecter); };
	Timer = setTimeout(Func, 20);
}

</script>

		<?php
		  $display = 5; 
          $start = (isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['page'] : 0;
		  $page = ($start-1)*5;
          $q = "SELECT product_id, product_name, LEFT(product_description, 400) AS product_description, product_image, product_price, product_type_id, user_id, position, DATE_FORMAT(date_post_product, '%b %d %Y') AS date_post ";
          $q .= " FROM products ";
          $q .= " ORDER BY date_post_product DESC LIMIT {$page},{$display}";
          $r = mysqli_query($dbc,$q);
          confirm_query($r, $q);
		  //Hiển thì products
          include('display_products.php');
  		?>