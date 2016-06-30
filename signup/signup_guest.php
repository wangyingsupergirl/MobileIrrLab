<?php
require_once '../includes/mil_init.php';
require_once '../includes/utility.php'; //required constant.php in utility.php
session_start();
$member = false;
if(array_key_exists('MemberServed',$_SESSION)){
    $member = $_SESSION['MemberServed'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>State of Florida Mobile Irrigation Lab (MIL) Program</title>
  <link rel="stylesheet" type="text/css" href="../styles/milStylesheet.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js" type="text/javascript"></script>
  <script src="../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
  <meta name="robots" content="nofollow" />
 </head>
 <body id="login">
  <div id="Wrapper">
   <div id="header">
    <h1>State of Florida</h1>
   </div>
   <div id="contentWrap">
    <div id="mainIndex">
     <h2>MIL Guest Sign Up</h2>
     <div class="errMsg">
     <?php echo  (array_key_exists('err', $_GET)? $_GET['err']:"");  ?>
    </div>
     <form  id ="guest_signup_form" action="./control.php" method="post">
      <fieldset>
       <input type="hidden" name="role" value="<?php echo GUEST_ROLE ?>"/>
       <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr>
         <td>* First Name:</td>
         <td><input name="first_name" value="<?php echo ($member?$member->getProperty('first_name'):"")?>"  type="text" size="32" maxlength="32" /></td>
        </tr>

        <tr>
         <td>* Last Name:</td>
         <td><input name="last_name" value="<?php echo ($member?$member->getProperty('last_name'):"")?>"  type="text" size="32" maxlength="32" /></td>
        </tr>

        <tr>
         <td> Title:</td>
         <td><input name="title" value="<?php echo ($member?$member->getProperty('title'):"")?>"  type="text" size="32" maxlength="32" /></td>
        </tr>
         <tr>
         <td> Company:</td>
         <td><input name="company" value="<?php echo ($member?$member->getProperty('company'):"")?>"  type="text" size="32" maxlength="32" /></td>
        </tr>
        <tr>
         <td>* Phone:</td>
         <td><input name="phone" type="text" value="<?php echo ($member?$member->getProperty('phone'):"")?>"  size="20" maxlength="20" />(Digit Only eg:3532221111)</td>
        </tr>
        <tr>
         <td>* City:</td>
         <td><input name="busi_city" type="text" value="<?php echo ($member?$member->getProperty('busi_city'):"")?>"  size="20" maxlength="20" /></td>
        </tr>
        <tr>
         <td>* State:</td>
         <td><input name="busi_state" type="text" value="<?php echo ($member?$member->getProperty('busi_state'):"")?>"  size="20" maxlength="20" /></td>
        </tr>
        <tr>
         <td>* Zip:</td>
         <td><input name="busi_zip" type="text" value="<?php echo ($member?$member->getProperty('busi_zip'):"")?>"  size="5" maxlength="6" /></td>
        </tr>
       </table>
      </fieldset>

      <fieldset>
        <h4>*Interested in Which MIL Labs?</h4>
        <?php require_once 'form_component/mil_labs_checkbox.php';?>
      </fieldset>
      <fieldset>
       <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">

<?php require_once 'form_component/username_pwd.php'; ?>
       </table>
      </fieldset>
      <div class="form-btns" style="margin-top: 20px;">
       <input class="button" type="submit" id="submit_membership_application" name="submit_membership_application" value="Submit" />
       <input class="button" type="submit" name="back_login" value="Cancel" />
      </div>

     </form>
    </div>

    <span class="clearing"></span>

   </div>
   <div id="sponsorLogos">
    <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
   </div>

  </div>
<script>
    $('#submit_membership_application').click(function(){
           $("#guest_signup_form").validate({
                rules: {
                    first_name:"required",
                    last_name:"required",
                    busi_city: "required",
                    busi_state:"required",
                    password:"required",
                    busi_zip: {   
                        required:true,
                        digits:true,
                        minlength: 5,
                        maxlength: 5
                    },
                    phone:{
                        required:true,
                        digits:true,
                        minlength: 10,
                        maxlength: 10
                    },
                    username:{
                        required:true
                        ,email:true
                    },
                    re_password:{
                        required:true
                        ,equalTo: "#password"
                    }
                    
                }
            });
        })
          
      $('#password').change(
        function(){
            var password = $('#password').val();
             $.post(
            "control.php",
            { password: password,  is_valid_password:"" },
            function(msg) {
            $('#password_err').html(msg);
            //jump to top of browser page, display error/suc msg (#profile_notification)to users
          
            
            });
        }
      )
        $('#username').change(
        function(){
            var username = $('#username').val();
           $.post(
            "control.php",
            { username: username,  is_username_available:"" },
            function(msg) {
            $('#username_err').html(msg);
            //jump to top of browser page, display error/suc msg (#profile_notification)to users
          
            
            });
        }
      )
    </script>  
 </body>
</html>