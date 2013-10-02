// JavaScript Document
//Chỉ cho nhập số và backspace , không cho nhập text
	function validate(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		var keys = [8,48,49,50,51,52,53,54,55,56,57]; // 0->
		if( keys.indexOf(key) == -1 ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		}
	};

//Hiển thị datePicker
	 $(function() {
		$( ".datepicker" ).datepicker();
		// getter
		var dateFormat = $( ".datepicker" ).datepicker( "option", "dateFormat" );
		// setter
		$( ".datepicker" ).datepicker( "option", "dateFormat", "yyyy-mm-dd" );
	});

<!--Hiệu ứng phân trang-->
$(document).ready(function(){
	
    function showLoader(){    
        $('.search-background').fadeIn(200);
    }
    
    function hideLoader(){    
        $('.search-background').fadeOut(200);
    };
    
    $(".main_nav li").click(function(){
		$(".main_nav li").removeClass('active');
		$(this).addClass('active');
        showLoader();
        $("#content_show").load("includes/pagination.php?page=" + this.id, function(){
			hideLoader();			
			$("img.product_image").lazyload();			
			});
		scrollToTop();
        return false;
    });	
	
	showLoader();
    $("#content_show").load("includes/pagination.php?page=1", function(data) {
		alert(data);
		hideLoader();		
		$("img.product_image").lazyload();
		});
    	
	$(".list_type li").click(function(){
		$(".list_type li").removeClass('active');
		$(this).addClass('active');
	});

	<!--Random recent post-->
		$(function(){
			$('#vertical-ticker').totemticker({
				row_height	:	'50px',
				next        :   null,
				previous    :   null,
				stop        :   null,
				start       :   null,
				speed       :   200,
				interval    :   4000,
				max_items   :   null,
				mousestop   :   false,
				direction	:	'down'
			});
		});
		
		// Hiệu ứng cuộn lên đầu trang 
		function scrollToTop(){
			$('body,html').animate({scrollTop:0},800);	
		}
		
		//Check box khuyến mãi.
		$cb = $('#cb-sale');
		$parent_cb = $cb.parent();
		var flag = true;
        $cb.click(function(){
			if(flag == true){
				//$(this).stop();
				$('#div_sale').slideDown();
				$('#div_sale').css("display","inline-block");				
				flag = false;
			}else {
				//$(this).stop();
				$parent_cb.parent().find('div#div_sale').slideUp();				
				flag= true;
			}
		});		
		$('#count_rate').on("input", function(){
			var elem = document.getElementById("count_rate_percent");
			var count_rate = parseInt(document.getElementById("count_rate").value);
			var count_price = parseInt(document.getElementById("product_price").value);
			var set_rate = count_rate*100/count_price;
			elem.value = set_rate.toPrecision(4) + "% of cost";								
		});				
});
