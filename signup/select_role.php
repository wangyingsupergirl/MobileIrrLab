<?php
require_once '../includes/constant.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>State of Florida Mobile Irrigation Lab (MIL) Program - Meeting Description</title>
<link rel="stylesheet" type="text/css" href="../styles/milStylesheet.css" />
 <script src="../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
 <script src="../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
<meta name="robots" content="nofollow" />
</head>

<body>
    <div id="Wrapper">
    <div id="header">
    <h1>State of Florida</h1>
    <p>Mobile Irrigation Laboratory Program</p>
    </div>
    <div id="contentWrap">
    <div id="mainIndex">
    <h2>How would you describe yourself?</h2>
    <div id="error_msg"  style="color:red"></div>
        <form id ="role_form " action="./control.php" method="post" >
        <table>
        <tr>
            <td>
                <input type="radio" name="role" value="<?php echo CONTRACTOR_ROLE;?>"/>
            </td>
            <td>
                <label>Contractor</label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="role" value="<?php echo EMPLOYEE_ROLE;?>"/>
            </td>
            <td>
                <label>Employee</label>
            </td>
        </tr>
         <tr>
            <td>
                <input type="radio" name="role" value="<?php echo PARTNER_ROLE;?>"/>
             </td>
             <td>
                <label>Partner</label>
             </td>
        </tr>
         <tr>
            <td>
                <input type="radio" name="role" value="<?php echo GUEST_ROLE;?>" />
             </td>
             <td>
                <label>Guest</label>
             </td>
        </tr>
        </table>
        <div class="form-btns" style="margin-top: 20px;">
        <input class="button" type="submit" name="submit_role_selection" id="submit_role_selection" value="Next"/>
        </div>
        </form>
    </div>
    </div>

<div id="sponsorLogos">
<p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
</div>

</div>
<script>
$(document).ready(function(){
  $('#submit_role_selection').click(function() {
    if (!$("input[@name='name']:checked").val()) {
        $('#error_msg').html('Select role first!');
        return false;
    }else {
      
    }
  });
});
</script>

</body>
</html>
