
  <tr>
    <td>*Username</td>
    <td><input id="username" name="username" value="<?php echo ($member?$member->getProperty('username'):"")?>" type="text" class="required" size="56" maxlength="128" /><div id='username_err' style="color:red"></td>
    
	</tr>
  <tr>
    <td colspan="2"><p><em>e.g. myname@example.com. This email address will be used to sign-in to your account.</em></p></td>
  </tr>
  <tr>
      <td colspan="2">    
    Your new password must be at least 6 characters in length.<br />
    Use a combination of letters(upper case and lower case) and numbers.<br />
    Passwords are case-sensitive. <br /></td>
  </tr>
  <tr>
    <td>*Password</td>
    <td><input id="password" name="password" value="" type="password" size="32" maxlength="32" class="required" /><div id='password_err' style="color:red"></div></td>
  </tr>
  <tr>
    <td>*Re-enter password</td>
    <td><input id="re_password" name="re_password" value="" type="password" size="32" maxlength="32"  class="required" /></div></td>
  </tr>


