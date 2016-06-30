<?php
/*
 * Required by page "/eval_table/eval_type_method.php". Use class EvalTypeWaterSavingType in package.php. Use getETWSTList() in utility.php
 */
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php'; //used by att1a_required.php
session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>State of Florida Mobile Irrigation Lab (MIL) Program</title>
	<link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
	<script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
	<meta name="robots" content="nofollow" />
	
</head>
<body id="login">
<div id="Wrapper">
    <div id="header">
    <h1>State of Florida</h1>
    </div>

    <div id="contentWrap">
    

        <span class="clearing"></span>
<?php
$role = $_GET['role'];
if($role==1){
require_once "contractor_review.php";
}else{
require_once "employee_review.php";
}?>
        </div>
        <span class="clearing"></span>
        <div id="sponsorLogos">
            <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
        </div>
        </div>
    </body>
</html>
