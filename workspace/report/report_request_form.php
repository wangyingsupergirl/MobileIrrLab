<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
//ini_set('display_errors', 1);
session_start();
$labs_arr = $_SESSION['MILLabs'];
$method = $_SESSION['Method'];
$report_id = $_GET['report_id']
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Report 11a Request Form</title>
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

                <?php
 				if ($report_id == '6a' || $report_id == '12a' || $report_id == '13a' || $report_id == '14a')
                echo "<h2>Report {$report_id} Request Form - First Follow Up Evaluation Date Range</h2>";
                else if ($report_id == '6b')
                echo "<h2>Report 6b Request Form - Latest Follow Up Evaluation Date Range</h2>";
                else if ($report_id == '12b' || $report_id == '12b' || $report_id == '13b' || $report_id == '14b')
                echo "<h2>Report {$report_id} Request Form - Initial Evaluation Date Range</h2>";
                else if ($report_id == '6c' || $report_id == '12c' || $report_id == '13c' || $report_id == '14c')
                echo "<h2>Report {$report_id} Request Form - Latest Follow Up Evaluation Date Range</h2>";
                else if ($report_id == '6d' || $report_id == '12d' || $report_id == '13d' || $report_id == '14d')
                echo "<h2>Report {$report_id} Request Form - Replacement Evaluation Date Range</h2>";
                else if ($report_id == '3')
                echo "<h2>Report 3 Request Form - Initial Evaluation Date Range</h2>";
                else
                echo "<h2>Report {$report_id} Request Form</h2>";
                ?>

                <form id="request_report" action="control.php" method="post">

                    <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">

                        <?php
                        if ($report_id != '1a' && $report_id != '2' && $report_id != '4b' && $report_id != '6a' && $report_id != '6b' && $report_id != '6c' && $report_id != '6d' && $report_id != '12a'
                        && $report_id != '12b' && $report_id != '12c' && $report_id != '3' && $report_id != '12d' && $report_id != '13d' && $report_id != '13c' && $report_id != '14d' && $report_id != '14c' && $report_id != '13a' && $report_id != '13b' && $report_id != '14a' && $report_id != '14b') {
                        ?>
                        <tr>
                            <td align="right">MIL Lab Name: </td>
                            <td>
                                <select name="mil_id" id="mil_id">
                                    <!--JQuery Form Validation default is "" not 0 -->
                                    <option value="" selected="selected">Choose one</option>
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
                            <?php } ?>
                        <tr>
                            <?php
                            if ($report_id == '7') {
                            echo "<td align='right'>Calendar Year of Evaluation:</td>
                                      <td><select id='start_yr' name='cal_start_yr'>";
                            } else if ($report_id == '9') {
                            echo "<td align='right'>FDACS Fiscal Year of Evaluation:</td>
                                      <td><select id='start_yr' name='cal_start_yr'>";

                            /*
                              echo "<td align='right'>FDACS Year of Evaluation From:</td>
                              <td><select id='start_yr' name='cal_start_yr'>"; */

                            } else if ($report_id == '1a' || $report_id == '2' || $report_id == '4b' || $report_id == '3' || $report_id == '6a' || $report_id == '6b' || $report_id == '6c' || $report_id == '6d' || $report_id == '12a' || $report_id == '12b' || $report_id == '12c'
                            || $report_id == '12d' || $report_id == '13a' || $report_id == '13b' || $report_id == '14a' || $report_id == '14b' || $report_id == '13d' || $report_id == '13c' || $report_id == '14d' || $report_id == '14c') {
                                echo "<td align='right'>Calendar Year of Evaluation From:</td>
                                      <td><select id='start_yr' name='cal_start_yr'>";
                
                            } else {

                                echo "<td align='right'>Calendar Year of Evaluation From:</td>
                                      <td><select id='start_yr' name='cal_start_yr'>";
                            }
                            ?>
                            <option value="" selected="selected">Choose one</option>
                            <?php
                            if ($report_id != 9) {
                                //if ($report_id == '1a' || $report_id == '2' || $report_id == '4b' || $report_id == '6a'||$report_id == '6b'|| $report_id == '6c' || $report_id == '6d') {
                                $begin = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION;
                                $end = END_OF_CALENDAR_YEAR_OF_EVALUATION;
                                for ($i = $begin; $i <= $end; $i++) {
                                    echo "<option value='$i'>$i  </option>";
                                }
                                //}
                            } else {
                                $begin = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION;
                                $end = END_OF_CALENDAR_YEAR_OF_EVALUATION;
                                for ($i = $begin; $i <= $end; $i++) {
                                    $j = $i + 1;
                                    echo "<option value='$i'>$i - $j </option>";
                                }
                            }
                            ?>
                            </select>

                            <?php
                            if ($report_id != '9' && $report_id != '7') {
                                //if ($report_id == '1a' || $report_id == '2' || $report_id == '4b' || $report_id == '6a'||$report_id == '6b'|| $report_id == '6c' || $report_id == '6d') {
                                echo "To: <select id = 'end_yr' name= 'cal_end_yr'>";
                                echo "<option value='' selected='" . "selected'>Choose one</option>";
                                $begin = BEGINNING_OF_CALENDAR_YEAR_OF_EVALUATION;
                                $end = END_OF_CALENDAR_YEAR_OF_EVALUATION;
                                for ($i = $begin; $i <= $end; $i++) {
                                    echo "<option value='$i'>$i  </option>";
                                }

                                echo "</select>";
                            }
                            // }
                            ?> 
                            </td>      
                        </tr>
                        <tr>
                            <?php
                            /*
                              if ($report_id == '1a' || $report_id == '2' || $report_id == '4b' || $report_id == '6a'||$report_id == '6b'|| $report_id == '6c' || $report_id == '6d') {
                              echo "<td align='right'>Calendar Month of Evaluation From:</td>
                              <td><select  name='cal_start_month' id ='cal_start_month'>";
                              }else if ($report_id == '9'){

                              echo "<td align='right'>FDACS Month of Evaluation From:</td>
                              <td><select  name='cal_start_month' id='cal_start_month'>";

                              }else{

                              echo "<td align='right'>Calendar Month of Evaluation From:</td>
                              <td><select name='cal_start_month' id='cal_start_month'>";

                              } */
                            if ($report_id == '7') {
                                echo "<td align='right'>Calendar Month Period of Evaluation:</td>
                                       <td><select id='quarter' name='fed_quarter'>";
                                echo '<option value="" selected>Choose one</option>
                                    <option value="2" >January to March</option>
                                    <option value="3" >April to June</option>
                                    <option value="4" >July to September</option>
                                    <option value="1" >October to December</option>
                                    </select></td>';
                            } else if ($report_id == '9') {
                                echo "<td align='right'>From:</td>
                                      <td><select id='quarter9from' name='quarter9from'>";
                                echo '<option value="" selected>Choose one</option>
                                    <option value="1" >July of Fiscal Year(Qtr1)</option>
                                    <option value="2" >October of Fiscal Year(Qtr2)</option>
                                    <option value="3" >January of Fiscal Year(Qtr3)</option>
                                    <option value="4" >April of Fiscal Year(Qtr4)</option>
                                    </select></td></tr>';

                                echo "<tr><td align='right'>To:</td>
                                       <td><select id='quarter9to' name='quarter9to'>";
                                echo '<option value="" selected>Choose one</option>
                                    <option value="1" >September of Fiscal Year(Qtr1)</option>
                                    <option value="2" >December of Fiscal Year(Qtr2)</option>
                                    <option value="3" >March of Fiscal Year(Qtr3)</option>
                                    <option value="4" >June of Fiscal Year(Qtr4)</option>
                                    </select></td></tr>';
                            } else {
                                echo "<td align='right'>Calendar Month of Evaluation From:</td>
                                      <td><select name='cal_start_month' id='cal_start_month'>";
                                $start_month = 1;
                                $end_month = 12;
                                for ($i = $start_month; $i <= $end_month; $i++) {
                                    echo "<option value='$i' ";

                                    $month_name = Utility::getMonthName($i);
                                    echo ">$month_name</option>";
                                }
                                echo "</select> ";
                                echo "To: <select  name='cal_end_month' id='cal_end_month'>";
                                for ($i = $start_month; $i <= $end_month; $i++) {
                                    echo "<option value='$i' ";

                                    $month_name = Utility::getMonthName($i);
                                    echo ">$month_name</option>";
                                }
                                echo "</select>";
                                echo "</td>";
                            }
                            ?>
                        </tr>
                       <tr> 
<?php
if ($report_id == '12a' || $report_id == '12b' || $report_id == '12d'|| $report_id == '12c' || $report_id == '13a' || $report_id == '13b' || $report_id == '14a' || $report_id == '14b' || $report_id == '13d' || $report_id == '13c' 

|| $report_id == '14d' || $report_id == '14c' || $report_id == '6c' || $report_id == '6d' || $report_id == '6a' || $report_id == '6b' || $report_id == '1a' || $report_id == '2' || $report_id = '4b')
{

$eval_method_array = array('irr' => 'Irrigation System Only', 'firm' => 'Farm Irrigation Rating Method (FIRM/FIRI)');
echo "<td align='right'>Evaluation Method:</td>
       <td><select id='method' name = 'eval_method'>";
      echo '<option value="" selected>Choose one</option>';
      foreach ($method as $key => $value){
      	$eval_method = $eval_method_array[$value['eval_method']];
      	echo "<option value= $value[eval_method] >$eval_method</option>";
      }
     echo '<option value ="both" >Both</option>';
     echo "</select>";
     echo "</td>";
     }
?>
</tr>
                        <tr>
                            <td align="right"></td>
                            <td>
                                <div class="form-btns">
                                    <input  class="button" id='request' name="request_report:<?php echo $report_id; ?>" type="submit" value="Request" tabindex="3" />
                                    <input  class="button" name="back_to_reports_list" id ="back_to_reports_list" type="submit" value="Back" tabindex="3" />
                                    <!--<input  class="button" id='request_html' name="request_html:<?php echo $report_id; ?>" type="submit" value="Request HTML" tabindex="3" />-->
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
        <script>
            $('#request').click(function () {
                $.validator.messages.required = "{1} is required.";
                $.validator.messages.number = "{1} must be a number.";
                //$.validator.messages.required = "{4} is required.";
                $.validator.messages.min = "The field should be greater or equal to {0}.";
                $.validator.messages.max = "The field should be less or equal to {0}.";

                $.validator.addMethod("QuarterFromToCheck", function (value, element) {
                    return parseInt(value) >= parseInt($("#quarter9from").val());


                }, "From Qtr can not be greatter than To Qtr");

                var names = {
                    mil_id: 'MIL Lab Name'
                    , start_yr: 'Year of Evaluation'
                    , start_month: 'Month of Evaluation'
                    , quarter: 'Quarter of Evaluation'
                    , eval_method: 'Evaluation Method'
                };

                $("#request_report").validate({
                    rules: {
                        quarter9to: {
                            QuarterFromToCheck: true,
                            required: [true, names.quarter]
                        },
                        quarter9from: {
                            required: [true, names.quarter]
                        },
                        mil_id: {
                            required: [true, names.mil_id]
                        },
                        cal_start_yr: {
                            required: [true, names.start_yr]
                        },
                        cal_end_yr: {
                            required: [true, names.start_yr]
                        },
                        cal_end_month: {
                            required: [true, names.start_month]
                        },
                        cal_start_month: {
                            required: [true, names.start_month]
                        },
                        eval_method:{
                            required: [true, names.eval_method]
                        },
                        fdacs_quarter: {
                            required: [true, names.quarter]
                        },
                        fed_start_yr: {
                            required: [true, names.start_yr]
                        },
                        fed_quarter: {
                            required: [true, names.quarter]
                        }
                    }
                });

            })

            $('#request_html').click(function () {
                $.validator.messages.required = "{1} is required.";
                $.validator.messages.number = "{1} must be a number.";
                $.validator.messages.min = "The field should be greater or equal to {0}.";
                $.validator.messages.max = "The field should be less or equal to {0}.";

                var names = {
                    mil_id: 'MIL Lab Name'
                    , start_yr: 'Year of Evaluation'
                   , eval_method: 'Evaluation Method'
                    ,quarter: 'Quarter of Evaluation'
                };

                $("#request_report").validate({
                    rules: {
                        quarter9from: {
                            QuarterFromToCheck: true,
                            required: [true, names.quarter]
                        },
                        quarter9to: {
                            required: [true, names.quarter]
                        },
                        mil_id: {
                            required: [true, names.mil_id]
                        },
                        fdacs_start_yr: {
                            required: [true, names.start_yr]
                        },
                        start_yr: {
                            required: [true, names.start_yr]
                        },
                        eval_method: {
                             required: [true, names.eval_method]
                       },
                        fdacs_quarter: {
                            required: [true, names.quarter]
                        },
                        fed_start_yr: {
                            required: [true, names.start_yr]
                        },
                        fed_quarter: {
                            required: [true, names.quarter]
                        }
                    }
                });

            })
        </script>
    </body>
</html>