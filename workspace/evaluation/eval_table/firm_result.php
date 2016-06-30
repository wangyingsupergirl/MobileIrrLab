<fieldset>
			<table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
<tr>
	<td colspan='2'>IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS RESULT<br />
        Savings from Irrigation System and Mgmt, per Firm(Ac-ft)   
    </td>
</tr>
<?php 
if($eval_type==2||$eval_type==3){
// base on irrigation type calculate AWS or PWS?>
<tr>
<td align="right">Actual*:</td>
<td><input name="firm_aws" type="text" value="<?php echo $eval->getProperty('firm_aws'); ?>" size="32" maxlength="32"></td>
</tr>
<?php }else if($eval_type == 1){ ?>
<tr>
<td align="right">Potential*:</td>
<td><input name="firm_pws" type="text" value="<?php echo $eval->getProperty('firm_pws'); ?>" size="32" maxlength="32"></td>
</tr>
<?php }?>	
<tr>
<td align="right">Immediate:</td>
<td>
<input name="firm_iws" type="text" value="<?php echo $eval->getProperty('firm_iws'); ?>" size="32" maxlength="32">
</td>
</tr>
	</table>
           	</fieldset>