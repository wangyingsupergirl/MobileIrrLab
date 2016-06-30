<?php
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
session_start();

if (array_key_exists('PackageObject', $_SESSION)) {
    $package = $_SESSION['PackageObject'];
    if(array_key_exists('MILLabs',$_SESSION)){
        $Labs = $_SESSION['MILLabs'];
    }else{
        echo 'MIL Labs is not in the session';
        exit;
    }
    if(array_key_exists('MemberServed',$_SESSION)){
        $MemberServed = $_SESSION['MemberServed'];
    }else{
        echo 'MIL MemberServed is not in the session';
        exit;
    }
    $mil_id = $package->getProperty('mil_id');
    $mil_display_name = $Labs[$mil_id]->getDisplayName();
    $fq = new FiscalQuarter($package->getProperty('eval_yr'), $package->getProperty('eval_month'), $_SESSION['MemberServed']->getProperty('fiscal_standard'));
    $msg = false;
    if (array_key_exists('msg', $_GET)) {
        $msg = $_GET['msg'];
    }
} else {
    echo 'Error package informaiton is not in the session';
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign In</title>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
        <script src="../../js/util.js" type="text/javascript"></script>
        <meta name="robots" content="nofollow" />

    </head>

    <body id="login">
        <div id="largeWrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>
            <form action="control_evaluation.php" method="post">
                <div id="largeContentWrap">
                    <span style="color: red">
                        <?php echo(array_key_exists('before', $_GET) ? 'The package has been created before.' : '') ?>
                    </span>
                    <h3><?php echo 'Package ID: <i>' . $package->getPackageName() . '</i>'; ?>  <?php echo 'MIL Name: <i>' . $mil_display_name . '</i>'; ?></h3>
                    <fieldset>
                        <div id="p1">
                            <table>
                                <tr>
                                    <td>
                                        <?php echo 'Fiscal Year from ' . $fq->getDisplayPref() . ' (' . $fq->getFiscalYr() . ' Quater: ' . $fq->getFiscalQtr() . ' ' . $fq->getFiscalDQtr() . ')'; ?>
                                    </td>
                                    <td><input class="button" type="submit" name="add_new_eval" value="Add Evaluation" style="margin: 0 10px;"/>
                                    </td>
                                     <td><input class="button" type="submit" name="preview_package" value="Preview"/>
                                    </td>
                                    <td><input class="button" type="submit" name="back_to_workspace" value="Back"/>
                                    </td>
                                </tr>
                            </table>
                            <div id="msg"><?php echo $msg ?></div>
                            <table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
                                <tbody>
                                    <tr bgcolor="#fcfdf3">
                                        <td align="center" width="5%">&nbsp;</td>
                                        <td width="20%">&nbsp;ID</td>
                                        <td width="8%">&nbsp;ID used by MIL</td>
                                        <td width="8%">&nbsp;Evaluation Type</td>
                                        <td width="14%">&nbsp;Evaluation Method</td>
                                        <td width="10%">&nbsp;Status</td>
                                        <td width="15%">&nbsp;Date Modified</td>
                                        <td width="25%">&nbsp;Operations</td>
                                    </tr>
                                    <?php
                                    $index = 1;
                                    $boolean = $package->hasEvals();
                                    if ($boolean == true) {
                                        $eval_list = $package->getEvalList();
                                        foreach ($eval_list as $id => $eval) {
                                            $eval_type = $eval->getProperty('eval_type');
                                            $table_name = 'eval_types_water_saving_types';
                                            $table = Utility::getLookupTable($table_name, null);

                                            $tuple = $_SESSION[$table_name][$eval_type];
                                            $eval_type = $tuple->getProperty('evaluation_type');
                                            $btns = new Buttons($eval,$MemberServed->getProperty('role'));
                                            echo
                                            '<tr bgcolor="#eeeeee">' .
                                            '<td align="center">' . $index . '</td>' .
                                            '<td>Evaluation ' . $eval->getProperty('id') . '</td>' .
                                            '<td>' . $eval->getProperty('display_id') . '</td>' .
                                            '<td>&nbsp;' . $eval_type . '</td>' .
                                            //To be modify
                                            '<td>&nbsp;' . ($eval->getProperty('eval_method') == 'irr' ? 'Irrigation System' : 'Firm') . '</td>' .
                                            '<td>&nbsp;' . $eval->getProperty('status') . '</td>' .
                                            '<td>&nbsp;' . $eval->getLastModifiedTime() . '</td>' .
                                            '<td>&nbsp;' .
                                            //'<input class="button" type="submit" name=edit_eval:' . $eval->getProperty('id') . ' value="Edit"/>' .
                                            //'<input class="button" type="button" name=delete_eval:' . $eval->getProperty('id') . ' value="Delete"/>'
                                            $btns->getString() .'</td>' .
                                            '</tr>';
                                            $index++;
                                        }
                                    }
                                    ?>
                                    <tr bgcolor="#eeeeee">
                                        <td align="center"><?php echo $index;
                                    $index++; ?></td>
                                        <td><a href=""> </a>Evaluation Waiting List</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>&nbsp;<?php echo ($package->getWaitingEvalList()==null? 'System Created':'Added')?></td>
                                        <td>&nbsp;<input class="button" type="submit" name="edit_waiting_list" value="Edit"/></td>
                                    </tr>
                                    <tr bgcolor="#fcfdf3">
                                        <td align="center"><?php echo $index;
                                            $index++; ?></td>
                                        <td><a href=""> </a>Conservation Education and Outreach Report</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>&nbsp;<?php echo ($package->getEducationReportList()==null? 'System Created':'Added')?></td>
                                        <td>&nbsp;<input class="button" type="submit" name="edit_education_report" value="Edit"/></td>
                                    </tr>
                                    <tr bgcolor="#fcfdf3">
                                        <td align="center">
                                            <?php echo $index;
                                                         $index++; ?>
                                        </td>
                                        <td>Contract</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>&nbsp;<?php echo ($package->getProperty('contract_id')?'Selected':'Not selected yet')?></td>
                                        <td>&nbsp;<input class="button" type="submit" name="select_package_contract" value="Edit"/></td>
                                    </tr>
                                    <tr bgcolor="#fcfdf3">
                                        <td align="center">
                                            <?php echo $index;
                                                         $index++; ?>
                                        </td>
                                        <td>Comments</td>
                                         <td colspan ="5"><?php echo $package->getProperty('comments')?></td>
                                         <td>&nbsp;<input class="button" type="submit" name="edit_package_comments" value="Edit"/></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </fieldset>
                    <fieldset>
                        <h3>Related Evaluations:</h3> 
                        Below evaluations don't belong to above Package.<br />

                        <?php
                        if ($boolean == true) {
                            ?>
                            <table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
                                <tbody>
                                    <tr bgcolor="#fcfdf3">
                                        <td align="center" width="10%">Initial Evaluation ID</td>
                                        <td width="10%">&nbsp;Evaluation Type</td>
                                        <td width="20%">&nbsp;Evaluation Method</td>
                                        <td width="20%">&nbsp;Last Modified</td>
                                        <td width="20%">&nbsp;Related to</td>
                                        <td width="20%">&nbsp;Operations</td>
                                    </tr>
                                    <?php
                                    $eval_list = $package->getEvalList();
                                    foreach ($eval_list as $id => $eval) {
                                        $init_eval = $eval->getProperty('initEval');

                                        if ($init_eval != false) {
                                            $eval_type = $init_eval->getProperty('eval_type');
                                            $btns = new Buttons($init_eval,$MemberServed->getProperty('role'));
//if eval and initial eval are in the same package, we don't need to display it;
                                            if ($eval->inSamePackage($init_eval)) {
                                                continue;
                                            }
                                            $tuple = $_SESSION['eval_types_water_saving_types'][$eval_type];
                                            $eval_type = $tuple->getProperty('evaluation_type');
                                            echo '<tr bgcolor="#eeeeee">' .
                                            '<td>' . $init_eval->getProperty('id') . '</td>' .
                                            '<td>&nbsp;' . $eval_type . '</td>' .
                                            '<td>&nbsp;' . ($init_eval->getProperty('eval_method') == 'irr' ? 'Irrigation System' : 'Firm') . '</td>' .
                                            '<td>&nbsp;' . $init_eval->getLastModifiedTime() . '</td>' .
                                            '<td> Evaluation: ' . $eval->getProperty('id') . '</td>' .
                                            '<td>'.$btns->getString().'</td>' .

                                            '</tr>';
                                        }
                                        
                                    }
                                    ?></tbody>
                            </table>
                        <?php } ?>
                    </fieldset> 
                    <fieldset>
                         <div id ="admin_comments">
                            <?php
                            $admin_comments = $package->getProperty('admin_comments');
                            if($admin_comments!=='NA'){
                                echo "Dispproved Reasons: $admin_comments";
                            }
                            ?>
                        </div>
                    </fieldset>
                </div>
            </form>
            <?php require_once dirname(__FILE__)."/../pop_up_delete_window.php"; ?>
           
        </div>
        <span class="clearing"></span>
        <div id="sponsorLogos">
            <p>Sponsored by the State of Florida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
        </div>
    </body>
</html>