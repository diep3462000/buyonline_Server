<?php 
	if (is_admin()) {
		include('manager_by_admin.php');
	}else {
		include('logo_company.php');
	}
?>