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

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 7);



$pdf->AddPage('L','LETTER');
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
ob_clean(); //Clear any previous output 
ob_start();?>
<h2 style='text-align:left;font-family:helvetica, arial, sans-serif;margin: 25px 10px 0 10px; padding-bottom: 0;'>Report No. 11a:
        IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS DATA AND RESULTS, PER MIL Handbook</h2>
 <h4 style='font-weight: 100; text-align:left;font-family:helvetica, arial, sans-serif;margin: 0 10px;color:#666;'>MIL ID: $mil_id&nbsp;&nbsp; MIL Name: $mil_name&nbsp;&nbsp; Federal Quarter: &nbsp;&nbsp; Federal Fisical Year: $start_year-$end_year &nbsp;&nbsp;</h4> 
<table  border="1" cellpadding="2" cellspacing="0"  style="text-align:center;font-weight:100; font-family:helvetica,arial,sans-serif; margin: 5px 10px;border-collapse: collapse;">
      <tr style="background-color:#ccc;">
      <th width="8%" rowspan="2">Eval ID #</th>
      <th width="7%"rowspan="2">Evaluation Type</th>
      <th width="6%" rowspan="2" >Evaluation Method</th>
      <th width="6%" rowspan="2">Irrigation System Type</th>
      <th width="11%" colspan="2" >Irrig System Distrib or Emiss Unif (%)</th>
      <th width="5%"  rowspan="2">Irrig Sys Ac</th>
      <th width="9%"  colspan="2">Land Use</th>
      <th width="8%"  colspan="2">Annual Water Use (in.)</th>
      <th width="6%"  rowspan="2">Irrigation System Problems</th>
      <th width="34%"  colspan="7">Water Savings (ac-ft) - Irrigation System Only</th>
      
  </tr>
  <tr style="font-family: helvetica, arial, verdana;">
      <th>Max</th>
      <th>Per Eval</th>
      <th>Type</th>
      <th>Name or Crop</th>
      <th>NIR</th>
      <th>Actual</th>
      <th>Type</th>
      <th>DU or EU Imprv</th>
      <th>Sched. Imprv</th>
      <th>Planned Repairs</th>
      <th>Imm Repairs</th>
      <th>Total AWS</th>
      <th>Total PWS</th>
  </tr><tr style="background-color:#FFF;">
        <td>4f10b804e11de</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>85</td>
        <td>69.00</td>
        <td>46.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>56,38,27,12</td>
        <td>Potential</td>
        <td>7.91</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>7.91</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f10cb07d43e6</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>85</td>
        <td>78.00</td>
        <td>41.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>56,12</td>
        <td>Potential</td>
        <td>2.73</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>2.73</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f10ceb3eb4fb</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>85</td>
        <td>79.00</td>
        <td>40.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>12,56</td>
        <td>Potential</td>
        <td>2.25</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>2.25</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11b9c58d748</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>85</td>
        <td>66.00</td>
        <td>39.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>2.26</td>
        <td>NA</td>
        <td>12,38</td>
        <td>Potential</td>
        <td>2.49</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>2.49</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f10c465a9717</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>77.00</td>
        <td>39.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>15.05</td>
        <td>NA</td>
        <td>12,4,26</td>
        <td>Potential</td>
        <td>11.49</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>11.49</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f10d6b2b83b1</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>83.00</td>
        <td>154.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>56,27</td>
        <td>Potential</td>
        <td>13.68</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>13.68</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f10de68c4f96</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>84.00</td>
        <td>141.00</td>
        <td>Ag</td>
        <td>Corn</td>
        <td>7.40</td>
        <td>NA</td>
        <td>56,4,5,37</td>
        <td>Potential</td>
        <td>11.01</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>11.01</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f10e2b179af3</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>76.00</td>
        <td>130.00</td>
        <td>Ag</td>
        <td>Corn</td>
        <td>7.40</td>
        <td>NA</td>
        <td>56,12,26,304</td>
        <td>Potential</td>
        <td>20.2</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>20.2</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f10e6101eb85</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>62.00</td>
        <td>16.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>9,4</td>
        <td>Potential</td>
        <td>5.53</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>5.53</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f10ea68e7504</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>68.00</td>
        <td>10.00</td>
        <td>Ag</td>
        <td>Sorghum</td>
        <td>8.12</td>
        <td>NA</td>
        <td>27</td>
        <td>Potential</td>
        <td>2.75</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>2.75</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f10ec8e383c9</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>87.00</td>
        <td>131.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>1.08</td>
        <td>NA</td>
        <td>27,26</td>
        <td>Potential</td>
        <td>1.01</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>1.01</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f10ef0a8117b</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>70.00</td>
        <td>133.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>1.08</td>
        <td>NA</td>
        <td>27,12</td>
        <td>Potential</td>
        <td>4.37</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>4.37</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f118bdd40350</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>78.00</td>
        <td>143.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>19.66</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>19.66</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f1190114b943</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>60.00</td>
        <td>58.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>22.03</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>22.03</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f1191f14b2d1</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>66.00</td>
        <td>42.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>11.94</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>11.94</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11970b29482</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>65.00</td>
        <td>56.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>12,26</td>
        <td>Potential</td>
        <td>16.75</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>16.75</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f1198f172b27</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>59.00</td>
        <td>36.00</td>
        <td>Ag</td>
        <td>Corn</td>
        <td>7.40</td>
        <td>NA</td>
        <td>27</td>
        <td>Potential</td>
        <td>14.01</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>14.01</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f119c32be186</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>78.00</td>
        <td>122.00</td>
        <td>Ag</td>
        <td>Cotton</td>
        <td>9.78</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>21.7</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>21.7</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f119f4ed9518</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>54.00</td>
        <td>133.00</td>
        <td>Ag</td>
        <td>Corn</td>
        <td>7.40</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>64.63</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>64.63</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11a1473cf29</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>74.00</td>
        <td>37.00</td>
        <td>Ag</td>
        <td>Beans</td>
        <td>2.41</td>
        <td>NA</td>
        <td>301</td>
        <td>Potential</td>
        <td>2.14</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>2.14</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f11a662d0aa3</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>24.00</td>
        <td>62.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>2.26</td>
        <td>NA</td>
        <td>12,26</td>
        <td>Potential</td>
        <td>36.23</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>36.23</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11a865c8d69</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>66.00</td>
        <td>175.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>15.05</td>
        <td>NA</td>
        <td>12,26</td>
        <td>Potential</td>
        <td>99.06</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>99.06</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f11ae64d267f</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>65.00</td>
        <td>34.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>2.26</td>
        <td>NA</td>
        <td>56,12</td>
        <td>Potential</td>
        <td>3.04</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>3.04</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11b2e3a44a8</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>84.00</td>
        <td>122.00</td>
        <td>Ag</td>
        <td>Peanut</td>
        <td>7.56</td>
        <td>NA</td>
        <td>304,27</td>
        <td>Potential</td>
        <td>9.73</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>9.73</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f11b48f17e2f</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>77.00</td>
        <td>133.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>2.26</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>5.88</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>5.88</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11b71d9e19f</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>69.00</td>
        <td>137.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>2.26</td>
        <td>NA</td>
        <td>12,27</td>
        <td>Potential</td>
        <td>9.95</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>9.95</td>
            </tr><tr style="background-color:#FFF;">
        <td>4f11b84261bc2</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>79.00</td>
        <td>135.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>2.26</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>5.14</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>5.14</td>
            </tr><tr style="background-color:#EEE;">
        <td>4f11bc9672328</td>
        <td>Initial</td>
        <td>Irrigation System Only</td>
        <td>Initial</td>
        <td>94</td>
        <td>54.00</td>
        <td>33.00</td>
        <td>Ag</td>
        <td>Other</td>
        <td>15.05</td>
        <td>NA</td>
        <td>12</td>
        <td>Potential</td>
        <td>32.61</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>NA</td>
        <td>32.61</td>
            </tr></table>
<?  
$html = ob_get_contents();
ob_end_clean();
$pdf->writeHTML($html, true, false, false, false, '');


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('printOut.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+ ?>
