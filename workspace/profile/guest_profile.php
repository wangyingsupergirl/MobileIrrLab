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
         <td><input name="phone" type="text" value="<?php echo ($member?$member->getProperty('phone'):"")?>"  size="20" maxlength="20" /></td>
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
         <table>
        <tr>
        <td>*MIL Lab:</td>
        <?php
            echo '<td>';
            foreach($Labs as $lab){
                echo '<span class="mil_lab_name">'.$lab->getDisplayName().'</span>';
            }
            echo '</td>';
        ?>
        </tr>
        <tr>
        <td>
        <input type="hidden" name="mem_id" value="<?php echo $MemberServed->getProperty('mem_id');?>" />
        </td>
        <td>
        <div class="form-btns" style="margin-top: 20px;">
        <input class="button" type="submit"  name="save_profile" value="Save">
        </div>
        </td>
        </table>
        </form>
    </fieldset>

<?php require_once dirname(__FILE__)."/change_username_pwd.php"?>
</div>
</div>