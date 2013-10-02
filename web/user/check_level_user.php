<?php 
	if(isset($_SESSION['level_id']))
	{
		if($_SESSION['level_id'] == 1)
		{
			 include('includes/manager_by_admin.php');
		} else if ($_SESSION['level_id'] == 2)
		{
			include('includes/logo_company.php');
		} else {
			include('includes/logo_company.php');
		}
	} else {
		redirect_to('/user/login_page.php');
	}
?>