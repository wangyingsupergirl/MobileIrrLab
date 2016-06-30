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
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>State of Florida Mobile Irrigation Lab (MIL) Program - Review Package</title>
<link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
<script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
<script src="../../js/jquery-validate/jquery.validate.js" type="text/javascript"></script>
<meta name="robots" content="nofollow" />
</head>

<body id="login">
<div id="largeWrapper">
    <div id="header"><h1>State of Florida</h1><p>Mobile Irrigation Laboratory Program</p></div>
    
    <form action="control_evaluation.php" method="post">
        <div id="largeContentWrap">
        <h3>Package ID: <i><?php  echo $package->getPackageName(); ?></i>  MIL Name: <i><?php echo $mil_display_name;?></i></h3>
            <fieldset>
            <div id="p1">
                <?php echo 'Fiscal Year: '. $fq->getFiscalYr() . ', Quater: ' . $fq->getFiscalQtr() . ', Months: ' . $fq->getFiscalDQtr(); ?>
                <table border="0" cellpadding="0" cellspacing="0" class="subForms" style="margin-left: 0;">
                    <tbody>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">&nbsp;</td>
                        <td width="80%">&nbsp;Name</td>
                        <td width="10%">&nbsp;Operations</td>
                        </tr>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">1</td>
                        <td>&nbsp;IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS DATA AND RESULTS, PER MIL HANDBOOK</td>
                        <td>&nbsp;<input class="button" name="review_package_details:11a" value="Download" tabindex="3" type="submit"/></td>
                        </tr>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">2</td>
                        <td>&nbsp;IRRIGATION SYSTEM WATER SOURCE, PUMPING STATION, AND OTHER  INFO</td>
                        <td>&nbsp;<input class="button" name="review_package_details:11b" value="Download" tabindex="3" type="submit"/></td>
                        </tr>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">3</td>
                        <td>&nbsp;TRACKING TABLE FOR INITIAL EVALUATIONS, FOLLOW UP EVALUATIONS, OR REPLACEMENTS </td>
                        <td>&nbsp;<input class="button" name="review_package_details:11c" value="Download" tabindex="3" type="submit"/></td>
                        </tr>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">4</td>
                        <td>&nbsp;MIL EVALUATION WAITING LIST </td>
                        <td>&nbsp;<input class="button" name="review_package_details:7" value="Download" tabindex="3" type="submit"/></td>
                        </tr>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">5</td>
                        <td>&nbsp;MIL Conservation Education and Outreach Report</td>
                        <td>&nbsp;<input class="button" name="review_package_details:8" value="Download" tabindex="3" type="submit"/></td>
                        </tr>
                        <tr bgcolor="#fcfdf3">
                        <td align="center" width="5%">6</td>
                        <td>&nbsp;CONDENSED QUARTERLY REPORT FORM
                        AGRICULTURAL MOBILE IRRIGATION LABS
                        </td>
                        <td>&nbsp;<input class="button" name="review_package_details:9" value="Download" tabindex="3" type="submit"/></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            </fieldset>

            <fieldset>
                <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                    <tr><td colspan='2'></td></tr>
                    <tr>
                    <td align="right">Comment Box*: </td>
                    <td>
                    <textarea id="admin_comments" name="admin_comments" COLS="80" ROWS="2" maxlength="500"></textarea>
                    <br />Maximum characters: 500<br />
                    You have <input type="text" id="countdown" size="3" value="500"/> characters left.
                    </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input class="button" name="approve_package" value="Approve" type="submit"/>
                        <input class="button" name="disapprove_package" value="Disapprove" type="submit"/>
                        <input class="button" type="submit" name="back_to_workspace" value="Cancel"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>

    </form>
</div>

<span class="clearing"></span>
<div id="sponsorLogos">
<p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
</div>
</body>
</html>