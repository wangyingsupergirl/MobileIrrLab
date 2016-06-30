<?php
//============================================================+
// File name: simple_report.php is developed based on the file below
// Example 061 for TCPDF class
// Last Update : 2010-08-08
//@link http://tcpdf.org
//============================================================+
require_once(dirname(__FILE__)."/../../../includes/tcpdf/config/lang/eng.php");
require_once(dirname(__FILE__)."/../../../includes/tcpdf/tcpdf.php");
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(True, PDF_MARGIN_FOOTER);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('helvetica', '', 7);
// add a page
ob_start();
include "invoice_pdf.php";
$html = ob_get_clean();
$pdf->AddPage('P','LETTER');
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('printOut.pdf', 'I');