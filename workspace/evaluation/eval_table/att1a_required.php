<fieldset>
<table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
<tr><td colspan='2'>IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS DATA</td></tr>
<tr>
<td align="right">Database Evaluation ID*: </td><td>
<?php
/*
* $eval declared in ../eval_content.php
* $eval has been checked 'not null' by 'eval_content.php';
*/
echo $eval->getProperty('id');
?>
<?php
$init_eval = false;
$type = null;
if ($eval_type == FOLLOW_UP_EVALUATION||$eval_type == REPLACEMENT_EVALUATION) {
    $init_eval = $eval->getProperty('initEval');
}
if ($init_eval != false) {
echo '/';
echo ($eval_type==FOLLOW_UP_EVALUATION?" Initial ":" Last ");
echo 'Evaluation: ';
echo $init_eval->getProperty('id');
}
?></td>
</tr>
<tr>
<td align="right">Evaluation ID used by MIL*: </td>
<td>
    <?php 
    $display_id =  $eval->getProperty('display_id');
    if($display_id!=''){
        echo $display_id;
        echo "<input id='display_id' name='display_id' type='hidden' value='$display_id' size='32' maxlength='32'/>";
    }else{
    ?>
    <input id="display_id" name="display_id" type="text" value="" size="32" maxlength="32" /><br />(Please make sure it entered correctly.You can not change this entry, after the submission)
    <?php 
    }
    ?>
</td>
</tr>


<!-------Begin of multiple choices Component Evaluation Funding Sources ------->
<tr>
<td align="right">Evaluation Funding Sources*: </td>
<td>
<!--Component I drop down list-->
<select id="eval_funding_sources_dropdownlist">
<option value="0" selected>Choose one</option>
<?php
$table_name = 'eval_funding_sources';
Utility::printOptions($table_name, null);
?>
</select>
<!--Component II add button-->
<input id="eval_funding_sources_addbutton" type="button" value="Add" />
</td>
</tr>
<tr>
<td>
<!--Component III error message field-->
<div class ="err_msg" id="eval_funding_sources_errmsgfield">
<!--JQuery can not catch the tag, if beginning and ending tag in the same line--> 
</div>
</td>
<td>
<!--Component IV display list-->
<div id="eval_funding_sources_displaylist">
</div>
<!--Component V hidden input box-->
<input id="eval_funding_sources" name="eval_funding_sources" type="hidden" value="<?php echo $eval->getProperty('eval_funding_sources'); ?>" />
<td>
<tr>
<!-----End of multiple choices Component Evaluation Funding Sources------------->

<!---Beginning of evaluation year and month area 
stack_size = 1 => this evaluation belong to this package. could be initial evaluation or follow up evaluation
stack_size = 2 => enter the initial evaluation of a follow up evaluation which belongs to this package
this initial evaluation may belong to this package too (if eval month and year in the same cycle otherwise not)
-->
<tr>
<td align="right">Evaluation Year*: </td>
<td>

<?php
$eval_yr = $eval->getProperty('eval_yr');
$stack_size = $_SESSION['eval_stack']->size();
if ($eval->getProperty('isSingleEval')!=true) { //this evaluation belong to this package
?>
<?php echo $package->getProperty('eval_yr'); ?>
<input id="eval_yr" name="eval_yr" type="hidden" value="<?php echo $package->getProperty('eval_yr'); ?>" size="32" maxlength="32"/>

<?php
} else {
////this evaluation doesn't belong to this package
?>
<select id="eval_yr" name="eval_yr">
<option value="">Choose one</option>
<?php
$start_year = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION;
$end_year = END_OF_CALENDAR_YEAR_OF_EVALUATION;
for ($i = $start_year; $i <= $end_year; $i++) {
echo "<option value='$i' ";
if ($eval_yr == $i) {
echo "selected";
}
echo ">$i</option>";
}
?>
</select>
<?php
}
?>

</td></tr>
<tr><td align="right">Evaluation Month*: </td>
<td> 
<?php
$eval_month = $eval->getProperty('eval_month');
$cycle = $package->getCycle();
if ($eval->getProperty('isSingleEval')!=true && $cycle == 1) { // same as package year
// this evaluation  belong to this package
$i = $package->getProperty('eval_month');
$month_name = Utility::getMonthName($i);
?>
<?php echo $month_name; ?>
<input name="eval_month" type="hidden" value="<?php echo $i; ?>" size="32" maxlength="32"/>

<?php
} else if ($eval->getProperty('isSingleEval')!=true && $cycle == 3) {
?>
<select  name="eval_month">
<option value="">Choose one</option>
<?php
$package_month= $package->getProperty('eval_month');
$range = FiscalQuarter::getQuarterRange($package_month);
for ($i = $range['min']; $i <= $range['max']; $i++) {
echo "<option value='$i' ";
if ($eval_month == $i) {
echo "selected";
}
$month_name = Utility::getMonthName($i);
echo ">$month_name</option>";
}
?>
</select>
<?php
} else if ($eval->getProperty('isSingleEval')) {
//this evaluation doesn't belong to this package
?>
<select  name="eval_month">
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
<?php
}
?>
</td>
</tr>
<!--End of eval month and year--->
<!--Beginning of irrigation system type: 
if current evaluation is follow up evaluation(init_eval!=false), the input box is changed to be a uneditable text-->
<tr>
<td align="right">Irrigation System Type*: </td>
<td>
<?php
$table_name = 'irr_sys_types';
if ($init_eval != false&&$eval->getProperty('eval_type')!=3) {
//Replacement evaluation can have different irrigation system.
$irr_sys_type = $init_eval->getProperty('irr_sys_type');
Utility::printSelectedOption($table_name,null, $irr_sys_type);
echo "<input id='irr_sys_type' name='irr_sys_type' value='$irr_sys_type' type='hidden'>";
} else {
?>
<select id="irr_sys_type" name="irr_sys_type">
<option value="">Choose one</option>
<?php
Utility::printOptions($table_name, null, $irr_sys_type);
?>
</select> 
<?php
}
?>
</td>
</tr>
<!--End of irrigation system type-->
<tr>
<td align="right">Irrigation System DU or EU*:</td>
<td><input id ="irr_sys_du" name="irr_sys_du" type="text" value="<?php echo $eval->getProperty('irr_sys_du'); ?>" size="20" maxlength="20" />
(Max:<span id="max_du_eu"> </span>)
<?php
if ($init_eval != false) {
echo ($eval_type==FOLLOW_UP_EVALUATION?"Initial ":"Last ");
echo 'Evaluation DU or EU: ';
echo $init_eval->getProperty('irr_sys_du');
}
?>
</td>
</tr>
<tr>
<td align="right">Irrigation System Acres*:</td>
<td><input id= "acre" name="acre" type="text" value="<?php echo $eval->getProperty('acre'); ?>" size="32" maxlength="32" />
<?php
if ($init_eval != false) {
echo ($eval_type==FOLLOW_UP_EVALUATION?"Initial ":"Last ");;
echo 'Evaluation Acres: ';
echo $init_eval->getProperty('acre');
}
?>
</td>
</tr>
<tr>
<td align="right">Land Use:</td>
<td>
<?php
$package = $_SESSION['PackageObject'];
$mil_id = $package->getProperty('mil_id');
$mil_type = $_SESSION['MILLabs'][$mil_id]->getProperty('mil_type');
echo $mil_type . ' - ';
?>
Category*:
<select id="crop_category_dropdownlist" >
<option value="" selected>Choose one</option>
<?php
$table_name = 'ag_urban_types_names';
$constrain = 'where type = "' . $mil_type . '" order by name';
Utility::printOptions($table_name, $constrain);
if ($init_eval != false) {
$category_ids = $init_eval->getProperty('crop_category');
$ids=explode(',',$category_ids);
foreach($ids as $category_id ){
echo $category_id;
$table = $_SESSION[$table_name];
$obj = $table[$category_id];
$col = $obj->getDisplayCol();
$category = $obj->getProperty($col);
}
?>
</select>
<?php
echo ($eval_type==FOLLOW_UP_EVALUATION?"Initial ":"Last ");
echo "Evaluation Category: $category";
}
?>
<input id="crop_category_addbutton" type="button" value="Add" />
</td>
</tr>
<tr>

<td>
<!--Component III error message field-->
<div class ="err_msg" id="crop_category_errmsgfield">
<!--JQuery can not catch the tag, if beginning and ending tag in the same line--> 
</div>
</td>
<td>
<!--Component IV display list-->
<div id="crop_category_displaylist">
</div>
<!--Component V hidden input box-->
<input id="crop_category" name="crop_category"   value="<?php echo $eval->getProperty('crop_category'); ?>" />
<td>
</tr>

<tr>
<td align="right">Choose Annual Water Use*:</td>
<td> <input id="nir_checkbox" name ="nir_checkbox" type="checkbox" class="checkinputcombo"/>
NIR(inches)*:
<input  class="checkinputcombo"   id="nir_water_use" name="nir_water_use" type="text" value="<?php echo $eval->getProperty('nir_water_use'); ?>" size="10" maxlength="10" />
<?php
if ($init_eval != false) {
echo ($eval_type==FOLLOW_UP_EVALUATION?"Initial ":"Last ");
echo 'Evaluation NIR: ';
echo $init_eval->getProperty('nir_water_use');
}
?>
(0-99)</td>
<tr>
<td><span id="annual_water_use_error" style="color:red; font-style: italic"></span></td>
<td>
<?php $actual_water_use = $eval->getProperty('actual_water_use'); ?>
<input id="awu_checkbox" name="awu_checkbox" type="checkbox" class="checkinputcombo" />
Actual*:&nbsp;
<input  class="checkinputcombo"  id="actual_water_use" name="actual_water_use" type="text" value="<?php echo $eval->getProperty('actual_water_use'); ?>" size="10" maxlength="10"/>
<?php
if ($init_eval != false) {
echo ($eval_type==FOLLOW_UP_EVALUATION?"Initial ":"Last ");
echo 'Evaluation AWS: ';
echo $init_eval->getProperty('actual_water_use');
}
?>(>=0)
</td>
</tr>

<!--Begin of multiple choices Component Irrigation System Problems -->
<tr>
<td align="right">Irrigation System Problems:</td>
<td>
<!--Component I drop down list-->
<select id="irr_sys_problems_dropdownlist">
<option value="0">Choose One</option>
<?php
$table_name = 'irr_sys_problems';
Utility::printOptions($table_name, null);
?>
</select>
<!--Component II add button-->
<input id="irr_sys_problems_addbutton" type="button" value="Add" />
</td>
</tr>
<tr>

<td>
<!--Component III error message field-->
<div class ="err_msg" id="irr_sys_problems_errmsgfield">
<!--JQuery can not catch the tag, if beginning and ending tag in the same line--> 
</div>
</td>
<td>
<!--Component IV display list-->
<div id="irr_sys_problems_displaylist">
</div>
<!--Component V hidden input box-->
<input id="irr_sys_problems" name="irr_sys_problems"  value="<?php echo $eval->getProperty('irr_sys_problems'); ?>" />
<td>
</tr>
<!--End of multiple choices Component Irrigation System Problems-->

</table>
</fieldset>
<script type="text/javascript">
/************************ Beginning of Javacript of Annual Water Use Section**********/

/* PART 1. Page Load
* if value in input box != 0, check corresponding checkbox and show input box
* else uncheck corresponding checkbox and hide input box
*/
$(".checkinputcombo:input[type=text]").each(
function(){
var parent = $(this).parent();
//parent xia de checkbox
var checkbox = $(':checkbox',parent);
if($(this).val() != null && $(this).val() != ''&& $(this).val()!='NA'){
$(this).show();
checkbox.attr('checked',true);
}else{
$(this).hide();
$(this).val('');
checkbox.attr('checked',false);
}
}
);
/*
* PART 2. When check or uncheck checkbox
* check, show input box
* uncheck, hide input box and set input box value = 0*/
$(".checkinputcombo:checkbox").click(
function(){
var parent = $(this).parent();
var text = $('input[type=text]',parent);
if($(this).attr('checked')){
text.show('slow');
}else{
text.hide('slow');
text.val('');
}
}
)
/**********************End of Annual Water Use Section*************************/


var irr_sys_problems_multiple_choices = new MultipleChoicesList('irr_sys_problems','../../images/iDelete.gif');
irr_sys_problems_multiple_choices.load();

var eval_funding_sources_multiple_choices = new MultipleChoicesList('eval_funding_sources','../../images/iDelete.gif');
eval_funding_sources_multiple_choices.load();
var ag_types_multiple_choices = new MultipleChoicesList('crop_category','../../images/iDelete.gif');
ag_types_multiple_choices.load();

</script>