<?php
require_once dirname(__FILE__).'/../../includes/utility.php'; 
require_once dirname(__FILE__).'/../../includes/input/package/WaitEval.php'; 
require_once dirname(__FILE__).'/../../includes/input/package/Package.php'; 
session_start();
unset($_SESSION['fl_county']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign In</title>
<link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
<meta name="robots" content="nofollow" />
<script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
<script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
</head>
<body id="login">
<div id="largeWrapper">
<div id="header">
<h1>State of Florida</h1>

<p>Mobile Irrigation Laboratory Program</p>
</div>

<div id="largeContentWrap">
<h2>MIL Evaluation Waiting List:</h2>

<form id="waiting_list_form" action="./control_evaluation.php" method="post">
<fieldset>
<table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
<tr>
<td align="right">County Name*: </td>
<td>
<select id="county_id" name="county_id">
<?php 

$package = $_SESSION['PackageObject'];
$table_name = 'fl_county';
Utility::printOptions($table_name, $package->getProperty('mil_id'));


?>
</select>
</td>
</tr>

<tr>
<td align="right">Crop Name*:</td>
<td> 
<select id="category_id" name="category_id">
            <?php
$table_name = 'ag_urban_types_names';

Utility::setLookupTableToSession($table_name,null);
Utility::printOptions($table_name,null);
?>
</select></td>
</tr>

<tr>
<td align="right">Total Count*:</td>
<td><input id="total_count" name="total_count" value="" type="text" size="20" maxlength="32" /></td>
</tr>

<tr>
<td align="right">Approx Total Acres*:</td>
<td><input  id="total_acres" name="total_acres" value="" type="text" size="20" maxlength="10" /></td>
</tr>

<tr>
<td align="right"></td>
<td><div class="form-btns" style="margin: 20px 10px 0 0; float:left;">
<input class="button" type="submit" id="add_waiting_eval"  name="add_waiting_eval" value="Add More" />
</div>
<div class="form-btns" style="margin: 20px 10px 0 0; float:left;">
<input class="button" type="submit" name="back_to_package" value="Back" />
</div>

</td>	


</tr>
</table>
</fieldset>

<p style="clear:left"></p>


<fieldset>
<table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
<tbody>
<?php 


$package = $_SESSION['PackageObject'];
$boolean = $package->isWaitingEvalListEmpty();
if($boolean!=false){
echo '<tr bgcolor="#fcfdf3">
    <td align="center" width="10%">&nbsp;</td>
    <td width="20%">&nbsp;County</td>
    <td width="20%">&nbsp;Category</td>
    <td width="20%">&nbsp;Total Count</td>
    <td width="20%">&nbsp;Approx Total Acres</td>
    <td width="10%">&nbsp;Action</td>
  </tr>';
$i = 1;
$waiting_list = $package->getWaitingEvalList();
foreach( $waiting_list as $key => $wait_eval){
    echo '<tr bgcolor="#eeeeee">
            <td align="center" width="10%">'.$i.'</td>
            <td width="20%">
             '.$wait_eval->getProperty('county_id').'</td>
            <td width="20%">'.$wait_eval->getProperty('category_id').'</td>
            <td>'.$wait_eval->getProperty('total_count').'</td>
            <td>'.$wait_eval->getProperty('total_acres').'</td>
            <td><input class="button" type="submit" name="delete_waiting_eval:'.$wait_eval->getProperty('id').'" value="Delete" /></td>
            </tr>
    ';
    $i++;
}

}

?>

</tbody>
</table>
</fieldset>
</form>
</div>
<script>
$('#add_waiting_eval').click(function(){
$.validator.messages.required = "{1} is required.";
$.validator.messages.number = "{1} must be a number.";
$.validator.messages.min = "The field should be greater or equal to {0}.";
$.validator.messages.max = "The field should be less or equal to {0}.";

var names = {
total_count: 'Total Count'
,total_acres: 'Approx Total Acres'
};

$("#waiting_list_form").validate({
rules: {
total_count:{
    required: [true,names.total_count],
    number:[true,names.total_count],
    min: 0
},
total_acres:{
    required: [true,names.total_acres],
    number:[true, names.total_acres],
    min: 0
}
}
});

})
</script>
<span class="clearing"></span>
<div id="sponsorLogos">
    <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
</div>

</div>


</body>
</html>