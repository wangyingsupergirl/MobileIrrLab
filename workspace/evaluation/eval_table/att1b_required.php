<fieldset>
 <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
  <tr><td colspan='2'>IRRIGATION SYSTEM WATER SOURCE, PUMPING STATION, AND OTHER  INFO</td></tr>
  <tr> 
   <td align="right">County Name*: </td>


   <td>
    <?php
    $table_name = 'fl_county';
    
    if ($init_eval != false) {
     $county_id = $init_eval->getProperty('county_id');
     Utility::printSelectedOption($table_name, $eval->getProperty('mil_id'), $county_id);
     echo "<input id='county_id' name='county_id' value='$county_id' type='hidden'>";
    } else {
     ?>
     <select id="county_id" name="county_id">
      <option value="">Choose one</option>
      
      <?php
      
      Utility::printOptions($table_name, $eval->getProperty('mil_id'), $county_id);
      ?>
     </select> 
     <?php
    }
    ?>
   </td>
  </tr> 
  <tr>
   <td align="right">Zip Code*:</td>
   <td>
    <?php
    if ($init_eval) {
     $zip_code = $init_eval->getProperty('zip_code');
     echo "$zip_code<input id='zip_code' name='zip_code' value='$zip_code' type='hidden'>";
    } else {
     $zip_code = $eval->getProperty('zip_code');
     echo "<input name='zip_code' value='$zip_code' type='text' size='10' maxlength='10' />";
    }
    ?>

  </tr>
  <tr>
   <td align="right">Irrigation System ID:</td>
   <td><?php echo $eval->getProperty('farm_id'); ?></td>
  </tr>
  <tr>
   <td align="right">Soil Type No.*:</td>
   <td>
    <?php
    if ($init_eval) {
     $soil_type = $init_eval->getProperty('soil_type');
     echo "$soil_type<input id='soil_type' name='soil_type' value='$soil_type' type='hidden'>";
    } else {
     $soil_type = $eval->getProperty('soil_type');
     echo "<input name='soil_type' value='$soil_type' type='text' size='10' maxlength='10' />";
    }
    ?>

   </td>
  </tr>
  <tr>
   <td align="right">Water Source*: </td>
   <td>
    <select name="water_source">
     <option value="" selected>Choose one</option>
     <?php
     $table_name = 'water_source_types';
     $constrain = null;
     Utility::printOptions($table_name, $constrain);
     ?>
    </select>
   </td>
  </tr>
  <tr>
   <td align="right">TDS:</td>
   <td><input name="tds" type="text" value="<?php echo $eval->getProperty('tds'); ?>" size="20" maxlength="20"></td>
  </tr>
  <tr>
   <td align="right">pH:</td>
   <td><input name="ph" type="text" value="<?php echo $eval->getProperty('ph'); ?>" size="32" maxlength="32" /></td>
  </tr>
  <tr>
   <td align="right">Pump Type*:</td>
   <td>
    <select name="pump_type">
     <option value="" selected>Choose one</option>
     <?php
     $table_name = 'pump_types';
     $constrain = null;
     Utility::printOptions($table_name, $constrain);
     ?>
    </select>
   </td>
  </tr>
  <tr>
   <td align="right">Has Permanent Flow Meter*:</td>
   <td>
    <?php
    $flow = $eval->getProperty('has_flow_meter');
    $flow = trim($flow);
    ?>
    <select id ="has_flow_meter" name="has_flow_meter">
     <option value="yes" <?php echo ($flow == 'yes' ? 'selected' : ''); ?>>Yes</option>
     <option value="no" <?php echo ($flow == 'no' ? 'selected' : ''); ?>>No</option>
    </select>
   </td>
  </tr>
  <tr>
   <td align="right">Device Used to Measure GPM*:</td>
   <td>
    <select id ="device_gpm" name="device_gpm">

     <?php
     $table_name = 'device_gpm';
     $constrain = null;
     Utility::printOptions($table_name, $constrain);
     ?>
    </select>
   </td>
  </tr>
  <tr>
   <td align="right" id="gpm_title">Gallons per Minute</td>
   <td></td>
  </tr>
  <tr id="from_flow_meter_line">
   <td align="right">From Permanent Flow Meter:</td>
   <td><input id="from_flow_meter" name="from_flow_meter" type="text" value="<?php echo $eval->getProperty('from_flow_meter'); ?>" size="32" maxlength="32" /></td>
  </tr>
  <tr id="from_device_line">
   <td align="right">From Device used to verify GPM:</td>
   <td><input id="from_device" name="from_device" type="text" value="<?php echo $eval->getProperty('from_device'); ?>" size="32" maxlength="32" /></td>
  </tr>
  <tr>
   <td align="right">Motor Type*:</td>
   <td>
    <select name="motor_type">
     <option value="" selected>Choose one</option>
     <?php
     $table_name = 'motor_types';
     $constrain = null;
     Utility::printOptions($table_name, $constrain);
     ?>
    </select>
   </td>
  </tr>
 </table>
</fieldset>

<script>
 var display = check_flow_meter();
 display += check_device_gpm();

 function check_flow_meter(){
  var text = $("#has_flow_meter option:selected").text();
  if(text != 'No'){
   $('#from_flow_meter_line').show();
   $('gpm_title').show();
   return 0;
  }else{
   $('#from_flow_meter_line').hide();
   $('#from_flow_meter').val("");
   var text = $("#device_gpm option:selected").val();
   if(text == 7){
    $('gpm_title').hide();  
   }
   return 1;
  }
 }
 function check_device_gpm(){
  var text = $("#device_gpm option:selected").val();
  if(text == 7){
   $('#from_device_line').hide();
   $('#from_device').val("");
   var text = $("#has_flow_meter option:selected").text();
   if(text == 'No'){
    $('#gpm_title').hide();  
   }
   return 0;
  }else{
   $('#from_device_line').show();
   $('#gpm_title').show();
   return 1;
  }

 }
 $('#has_flow_meter').change(
 function(){
  check_flow_meter();
 }
)
 $('#device_gpm').change(
 function(){
  check_device_gpm();
 }
)
</script>