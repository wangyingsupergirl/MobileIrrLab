<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/input/package/EducationReport.php';
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
session_start();
$package = $_SESSION['PackageObject'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - MIL Conservation Education and Outreach Report:</title>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <meta name="robots" content="nofollow" />
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
        <script src="../../js/multiple_choices_list.js" type="text/javascript"></script>
       <link type="text/css" href="../../js/jquery-ui/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
      <!--  <script type="text/javascript" src="../../js/jquery-ui/js/jquery-1.6.2.min.js"></script>
       --><script type="text/javascript" src="../../js/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script>
            $(function() {
                $( "#datepicker" ).datepicker({
                    changeMonth: true,
                    changeYear: true
                });
                $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
            });
        </script>

    </head>

    <body id="login">
        <div id="Wrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>

            <div id="contentWrap">

                <h2>MIL Conservation Education and Outreach Report:</h2>


                <form id="education_report_form" action="./control_evaluation.php" method="post" name="Login">
                    <fieldset>
                        <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm" style="margin-top: 20px;">
                            <tr>
                                <td align="right">Date*: </td>
                                <td><input name="presentation_date"   id="datepicker" /></td>
                            </tr>

                            <!--Begin of multiple choices Component Evaluation Funding Sources -->

                            <tr>
                                <td align="right">Type of Presentation*:</td>
                                <td>
                                    <!--Component I drop down list-->
                                    <select id="presentation_types_dropdownlist">
                                        <option value="" selected="selected">Choose one</option>
                                        <?php
                                        $table_name = 'presentation_types';
                                        Utility::printOptions($table_name, null);
                                        ?>
                                    </select>
                                    <!--Component II add button-->
                                    <input id="presentation_types_addbutton" type="button" value="Add" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <!--Component III error message field-->
                                    <div class ="err_msg" id="presentation_types_errmsgfield">
                                        <!--JQuery can not catch the tag, if beginning and ending tag in the same line-->
                                    </div>
                                </td>
                                <td>
                                    <!--Component IV display list-->
                                    <div id="presentation_types_displaylist">
                                    </div>
                                    <!--Component V hidden input box-->
                                    <input id="presentation_types" name="presentation_types" type="hidden" value="" />
                                </td>
                            </tr>

                            <!--End of multiple choices Component Evaluation Funding Sources-->



                            <tr>
                                <td align="right">Name of Group*:</td>
                                <td><input name="group_name" value="" type="text" size="20" maxlength="32"/></td>
                            </tr>

                            <tr>
                                <td align="right">Number Attending*:</td>
                                <td><input name="attending_num" value="" type="text" size="20" maxlength="10"/></td>
                            </tr>

                            <tr>
                                <td align="right">City or Town*:</td>
                                <td><input name="city" value="" type="text" size="20" maxlength="20"/></td>
                            </tr>
                            <tr>
                                <td align="right">Duration (hrs)*:</td>
                                <td><input name="duration" value="" type="text" size="20" maxlength="10"/></td>
                            </tr>

                            <tr>
                                <td align="right"></td>
                                <td>
                                    <div class="form-btns" style="margin: 5px 10px 0 0; float:left;">
                                        <input class="button" type="submit" id="add_eval_education" name="add_eval_education" value="Add More"/>
                                    </div>
                                    <div class="form-btns" style="margin: 5px 10px 0 0; float:left;">
                                        <input class="button" type="submit" name="back_to_package" value="Back"/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <p style="clear:left"></p>
                    <script type="text/javascript">

                        var presentation_types_multiple_choices = new MultipleChoicesList('presentation_types','../../images/iDelete.gif');
                        presentation_types_multiple_choices.load();
                    </script>

                    <fieldset>
                        <table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
                            <tbody>
                                <?php
                                $boolean = $package->isEducationReportListEmpty();
                                if ($boolean != false) {
                                    echo '<tr bgcolor="#fcfdf3">
            <td align="center" width="5%">&nbsp;</td>
            <td width="20%">&nbsp;Date</td>
            <td width="10%">&nbsp;Presentation</td>
            <td width="15%">&nbsp;Group</td>
            <td width="15%">&nbsp;# of Attending</td>
            <td width="20%">&nbsp;Location</td>
            <td width="10%">&nbsp;Duration</td>
             <td width="10%">&nbsp;Operations</td>
            </tr>';
                                    $i = 1;
                                    $nodes = $package->getEducationReportList();
                                    foreach ($nodes as $key => $node) {
                                        echo '<tr bgcolor="#eeeeee">
            <td>' . $i . '</td>
            <td>
            <p>' . $node->getProperty('presentation_date') . '</p></td>
            <td>' . $node->getDisplayName('presentation_types') . '</td>
            <td>' . $node->getProperty('group_name') . '</td>
            <td>' . $node->getProperty('attending_num') . '</td>
            <td>' . $node->getProperty('city') . '</td>
            <td>' . $node->getProperty('duration') . '</td>
            <td><input class="button" type="submit" name="delete_eval_education:' . $node->getId() . '" value="Delete" /></td>
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
            <span class="clearing"></span>
            <div id="sponsorLogos">
                <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>

        </div>
<script>
    $('#add_eval_education').click(function(){
$.validator.messages.required = "{1} is required.";
$.validator.messages.number = "{1} must be a number.";
$.validator.messages.min = "The field should be greater or equal to {0}.";
$.validator.messages.max = "The field should be less or equal to {0}.";

var names = {
presentation_date :  "Date"
,presentation_types :  "Type of Presentation"
,group_name : "Name of Group"
,attending_num : "Number Attending"
,city : "City or Town"
,duration: "Duration (hrs)"
};

$("#education_report_form").validate({
rules: {
presentation_date:{
    required: [true,names.presentation_date]
},
presentation_types:{
    required: [true,names.presentation_types]
},
group_name:{
    required:[true,names.group_name]
},
attending_num:{
    required:[true,names.attending_num]
},
city:{
    required:[true, names.city]
},
duration:{
    required:[true, names.duration],
    number:[true,names.duration],
    min: 0
}

}
});

})
</script>
    </body>
</html>

