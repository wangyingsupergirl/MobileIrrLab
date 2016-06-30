<?php 
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>State of Florida Mobile Irrigation Lab (MIL) Program - Search Criteria</title>
  <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
  <script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
<meta name="robots" content="nofollow" />

<script type="text/javascript">
function load() {
var load = window.open('./get_last_eval.php','','scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no');
}
</script>
</head>

<body id="login">
<div id="Wrapper">
<div id="header">
<h1>State of Florida</h1>
<p>Mobile Irrigation Laboratory Program</p>
</div>

<div id="contentWrap">
<div id="mainIndex">

<form action="control_evaluation.php" method="post"><h4>Select none or more:</h4>
<fieldset>
<table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
<tr>
<td width="250"> Evaluation ID Number<br />(13 digits system ID): </td>
<td><input name="id" value="" type="text" size="32" maxlength="32" /></td>
</tr>
<tr>
<td width="250"> Evaluation Year: </td>
<td><select id="eval_yr" name="eval_yr">
<option value="">Choose one</option>
<?php
$start_year = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION_FROM_1990;
$end_year = END_OF_CALENDAR_YEAR_OF_EVALUATION;
for ($i = $start_year; $i <= $end_year; $i++) {
echo "<option value='$i' >$i</option>";
}
?>
</select></td>
</tr>
<tr>
<td width="250"> Evaluation Month: </td>
<td><select  name="eval_month">
<option value="">Choose one</option>
<?php
$start_month = 1;
$end_month = 12;
for ($i = $start_month; $i <= $end_month; $i++) {
echo "<option value='$i' ";
if ($eval_month == $i) {
echo "selected";
}
$month_name = Utility::getMonthName($i);
echo ">$month_name</option>";
}
?>
</select>

</td>
</tr>
<tr>
<td width="250"> Irrigation System Type: </td>
<td>
<select name="irr_sys_type">
<option value="">Choose one</option>
<?php 
$table_name = 'irr_sys_types';
Utility::printOptions($table_name,null);
?>
</select>
</td>
</tr>

<tr>
<td width="250">
Crop name:</td>
<td>
<select name="crop_category">
<option value="" >Choose one</option>
<?php
session_start();
$package = $_SESSION['PackageObject'];
$mil_id = $package->getProperty('mil_id');
$mil_type = $_SESSION['MILLabs'][$mil_id]->getProperty('mil_type');
$table_name = 'ag_urban_types_names';
$constrain = 'where type = "'.$mil_type.'"';
Utility::printOptions($table_name,$constrain);
?>
</select>
</td>
</tr>
<tr>
<td width="250"> County:</td>
<td>    <?php
    $table_name = 'fl_county';?>
  
     <select id="county_id" name="county_id">
      <option value="">Choose one</option>
      <?php
      Utility::printOptions($table_name, $mil_id, $county_id);
      ?>
     </select> 
   </td>
</tr>
<tr>
<td width="250"> Acre Range :</td>
<td> above: <input name="acre_from" type="text" value="" size="20" maxlength="20" /><br /> 
below: <input name="acre_to" type="text" value="" size="20" maxlength="20" />
</td>
</tr>
<tr>
<td width="250"> Zip Code:</td>
<td><input name="zip_code" type="text" value="" size="20" maxlength="32" /> </td>
</tr>
</table>
</fieldset>
<div class="form-btns" style="margin-top: 20px;">
<input class="button" type="submit" name="search_eval" value="Next" />
<input class="button" type="submit" name="back_to_db_enter" value="Back" />
</div>
</form>
</div>			
</div>
<span class="clearing"></span>
<div id="sponsorLogos">
<p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
</div>

</div>
</body>
</html>