<?php
require_once dirname(__FILE__) . '/../../includes/mil_init.php';
require_once dirname(__FILE__) . '/../../includes/utility.php'; //required constant.php in utility.php
session_start();
$member = false;
if (array_key_exists('memberReviewed', $_SESSION)) {
 $member = $_SESSION['memberReviewed'];
} else {
 echo 'Error no memberReviewed para in session';
 exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign Up</title>
  <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
  <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
  <script src="../../js/multiple_choices_list.js" type="text/javascript"></script>
 </head>

 <body id='login'>
  <div id="largeWrapper">
   <div id="header">
    <h1>State of Florida</h1>
    <p>Mobile Irrigation Laboratory Program</p>
   </div>

   <div id="largeContentWrap">
    <h2>MIL Partner Sign Up</h2>
    <div class="errMsg">
     <?php
     if (array_key_exists('err', $_GET)) {
      echo $_GET['err'];
     }
     ?>
    </div>

    <form action="./control.php" id ="partner_signup_form" method="post" name="Login">
     <fieldset>	
      <table>
       <tr>
        <td>*Partner Name:</td>
        <td>
         <select id="partner_name" name="partner_name">
          <option value="">Choose one</option>
          <?php
          $table_name = 'partner';
          Utility::printOptions($table_name, null);
          ?>
         </select>
        </td>
       </tr>
       <tr><td><input type="hidden" id="role" name="role" value="<?php echo PARTNER_ROLE ?>"/></td></tr>
      </table>
     </fieldset>
     <?php require_once 'form_component/mil_labs_checkbox_func.php'; ?>
     <fieldset id="responsibleLabs">
      <h4>Currently Providing Funding to MIL Lab(s):</h4> 
      <?php echo getLabs($member, "funded_labs_id") ?>
      <div style="clear:both"></div>
      <h4>Currently Providing In-kind Service to MIL Lab(s):</h4> 
      <?php echo getLabs($member, "inkind_labs_id") ?>
     </fieldset>

     <fieldset>
      <h4>Contact Information</h4>
      <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
       <tr>
        <td>*Business Address:</td>
        <td><input id="busi_addr" name="busi_addr" type="text" value="<?php echo ($member ? $member->getProperty('busi_addr') : "test1") ?>" size="32" maxlength="32" /></td>
        <td>*Remittance Address:</td>
        <td><input id="remit_addr" name="remit_addr"  type="text"  value="<?php echo ($member ? $member->getProperty('remit_addr') : "test1") ?>"  size="32" maxlength="32" /></td>
       </tr>

       <tr>
        <td>* City:</td>
        <td><input id="busi_city" name="busi_city" type="text"  value="<?php echo ($member ? $member->getProperty('busi_city') : "test1") ?>" size="32" maxlength="32"/></td>
        <td>* City:</td>
        <td><input id="remit_city" name="remit_city" type="text"  value="<?php echo ($member ? $member->getProperty('remit_city') : "test1") ?>" size="32" maxlength="32"/></td>
       </tr>
       <tr>
        <td>* State:</td>
        <td><input id="busi_state" name="busi_state" type="text"  value="<?php echo ($member ? $member->getProperty('busi_state') : "test1") ?>" size="32" maxlength="32"/></td>

        <td>* State:</td>
        <td><input id="remit_state" name="remit_state" type="text" value="<?php echo ($member ? $member->getProperty('remit_state') : "test1") ?>" size="32" maxlength="32"/></td>
       </tr>
       <tr>
        <td>* Zip:</td>
        <td><input id="busi_zip" name="busi_zip" type="text" value="<?php echo ($member ? $member->getProperty('busi_zip') : "32603") ?>" size="5" maxlength="5"/></td>
        <td>* Zip:</td>
        <td><input id="remit_zip" name="remit_zip" type="text" value="<?php echo ($member ? $member->getProperty('busi_zip') : "32603") ?>" size="5" maxlength="5"/></td>
       </tr>
       <tr>
        <td>Is business address<br /> the same with<br /> remittance address?</td>
        <td>
         <div class="radioButt memberBasic">
          <input type="radio" id = "same" name="same"/><label style="float:left;">Yes</label>
         </div>
         <div class="radioButt memberBasic">
          <input type="radio" id = "not_same" name="same"/> <label style="float:left;">No</label>
         </div>
        </td>
       </tr>
       <tr>
        <td> &nbsp; </td>
        <td colspan="3">&nbsp;</td>
       </tr>
       <?php require_once 'form_component/mem_basic_info.php'; ?>
       <?php require_once 'form_component/username_pwd.php'; ?>
       <tr>
        <td></td>
        <td colspan="3"></td>
       </tr>
      </table>
     </fieldset>
     <fieldset>
      <table border="0" cellpadding="0" cellspacing="0">
       <tr><td colspan='2'></td></tr>
       <tr>
        <td align="right">Comment Box: </td>
        <td>
         <textarea id="admin_comments" name="admin_comments" COLS=80 ROWS=2 maxlength="500"></textarea>
         <br />Maximum characters: 500<br />
         You have <input type="text" id="countdown" size="3" value="500"/> characters left.
        </td>
       </tr>
       <tr>
        <td></td>
        <td><input class="button" name="approve_membership" value="Approve" type="submit"/>
         <input class="button" name="disapprove_membership" value="Disapprove" type="submit"/>
         <input class="button" type="submit" name="back_to_member_list" value="Cancel"/>
        </td>
       </tr>
      </table>
     </fieldset>

    </form>

   </div>
   <span class="clearing"></span>
   <div id="sponsorLogos">
    <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
   </div>

  </div>
  <script type="text/javascript">
   $("#same").click(
   function(){
    $('#remit_addr').val( $('#busi_addr').val());
    $('#remit_city').val( $('#busi_city').val());
    $('#remit_state').val( $('#busi_state').val());
    $('#remit_zip').val( $('#busi_zip').val());
   }
  );
   $("#not_same").click(
   function(){
    $('#remit_addr').val("");
    $('#remit_city').val("");
    $('#remit_state').val("");
    $('#remit_zip').val("");
   }
  );
        
   $('#submit_membership_application').click(function(){
    $.validator.messages.required = "This field is required.";
    $.validator.messages.number = "{1} must be a number.";
    $.validator.messages.min = "The field should be greater or equal to {0}.";
    $.validator.messages.max = "The field should be less or equal to {0}.";
    $.validator.messages.equalTo = "The Re-enter password doesn't match password";
          
    var names = {
     partner_name: 'Partner Name'
     ,busi_addr: 'Business Address'
     ,remit_addr: 'Remittance Address'
     ,busi_city:'City in Business Address'
     ,remit_city: 'City in Remittance Address'
     ,busi_state:'State in Business Address'
     ,remit_state: 'State in Remittance Address'
     ,busi_zip:'Zip Code in Business Address'
     ,remit_zip:'Zip Code in Business Address'
     ,first_name:'First Name'
     ,last_name:'Last Name'
     ,title:'Title'
     ,phone:'Phone'
     ,fiscal_standard:'Fiscal Standard'
     ,username: 'User Name'
     ,password: 'Password'
     ,re_password:'Re-enter password'
    };
             
    $("#partner_signup_form").validate({
     rules: {
      partner_name: {   
       required:true
      },
      busi_addr: {   
       required:[true,names.busi_addr]
      },
      remit_addr: {   
       required:[true,names.remit_addr]
      },
      busi_city: {   
       required:[true,names.busi_city]
      },
      remit_city: {   
       required:[true,names.remit_city]
      },
      busi_state:{
       required:[true,names.busi_state]
      },
      remit_state:{
       required:[true,names.remit_state]
      }, 
      busi_zip: {   
       required:[true,names.busi_zip],
       digits:true,
       minlength: 5,
       maxlength: 5
      },
      remit_zip: {   
       required:[true,names.remit_zip],
       digits:true,
       minlength: 5,
       maxlength: 5
      },
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
      },
      funded_lab_id:{
       required:true
      },
      inkind_lab_id:{
       required:true
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
 </script>
</body>
</html>
