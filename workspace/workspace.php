<?php
require_once dirname(__FILE__) . '/../includes/mil_init.php';
require_once dirname(__FILE__) . '/../includes/utility.php';
/* * **************Log**************************** */
$page_start = microtime(true);
session_start();
if (array_key_exists('MemberServed', $_SESSION)) {
    /*
     * Parameters below will be used by all the pages included by workspace.
     * To distinguish with other parameters, the first letter of each word is capitalized.
     *
     */
    $MemberServed = $_SESSION['MemberServed'];
    $MemberServedRole = $MemberServed->getProperty('role');
    $MemberServedStatus = $MemberServed->getProperty('status');
    $FiscalStandard = $MemberServed->getProperty('fiscal_standard');
    $Packages = Utility::getAllPackage($MemberServed);
    $Labs = Utility::getAllLab($MemberServed);
    $Method = Utility::getAllMethod($MemberServed);
    $_SESSION['MILLabs'] = $Labs;
    $_SESSION['Packages'] = $Packages;
    $_SESSION['Method'] = $Method;
    //$_SESSION['Method_name'] = array('irr' => 'Irrigation System Only', 'firm' => 'Farm Irrigation Rating Method (FIRM/FIRI)', "irr' or 'firm" => 'Irrigation System Only AND Farm Irrigation Rating Method (FIRM/FIRI)');
    
} else {
    echo 'Login First! No member information in the session';
    exit;
}
/* * **************Log**************************** */
$query_end = microtime(true);
//$log->logInfo('Profile: data query end ' . $time_end);
$time = $query_end- $page_start;
$kLog->logInfo('Data Query Exec Time: ' . $time);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Administrator Workspace</title>
        <link rel="stylesheet" type="text/css" href="../styles/milStylesheet.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js"></script>
        <?php require_once 'head/head.php' ?>

    </head>

    <body class="soria">
        <!--Header Start here-->
        <div id="header">
            <h1></h1>
            <p><a style="display:inline;" href="../control_login.php?logout=1" class="logout">Logout</a></p>
        </div>
        <!--Content Start here-->
        <div id="main" dojotype="dijit.layout.BorderContainer" design="headline" persist="true" livesplitters="false">
            <!--Main Content starts here-->
            <div id="maincontent" dojotype="dijit.layout.TabContainer" tabposition="top" region="center">


                <?php
                $member = $MemberServed;
                if ($MemberServedStatus == 'approved') {
                    if ($MemberServedRole == ADMIN_ROLE) {

                        require_once dirname(__FILE__) . '/profile/admin_profile.php';
                        require_once dirname(__FILE__) . '/evaluation/evaluation.php';
                        require_once dirname(__FILE__) . '/report/reports_list.php';
                        $time_start = microtime(true);
                        require_once dirname(__FILE__) . '/member/member_list.php';
                        $time_end = microtime(true);
                        $time = $time_end - $time_start;
                        $kLog->logInfo('Member Tab Loading Time: ' . $time);
                        require_once dirname(__FILE__) . '/contract/contracts_list.php';
                        require_once dirname(__FILE__) . '/contractor/contractors_list.php';
                        require_once dirname(__FILE__) . '/lab/labs_list.php';
                        require_once dirname(__FILE__) . '/partner/partners_list.php';
                    } else if ($MemberServedRole == CONTRACTOR_ROLE) {
                        require_once dirname(__FILE__) . '/profile/contractor_profile.php';
                        require_once dirname(__FILE__) . '/evaluation/evaluation.php';
                        require_once dirname(__FILE__) . '/report/reports_list.php';
                        require_once dirname(__FILE__) . '/contract/contracts_list.php';
                        require_once dirname(__FILE__) . '/contractor/contractors_list.php';
                        require_once dirname(__FILE__) . '/lab/labs_list.php';
                    } else if ($MemberServedRole == EMPLOYEE_ROLE) {
                        require_once dirname(__FILE__) . '/profile/employee_profile.php';
                        require_once dirname(__FILE__) . '/evaluation/evaluation.php';
                        require_once dirname(__FILE__) . '/report/reports_list.php';
                        require_once dirname(__FILE__) . '/contractor/contractors_list.php';
                        require_once dirname(__FILE__) . '/lab/labs_list.php';
                    } else if ($MemberServedRole == PARTNER_ROLE) {
                        require_once dirname(__FILE__) . '/profile/partner_profile.php';
                        require_once dirname(__FILE__) . '/report/reports_list.php';
                        require_once dirname(__FILE__) . '/lab/labs_list.php';
                        //require_once dirname(__FILE__) . '/partner/partners_list.php';
                    } else if ($MemberServedRole == GUEST_ROLE) {
                        require_once dirname(__FILE__) . '/profile/guest_profile.php';
                        require_once dirname(__FILE__) . '/report/reports_list.php';
                    }
                    require_once dirname(__FILE__) . "/pop_up_delete_window.php";
                } else {
                    
                }
                ?>
            </div>
        </div>
        <!--Footer Start here-->
<?php
require_once 'footer.php';
$tab_end = microtime(true);
//$log->logInfo('Profile: html load' . $time_end);
$time = $tab_end - $query_end;
$kLog->logInfo('Tabs Loading Time: ' . $time);
$total =  $tab_end - $page_start;
$kLog->logInfo('Total Page Loading Time: ' . $total);
?>
</body>
</html>