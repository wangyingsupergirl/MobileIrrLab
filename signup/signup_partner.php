<?php
require_once '../includes/mil_init.php';
require_once '../includes/utility.php'; //required constant.php in utility.php
session_start();
$member = false;
if(array_key_exists('MemberServed',$_SESSION)){
    $member = $_SESSION['MemberServed'];
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign Up</title>
  <link rel="stylesheet" type="text/css" href="../styles/milStylesheet.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"></script>
  <script type="text/javascript" src="../js/jquery-validate/jquery.validate.js"></script>
<script src="../js/multiple_choices_list.js" type="text/javascript"></script>
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
     <?php echo  (array_key_exists('err', $_GET)? $_GET['err']:"");  ?>
    </div>

    <form action="./control.php" id ="partner_signup_form" method="post" name="Login">
     <fieldset>	
      <table>
       <tr>
        <td>*Partner Name:</td>
        <td>
         <select id="partner_name" name="partner_name" class="required">
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
        <td><input id="busi_addr" name="busi_addr" type="text" class="required"  value="<?php echo ($member?$member->getProperty('busi_addr'):"")?>" size="32" maxlength="32" /></td>
        <td>*Remittance Address:</td>
        <td><input id="remit_addr" name="remit_addr"  type="text" class="required"  value="<?php echo ($member?$member->getProperty('remit_addr'):"")?>"  size="32" maxlength="32" /></td>
       </tr>

       <tr>
        <td>* City:</td>
        <td><input id="busi_city" name="busi_city" type="text" class="required"  value="<?php echo ($member?$member->getProperty('busi_city'):"")?>" size="32" maxlength="32"/></td>
        <td>* City:</td>
        <td><input id="remit_city" name="remit_city" type="text" class="required"  value="<?php echo ($member?$member->getProperty('remit_city'):"")?>" size="32" maxlength="32"/></td>
       </tr>
       <tr>
        <td>* State:</td>
        <td><input id="busi_state" name="busi_state" type="text" class="required"  value="<?php echo ($member?$member->getProperty('busi_state'):"")?>" size="32" maxlength="32"/></td>
       
        <td>* State:</td>
        <td><input id="remit_state" name="remit_state" type="text" class="required" value="<?php echo ($member?$member->getProperty('remit_state'):"")?>" size="32" maxlength="32"/></td>
       </tr>
       <tr>
        <td>* Zip:</td>
        <td><input id="busi_zip" name="busi_zip" type="text" class="required" value="<?php echo ($member?$member->getProperty('busi_zip'):"")?>" size="5" maxlength="5"/></td>
        <td>* Zip:</td>
        <td><input id="remit_zip" name="remit_zip" type="text" class="required" value="<?php echo ($member?$member->getProperty('busi_zip'):"")?>" size="5" maxlength="5"/></td>
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
     
<?php
if ($member){
 if ($member->getProperty('admin_comments') != '') {
  echo "<fieldset>Disapprove Reason: {$member->getProperty('admin_comments')}</fieldset>";
 }}
?>
      
     <div class="form-btns" style="margin-top: 20px">
      <input class="button" type="submit" id="submit_membership_application"  name="submit_membership_application" value="Submit"/>
      <input class="button" type="submit" name="back_login" value="Cancel"/>
     </div>

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
   $(document).ready(function(){
  $("#partner_signup_form").validate({
   rules: {
    busi_zip: {   
     required:true,
     digits:true,
     minlength: 5,
     maxlength: 5
    },
    remit_zip: {   
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
      }, username:{
       required:true,
       email:true
      },
      re_password:{
       required:true
       ,equalTo: "#password"
      }
   }
  });
  
 });

          
   $('#password').change(
   function(){
    var password = $('#password').val();
    $.post(
    "control.php",
    { password: password,  is_valid_password:"" },
    function(msg) {
     $('#password_err').html(msg);
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
     });
   }
  )
  </script>
 </script>
</body>
</html>
