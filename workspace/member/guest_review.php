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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>State of Florida Mobile Irrigation Lab (MIL) Program</title>
  <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />

  <meta name="robots" content="nofollow" />
 </head>
 <body id="login">
  <div id="Wrapper">
   <div id="header">
    <h1>State of Florida</h1>
   </div>
   <div id="contentWrap">
    <div id="mainIndex">
     <h2>MIL Guest Membership Review</h2>
     <div class="errMsg">
      <?php
      if (array_key_exists('err', $_GET)) {
       echo $_GET['err'];
      }
      ?></div>
     <form action="./control.php" method="post">
      <fieldset>
       <input type="hidden" name="role" value="<?php echo GUEST_ROLE ?>"/>
       <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr>
         <td>* First Name:</td>
         <td><input name="first_name" value="<?php echo ($member ? $member->getProperty('first_name') : "") ?>"  type="text" size="32" maxlength="32" /></td>
        </tr>

        <tr>
         <td>* Last Name:</td>
         <td><input name="last_name" value="<?php echo ($member ? $member->getProperty('last_name') : "") ?>"  type="text" size="32" maxlength="32" /></td>
        </tr>

        <tr>
         <td> Title:</td>
         <td><input name="title" value="<?php echo ($member ? $member->getProperty('title') : "") ?>"  type="text" size="32" maxlength="32" /></td>
        </tr>
        <tr>
         <td> Company:</td>
         <td><input name="company" value="<?php echo ($member?$member->getProperty('company'):"")?>"  type="text" size="32" maxlength="32" /></td>
        </tr>
        <tr>
         <td>* Phone:</td>
         <td><input name="phone" type="text" value="<?php echo ($member ? $member->getProperty('phone') : "") ?>"  size="20" maxlength="20" /></td>
        </tr>
        <tr>
         <td>* City:</td>
         <td><input name="busi_city" type="text" value="<?php echo ($member ? $member->getProperty('busi_city') : "") ?>"  size="20" maxlength="20" /></td>
        </tr>
        <tr>
         <td>* State:</td>
         <td><input name="busi_state" type="text" value="<?php echo ($member ? $member->getProperty('busi_state') : "") ?>"  size="20" maxlength="20" /></td>
        </tr>
        <tr>
         <td>* Zip:</td>
         <td><input name="busi_zip" type="text" value="<?php echo ($member ? $member->getProperty('busi_zip') : "") ?>"  size="5" maxlength="6" /></td>
        </tr>
       </table>
      </fieldset>

      <fieldset>
       <h4>*Interested in MIL Labs?</h4>
       <?php
       require_once 'form_component/mil_labs_checkbox.php';
       ?>
      </fieldset>
      <fieldset>
       <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">

        <?php require_once 'form_component/username_pwd.php'; ?>
       </table>
      </fieldset>
      <fieldset>
       <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr><td colspan='2'></td></tr>
        <tr>
         <td align="right">Comment Box: </td>
         <td>
          <textarea id="admin_comments" name="admin_comments" COLS=40 ROWS=2 maxlength="500"></textarea>
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

   </div>
   <div id="sponsorLogos">
    <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
   </div>

  </div>


 </body>
</html>