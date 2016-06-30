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
        <script src="../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
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
                    <h2>MIL Employee Sign Up</h2>
                     <div class="errMsg">
						<?php echo  (array_key_exists('err', $_GET)? $_GET['err']:"");  ?>
					</div>
                     <form  id ="employee_signup_form" action="./control.php" method="post">
                        <fieldset>
                            <input type="hidden" name="role" value="<?php echo EMPLOYEE_ROLE?>"/>
                            <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                                <?php require_once 'form_component/mem_basic_info.php'; ?>
                            </table>
                        </fieldset>

                        <fieldset>
                            <h4>*Working in MIL Labs?</h4>
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
            $.validator.messages.required = "{1} is required.";
            $.validator.messages.number = "{1} must be a number.";
            $.validator.messages.min = "The field should be greater or equal to {0}.";
            $.validator.messages.max = "The field should be less or equal to {0}.";
            $.validator.messages.equalTo = "The Re-enter password doesn't match password";
           var names = {
                first_name:'First Name'
                ,last_name:'Last Name'
                ,title:'Title'
                ,phone:'Phone'
                ,fiscal_standard:'Fiscal Standard'
                ,username: 'User Name'
                ,password: 'Password'
                ,re_password:'Re-enter password'
             };
            $("#employee_signup_form").validate({
                rules: {
                    first_name:{
                        required:[true,names.first_name]
                    },
                    last_name:{
                        required:[true,names.last_name]
                    },
                    title:{
                        required:[true,names.title]
                    },
                    phone:{
                        required:[true,names.phone],
                        digits:true,
                        minlength: 10,
                        maxlength: 10
                    },
                    fiscal_standard:{
                        required:[true,names.fiscal_standard]
                    },
                    username:{
                        required:[true,names.username]
                        ,email:[true,names.username]
                    },
                    password:{
                        required:[true, names.password]
                    },
                    re_password:{
                        required:[true, names.re_password]
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