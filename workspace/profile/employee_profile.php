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
        <?php 
            require_once dirname(__FILE__).'/../../signup/form_component/mem_basic_info.php';
        ?>

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