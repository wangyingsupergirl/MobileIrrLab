<!--My Profile starts here-->
<div class="content"  dojotype="dijit.layout.ContentPane" title="My Account" <?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] == 0 ? "selected" : "") ?>>
    <div id="sign-up">
        <form action="<?php echo MIL_SERVER_ROOT; ?>/workspace/profile/control_profile.php" method="post">
<fieldset>
    <div id="profile_notification" style ="color: blue;">
    <?php
  
    if(array_key_exists('accountUpdate',$_GET)){
        $account_update = $_GET['accountUpdate'];
        if($account_update==1){
                echo 'Your profile has been updated successfully';
        }else{
                echo 'Sorry, we can not updated your profile this time.';
        }

    }
    ?>
    </div>
</fieldset>


<fieldset>
    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
 <tr> <td>*Contractor Name: &nbsp;</td>
        <td>
        <select id="partner_name" name="partner_name" disabled>
        <option value="0" selected>Choose one</option>
        <?php
            $table_name = 'partner';
            Utility::printOptions($table_name,null);
        ?>
        </select>
        </td></tr>
<tr><td><h4>Contact Information</h4></td></tr>

<tr>
<td>*Business Address:</td>
<td><input id="busi_addr" name="busi_addr" type="text"  value ="<?php if($member) echo $member->getProperty('busi_addr');?>" size="32" maxlength="32" /></td>
<td>*Remittance Address:</td>
<td><input id="remit_addr" name="remit_addr"   value ="<?php if($member) echo $member->getProperty('remit_addr');?>"  type="text" value="" size="32" maxlength="32" /></td>
</tr>

<tr>
<td>* City:</td>
<td><input id="busi_city" name="busi_city" type="text"  value ="<?php if($member) echo $member->getProperty('busi_city');?>" size="32" maxlength="32"></td>
<td>* City:</td>
<td><input id="remit_city" name="remit_city" type="text"  value ="<?php if($member) echo $member->getProperty('remit_city');?>" size="32" maxlength="32"></td>
</tr>

<tr>
<td>* State:</td>
<td><input id="busi_state" name="busi_state" type="text"  value ="<?php if($member) echo $member->getProperty('busi_state');?>" size="32" maxlength="32"></td>
<td>* State:</td>
<td><input id="remit_state" name="remit_state" type="text"  value ="<?php if($member) echo $member->getProperty('remit_state');?>" size="32" maxlength="32"></td>
</tr>

<tr>
<td>* Zip:</td>
<td><input id="busi_zip" name="busi_zip" type="text"  value ="<?php if($member) echo $member->getProperty('busi_zip');?>" size="5" maxlength="5"></td></td>
<td>* Zip:</td>
<td><input id="remit_zip" name="remit_zip" type="text"  value ="<?php if($member) echo $member->getProperty('remit_zip');?>" size="5" maxlength="5"></td>
</tr>

<tr>
<td>Is business address<br /> the same with<br /> remittance address?</td>
<td>
    <div class="radioButt memberBasic">
        <input type="radio" id = "same" name="same"><label style="float:left;">Yes</label>
        </div>
        <div class="radioButt memberBasic">
        <input type="radio" id = "not_same" name="same"> <label style="float:left;">No</label>
        </div>
</td>
</tr>

<tr>
<td>&nbsp;</td>
<td colspan="3">&nbsp;</td>
</tr>
<?php require_once dirname(__FILE__).'/../../signup/form_component/mem_basic_info.php';?>

<tr>
<td></td>
<td colspan="3"></td>
</tr>
</table>
<input type="hidden" name="mem_id" value="<?php if($member) echo $member->getProperty('mem_id'); ?>" />
<input type="hidden" name="role" value="<?php if($member) echo $member->getProperty('role');?>" />
<input type="hidden" name="username" value="<?php if($member) echo $member->getProperty('username');?>" />

<div class="form-btns" style="margin-top: 20px; clear: left">
<input class="button" type="submit"  name="save_profile" value="Save">
</div>
</fieldset>
</form>
<script>
$("#same").click(
function(){
        $('#remit_addr').val( $('#busi_addr').val());
        $('#remit_city').val( $('#busi_city').val());
        $('#remit_state').val( $('#busi_state').val());
        $('#remit_zip').val( $('#busi_zip').val());
});
$("#not_same").click(
function(){
        $('#remit_addr').val("");
        $('#remit_city').val("");
        $('#remit_state').val("");
        $('#remit_zip').val("");
});

</script>

<?php require_once dirname(__FILE__)."/change_username_pwd.php"?>
</div>
</div>