<!--Beginning of changing username-->
<fieldset>
<div id="username">
    
    <div id="username_display_mode">
     <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
      <tr>
       <td>Username:  
             <!--Don't change id of the span below, it is used by java script -->
           <span id="username_displayed"><?php echo $MemberServed->getProperty('username'); ?> </span>
       </td>
       <td>
           <!--Don't change id and class name of the span below, it is used by java script -->
           <span id="username_change" class="profile_change_btn">Change </span>
       </td>
        </tr>
     </table>
    </div>
    <!--Don't change any id name inside div id username_edit_mode(including div itself), all are used by javacript-->
    <div id="username_edit_mode" style='display:none'  class="mainContactForm">
        <form id="save_username_form" action="" method="post" onsubmit="return false;">
      <input type="text" id ="new_username" name="username"/>
     <input type="submit" id="save_username" value="Change User Name" name="save_username">
      </form>
     </div>    
</div>
</fieldset>
<!---End of changing username-->

<!--Beginning of changing password-->
<fieldset>
<div id="password">
    <div id="password_display_mode">
     <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
      <tr>
       <td>Password:  <span id="password_displayed">****** </span></td>
        <td>
            <!--Don't change id and class name of the span below, it is used by java script -->
            <span id="password_change" class="profile_change_btn">Change </span>
        </td>
        </tr>
     </table>
    </div>
     <!--Don't change any id name inside div id username_edit_mode(including div itself), all are used by javacript-->
     <div id="password_edit_mode" style='display:none'  class="mainContactForm">
         <div id="password_rules">
             <ul>
              <li>Your new password must be at least 6 characters in length.</li>
              <li>Use a combination of letters(both upper and lower case)and numbers only.</li>
              <li>Passwords are case-sensitive. Remember to check your CAPS lock key.</li>
              </ul>
         </div>
         <form id="save_password_form"  action="" method="post" onsubmit="return false;">
          <table>
              <tr><td>Old Password*:</td>
                      <td><input type="password" id ="old_password" name="old_password"/></td>
              </tr>
              <tr>
                  <td>New Password*:</td>
                  <td><input type="password" id ="new_password" name="new_password"/></td>
              </tr>
              <tr>
                  <td>Confirm New Password*:</td>
                  <td><input type="password" id ="retyped_new_password" name="retyped_new_password"/></td>
              </tr>
             <!-- <input type="hidden" id="mem_id" name="mem_id" value="<?php echo $MemberServed->getProperty('mem_id');?>" />-->
              <tr>
                  <td></td>
                  <td>
                  <input type="submit"  id="save_password" value="Change Password" name="save_password">
                  </td>
               </tr>
             </table>
          </form>
         </div>
    </div>
    
</fieldset>
<!--End of changing password-->
<script>
$('.profile_change_btn').click(
 function(){
    changeButton(this); 
 }
)

function changeButton(btn){
    //determine which field(username or password)need to change
    var id = $(btn).attr('id');
    var arr = id.split("_");
    if(arr.length>1){
      var field_name = arr[0];  
    }else{
        alert('invalid btn name, should be fieldname_change');
        return;
    }
    var edit_mode_selector = '#'+field_name+'_edit_mode';
    if($(btn).html()=='Change'){
        $(btn).html('Hide');
        $(edit_mode_selector).show();
    }else{
        $(btn).html('Change')
        $(edit_mode_selector).hide()
    }
 }
$('#save_username_form').submit(
function(){
 var new_username = $('#new_username').val();
 var mem_id = $('#mem_id').val();
 $.post(
 "./profile/control_profile.php",
 { username: new_username, mem_id:mem_id, save_username:"" },
 function(msg) {
     $('#profile_notification').html(msg);
       //syc displayed username with the one in session each time
        $.post(
        "./profile/control_profile.php",
        {sync_username:"" },
        function(username) {
          $('#username_displayed').html(username);
          //jump to top of browser page, display error/suc msg (#profile_notification)to users
          $('html, body').animate({ scrollTop: 0 }, 'fast');
          //hide edit field
          changeButton('#username_change');
    });
 });
}
);

$('#save_password_form').submit(
function(){
 var new_password = $('#new_password').val();
 var old_password = $('#old_password').val();
 var retyped_new_password = $('#retyped_new_password').val();
 var mem_id = $('#mem_id').val();
 $.post(
 "./profile/control_profile.php",
 { old_password: old_password, new_password: new_password, retyped_new_password: retyped_new_password,  mem_id : mem_id,  save_password:"" },
 function(msg) {
     $('#profile_notification').html(msg);
      //jump to top of browser page, display error/suc msg (#profile_notification)to users
      $('html, body').animate({ scrollTop: 0 }, 'fast');
     //hide edit field
     changeButton('#password_change');
  });
}
);

</script>
