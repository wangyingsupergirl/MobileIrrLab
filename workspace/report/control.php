<?php

require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/mil_init.php';
require_once dirname(__FILE__) . '/../../includes/report/report.php';
require_once dirname(__FILE__) . '/../../includes/report/report_11a.php';
require_once dirname(__FILE__) . '/../../includes/report/report_11b.php';
require_once dirname(__FILE__) . '/../../includes/report/report_11c.php';
require_once dirname(__FILE__) . '/../../includes/report/report_7.php';
require_once dirname(__FILE__) . '/../../includes/report/report_8.php';
require_once dirname(__FILE__) . '/../../includes/report/report_9.php';
require_once dirname(__FILE__) . '/../../includes/report/report_1a.php';
require_once dirname(__FILE__) . '/../../includes/report/report_2.php';
require_once dirname(__FILE__) . '/../../includes/report/report_3.php';
require_once dirname(__FILE__) . '/../../includes/report/report_4b.php';
require_once dirname(__FILE__) . '/../../includes/report/report_6a.php';
require_once dirname(__FILE__) . '/../../includes/report/report_6b.php';
require_once dirname(__FILE__) . '/../../includes/report/report_6c.php';
require_once dirname(__FILE__) . '/../../includes/report/report_6d.php';
require_once dirname(__FILE__) . '/../../includes/report/report_12a.php';
require_once dirname(__FILE__) . '/../../includes/report/report_12b.php';
require_once dirname(__FILE__) . '/../../includes/report/report_12c.php';
require_once dirname(__FILE__) . '/../../includes/report/report_12d.php';
require_once dirname(__FILE__) . '/../../includes/report/report_13a.php';
require_once dirname(__FILE__) . '/../../includes/report/report_13b.php';
require_once dirname(__FILE__) . '/../../includes/report/report_13c.php';
require_once dirname(__FILE__) . '/../../includes/report/report_13d.php';
require_once dirname(__FILE__) . '/../../includes/report/report_14a.php';
require_once dirname(__FILE__) . '/../../includes/report/report_14b.php';
require_once dirname(__FILE__) . '/../../includes/report/report_14c.php';
require_once dirname(__FILE__) . '/../../includes/report/report_14d.php';

session_start();
//ini_set('display_errors',1);
$_SESSION['tab'] = REPORT_TAB;
foreach ($_POST as $paraName => $paraVal) {
    if (strstr($paraName, 'view_report')) {
        $pieces = explode(':', $paraName);
        $report_id = $pieces[1];
        $report_page = 'report_request_form.php?report_id=' . $report_id;
        header("Location: ./$report_page");
        exit;
    } else if (strstr($paraName, 'request_report')) {
        $pieces = explode(':', $paraName);
        $report_id = $pieces[1];
        $report = Report::createReport($report_id);
        //var_dump($report_id);
        $_POST['mil_labs'] = $_SESSION['MemberServed']->getProperty('labs_id');
        $_POST['cal_end_yr'] = (array_key_exists('cal_end_yr', $_POST) ? $_POST['cal_end_yr'] : '');
        $report_start_time = microtime(true);
        $result = $report->requestDBData($_POST, 'approved');
        $report_end_time = microtime(true);
        $time = $report_end_time- $report_start_time;
        $kLog->logInfo("Report $report_id Request Time: $time");
        if ($result == false) {
            header("Location: ./report_results/no_report_available.php");
            exit;
        }
       if ($report_id == '1a' || $report_id == '2' || $report_id == '4b') {
            $generate_report_start = microtime(true);
            $html = $report->getReport();
            $generate_report_end = microtime(true);
            $time = $generate_report_end- $generate_report_start;
            $kLog->logInfo("Report \$report->getReport() Time: $time");
            echo $html;
        } else {
            $_SESSION['report'] = $report;
            //echo $report->getReport();
            header("Location: ./report_results/simple_report.php");
        }
        exit;
    } else if (strstr($paraName, 'request_another')) {
        $pieces = explode(':', $paraName);
        $report_id = $pieces[1];
        $report_page = 'report_request_form.php?report_id=' . $report_id;
        header("Location: ./$report_page");
        exit;
    } else if (strstr($paraName, 'print_in_pdf')) {
        $pieces = explode(':', $paraName);
        $report_id = $pieces[1];
        $report_page = 'report_results/report_table.php';
        header("Location: ./$report_page");
        exit;
    } else if (strstr($paraName, 'request_html')) {
        $pieces = explode(':', $paraName);
        $report_id = $pieces[1];
        $report = Report::createReport($report_id);
        $result = $report->requestDBData($_POST, 'approved');
        if ($result == false) {
            header("Location: ./report_results/no_report_available.php");
            exit;
        }
        $_SESSION['report'] = $report;
        header("Location: ./report_results/sample_html.php");
        exit;
    }
}
if (array_key_exists('back_to_reports_list', $_POST)) {

    header("Location:../workspace.php");
    exit;
}
?>
