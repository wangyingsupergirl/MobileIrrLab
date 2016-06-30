
  <tr>
    <td>*Username</td>
    <td><input name="username" value="<?php if($member) echo $member->getProperty('username'); ?>" type="text" size="32" maxlength="32" /></td>
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
 
