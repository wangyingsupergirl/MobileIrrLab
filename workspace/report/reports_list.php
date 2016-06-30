<?php
require_once dirname(__FILE__) . '/../../includes/utility.php';
require_once dirname(__FILE__) . '/../../includes/constant.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitIrrSys.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/FollowFirm.php';
require_once dirname(__FILE__) . '/../../includes/input/package/evaluation/InitFirm.php';

$authorized_reports = $member->getPropertyByType('reports_id', 'array');
?>
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Reports"
     <?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] == 2 ? "selected" : "") ?>>
    <fieldset>
        <div>Select the report
            <!-form action="<?php echo 'http://localhost/MobileIrrLab-master/workspace/report/'; ?>control.php" method="post"-->
            <form action="<?php echo MIL_SERVER_ROOT . 'MobileIrrLab-master/workspace/report/'; ?>control.php" method="post">
                <table border="0" cellpadding=0 cellspacing=0 class="subForms">
                    <thead>
                    <th>&nbsp;</th>
                    <th>Report Serial No.</th>
                    <th>Report Name</th>
                    <th>Actions</th>
                    </thead>
                    <?php
                    if ($MemberServedRole != null) {
                        ?>   

                        <tr>
                            <th colspan = "4">REPORTS BY MIL</th>
                        </tr>

                        <?php
                    }
                    if ($MemberServedRole != null || ($authorized_reports != null && in_array('11a', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>1</td>
                            <td>Report No. 11A</td>
                            <td>BY IRRIGATION SYSTEM EVALUATIONS: WATER SAVINGS DATA AND RESULTS</td>
                            <td><input class="button" type="submit" name="view_report:11a" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ($MemberServedRole != null || ($authorized_reports != null && in_array('11b', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>2</td>
                            <td>Report No. 11B</td>
                            <td>BY IRRIGATION SYSTEM: WATER SOURCE, PUMPING STATION, AND OTHER  INFO</td>
                            <td><input class="button" type="submit" name="view_report:11b" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ($MemberServedRole != null || ($authorized_reports != null && in_array('11c', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>3</td>
                            <td>Report No. 11C</td>
                            <td>BY IRRIGATION SYSTEM: TRACKING TABLE FOR INITIAL, FOLLOW UP, AND/OR REPLACEMENT EVALUATIONS</td>
                            <td><input class="button" type="submit" name="view_report:11c" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ($MemberServedRole != null || ($authorized_reports != null && in_array('7', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>4</td>
                            <td>Report No. 7</td>
                            <td>MIL EVALUATION WAITING LIST</td>
                            <td><input class="button" type="submit" name="view_report:7" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ($MemberServedRole != null || ($authorized_reports != null && in_array('8', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>5</td>
                            <td>Report No. 8 </td>
                            <td>MIL CONSERVATION EDUCATION AND OUTREACH REPORT</td>
                            <td><input class="button" type="submit" name="view_report:8" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ($MemberServedRole != null || ($authorized_reports != null && in_array('9', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>6</td>
                            <td>Report No. 9</td>
                            <td>CONDENSED REPORT FORM - AGRICULTURAL MILS</td>
                            <td><input class="button" type="submit" name="view_report:9" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ( $MemberServedRole != GUEST_ROLE ||
                            ($authorized_reports != null && in_array('1a', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>7</td>
                            <td>Report No. 1A </td>
                            <td>MIL WATER SAVINGS, ACREAGE & EVALUATION SUMMARY</td>
                            <td><input class="button" type="submit" name="view_report:1a" value="View"/></td>
                        </tr>
                        <?php
                    }
                    if ($MemberServedRole != GUEST_ROLE ||
                            ($authorized_reports != null && in_array('2', $authorized_reports))) {
                        ?>
                        <tr>
                            <td>8</td>
                            <td>Report No. 2</td>
                            <td>AGRICULTURAL MILS - POTENTIAL WATER SAVINGS SUMMARY</td>
                            <td><input class="button" type="submit" name="view_report:2" value="View"/></td>
                        </tr>

                        <?php
                    }
                    /*
                      if($MemberServedRole != PARTNER_ROLE&&$MemberServedRole!=GUEST_ROLE ||
                      ($authorized_reports != null && in_array('6b' ,$authorized_reports))){
                      ?> */
                    if ($MemberServedRole != GUEST_ROLE ||
                            ($authorized_reports != null && in_array('6b', $authorized_reports))) {
                        ?>

                        <tr>
                            <th colspan = "4">REPORTS BY COUNTY</th>
                        </tr>

                        <tr>
                            <td>9</td>
                            <td>Report No. 3</td>
                            <td>AGRICULTURE POTENTIAL WATER SAVINGS (INITIAL EVALUATIONS ONLY)</td>
                            <td><input class="button" type="submit" name="view_report:3" value="View"/></td>
                        </tr>

                        <tr>
                            <td>10</td>
                            <td>Report No. 6A</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL AND FIRST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:6a" value="View"/></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Report No. 6B</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL AND LATEST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:6b" value="View"/></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Report No. 6C</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL EVALUATION, AND FIRST AND LATEST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:6c" value="View"></td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Report No. 6D</td>
                            <td>AGRICULTURE ACTUAL WATER SAVINGS (IRRIGATION SYSTEMS REPLACED)</td>
                            <td><input class="button" type="submit" name="view_report:6d" value="View"/></td>
                        </tr>
                        <tr>
                            <th colspan = "4">REPORTS BY CROP AND COUNTY</th>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Report No. 12A</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL AND FIRST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:12a" value="View"/></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Report No. 12B</td>
                            <td>AGRICULTURE POTENTIAL WATER SAVINGS (INITIAL EVALUATIONS ONLY)</td>
                            <td><input class="button" type="submit" name="view_report:12b" value="View"/></td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>Report No. 12C</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL EVALUATION, AND FIRST AND LATEST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:12c" value="View"/></td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>Report No. 12D</td>
                            <td>AGRICULTURE ACTUAL WATER SAVINGS (IRRIGATION SYSTEMS REPLACED)</td>
                            <td><input class="button" type="submit" name="view_report:12d" value="View"/></td>
                        </tr>
                        <?PHP
                        if ( $MemberServedRole != GUEST_ROLE ||
                                ($authorized_reports != null && in_array('4b', $authorized_reports))) {
                            ?>
                            <tr>
                                <th colspan = "4">REPORTS BY IRRIGATION SYSTEM TYPE AND COUNTY</th>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Report No. 4B </td>
                                <td>AG ACRES EVALUATED - BY COUNTY AND TYPE OF IRRIGATION SYSTEM</td>
                                <td><input class="button" type="submit" name="view_report:4b" value="View"/></td>
                            </tr>
        <?PHP
    } else {
        ?>
                            <tr>
                                <th colspan = "4">REPORTS BY IRRIGATION SYSTEM TYPE AND COUNTY</th>
                            </tr>
        <?PHP }
    ?>

                        <tr>
                            <td>19</td>
                            <td>Report No. 13A</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL AND FIRST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:13a" value="View"/></td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>Report No. 13B</td>
                            <td>AGRICULTURE POTENTIAL WATER SAVINGS (INITIAL EVALUATIONS ONLY)</td>
                            <td><input class="button" type="submit" name="view_report:13b" value="View"/></td>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>Report No. 13C</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL EVALUATION, AND FIRST AND LATEST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:13c" value="View"/></td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td>Report No. 13D</td>
                            <td>AGRICULTURE ACTUAL WATER SAVINGS (IRRIGATION SYSTEMS REPLACED)</td>
                            <td><input class="button" type="submit" name="view_report:13d" value="View"/></td>
                        </tr>
                        <tr>
                            <th colspan = "4">REPORTS BY CROP, IRRIGATION SYSTEM TYPE AND COUNTY</th>
                        </tr>
                        <tr>
                            <td>23</td>
                            <td>Report No. 14A</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL AND FIRST FOLLOW UP EVALUATIONS)</td>
                            <td><input class="button" type="submit" name="view_report:14a" value="View"/></td>
                        </tr>
                        <tr>
                            <td>24</td>
                            <td>Report No. 14B</td>
                            <td>AGRICULTURE POTENTIAL WATER SAVINGS (INITIAL EVALUATIONS ONLY)</td>
                            <td><input class="button" type="submit" name="view_report:14b" value="View"/></td>
                        </tr>
                        <tr>
                            <td>25</td>
                            <td>Report No. 14C</td>
                            <td>AGRICULTURE POTENTIAL VS ACTUAL WATER SAVINGS (INITIAL EVALUATIONS, AND FIRST AND LATEST FOLLOW UP EVALUATIONs)</td>
                            <td><input class="button" type="submit" name="view_report:14c" value="View"/></td>
                        </tr>
                        <tr>
                            <td>26</td>
                            <td>Report No. 14D</td>
                            <td>AGRICULTURE ACTUAL WATER SAVINGS (IRRIGATION SYSTEMS REPLACED)</td>
                            <td><input class="button" type="submit" name="view_report:14d" value="View"/></td>
                        </tr>

<?php } ?>
                </table>  
            </form>
        </div>   
    </fieldset>

</div>