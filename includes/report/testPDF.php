<?php
//============================================================+
// File name: simple_report.php is developed based on the file below
// Example 061 for TCPDF class
// Last Update : 2010-08-08
//@link http://tcpdf.org
//============================================================+


require_once(dirname(__FILE__)."/../tcpdf/config/lang/eng.php");
require_once(dirname(__FILE__)."/../tcpdf/tcpdf.php");
require_once(dirname(__FILE__).'/report.php');
require_once(dirname(__FILE__).'/report_11a.php');
require_once(dirname(__FILE__).'/report_11b.php');
require_once(dirname(__FILE__).'/report_11c.php');
require_once dirname(__FILE__) .'/report_7.php';
require_once dirname(__FILE__) .'/report_8.php';
require_once dirname(__FILE__) .'/report_9.php';
require_once(dirname(__FILE__).'/report_1a.php');
require_once dirname(__FILE__) .'/report_2.php';
require_once dirname(__FILE__) .'/report_4b.php';
require_once dirname(__FILE__) .'/report_6b.php';
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP*0.5, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(True, PDF_MARGIN_FOOTER*1.5);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('helvetica', '', 7);
// add a page

$params = array(
    'mil_labs'=>'16,18'
    ,'cal_start_yr'=>2011
    ,'cal_start_month'=>1
    ,'cal_end_yr'=>2011
    ,'cal_end_month'=>12
);
/********************
 * Report 1A start here
 * ********************/

$report = new Report1A();
$report->requestDBData($params);
$orientation = $report->getPageOrientation();
$pdf->AddPage($orientation,'LETTER');

$html = $report->getReport();
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

/********************
 * Report 2 start here
 * ********************/

$report = new Report2();
$report->requestDBData($params);
$orientation = $report->getPageOrientation();
$pdf->AddPage($orientation,'LETTER');
$html = $report->getReport();
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

/********************
 * Report 4b start here
 * ********************/
$report = new Report4b();
$report->requestDBData($params);
$orientation = $report->getPageOrientation();
$pdf->AddPage($orientation,'LETTER');
$html = $report->getReport();
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


/********************
 * Report 6b start here
 * ********************/
$report = new Report6b();
$report->requestDBData($params);
$orientation = $report->getPageOrientation();
$pdf->AddPage($orientation,'LETTER');
$html = $report->getReport();
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
//Close and output PDF document
$pdf->Output('printOut.pdf', 'I');

