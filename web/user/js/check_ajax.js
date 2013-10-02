// JavaScript Document
$(document).ready(function(e) {
    $('#email').change(function() {
		var email = $(this).val();		
		//Khi mà gõ vào hơn 8 ký tự thì mới check . 
		if(email.length > 8) {
			//Khi mà quá nhiều data thì hiển thị dòng này , ko thì làm cái dưới .
			$('#available').html('<span class="check">Checking availability ...</span>');
			
			//type : Get m, ở đây cần lấy data trong database nên dùng get
			//url đây là đường dẫn , nơi xử lý ajax, Chú ý là khi đặt cái này thì ngang hàng với trang resgiter , vì check ngang nhau .
			// Data đây là dữ liệu để gửi lên , cụ thể mình cần gửi lên email để check trong database
			//Check xem thành công không ?
			$.ajax({				
				type: "get",
				url: "check_email.php",				
				data: "email="+ email,				
				success: function(response) {
					if(response == "YES"){
						$('#available').html('<span class="success">Email is available .</span>');
					} else if (response == "NO"){
						$('#available').html('<span class="error">Email is NOT available .</span>');
					}
				}
			});
		}else {
			$('#available').html('<span class="short">Email is too short.</span>');
		}
	});
	
	
	 $('#category').change(function() {
		var category = $(this).val();		
		//Khi mà gõ vào hơn 8 ký tự thì mới check . 
		if(category.length > 3) {
			//Khi mà quá nhiều data thì hiển thị dòng này , ko thì làm cái dưới .
			$('#available').html('<span class="check">Checking availability ...</span>');
			$.ajax({				
				type: "get",
				url: "check_category.php",				
				data: "category="+category,				
				success: function(response) {
					if(response == "YES"){
						$('#available').html('<span class="success">Category is available .</span>');
					} else if (response == "NO"){
						$('#available').html('<span class="error">Category is NOT available .</span>');
					}
				}
			});
		}else {
			$('#available').html('<span class="short">Category is too short.</span>');
		}
	});

});