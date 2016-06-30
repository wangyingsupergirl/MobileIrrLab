<?php

require_once(dirname(__FILE__)."/../../../includes/tcpdf/config/lang/eng.php");
require_once(dirname(__FILE__)."/../../../includes/tcpdf/tcpdf.php");
require_once(dirname(__FILE__).'/../../../includes/report/report.php');
require_once(dirname(__FILE__).'/../../../includes/report/report_11a.php');
require_once(dirname(__FILE__).'/../../../includes/report/report_11b.php');
require_once(dirname(__FILE__).'/../../../includes/report/report_11c.php');
require_once dirname(__FILE__) .'/../../../includes/report/report_7.php';
require_once dirname(__FILE__) .'/../../../includes/report/report_8.php';
require_once dirname(__FILE__) .'/../../../includes/report/report_9.php';
session_start();
$report = $_SESSION['report'];
$orientation = $report->getPageOrientation();

//$html = file_get_contents('pdfcontent.html');
/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
//$html = file_get_contents('pdfcontent.php');

$html = $report->getReport();
echo $html;
?>
