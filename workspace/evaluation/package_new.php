<?php
/*
 * Users can create a new package in this page
 * Different forms for 2 kinds of billing cycle MIL Labs
 * Form Validation on both client and server side.
 */
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
session_start();
$labs_arr = $_SESSION['MILLabs'];
/*Get all the lab id concated by "," of which the billing cycle is 1 month*/
$labs_1month = '';
foreach ($labs_arr as $key => $val) {
    if ($val->getProperty('billing_cycle') == 1) {
        $labs_1month .= ',' . $val->getProperty('mil_id');
    }
}
$labs_1month = substr($labs_1month, 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Add More Packages</title>
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <meta name="robots" content="nofollow" />
    </head>

    <body id="login">
        <div id="Wrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>
            <div id="contentWrap">
                <h2>New Package</h2>
                <form id="new_package_form" action="./control_evaluation.php" method="post">
                    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                        <tr>
                            <td align="right">MIL Lab Name: </td>
                            <td>
                                <select name="mil_id" id="mil_id">
                                    <!--JQuery Form Validation default is "" not 0 -->
                                    <option value="" selected>Choose one</option>
                                    <?php
                                    foreach ($labs_arr as $key => $val) {
                                        echo '<option value="' . $val->getProperty('mil_id') . '">';
                                        echo $val->getDisplayName();
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr id="eval_year_area">
                            <td align="right">Calendar Year of Evaluation:</td>
                            <td><select id="eval_yr" name="eval_yr">
                                    <option value="" selected>Choose one</option>
                                    <?php
                                    $begin = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION_FROM_1990;
                                    $end = END_OF_CALENDAR_YEAR_OF_EVALUATION;
                                    for ($i = $begin; $i <= $end; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr id="eval_month_area">
                            <!-- INSERT BY JAVASCRIPT-->
                        </tr>


                        <tr>
                            <td align="right"></td>
                            <td>
                                <div class="form-btns">
                                    <input  class="button" name="new_package" id ="add_button" type="submit" value="Add" tabindex="3" />
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
                <span class="clearing"></span>
            </div>
            <div id="sponsorLogos">
                <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>
        </div>
        <script type="text/javascript">
            
            
            var mil_id = $('#mil_id  option:selected').val();
            if(mil_id==""){
                $('#eval_year_area').hide();
                $('#eval_month_area').hide();
                $('#add_button').hide();
            }
            var eval_months = 
                '<td align="right">Calendar Months of Evaluation:</td>'+
                '<td>'+
                '<select id="eval_months" name="eval_months">'+
                '<option value="" selected>Choose one</option>'+
                '<option value="1-3">Jan-Mar</option>'+
                '<option value="4-6">Apr-Jun</option>'+
                '<option value="7-9">Jul-Sep</option>'+
                '<option value="10-12">Oct-Dec</option>'+
                '</select>'+
                '</td>';
            var eval_month = '<td align="right">Calendar Month of Evaluation:</td>'+
                '<td>'+
                '<select id="eval_months" name="eval_months">'+
                '<option value="" selected>Choose one</option>'+
                '<option value="1-1">Jan</option>'+
                '<option value="2-2">Feb</option>'+
                '<option value="3-3">Mar</option>'+
                '<option value="4-4">Apr</option>'+
                '<option value="5-5">May</option>'+
                '<option value="6-6">Jun</option>'+
                '<option value="7-7">Jul</option>'+
                '<option value="8-8">Aug</option>'+
                '<option value="9-9">Sep</option>'+
                '<option value="10-10">Oct</option>'+
                '<option value="11-11">Nov</option>'+
                '<option value="12-12">Dec</option>'+
                '</select>'+
                '</td>'
            $('#mil_id').change(
            function(){
                var mil_id = $('#mil_id  option:selected').val();
                var mil_id_cycle_one_month = [<?php echo $labs_1month; ?>];
                var length = mil_id_cycle_one_month.length;
                var is_one_month = false;
                for(var i = 0; i < length; i++){
                    var cur_id = mil_id_cycle_one_month[i];
                    if(mil_id==cur_id){
                        is_one_month = true;
                        break;
                    }
                }
                if(!is_one_month){
                    $('#eval_year_area').show();
                    $('#eval_month_area').html(eval_months);
                    $('#eval_month_area').show();
                    $('#add_button').show();
                }else{
                    $('#eval_year_area').show();
                    $('#eval_month_area').html(eval_month);
                     $('#eval_month_area').show();
                    $('#add_button').show();
                }

            }
        )
       /*
       $("#add_button").click(
       function(){
           var eval_yr = $("#eval_yr").val();
           if(eval_yr=='9999'){
               $("#err_msg").html('Evaluation Year is required!')
           }
           var eval_yr = $("#eval_months").val();
           if(eval_yr=='9999'){
               $("#err_msg").html('Evaluation Year is required!')
           }
           return false;
       });*/

$('#add_button').click(function(){
$.validator.messages.required = "{1} is required.";
$.validator.messages.number = "{1} must be a number.";
$.validator.messages.min = "The field should be greater or equal to {0}.";
$.validator.messages.max = "The field should be less or equal to {0}.";
var names = {
eval_yr: 'Evaluation Year'
,eval_months: 'Evaluation Month'
,mil_lab: 'MIL Lab'
};
$("#new_package_form").validate({
 rules: {
  eval_yr:{
  required:[true,names.eval_yr]
  },
  eval_months:{
   required: [true,names.eval_months]
  }, 
  mil_lab:{
   required: [true,names.mil_lab]
  }
 }
});
})


        </script>
    </body>
</html>