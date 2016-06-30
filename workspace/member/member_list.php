
<div class="content" dojotype="dijit.layout.ContentPane" title="MIL Members" <?php echo(!array_key_exists('tab', $_SESSION) || $_SESSION['tab'] ==5 ? "selected" : "")  ?>>
    <div id="subcontent_member" dojotype="dijit.layout.TabContainer" tabposition="top" region="center" >
        <div class="content" dojotype="dijit.layout.ContentPane" title="Pending">
            <form action="./member/control.php" method="post">
                <div id="msg"><?php echo (array_key_exists('msg',$_GET)? $_GET['msg']:'')?></div>
                <table border="0" cellpadding=0 cellspacing=0 class="subForms">
                    <thead>
                        <th>&nbsp;</th>
                        <th>MIL Member ID </th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Apply Date</th>
                        <th>Actions</th>
                    </thead>
                    <?php
                        $members = Utility::getAllMember();
                        $_SESSION['members'] = $members;
                         $i = 1;
                         foreach ($members as $id => $member) {
                            if($member->getProperty('status')!='submitted') continue;
                            $btns = new Buttons($member, $MemberServed->getProperty('role'));
                            echo
                            '<tr>
                                <td>' . $i . '</td>
                                <td>' . $member->getProperty('username'). '</td>
                                <td>' . $member->getProperty('first_name') . '</td>
                                <td>' . $member->getProperty('last_name') . '</td>
                                <td>' . $member->getDisplayName('role'). '</td>
                                <td>' .$member->getProperty('status')."</td>
                                <td>" .$member->getProperty('apply_time')."</td>
                                <td>"  . $btns->getString() . '</td>
                              </tr>';
                             $i++;
                        }
                    ?>
                    

                </table>
            </form>
        </div>
        <div class="content" dojotype="dijit.layout.ContentPane" title="Reviewed">
           <form action="./member/control.php" method="post">
                <table border="0" cellpadding=0 cellspacing=0 class="subForms">
                    <thead>
                    <th>&nbsp;</th>
                    <th>MIL Member ID </th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Reviewed Date</th>
                    <th>Actions</th>
                    </thead>
                    <?php
        
         $i = 1;
         $memdb_start = microtime(true);
         foreach ($members as $id => $member) {
            if($member->getProperty('status')=='approved') {
            $btns = new Buttons($member, $member->getProperty('role'));
           
            echo
            '<tr>
                <td>' . $i . '</td>
                <td>' . $member->getProperty('username'). '</td>
                <td>' . $member->getProperty('first_name') . '</td>
                <td>' . $member->getProperty('last_name') . '</td>
               <td>' . $member->getDisplayName('role'). '</td>
                <td>' .$member->getProperty('status')."</td>
                <td>" .$member->getProperty('approved_time')."</td>
                 <td>".$btns->getString()."</td></tr>";
             $i++;
             
             }
        }
         ?>


                </table>
            </form>

        </div>

    </div>
</div>