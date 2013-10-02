<?php 
	include('includes/mysqli_connect.php');
	include('includes/functions.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Retrieve Password</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

<script type="text/javascript">

 $(document).ready(function() {
/*
* In-Field Label jQuery Plugin
* http://fuelyourcoding.com/scripts/infield.html
*
* Copyright (c) 2009 Doug Neiner
* Dual licensed under the MIT and GPL licenses.
* Uses the same license as jQuery, see:
* http://docs.jquery.com/License
*
* @version 0.1
*/
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='password']," + "input#" + for_attr + "[type='tel']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


    $("#RegisterUserForm label").inFieldLabels();
});

</script>

<link  type="text/css" rel="stylesheet" href="css/register.css"/>


<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $uid = FALSE; 
            $errors = array();
            // Bat dau xu ly form.
            if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $e = mysqli_real_escape_string($dbc, $_POST['email']);
                
                // Kiem tra trong CSDL de xem email co ton tai hay khong
                $q = "SELECT user_id FROM users WHERE email = '{$e}'";
                $r = mysqli_query($dbc, $q); confirm_query($r, $q);
                if(mysqli_num_rows($r) == 1) {
                    // Tim thay email trong CSDL
                    list($uid) = mysqli_fetch_array($r, MYSQLI_NUM);
                }
            } else {
                // Quen ko dien email
                $errors[] = "<p class='error' style='text-align:center;'>Please enter your email</p>";
            }
            
            if($uid) {
                // Neu co uid, thi chuan bi update lai mat khau cua nguoi dung
                $temp_pass = substr(md5(uniqid(rand(), true)), 3, 10);
                
                // Updat CSDL voi password tam thoi
                $q = "UPDATE users SET password = SHA1('$temp_pass') WHERE user_id = {$uid} LIMIT 1";
                $r = mysqli_query($dbc, $q); confirm_query($r, $q);
                if(mysqli_affected_rows($dbc) == 1) {
                    // Neu update thanh cong, thi email den nguoi dung password tam thoi
                    $body = "Your password has been temporarily changed to {$temp_pass}. Please use this email address and the new password. Make sure you change it later.";
                    mail($e, "Your temporary password", $body, "FROM: admin localhost");
                    $errors[] = "<p class='success' style='text-align:center;'>Your password has been changed successfully. You will receive an email with the new password.<p>";
                } else {
                    $errors[] = "<p class='error' style='text-align:center;'>Your password cannot be changed due to a system error.</p>";
                }
            } else {
                $errors[] = "<p class='error' style='text-align:center;'>The email could not be found in our database.<p>";
            }
        } // END main IF
?>

</head>

<body>

<div id="registration">
 <h2>Don't remember Password</h2>
<?php 
    if(isset($errors)) {
    foreach ($errors as $e) {
        echo $e;
    }
	}
?>
 <form id="RegisterUserForm" action="" method="post">
 	<fieldset>        
         <p>
            <label for="email">Your Email</label>
            <input id="email" name="email" type="email" class="text" value="<?php if (isset($_POST['email'])) {
                echo htmlentities($_POST['email']);
            } ?>" />
         </p>        
         <p class="submit">
            <button type="submit"></button>
            <a class="go_login" href="login_page.php"></a>
         </p>
 	</fieldset>

 </form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

<script type="text/javascript">

        $(document).ready(function() {
/*
* In-Field Label jQuery Plugin
* http://fuelyourcoding.com/scripts/infield.html
*
* Copyright (c) 2009 Doug Neiner
* Dual licensed under the MIT and GPL licenses.
* Uses the same license as jQuery, see:
* http://docs.jquery.com/License
*
* @version 0.1
*/
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='password']," + "input#" + for_attr + "[type='tel']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


        							$("#RegisterUserForm label").inFieldLabels();
								   });

</script>

</body>
</html>
