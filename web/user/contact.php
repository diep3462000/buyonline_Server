<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
	include('includes/header.php');
	include('includes/check_level_user.php');
?>
    <?php 	
		// Xử lý form contact 	
    	if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri ton tai , xu ly form		
			$errors = array();	
			$clean = array_map('clean_email', $_POST);
            if (empty($clean['name'])) {
                $errors[] = 'name';
            }

            //Check email
            if (!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $clean['email'])) {
                $errors[] = 'email';
            }

            if (empty($clean['profile'])) {
                $errors[] = 'profile';
            }

            //Check answer question random
            if (isset($_POST['captcha']) && trim($_POST['captcha']) != $_SESSION['q']['answer']) {
                $errors[] = "wrong";
            }

            //Check spam bot with field FILL

            if (!empty($_POST['url'])) {
                redirect_to('thankyou.html');
            }

             //Kiểm tra có lỗi ở form ko ? ko thì gửi mail 
                if (empty($errors)) {
                    $body = "Name: {$clean['name']} \n\n Content: \n ". strip_tags($clean['profile']);
        
                    //70 chữ 1 dòng
                    $body = wordwrap($body, 70);
                    if(mail('nguyen_huy_hung_89@yahoo.com', 'Contact form submission', $body, 'FROM: localhost@localhost'))
                    {
                        $messages =  "<p class='success' style='color:green; font-weight:bold;'>Thank you for contacting me.</p>";
                        $_POST = array();
                    }else {
                        $messages = "<p class='error_warning' style='color :#CC0000; font-weight: bold;'>Sorry, your email coult not be sent.</p.";
                    }        
                }else {
                    $messages = "<p class='error_warning' style='color :#CC0000; font-weight:bold;'>Please full out all the required fields. </p>";
                }
		} // End main submit
    ?>
    <div id="left-content">
    	<!--BEGIN #signup-form -->
    <div id="signup-form">        
        <!--BEGIN #subscribe-inner -->
        <div id="signup-inner">
			<p class="contact">Contact Form</p>
            <?php 
                if (!empty($messages)) {
                    echo $messages;
                }
            ?>
            <form id="contact" action="" method="post">            	
                <p>
                    <label for="name">Your Name: <span class="required">*</span></label>
                    <input id="name" type="text" name="name" value="<?php if (isset($_POST['name'])) {
                        echo htmlentities($_POST['name'], ENT_COMPAT,'UTF-8');
                    }?>" />
                    <?php if (isset($errors) && in_array('name', $errors)) {
                            echo "<span class='warning' style='float:none;'>Please enter your name.</span>";
                    }?>
                </p>
                
                <p>
                    <label for="email">Email:  <span class="required">*</span></label>
                    <input id="email" type="text" name="email" value="<?php if (isset($_POST['email'])) {
                        echo htmlentities($_POST['email'], ENT_COMPAT,'UTF-8');
                    }?>" />
                    <?php if (isset($errors) && in_array('email', $errors)) {
                            echo "<span class='warning' style='float:none;'>Please enter your Email.</span>";
                    }?>
                </p>
                
                <p>
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="<?php if (isset($_POST['phone'])) {
                        echo htmlentities($_POST['phone'], ENT_COMPAT,'UTF-8');
                    }?>" />
                </p>
                
                
                <p>
                    <label for="profile">Tell us about yourself:  <span class="required">*</span></label>
                    <textarea name="profile" id="profile" cols="30" rows="10"><?php if (isset($_POST['profile'])) {
                        echo htmlentities($_POST['profile'], ENT_COMPAT,'UTF-8');
                    }?></textarea>
                    <?php if (isset($errors) && in_array('profile', $errors)) {
                            echo "<span class='warning' style='float:none;'>Please enter your content.</span>";
                    }?>
                </p>

                <p>
                    <label for="captcha">Điền vào giá trị số câu hỏi sau : <?php echo captcha();?> <span class="required">*</span></label>
                    <input type="text" name="captcha" id="captcha" class="margin_input" value="" size="20" maxlength="80" tabindex="1"/>
                    <?php
                            if (isset($errors) && in_array('wrong', $errors)) {
                               echo "<p class='warning'>Wrong answer !!!</p>";
                            }
                    ?> 
                </p>

                <p class='hidden_field'>
                    <label for="url">Please DON'T fill this field !!!!<span class="required"></span></label>
                    <input type="text" name="url" id="url" class="margin_input" value="" size="20" maxlength="20" tabindex="1"/>
                </p>

                <p>
                	<button id="submit" type="submit">Submit</button>
                </p>
                
            </form>
            
		<div id="required">
		<p>* Required Fields</p>
		</div>
        
        <!--END #signup-inner -->
        </div>
        
    <!--END #signup-form -->   
    </div>
	</div> <!--End left-content-->
<?php include('includes/content_b.php');?>
<?php include('includes/footer.php');?>