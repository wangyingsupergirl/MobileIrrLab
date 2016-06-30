<?php
require_once dirname(__FILE__) . '/../../includes/input/package/Contract.php';
require_once dirname(__FILE__) . '/../../includes/input/package/Package.php';
require_once dirname(__FILE__) . '/../../includes/utility.php';
session_start();
$package = $_SESSION['PackageObject'];
$comments = $package->getProperty('comments');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program - Contractor Sign In</title>
        <link rel="stylesheet" type="text/css" href="../../styles/milStylesheet.css" />
        <meta name="robots" content="nofollow" />
        <script src="../../js/jquery-validate/lib/jquery.js" type="text/javascript"></script>
    </head>

    <body id="login">
        <div id="largeWrapper">
            <div id="header">
                <h1>State of Florida</h1>
                <p>Mobile Irrigation Laboratory Program</p>
            </div>
            <div id="largeContentWrap">

                <h2> Edit Comments</h2>
                <form action='control_evaluation.php' method='post'>

                    <fieldset>
                        <table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
                            <tr><td colspan='2'></td></tr>
                            <tr>
                                <td align="right">Additional Comment Box*: </td>
                                <td>
                                    <!--Recommend link
                                    http://www.mediacollege.com/internet/javascript/form/limit-characters.html
                                    -->
                                    <TEXTAREA id="comments" name="comments" COLS="80" ROWS="5" maxlength="500"><?php
                                        echo $comments;
                                        ?></TEXTAREA>
                                    <br />Maximum characters: 500<br />
                                    You have <input type="text" id="countdown" size="3" value="500"/> characters left.
                                </td>
                            </tr>
                        </table>
                        <div class="form-btns" style="margin-top: 20px;">
                            <input class="button" type="submit" name="package_comments_submit" value="Submit"/>
                            <input class="button" type="submit" name="package_comments_cancel" value="Cancel"/>
                        </div>
                    </fieldset>


                </form>

            </div>

            <span class="clearing"></span>
            <div id="sponsorLogos">
                <p>Sponsored by the State of Florida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>

        </div>
        <script>

            function setLeftCharsNum(selector){
                var cbox = $(selector);
                var val = cbox.val();
                var length = val.length;
                var maxlength = cbox.attr('maxlength');
                if(length > maxlength){
                    cbox.val(cbox.val().substring(0,maxlength));
                }else{
                    var left = maxlength-length;
                    $('#countdown').val(left);
                }
            }
            setLeftCharsNum('#comments');
            $('#comments').keyup(function(){
                setLeftCharsNum('#comments')
            });
        </script>
    </body>
</html>