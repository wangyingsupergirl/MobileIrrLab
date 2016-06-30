<?php
require_once dirname(__FILE__) . '/../../includes/mil_init.php';
require_once dirname(__FILE__) . '/../../includes/utility.php'; //required constant.php in utility.php
session_start();
$member = false;
if (array_key_exists('memberReviewed', $_SESSION)) {
    $member = $_SESSION['memberReviewed'];
} else {
    echo 'Error no memberReviewed para in session';
    exit;
}
Utility::getLookupTable("partner");
Utility::getLookupTable("report");
$authorized_reports = $member->getPropertyByType('reports_id','array');

?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign Up</title>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
        <script src="../../js/multiple_choices_list.js" type="text/javascript"></script>
    </head>
    <body id='login'>
        <div id="largeWrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>

            <div id="largeContentWrap">
                <h2>MIL Partner</h2>
                <div class="errMsg">
                    <?php
                    if (array_key_exists('err', $_GET)) {
                        echo $_GET['err'];
                    }
                    ?>
                </div>
                <div class="msg">
                    <?php
                    if (array_key_exists('msg', $_GET)) {
                        echo $_GET['msg'];
                    }
                    ?>
                </div>

                <form action="./control.php" id ="partner_report_form" method="post" name="Login">
                    <fieldset>	
                        *Partner Name: <?php
                    $partner_id = $member->getProperty("partner_name");
                    $partner = $_SESSION["partner"][$partner_id];
                    echo $partner->getProperty("name");
                    ?>
                        <table>
                            <tr>
                                <td colspan="3">

                                    <?php
                                    foreach ($_SESSION['report'] as $id => $report) {
                                        echo '<div>
                                                        <input name="report_id:' . $report->getProperty("id") . '" value ="' . $report->getProperty("id") . '" type="checkbox" class="checkBox"';
                                         if($authorized_reports!=null && in_array($report->getProperty("id") ,$authorized_reports)){
                                                echo 'checked';
                                        }
                                        echo '>';
                                        echo $report->getProperty("id") . ":" . $report->getProperty("name") . '</div>';

                                        echo '</div>';
                                    }
                                    ?>
                            </td>
                            </tr>
                            <tr>
                                <td colspan ="2"><input class="button" name="save_partner_report_list" value="Save" type="submit"/>
                                    <input class="button" type="submit" name="back_to_member_list" value="Back"/>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </form>
            </div>
            <span class="clearing"></span>
            <div id="sponsorLogos">
                <p>Sponsored by the State of Florida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>

        </div>

    </body>
</html>
