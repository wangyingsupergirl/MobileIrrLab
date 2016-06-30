<!--My Profile starts here-->
<div class="content"  dojotype="dijit.layout.ContentPane" title="My Account" <?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] == 0 ? "selected" : "") ?>>
<div id="sign-up">
    <fieldset>
    <!-- Main Message & Error Message will be displayed here-->
    <div id="profile_notification" style ="color: blue;">
    <?php
    if(isset($account_update)){
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
        <form action="./profile/control_profile.php" method="post">
        <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
        <tr>
         <td> Name:</td>
         <td><input name="company" value="<?php echo ($member?$member->getProperty('company'):"")?>"  type="text" size="128" maxlength="128" /></td>
        </tr>
        <tr>
         <td>* Phone:</td>
         <td><input name="phone" type="text" value="<?php echo ($member?$member->getProperty('phone'):"")?>"  size="20" maxlength="20" /></td>
        </tr>
        <tr>
         <td>*Business Address:</td>
         <td><input id="busi_addr" name="busi_addr" type="text"  value ="<?php if($member) echo $member->getProperty('busi_addr');?>" size="64" maxlength="64" /></td>
        </tr>

         <tr>
         <td>* City:</td>
         <td><input id="busi_city" name="busi_city" type="text"  value ="<?php if($member) echo $member->getProperty('busi_city');?>" size="32" maxlength="32"></td>
       </tr>

         <tr>
         <td>* State:</td>
         <td><input id="busi_state" name="busi_state" type="text"  value ="<?php if($member) echo $member->getProperty('busi_state');?>" size="32" maxlength="32"></td>
         </tr>

         <tr>
         <td>* Zip:</td>
         <td><input id="busi_zip" name="busi_zip" type="text"  value ="<?php if($member) echo $member->getProperty('busi_zip');?>" size="5" maxlength="5"></td></td>
         </tr>
         </table>
         <input type="hidden" name="mem_id" value="<?php if($member) echo $member->getProperty('mem_id'); ?>" />
         <input type="hidden" name="role" value="<?php if($member) echo $member->getProperty('role');?>" />
         <input type="hidden" name="username" value="<?php if($member) echo $member->getProperty('username');?>" />

         <div class="form-btns" style="margin-top: 20px; clear: left">
         <input class="button" type="submit"  name="save_profile" value="Save">
         </div>
        </form>
    </fieldset>

<?php require_once dirname(__FILE__)."/change_username_pwd.php"?>
</div>
</div>