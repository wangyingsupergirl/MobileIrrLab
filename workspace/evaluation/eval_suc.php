<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>State of Florida Mobile Irrigation Lab (MIL) Program</title>



        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <meta name="robots" content="nofollow" />
    </head>
    <body id="login">
        <div id="Wrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>

            <div id="contentWrap">
                <div id="mainIndex">

                    <h4>Evaluation Entered Successful</h4>
                    <h5>Evaluation has been added successfully!</h5>
                    <form action="./control_evaluation.php" method="post">
                        <input name="init" type="hidden" value="<?php echo (array_key_exists('init', $_GET) ? $_GET['init'] : "") ?>" />
                        <div class="form-btns" style="margin-top: 20px;">	
                            <input class="button" type="submit" name="eval_suc" value="Back" />
                        </div>

                    </form>
                </div>
            </div>
            <div id="sponsorLogos">
                <p>Sponsored by the State of FLorida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>
        </div>

    </body>
</html>


