<?php
	ob_start();
	session_start();
	define('BASE_URL', 'http://localhost/buy_online/web/user/');
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
	
	//Check người dùng đã đăng nhập chưa ? 
	function is_logged_in(){
		if (!isset($_SESSION['uid'])) {
            redirect_to('login_page.php');
    	}
	} // END logged_in
	
   // Tai dinh huong nguoi dung ve trang mac dinh la index
    function redirect_to($page = 'index.php') {
        $url = BASE_URL . $page;
        header("Location: $url");
        exit();
    }
	
	//Thông báo lỗi ra ngoài
	function report_error($msg)
	{
		if(!empty($msg)){
			foreach ($msg as $m){
				echo "ERROR :".$m. " Check again";
			}
		}
	}

	// Cắt chuỗi để hiển thị thành đoạn văn bản ngắn .
	function the_excerpt($text){
		$sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
		if (strlen($text) > 400) {
			$cutString = substr($sanitized, 0, 400);
			$words = substr($sanitized, 0, strrpos($cutString, ' '));
			return $words;
		}else {
			return $sanitized;
		}
	}

	//Tao paragraph từ CSDL
	function the_content($text){
		$sanitized = htmlentities($text, ENT_COMPAT, 'UTF-8');
		return str_replace(array("\r\n", "\n"), array("<p>", "</p>"), $sanitized);
	}

	// Kiểm tra có phải là admin hay không ?
	function is_admin(){
		return (isset($_SESSION['level_id']) && ($_SESSION['level_id'] == 1));
	}

	//Kiểm tra người dùng có thể vào trang admin hay không ?
	function admin_access(){
		if(!is_admin()) {
            redirect_to();
        }
	}

	//Validate ID
	function validate_id($id) {
		if (isset($id) && filter_var($id, FILTER_VALIDATE_INT, array('min_range' => 1))){
			$val_id = $id;
			return $val_id;
		}else {
			return NULL;
		}		
	}


	//Tạo captcha
	function captcha(){
		$qna = array(
			1 => array('question' => 'Mot cong mot', 'answer' => 2),
			2 => array('question' => 'Mot cong hai', 'answer' => 3),
			3 => array('question' => 'Mot cong ba', 'answer' => 4),
			4 => array('question' => 'Mot nhan nam', 'answer' => 5),
			5 => array('question' => 'Nang bach tuyet va ... chu lun', 'answer' => 7),
			6 => array('question' => 'Alibaba va ... ten cuop', 'answer' => 40),
			7 => array('question' => 'An mot qua khe , tra ... cuc vang', 'answer' => 1),
			8 => array('question' => 'Nam nhan nam', 'answer' => 25),
			9 => array('question' => 'Con voi co may chan ?', 'answer' => 4),
			10 => array('question' => '20 con ga thi co tong cong bao nhieu chan ?', 'answer' => 40),
			11 => array('question' => 'Bac Ho mat nam bao nhieu ?', 'answer' => 1890),
			12 => array('question' => 'Viet Nam co tong cong bao nhieu tinh thanh', 'answer' => 64)
			);
		$rand_key = array_rand($qna); // Lấy ngẫu nhiên một trong các array trên .
		$_SESSION['q'] = $qna[$rand_key];
	
		
		return $question = $qna[$rand_key]['question'];
		
		
		
	} // End function captcha
	
	// Phân trang 
	function pagination($display = 5,$q){
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


	function clean_email($value){
		$suspects = array('to:', 'bcc:', 'cc:', 'content-type:', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:');
		foreach ($suspects as $s) {
			if(strpos($value, $s) !== FALSE){
				return '';
			}
			// Trả về giá trị cho dấu xuống hàng 
			$value = str_replace(array('\n', '\r', '%0a', '%0d') , '', $value);
			return trim($value); 
		}
	}

	function view_counter($pro_id){
		$ip = $_SERVER['REMOTE_ADDR'];
		global $dbc;

		//Truy vấn csdl để xem product
		$q = "SELECT num_views, user_ip FROM count_views WHERE product_id = {$pro_id}";
		$r = mysqli_query($dbc, $q);
		confirm_query($r, $q);

		if (mysqli_num_rows($r) > 0) {
			// Nếu có kết quả trả về , có nghĩa là đã tồn tại trong table , update num_views
			list($num_views, $db_ip) = mysqli_fetch_array($r, MYSQLI_NUM);
			if ($db_ip != $ip) {				
				$q = "UPDATE count_views SET num_views = (num_views + 1) WHERE product_id = {$pro_id} LIMIT 1";
				$r = mysqli_query($dbc, $q);
				confirm_query($r, $q);				
			}			
		}else {
			// Nếu ko có kết quả trả về thì chèb vào .
			$q = "INSERT INTO count_views (product_id, num_views, user_ip) VALUES ({$pro_id},1, '{$ip}')";
			$r = mysqli_query($dbc, $q);
			confirm_query($r, $q);
			$num_views = 1;
		}
		return $num_views;
	}
	
	//Truy xuất dữ liệu của user
	function fetch_user($user_id){
		global $dbc;
		$q = "SELECT * FROM users WHERE user_id = {$user_id} LIMIT 1";
		$r = mysqli_query($dbc, $q);
		confirm_query($r, $q);

		if (mysqli_num_rows($r) >0) {
			// Nếu có kết quả trả về 
			return $result_set = mysqli_fetch_array($r, MYSQLI_ASSOC);
		}else {
			return FALSE;
		}
	}

	//Truy xuất dữ liệu của nhiều user
	function fetch_users($order){
		global $dbc;
		$q = "SELECT u.user_id, u.email, u.password, u.website, u.yahoo, u.bio, u.sex, u.mobile_phone, u.home_phone, u.avatar, u.level_id, u.active, DATE_FORMAT(u.registration_date, '%b %d %Y') AS date, CONCAT_WS(' ', first_name, last_name) AS name ";
		$q .= " FROM users AS u ";
		$q .= " ORDER BY {$order} ASC";
		$r = mysqli_query($dbc, $q);
		confirm_query($r, $q);
		if (mysqli_num_rows($r) >0) {
			//Nếu có kết quả trả về . 
			// Tạo ra 1 array trả về kết quả , vì ở đây trả về nhiều kết quả .
			$users = array();
			while ($results = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				$users[] = $results;
			}
			return $users;
		}else {
			//Nếu website mới chạy và ko có người dùng nào .
			return FALSE;
		}
	}

	function sort_table_users($order){
		// Sắp xếp theo thứ tự của table head
		switch ($order) {
		                    case 'id':
		                       $order_by = 'user_id';
		                        break;
		                    case 'name':
		                        $order_by = 'name';
		                        break;
		                    case 'email':
		                        $order_by = 'email';
		                        break;
		                    case 'sex':
		                        $order_by = 'sex';
		                        break;
		                    case 'reg':
		                        $order_by = 'registration_date';
		                        break;		                    
		                    default:
		                        $order_by = 'user_id';
		                        break;
		                } // END switch
		        return $order_by;

	}
	
	//Kiểm tra trang hiện tại 
	function current_page($page){
		if(basename($_SERVER['SCRIPT_NAME']) == $page){
			echo "class='here'";
		}	
	}
ob_flush();
?>