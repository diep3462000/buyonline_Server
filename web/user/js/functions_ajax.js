// JavaScript Document
$(document).ready(function() {
	
	//Subcribe friend
	$("#subcribe_friend").click(function(){
			var email = $('#email').val();
			var send_email = 'email=' + email;
			$.ajax({
				type: "POST",
				url: "subcribe_friend_ajax.php",
				data: send_email,
				success: function(response) {
					if(response == "OK"){
						$('#check_email').html('<span class="success">Email was sent successfully. </span>');
					} else if (response == "NO"){
						$('#check_email').html('<span class="error">Email is NOT available .</span>');
					}
				}			
			});	
	});
});