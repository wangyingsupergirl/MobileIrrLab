
 <tr>
 <td>* First Name:</td>
 <td><input name="first_name" value="<?php if($member) echo $member->getProperty('first_name');?>" type="text" size="32" maxlength="32" /></td>
 </tr>
				  
 <tr>
 <td>* Last Name:</td>
 <td><input name="last_name" value="<?php if($member) echo $member->getProperty('last_name'); ?>" type="text" size="32" maxlength="32" /></td>
 </tr>
				  
 <tr>
 <td>* Title:</td>
 <td><input name="title" value="<?php if($member) echo $member->getProperty('title'); ?>" type="text" size="32" maxlength="32" /></td>
 </tr>
			      
 <tr>
 <td>* Phone:</td>
 <td><input name="phone" type="text" value="<?php if($member) echo $member->getProperty('phone'); ?>" size="32" maxlength="32" /></td>
 </tr>

 <tr>
 <td>* Your Fiscal Yr Standard Ends In:</td>
 <td>
 <div class="radioButt memberBasic">
 	<input name="fiscal_standard" value="0" type="radio" <?php if($member&&$member->getProperty('fiscal_standard')=="0"){echo 'checked';} ?>> <label style="float:left;">June</label>
 </div>
 
<div class="radioButt memberBasic">
	<input name="fiscal_standard" value="1" type="radio" <?php if($member&&$member->getProperty('fiscal_standard')=="1"){echo 'checked';} ?>> <label style="float:left;">September</label>
</div>
<div class="radioButt memberBasic">
	<input name="fiscal_standard" value="2" type="radio" <?php if($member&&$member->getProperty('fiscal_standard')=="2"){echo 'checked';} ?>> <label style="float:left;">December</label>
</div>
</td>
</tr>