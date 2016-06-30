<?php
session_start();
$_SESSION = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>State of Florida Mobile Irrigation Lab (MIL) Program</title>
        <link rel="stylesheet" type="text/css" href="styles/milStylesheet.css" />
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
                    <h2>Sign In</h2>   
                    <div id='errmsg' style="color: red">
                        <?php echo (array_key_exists('err', $_GET) ? $_GET['err'] : ''); ?>
                    </div>
                    <form action="control_login.php" method="post">
                        <fieldset>
                            <label for="login">User Name</label>
                            <input name="username" class="text-input" id="login" tabindex="1" type="text" value="" />
                        </fieldset>

                        <fieldset>
                            <label for="password">Password  <!--|<a href="./password.php">Forgot?</a>--></label>
                            <input class="text-input" id="password" name="password" tabindex="2" type="password" value="" />
                        </fieldset>
                        <div class="form-btns">
                            <input class="button" type="submit" name="login" value="Sign In" tabindex="3" />
                        </div>

                        <div id="registerButts">
                            <a href="./signup/select_role.php">Register to become a Member</a> | <a href="mailto:fanjie@ufl.edu?subject=MIL%20Website">Need Assistance?</a>
                        </div>

                    </form>
                </div>
            </div>

            <div id="sponsorLogos">
                <p>Sponsored by the State of Florida, The St. Johns Water Management District, and The Florida Department of Agriculture</p>
            </div>

        </div>


    </body>
</html>
