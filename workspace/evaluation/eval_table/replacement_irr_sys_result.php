<fieldset>
<table id="irr_calculation" border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
  <tr>
  <td colspan='2' >IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS RESULT
  <br />Water Saving(ac-ft) - Irrigation System Only
  </td>
  </tr>
  <tr>
   <td align="right">Scheduled Improvements:</td>
   <td><input id="sched_imprv" name="sched_imprv" type="text" value="" size="32" maxlength="32"/></td>
   <td id="sched_imprv_gallon"></td>
   <td>
   </td>
  </tr>
  <tr>
   <td align="right">Planned Repairs:</td>
   <td><input id="sched_imprv" name="planned_repairs" type="text" value="" size="32" maxlength="32"/></td>
   <td id="planned_repairs_gallon"></td>
   <td>
   </td>
  </tr>
  <tr>
   <td align="right">Immediate Repairs:</td>
   <td><input id="imm_repairs" name="imm_repairs" type="text" value="" size="32" maxlength="32"/></td>
   <td id="imm_repairs_gallon"></td>
   <td>
   </td>
  </tr>
  <tr>
   <td></td>
   <td>
      <input class="button" type="button" id="calculate_replacement_water_saving" name="calculate_replacement_water_saving" value="Calculate Total Water Savings" onclick="calculateWaterSaving()"/>
   </td>
  </tr>
</table>
</fieldset>
<fieldset id="calculation_result" style="display:none;">
 <table  border="0" cellpadding="0" cellspacing="0" class="mainContactForm" >
  <tr>
   <td colspan='3' >Calculation Result From Initial Evaluation of New Irrigation System
    </td>
  </tr>
  <tr>
   <td align="right">Type:</td>
   <td colspan='2' >Potential</td>
  </tr>
  <tr>
   <td align="right">DU or EU improvement:</td>
   <td><span id="potiential_du"></span>  (<span id="potiential_du_gallon"></span> Millions of Gallons)</td>
   <td>
   </td>
  </tr>
  <tr>
   <td align="right">Total PWS:</td>
   <td><span id="pws"></span> (<span id="pws_gallon"></span> Millions of Gallons)</td>
   <td></td>
  </tr>
  <tr>
   <td colspan='3' >
       Calculation Result From Replacing the Old Irrigation System with a New Irrigation System

    
   </td>
  </tr>
  <tr>
   <td align="right">Type:</td>
   <td colspan='2' >Actual</td>
  </tr>
  <tr>
   <td align="right">DU or EU improvement:</td>
   <td><span id="actual_du"></span>   (<span id="actual_du_gallon"></span> Millions of Gallons)</td>
   <td>
   </td>
  </tr>
  <tr>
   <td align="right">Total AWS<!--/PWS-->:</td>
   <td><span id="aws"></span><!--/<span id="pws_last_eval"></span> --> (<span id="aws_gallon"></span><!--/<span id="pws_last_eval_gallon"></span>-->  Millions of Gallons)</td>
   <td></td>
  </tr>
 </table>
</fieldset>
