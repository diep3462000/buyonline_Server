<?php
	ob_start();
	session_start();
	define('BASE_URL', 'http://localhost/buy_online/ios/user/');
	define('LIVE', FALSE); // FALSE là trong quá trình phát triển , và TRUE là production.
	//Check result return ok or no ?
	function confirm_query($result, $query){
		global $dbc;
		if (!$result && !LIVE) {
			die("Query {$query} \n<br /> MySQL Error: ".mysqli_error($dbc));
		}
	}
	
	//Tạo function để báo lỗi riêng .
	function custom_error_handler($e_number, $e_message, $e_files, $e_line, $e_vars){
		//Tạo ra một câu báo lỗi riêng .
		$message = "<p class='warning_error'>Có lỗi xảy ra ở file {$e_files} tại dòng {$e_line} : {$e_message} \n";
		$e_message .= print_r($e_vars, 1);
		
		if(!LIVE){
			echo "<pre>".$message. "</pre> <br /> \n";
		}else {
			echo "Something is wrong";
		}
	}

	set_error_handler('custom_error_handler');
	
	// Phân trang 
	function pagination($display = 10,$q){
        global $dbc; global $start;
            // Nếu biến p không có, sẽ truy vấn CSDL để tìm xem có bao nhiêu page để hiển thị
            $r = mysqli_query($dbc, $q);
            confirm_query($r, $q);
            list($record) = mysqli_fetch_array($r, MYSQLI_NUM);
            
            // Tìm số trang bằng cách chia số dữ liệu cho số display
            if($record > $display) {
                $page = ceil($record/$display);
            } else {
                $page = 1;
            }
        
		return $page;
	} // END pagination
	
ob_flush();
?>